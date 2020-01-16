<?php
declare(strict_types=1);

namespace SP\Admin\Widgets\ModelGrid\Columns;

use Illuminate\Database\Eloquent\Model;

/**
 * Column with action buttons.
 *
 * @package SP\Admin\Widgets\ModelGrid\Columns
 */
class ActionColumn extends Column
{
    /**
     * @var int|null
     */
    protected ?int $cell_width = 100;
    /**
     * @var string|null
     */
    protected ?string $cell_class = 'text-center';
    /**
     * Format is [button name => route function].
     * Where `route function` is `function (Model $item): ?string {}`.
     *
     * @var array
     */
    private array $routes;

    /**
     * ActionColumn constructor.
     *
     * @param array $routes_definitions
     */
    public function __construct(array $routes_definitions)
    {
        $this->routes = $routes_definitions;
    }

    /**
     * {@inheritDoc}
     */
    public function boot(): void
    {
        // value
        $routes = $this->routes;
        $column = $this;

        $this->setValue(static function (Model $item) use (&$routes, &$column): string {
            $buttons = [];
            foreach ($routes as $action => $route_renderer) {
                try {
                    $route = $route_renderer($item);
                    $buttons[] = $column->{$action . 'Button'}($route);
                } catch (\Throwable $e) {
                }
            }

            return \implode(PHP_EOL, $buttons);
        });
    }

    /**
     * Renders button that shows entity details.
     *
     * @param string|null $route
     * @return string
     */
    protected function viewButton(?string $route): string
    {
        if ($route === null) {
            return '';
        }

        $translate_action = trans('View');

        return <<<BTN
<a class="text-decoration-none mr-3" href="$route" title="$translate_action">
    <i class="fa fa-eye"></i>
</a>
BTN;
    }

    /**
     * Renders button that edits entity.
     *
     * @param string|null $route
     * @return string
     */
    protected function editButton(?string $route): string
    {
        if ($route === null) {
            return '';
        }

        $translate_action = trans('Edit');

        return <<<BTN
<a class="text-decoration-none mr-3" href="$route" title="$translate_action">
    <i class="fa fa-pen-alt"></i>
</a>
BTN;
    }

    /**
     * Renders button that deletes entity.
     *
     * @param string|null $route
     * @return string
     */
    protected function deleteButton(?string $route): string
    {
        if ($route === null) {
            return '';
        }

        $translate_action = trans('Delete');
        $translate_shure = trans('Are you sure?');

        return <<<BTN
<a class="text-decoration-none" href="$route" title="$translate_action" data-method="delete" data-confirm="$translate_shure">
    <i class="fa fa-trash-alt"></i>
</a>
BTN;
    }

}
