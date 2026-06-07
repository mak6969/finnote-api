<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthWebTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test login page is accessible.
     */
    public function test_login_page_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertSee('Masuk');
        $response->assertSee('Daftar gratis di sini');
    }

    /**
     * Test registration page is accessible.
     */
    public function test_registration_page_can_be_rendered(): void
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertSee('Mulai Catat Keuanganmu');
        $response->assertSee('Masuk di sini');
    }

    /**
     * Test registration fails with invalid inputs.
     */
    public function test_registration_fails_with_invalid_data(): void
    {
        $response = $this->post('/register', [
            'name' => '',
            'email' => 'not-an-email',
            'password' => '123',
            'password_confirmation' => '1234',
        ]);

        $response->assertSessionHasErrors(['name', 'email', 'password']);
        $this->assertDatabaseCount('users', 0);
    }

    /**
     * Test registration succeeds and authenticates user.
     */
    public function test_registration_succeeds_and_redirects(): void
    {
        $response = $this->post('/register', [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertDatabaseHas('users', [
            'email' => 'budi@example.com',
            'name' => 'Budi Santoso',
        ]);
        $this->assertAuthenticated();
    }

    /**
     * Test login succeeds with correct credentials.
     */
    public function test_login_succeeds_with_correct_credentials(): void
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /**
     * Test login fails with incorrect credentials.
     */
    public function test_login_fails_with_incorrect_credentials(): void
    {
        User::factory()->create([
            'email' => 'test@example.com',
            'password' => bcrypt('password123'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHas('error', 'Email atau password salah!');
        $this->assertGuest();
    }

    /**
     * Test logout works.
     */
    public function test_logout_works(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}
