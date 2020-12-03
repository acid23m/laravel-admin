<?php
declare(strict_types=1);

namespace SP\Admin\Tests\Unit;

use SP\Admin\Models\SettingScript;
use SP\Admin\Tests\TestCase;

class SettingScriptTest extends TestCase
{
    protected bool $createDatabases = false;

    private string $public_path = '/tmp/test.site';
    private SettingScript $s;

    protected function getEnvironmentSetUp($app): void
    {
        passthru("mkdir -p $this->public_path");
        $app->instance('path.public', $this->public_path);
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->s = new SettingScript;
    }

    public function testCreate(): void
    {
        $s = $this->s;

        self::assertFileExists($s->getPath($s::HEAD));
        self::assertFileExists($s->getPath($s::BOTTOM));
    }

    public function testGetSet(): void
    {
        $s = $this->s;

        $s->set($s::HEAD, 'let a = "Text";');
        self::assertEquals('let a = "Text";', $s->get($s::HEAD));

        $s->set($s::BOTTOM, 'let b = "Text";');
        self::assertEquals('let b = "Text";', $s->get($s::BOTTOM));
    }

    public function testUrl(): void
    {
        $s = $this->s;
        $base_url = config('app.url');

        self::assertEquals("$base_url/js/" . $s::HEAD . '.js', $s->getUrl($s::HEAD));
        self::assertEquals("$base_url/js/" . $s::BOTTOM . '.js', $s->getUrl($s::BOTTOM));
    }

    public function tearDown(): void
    {
        parent::tearDown();

        passthru("rm -rf $this->public_path");
    }

}
