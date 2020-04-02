<?php
declare(strict_types=1);

namespace SP\Admin\Tests\Feature;

use SP\Admin\Models\User;
use SP\Admin\Tests\TestCase;
use SP\Admin\UseCases\Databases\AdminUser;

class AuthTest extends TestCase
{
    public function testLoginPageForGuest(): void
    {
        $response = $this->get('/admin/login');

        $response
            ->assertViewIs('admin::auth.login')
            ->assertSeeText(__('Admin Panel'))
            ->assertSeeText(__('Login'))
            ->assertSee('<form', false);
    }

    public function testEmptyCredentials(): void
    {
        $response = $this->post('/admin/login', [
            'username' => '',
            'password' => '',
        ]);

        $response->assertSessionHasErrors();
    }

    public function testWrongCredentials(): void
    {
        $response = $this->post('/admin/login', [
            'username' => 'some_username',
            'password' => 'strong_password',
        ]);

        $response->assertSessionHasErrors();

        $this->assertGuest('admin');
    }

    public function testLoginToDashboard(): void
    {
        $response = $this->post('/admin/login', [
            'username' => AdminUser::DEFAULT_USERNAME,
            'password' => AdminUser::DEFAULT_PASSWORD,
        ]);

        $response->assertRedirect('/admin/home');

        $this->assertAuthenticated('admin');
    }

    public function testLogout(): void
    {
        $user = User::first();
        $response = $this->actingAs($user)->post('/admin/logout');

        $response->assertRedirect();

        $this->assertGuest('admin');
    }

}
