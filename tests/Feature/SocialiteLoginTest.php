<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Hash;
use Laravel\Socialite\Facades\Socialite;
use Laravel\Socialite\Contracts\User as ProviderUser;
use Mockery;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SocialiteLoginTest extends TestCase
{
    use RefreshDatabase;

    protected function mockSocialite($email, $id = 'google-id-123', $name = 'Test User', $token = 'mock-token', $refreshToken = 'mock-refresh')
    {
        $providerUser = Mockery::mock(ProviderUser::class);
        $providerUser->shouldReceive('getId')->andReturn($id);
        $providerUser->shouldReceive('getEmail')->andReturn($email);
        $providerUser->shouldReceive('getName')->andReturn($name);
        $providerUser->shouldReceive('getAvatar')->andReturn('https://avatar.test');
        $providerUser->shouldReceive('getToken')->andReturn($token);
        $providerUser->shouldReceive('getRefreshToken')->andReturn($refreshToken);

        Socialite::shouldReceive('driver')
            ->once()
            ->with('google')
            ->andReturnSelf();

        Socialite::shouldReceive('user')
            ->once()
            ->andReturn($providerUser);
    }

    public function test_login_sebagai_bem()
    {
        $this->withoutExceptionHandling();
        session()->start();

        $this->mockSocialite('bem@pcr.ac.id');

        $response = $this->followingRedirects()->get('/auth/google/callback');

        $this->assertAuthenticated();
        $this->assertEquals('bem', auth()->user()->role);
        $response->assertSee('Dashboard');
    }

    public function test_login_sebagai_mahasiswa()
    {
        $this->withoutExceptionHandling();
        session()->start();

        $this->mockSocialite('2203ti1234@mahasiswa.pcr.ac.id');

        Http::fake([
            'https://v2.api.pcr.ac.id/*' => Http::response([
                'items' => [[
                    'email' => '2203ti1234@mahasiswa.pcr.ac.id',
                    'prodi' => 'Teknik Informatika',
                    'nim' => '2203TI1234',
                    'nama' => 'Mahasiswa Satu',
                ]]
            ], 200)
        ]);

        $response = $this->followingRedirects()->get('/auth/google/callback');

        $this->assertAuthenticated();
        $this->assertEquals('mahasiswa', auth()->user()->role);
        $this->assertEquals('2203TI1234', auth()->user()->nim);
        $response->assertSee('Dashboard');
    }

    public function test_login_sebagai_dosen()
    {
        $this->withoutExceptionHandling();
        session()->start();

        $this->mockSocialite('dosen@pcr.ac.id');

        Http::fake([
            'https://v2.api.pcr.ac.id/*' => Http::response([
                'items' => [[
                    'email' => 'dosen@pcr.ac.id',
                    'prodi' => 'Sistem Informasi',
                    'inisial' => 'DSN',
                    'posisi' => 'Dosen',
                ]]
            ], 200)
        ]);

        $response = $this->followingRedirects()->get('/auth/google/callback');

        $this->assertAuthenticated();
        $this->assertEquals('dosen', auth()->user()->role);
        $response->assertSee('Dashboard');
    }

    public function test_update_user_sudah_ada()
    {
        $this->withoutExceptionHandling();
        session()->start();

        $user = User::create([
            'name' => 'Old User',
            'email' => 'bem@pcr.ac.id',
            'password' => Hash::make('old-password'),
            'role' => 'bem',
            'google_id' => null,
        ]);

        $this->mockSocialite('bem@pcr.ac.id', 'google-id-456');

        $response = $this->followingRedirects()->get('/auth/google/callback');

        $user->refresh();
        $this->assertEquals('google-id-456', $user->google_id);
        $this->assertAuthenticated();
        $response->assertSee('Dashboard');
    }

    public function test_login_selain_emailPCR()
    {
        $this->withoutExceptionHandling();
        session()->start();

        $email = 'hacker@gmail.com';
        $this->mockSocialite($email);

        $response = $this->get('/auth/google/callback');

        $this->assertGuest();
        $response->assertRedirect('/');

        $response->assertSessionHas('error', 'Login hanya diperbolehkan dengan email PCR.');
    }


    public function test_logout_berhasil()
    {
        $this->withoutExceptionHandling();
        session()->start();

        $user = User::factory()->create(['role' => 'mahasiswa']);
        $this->actingAs($user);

        $response = $this->post('/logout', [
            '_token' => csrf_token(),
        ]);

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
