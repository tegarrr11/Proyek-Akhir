<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Peminjaman;
use App\Models\Diskusi;

class DiskusiIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function fitur_diskusi_trigger_adminbem()
    {
        // Buat user
        $mahasiswa = User::factory()->create(['role' => 'mahasiswa']);
        $admin = User::factory()->create(['role' => 'admin']);
        $bem = User::factory()->create(['role' => 'bem']);

        // Buat peminjaman (dimiliki mahasiswa)
        $peminjaman = Peminjaman::factory()->create([
            'user_id' => $mahasiswa->id,
            'judul_kegiatan' => 'Kegiatan Diskusi Test',
        ]);

        $this->actingAs($mahasiswa)
            ->post('/diskusi', [
                'peminjaman_id' => $peminjaman->id,
                'pesan' => 'Halo sebelum admin/bem mulai'
            ])
            ->assertStatus(403);

        $this->actingAs($admin)
            ->post('/diskusi', [
                'peminjaman_id' => $peminjaman->id,
                'pesan' => 'Diskusi dimulai oleh admin'
            ])
            ->assertStatus(200);

        $this->actingAs($mahasiswa)
            ->post('/diskusi', [
                'peminjaman_id' => $peminjaman->id,
                'pesan' => 'Oke admin, saya ikut diskusi'
            ])
            ->assertStatus(200);

        $this->actingAs($bem)
            ->post('/diskusi', [
                'peminjaman_id' => $peminjaman->id,
                'pesan' => 'BEM ikut diskusi'
            ])
            ->assertStatus(200);

        $this->assertDatabaseCount('diskusi', 3);

        $firstMessage = Diskusi::where('peminjaman_id', $peminjaman->id)->first();
        $this->assertEquals('admin', $firstMessage->role);
        $this->assertEquals('Diskusi dimulai oleh admin', $firstMessage->pesan);
    }
}
