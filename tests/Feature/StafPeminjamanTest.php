<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Gedung;
use App\Models\Peminjaman;
use App\Models\Fasilitas;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StafPeminjamanTest extends TestCase
{
    use RefreshDatabase;

    protected $dosen;

    protected function setUp(): void
    {
        parent::setUp();

        // Buat user role dosen
        $this->dosen = User::factory()->create([
            'role' => 'dosen',
        ]);

        // Buat gedung dummy
        $this->gedung = Gedung::factory()->create([
            'nama' => 'Gedung Teknik',
            'slug' => 'gedung-teknik',
        ]);

        // Buat fasilitas dummy di gedung ini
        $this->fasilitas = Fasilitas::factory()->create([
            'gedung_id' => $this->gedung->id,
            'nama_barang' => 'Proyektor',
            'stok' => 2,
        ]);
    }


    public function test_dosen_dapat_menampilkan_halaman_pengajuan()
    {
        // Login sebagai dosen
        $response = $this->actingAs($this->dosen)
            ->get(route('dosen.peminjaman'));

        $response->assertStatus(200);
        $response->assertViewIs('pages.dosen.peminjaman');
        $response->assertViewHasAll(['pengajuans', 'riwayats']);
    }

    public function test_dosen_dapat_mengajukan_peminjaman()
    {
        $this->actingAs($this->dosen);

        $response = $this->post('/dosen/peminjaman/store', [
            'judul_kegiatan' => 'Rapat Akademik',
            'tgl_kegiatan' => now()->addDays(7)->format('Y-m-d'),
            'waktu_mulai' => '08:00',
            'waktu_berakhir' => '10:00',
            'aktivitas' => 'Rapat',
            'organisasi' => 'Himasistifo',
            'penanggung_jawab' => 'Dr. Andi',
            'deskripsi_kegiatan' => 'Rapat koordinasi semester baru',
            'gedung' => $this->gedung->slug,
            'barang' => [
                ['id' => $this->fasilitas->id, 'jumlah' => 1]
            ],
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('peminjaman', [
            'judul_kegiatan' => 'Rapat Akademik',
            'organisasi' => 'Fakultas Teknik',
        ]);
    }


    public function test_dosen_dapat_menampilkan_form_pengajuan()
    {
        $response = $this->actingAs($this->dosen)
            ->get(route('dosen.peminjaman.create'));

        $response->assertStatus(200);
    }
}
