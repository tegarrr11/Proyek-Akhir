<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Gedung;
use App\Models\Peminjaman;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MultiRoleIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function pengajuan_dan_approval_2_tahap()
    {
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $bem       = User::factory()->create(['role' => 'bem']);
        $admin     = User::factory()->create(['role' => 'admin']);
        $gedung = new Gedung();
        $gedung->nama = 'Gedung Serbaguna';
        $gedung->slug = 'gedung-serbaguna';
        $gedung->kapasitas = 100;
        $gedung->deskripsi = 'Untuk acara umum';
        $gedung->jam_operasional = '08.00 - 17.00';
        $gedung->save();

        $this->actingAs($mahasiswa);

        $peminjaman = Peminjaman::create([
            'judul_kegiatan'      => 'Simulasi Acara',
            'tgl_kegiatan'        => now()->toDateString(),
            'waktu_mulai'         => '08:00',
            'waktu_berakhir'      => '10:00',
            'aktivitas'           => 'Rapat',
            'organisasi'          => 'BEM',
            'penanggung_jawab'    => 'Ketua BEM',
            'deskripsi_kegiatan'  => 'Deskripsi tes',
            'status'              => 'menunggu',
            'gedung_id'           => $gedung->id,
            'user_id'             => $mahasiswa->id,
            'verifikasi_bem'      => 'diajukan',
            'verifikasi_sarpras'  => 'diajukan',
        ]);

        $this->actingAs($bem)
            ->post(route('bem.peminjaman.approve', $peminjaman->id))
            ->assertRedirect()
            ->assertSessionHas('success');

        $this->assertEquals('diterima', $peminjaman->fresh()->verifikasi_bem);

        $this->actingAs($admin)
            ->post(route('admin.peminjaman.approve', $peminjaman->id))
            ->assertRedirect()
            ->assertSessionHas('success');
        
        $this->assertEquals('diterima', $peminjaman->fresh()->verifikasi_sarpras);

        $peminjaman->refresh();

        $this->assertEquals('diterima', $peminjaman->verifikasi_sarpras);
    }
}
