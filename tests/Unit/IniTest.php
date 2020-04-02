<?php
declare(strict_types=1);

namespace SP\Admin\Tests\Unit;

use SP\Admin\Contracts\Setting\AbstractBasic;
use SP\Admin\Tests\TestCase;

class IniTest extends TestCase
{
    protected bool $createDatabases = false;

    private AbstractBasic $ini;

    public function setUp(): void
    {
        parent::setUp();

        $this->ini = new class extends AbstractBasic {
            public function filePath(): string
            {
                return database_path('test.ini');
            }

            protected function sectionName(): string
            {
                return 'test';
            }
        };
    }

    public function testCreate(): void
    {
        $this->assertFileExists(database_path('test.ini'));
    }

    public function testGetSet(): void
    {
        $ini = $this->ini;

        $ini->set('key', 'value');

        $this->assertEquals('value', $ini['key']);
        $this->assertEquals('value', $ini->get('key'));
        $this->assertEquals('default value', $ini->get('key1', 'default value'));

        $this->assertIsArray($ini->getAll());
        $this->assertArrayHasKey('key', $ini->getAll());

        $ini['key'] = 'new value';
        $this->assertEquals('new value', $ini['key']);

        $ini['other_key'] = 'some value';
        $this->assertEquals([
            'key' => 'new value',
            'other_key' => 'some value',
        ], $ini->getAll());

        $ini->setAll([
            'key1' => 'val1',
            'key2' => 'val2',
        ]);
        $this->assertEquals([
            'key1' => 'val1',
            'key2' => 'val2',
        ], $ini->getAll());
    }

    public function tearDown(): void
    {
        parent::tearDown();

        unlink(database_path('test.ini'));
    }

}
