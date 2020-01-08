<?php
declare(strict_types=1);

namespace SP\Admin\Log;

use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Pagination\LengthAwarePaginator as LaravelPaginator;

/**
 * Log slicer.
 *
 * @package SP\Admin\Log
 */
class Paginator
{
    public const PER_PAGE = 10;
    public const PAGE_NAME = 'log_page';

    /**
     * @var iterable
     */
    private iterable $items;
    /**
     * @var int
     */
    private int $total;

    /**
     * Paginator constructor.
     *
     * @param iterable $items
     * @param int $total
     */
    public function __construct(iterable $items, int $total)
    {
        $this->items = $items;
        $this->total = $total;
    }

    /**
     * Log items paginator.
     *
     * @return AbstractPaginator
     */
    public function paginate(): AbstractPaginator
    {
        // slice
        $items = collect([]);
        $current_page = request()->get(self::PAGE_NAME, 1);
        $offset = ($current_page - 1) * self::PER_PAGE;
        $i = 0;

        foreach ($this->items as $item) {
            if ($i >= $offset && $i < $current_page * self::PER_PAGE) {
                $items->add($item);
            }
            if ($i >= $current_page * self::PER_PAGE) {
                break;
            }

            $i ++;
        }

        return new LaravelPaginator($items, $this->total, self::PER_PAGE, $current_page, [
            'pageName' => self::PAGE_NAME,
        ]);
    }

}
