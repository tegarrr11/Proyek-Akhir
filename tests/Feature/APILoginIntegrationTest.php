<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Socialite\Facades\Socialite;
use Mockery;
use Tests\TestCase;
use Illuminate\Support\Facades\Http;

class APILoginIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function mockSocialiteUser(string $email, string $name = 'Test User')
    {
        $abstractUser = (object) [
            'id' => 'mock-google-id',
            'name' => $name,
            'email' => $email,
            'avatar' => 'https://example.com/avatar.png',
            'token' => 'mock-token',
            'refreshToken' => 'mock-refresh',
        ];

        $provider = Mockery::mock('Laravel\Socialite\Contracts\Provider');
        $provider->shouldReceive('user')->andReturn($abstractUser);

        Socialite::shouldReceive('driver')->with('google')->andReturn($provider);
    }

    /** @test */
    public function user_yang_sudah_ada_direct_dashboard()
    {
        $user = User::factory()->create([
            'google_id' => 'mock-google-id',
            'email' => 'existing@mahasiswa.pcr.ac.id',
            'name' => 'Existing User',
            'role' => 'mahasiswa',
        ]);

        $this->mockSocialiteUser($user->email, $user->name);

        $response = $this->get('/auth/google/callback');

        $response->assertRedirect('mahasiswa/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function mahasiswa_baru_valid_sesuai_api_direct_dashboard()
    {
        $email = 'faiz@mahasiswa.pcr.ac.id';
        $this->mockSocialiteUser($email, 'New Mahasiswa');

        $this->mockHttpResponseForMahasiswa($email);

        $response = $this->get('/auth/google/callback');

        $this->assertDatabaseHas('users', [
            'email' => $email,
            'role' => 'mahasiswa'
        ]);

        $response->assertRedirect('mahasiswa/dashboard');
        $this->assertAuthenticated();
    }

    /** @test */
    public function invalid_domain_ditolak()
    {
        $email = 'outsider@gmail.com'; // domain tidak valid
        $this->mockSocialiteUser($email, 'Outsider');

        $response = $this->get('/auth/google/callback');

        // Harus redirect ke login, bukan dashboard
        $response->assertRedirect(route('login'));

        // Pastikan user tidak tersimpan di database
        $this->assertDatabaseMissing('users', ['email' => $email]);

        // Tidak ada user yang login
        $this->assertGuest();
    }

    protected function mockHttpResponseForMahasiswa(string $email)
    {
        $fakeData = [
            'items' => [
                [
                    'nama' => 'faiz',
                    'email' => $email,
                    'prodi' => 'SI',
                    'nim' => '12345678'
                ]
            ]
        ];

        Http::fake([
            '*' => Http::response($fakeData, 200)
        ]);
    }
}
