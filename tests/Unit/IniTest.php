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
        self::assertFileExists(database_path('test.ini'));
    }

    public function testGetSet(): void
    {
        $ini = $this->ini;

        $ini->set('key', 'value');

        self::assertEquals('value', $ini['key']);
        self::assertEquals('value', $ini->get('key'));
        self::assertEquals('default value', $ini->get('key1', 'default value'));

        self::assertIsArray($ini->getAll());
        self::assertArrayHasKey('key', $ini->getAll());

        $ini['key'] = 'new value';
        self::assertEquals('new value', $ini['key']);

        $ini['other_key'] = 'some value';
        self::assertEquals([
            'key' => 'new value',
            'other_key' => 'some value',
        ], $ini->getAll());

        $ini->setAll([
            'key1' => 'val1',
            'key2' => 'val2',
        ]);
        self::assertEquals([
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
