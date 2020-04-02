<?php
declare(strict_types=1);

namespace SP\Admin\Tests\Feature;

use SP\Admin\Tests\TestCase;

class HomeTest extends TestCase
{
    public function testCanonicalPageRedirect(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect('/admin/home');
    }

    public function testLoginPageRedirect(): void
    {
        $response = $this->get('/admin/home');

        $response->assertRedirect('/admin/login');
    }

}
