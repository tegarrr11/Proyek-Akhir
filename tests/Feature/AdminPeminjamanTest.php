<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Gedung;
use App\Models\Peminjaman;
use App\Models\Fasilitas;
use App\Models\DetailPeminjaman;
use App\Models\Diskusi;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Notification;
use App\Notifications\PengajuanDiterimaSarpras;

class AdminPeminjamanTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();
        $this->admin = User::factory()->create(['role' => 'admin']);
        Notification::fake();
    }

    public function test_admin_dapat_melihat_daftar_pengajuan()
    {
        $gedung = Gedung::factory()->create();
        Peminjaman::factory()->count(3)->create(['gedung_id' => $gedung->id]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.peminjaman', ['gedung_id' => $gedung->id]));

        $response->assertStatus(200);
        $response->assertViewHasAll(['pengajuans', 'riwayats', 'aktif']);
    }

    public function test_admin_dapat_melihat_detail_pengajuan()
    {
        $gedung = Gedung::factory()->create();
        $peminjaman = Peminjaman::factory()->create(['gedung_id' => $gedung->id]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.peminjaman.detail', $peminjaman->id));

        $response->assertStatus(200)
            ->assertJsonFragment([
                'id' => $peminjaman->id,
                'judul_kegiatan' => $peminjaman->judul_kegiatan
            ]);
    }

    public function test_admin_dapat_mengirim_diskusi_kepada_pengaju()
    {
        $admin = User::factory()->create([
            'role' => 'admin',
        ]);

        $peminjaman = Peminjaman::factory()->create();
        $this->actingAs($admin);

        $response = $this->post(route('diskusi.store'), [
            'peminjaman_id' => $peminjaman->id,
            'pesan' => 'Mohon konfirmasi kembali jadwalnya',
        ]);

        $response->assertSuccessful();
        $this->assertDatabaseHas('diskusi', [
            'peminjaman_id' => $peminjaman->id,
            'pesan' => 'Mohon konfirmasi kembali jadwalnya',
            'user_id' => $admin->id,
        ]);
    }


    public function test_admin_dapat_menambahkan_fasilitas()
    {
        $gedung = Gedung::factory()->create();

        $data = [
            'gedung_id' => $gedung->id,
            'nama_barang' => 'Kursi Lipat',
            'stok' => 20,
            'is_available' => true
        ];

        $response = $this->actingAs($this->admin)
            ->post(route('admin.fasilitas.store'), $data);

        $response->assertRedirect();
        $this->assertDatabaseHas('fasilitas', [
            'gedung_id' => $gedung->id,
            'nama_barang' => 'Kursi Lipat'
        ]);
    }

    public function test_admin_dapat_menghapus_fasilitas()
    {
        $fasilitas = Fasilitas::factory()->create();

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.fasilitas.destroy', $fasilitas->id));

        $response->assertRedirect();
        $this->assertDatabaseMissing('fasilitas', ['id' => $fasilitas->id]);
    }

    public function test_admin_dapat_mengimpor_fasilitas_dari_excel()
    {
        Storage::fake('local');
        $file = UploadedFile::fake()->create('fasilitas.xlsx');

        $response = $this->actingAs($this->admin)
            ->post(route('admin.fasilitas.import'), ['file' => $file]);

        $response->assertRedirect();
        $response->assertSessionHasNoErrors();
    }

    public function test_admin_dapat_menyetujui_pengajuan()
    {
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $peminjaman = Peminjaman::factory()->create([
            'user_id' => $mahasiswa->id,
            'verifikasi_bem' => 'diterima',
            'verifikasi_sarpras' => 'diajukan'
        ]);

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.peminjaman.approve', $peminjaman->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('peminjaman', [
            'id' => $peminjaman->id,
            'verifikasi_sarpras' => 'diterima'
        ]);

        Notification::assertSentTo($mahasiswa, PengajuanDiterimaSarpras::class);
    }

    public function test_admin_dapat_menandai_barang_sudah_diambil()
    {
        $peminjaman = Peminjaman::factory()->create([
            'verifikasi_sarpras' => 'diterima'
        ]);

        $response = $this->actingAs($this->admin)
            ->patch(route('admin.peminjaman.ambil', $peminjaman->id));

        $response->assertRedirect();
        $this->assertDatabaseHas('peminjaman', [
            'id' => $peminjaman->id,
            'status_peminjaman' => 'ambil'
        ]);
    }

    public function test_admin_dapat_menyelesaikan_peminjaman_dan_mengembalikan_stok()
    {
        $fasilitas = Fasilitas::factory()->create(['stok' => 0]);
        $peminjaman = Peminjaman::factory()->create([
            'status_peminjaman' => 'diambil'
        ]);

        DetailPeminjaman::factory()->create([
            'peminjaman_id' => $peminjaman->id,
            'fasilitas_id' => $fasilitas->id,
            'jumlah' => 2
        ]);

        $response = $this->actingAs($this->admin)
            ->post(route('admin.peminjaman.selesai', $peminjaman->id), [
                'checklist' => [$fasilitas->id]
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('peminjamans', [
            'id' => $peminjaman->id,
            'status_pengembalian' => 'selesai'
        ]);
        $this->assertDatabaseHas('fasilitas', [
            'id' => $fasilitas->id,
            'stok' => 2
        ]);
    }
}
