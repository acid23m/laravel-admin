<?php
declare(strict_types=1);

namespace SP\Admin\Models\Repositories;

use Illuminate\Contracts\Cache\Repository as Cache;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\LazyCollection;
use SP\Admin\Helpers\Formatter;

/**
 * Class TrashBinRepository.
 *
 * @package SP\Admin\Models\Repositories
 */
final class TrashBinRepository
{
    /**
     * @var array
     */
    private array $trashed_items = [];
    /**
     * @var Cache
     */
    private Cache $cache;

    /**
     * TrashBinRepository constructor.
     *
     * @param Cache $cache
     */
    public function __construct(Cache $cache)
    {
        $trash_bin = config('admin.trash_bin', []);
        foreach ($trash_bin as $item_class => $item_conf) {
            try {
                $models = $item_class::onlyTrashed()->cursor();
            } catch (\Throwable $e) {
                $models = [];
            }

            if (!empty($models)) {
                $this->trashed_items[] = $models;
            }
        }

        $this->cache = $cache;
    }

    /**
     * @return array
     */
    public function getTrashedItems(): array
    {
        return $this->trashed_items;
    }

    /**
     * Table rows and columns for one type of resource.
     *
     * @param iterable $models
     * @param array $config
     * @return array
     */
    private function tableDataForModel(iterable $models, array $config): array
    {
        $items = [];

        /**
         * Link button.
         *
         * @param string $route Url to action
         * @param string $icon Button icon
         * @param string $title Help tooltip
         * @param string $method Send method
         * @return string Html
         */
        $link = static function (string $route, string $icon, string $title, string $method = 'get'): string {
            $a = html()->a($route, $icon)->class('text-decoration-none mr-3')->attribute('title', $title);

            if ($method !== 'get') {
                $a = $a->data('method', $method);

                if ($method === 'delete') {
                    $a = $a->data('confirm', trans('Are you sure?'));
                }
            }

            return $a->toHtml();
        };


        foreach ($models as $model) {
            $items[] = [
                'group_name' => $config['group_name'] ?? '-',
                'label' => isset($config['label']) && $config['label'] instanceof \Closure ? $config['label']($model) : '-',
                'deleted_at' => Formatter::isoToLocalDateTime($model->deleted_at),
                'links' => [
                    isset($config['links']['view']) && $config['links']['view'] instanceof \Closure
                        ? $link($config['links']['view']($model), '<i class="fa fa-eye"></i>', trans('View'))
                        : '',
                    isset($config['links']['restore']) && $config['links']['restore'] instanceof \Closure
                        ? $link(
                        $config['links']['restore']($model),
                        '<i class="fa fa-trash-restore"></i>',
                        trans('Restore'),
                        'put'
                    )
                        : '',
                    isset($config['links']['delete']) && $config['links']['delete'] instanceof \Closure
                        ? $link(
                        $config['links']['delete']($model),
                        '<i class="fa fa-times"></i>',
                        trans('Delete forever'),
                        'delete'
                    )
                        : '',
                ],
            ];
        }

        return $items;
    }

    /**
     * Table data for all trashed items.
     *
     * @return Collection
     */
    public function tableDataForModels(): Collection
    {
        /** @var array $trash_bin */
        $trash_bin = config('admin.trash_bin', []);
        $items = collect([]);

        /** @var Collection|LazyCollection $trashed_items */
        foreach ($this->getTrashedItems() as $trashed_items) {
            /** @var Model $first */
            $first = $trashed_items->first();

            /**
             * @var string $item_class
             * @var array $item_config
             */
            foreach ($trash_bin as $item_class => $item_config) {
                if ($first !== null && \get_class($first) === $item_class) {
                    $items = $items->concat(
                        $this->tableDataForModel($trashed_items, $item_config)
                    );
                }
            }
        }

        return $items;
    }

    /**
     * Number of all trashed items.
     *
     * @return int
     */
    public function count(): int
    {
        $repository = $this;

        return (int)$this->cache->remember('trashed_items_count', 30, static function () use (&$repository): int {
            $count = 0;

            /** @var Collection|LazyCollection $trashed_items */
            foreach ($repository->getTrashedItems() as $trashed_items) {
                $count += $trashed_items->count();
            }

            return $count;
        });
    }

}
