<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Gedung;
use App\Models\Peminjaman;
use App\Models\Fasilitas;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminFasilitasTest extends TestCase
{
    use RefreshDatabase;

    protected $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'role' => 'admin',
            'email' => 'admin@pcr.ac.id',
        ]);

        $this->actingAs($this->admin);
    }

    /** @test */
    public function test_admin_dapat_menyetujui_peminjaman()
    {
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);

        $gedung = Gedung::factory()->create();

        $peminjaman = Peminjaman::factory()->create([
            'user_id' => $mahasiswa->id,
            'gedung_id' => $gedung->id,
            'verifikasi_bem' => 'diterima',
            'verifikasi_sarpras' => 'diajukan',
        ]);

        $response = $this->post("/admin/peminjaman/{$peminjaman->id}/approve");

        $response->assertRedirect();
        $this->assertEquals('diterima', $peminjaman->fresh()->verifikasi_sarpras);
    }

    /** @test */
    public function test_admin_dapat_mengirim_diskusi_ke_peminjaman()
    {
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);

        $gedung = Gedung::factory()->create();

        $peminjaman = Peminjaman::factory()->create([
            'user_id' => $mahasiswa->id,
            'gedung_id' => $gedung->id,
        ]);

        $this->actingAs($this->admin);

        $response = $this->post(route('diskusi.store'), [
            'peminjaman_id' => $peminjaman->id,
            'pesan' => 'Tolong lengkapi data ruangan',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('diskusi', [
            'peminjaman_id' => $peminjaman->id,
            'pesan' => 'Tolong lengkapi data ruangan',
            'role' => 'admin',
        ]);
    }

    /** @test */
    public function test_admin_dapat_menambah_fasilitas()
    {
        $gedung = Gedung::factory()->create();

        $response = $this->post(route('admin.fasilitas.store'), [
            'gedung_id' => $gedung->id,
            'nama_barang' => 'Mic Wireless',
            'stok' => 5,
        ]);

        $response->assertRedirect(route('admin.fasilitas'));

        $this->assertDatabaseHas('fasilitas', [
            'gedung_id' => $gedung->id,
            'nama_barang' => 'Mic Wireless',
            'stok' => 5,
        ]);
    }
}
