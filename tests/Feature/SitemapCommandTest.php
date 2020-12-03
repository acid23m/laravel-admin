<?php
declare(strict_types=1);

namespace SP\Admin\Tests\Feature;

use SP\Admin\Tests\TestCase;
use Spatie\Sitemap\SitemapServiceProvider;

class SitemapCommandTest extends TestCase
{
    protected bool $createDatabases = false;

    private string $sitemap_config_path = '/tmp/sitemap.php';

    protected function getEnvironmentSetUp($app): void
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('admin.sitemap', $this->sitemap_config_path);
    }

    protected function getPackageProviders($app): array
    {
        return array_merge(parent::getPackageProviders($app), [
            SitemapServiceProvider::class,
        ]);
    }

    public function setUp(): void
    {
        parent::setUp();

        // sitemap config
        if (!file_exists($this->sitemap_config_path)) {
            file_put_contents(
                $this->sitemap_config_path,
                <<<'SC'
<?php
use Illuminate\Support\Carbon;
return [
    'static' => [
        [
            'location' => fn(): string => url(''),
            'priority' => 0.9,
        ],
        [
            'location' => fn(): string => url('/posts'),
            'priority' => 0.5,
        ],
    ],
    'dynamic' => [
        [
            'collection' => function (): iterable {
                $model = new stdClass;
                $model->title = 'Post 1';
                $model->slug = 'post_1';
                $model->updated_at = Carbon::now();
                
                return [$model];
            },
            'location' => fn($model): string => url('/posts/post_1'),
            'last_modified' => fn($model): Carbon => $model->updated_at,
            'change_frequency' => 'monthly',
            'priority' => 0.5,
        ],
    ],
];
SC
            );
        }
    }

    public function testCreate(): void
    {
        $this->artisan('sitemap:generate')->expectsOutput('Sitemap generated.');

        self::assertFileExists(public_path('sitemap.xml'));
        self::assertFileExists(public_path('static_sitemap.xml'));
        self::assertFileExists(public_path('std_class_sitemap.xml'));

        unlink(public_path('sitemap.xml'));
        unlink(public_path('static_sitemap.xml'));
        unlink(public_path('std_class_sitemap.xml'));
    }

    public function tearDown(): void
    {
        parent::tearDown();

        unlink($this->sitemap_config_path);
    }

}
