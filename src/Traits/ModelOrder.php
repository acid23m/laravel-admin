<?php
declare(strict_types=1);

namespace SP\Admin\Traits;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

/**
 * Manipulates with entity position.
 *
 * @package SP\Admin\Traits
 */
trait ModelOrder
{
    /**
     * Resources ordered by this attribute.
     *
     * @return string
     */
    public function orderable(): string
    {
        return 'position';
    }

    /**
     * List of attributes for grouping.
     * ex.: return ['parent_id', 'type'];
     *
     * @return array
     */
    public function orderGroups(): array
    {
        return [];
    }

    /**
     * Move entity to the last position.
     *
     * @param bool $persist
     */
    public function lastPosition(bool $persist = true): void
    {
        // exclude this model
        $where = [
            ['id', '<>', (int)$this->id],
        ];
        $where_groups = $this->whereClauseForGroups();

        $count_without_this = self::withTrashed()->where(\array_merge($where, $where_groups))->count();

        $this->setAttribute($this->orderable(), $count_without_this + 1);

        if ($persist) {
            $this->save();
        }
    }

    /**
     * Place model to the new position.
     *
     * @param int $pos
     * @param bool $persist
     */
    public function moveToPosition(int $pos, bool $persist = true): void
    {
        $position_attr = $this->orderable();

        $where = [
            [$position_attr, '>', $pos],
        ];
        $where_groups = $this->whereClauseForGroups();

        // checks negative position
        if ($pos < 1) {
            return;
        }

        // checks if model is already on this position
        if ($pos === $this->$position_attr) {
            return;
        }

        // checks too big position number
        $count = self::withTrashed()->where($where_groups)->count();
        if ($pos > $count) {
            $pos = $count;
        }

        // shift next models
        self::withTrashed()->where(\array_merge($where, $where_groups))->update([
            $position_attr => DB::raw("$position_attr + 1"),
        ]);

        // place model to the new position
        $this->setAttribute($position_attr, $pos);

        if ($persist) {
            $this->save();
        }
    }

    /**
     * Swap this model with another.
     *
     * @param Model $model
     * @param bool $persist
     */
    public function swapWith(Model $model, bool $persist = true): void
    {
        $position_attr = $this->orderable();
        $curr_pos = $this->$position_attr;
        $new_pos = $model->$position_attr;

        $this->setAttribute($position_attr, $new_pos);
        $this->save();

        $model->setAttribute($position_attr, $curr_pos);

        if ($persist) {
            $this->save();
        }
    }

    /**
     * Increments position.
     *
     * @param bool $persist
     */
    public function moveToNextPosition(bool $persist = true): void
    {
        $position_attr = $this->orderable();
        $curr_pos = $this->$position_attr;

        $where = [
            [$position_attr, '=', $curr_pos + 1],
        ];
        $where_groups = $this->whereClauseForGroups();

        $next_model = self::withTrashed()->where(\array_merge($where, $where_groups))->first();

        if ($next_model !== null) {
            $this->swapWith($next_model, $persist);
        }
    }

    /**
     * Decrements position.
     *
     * @param bool $persist
     */
    public function moveToPreviousPosition(bool $persist = true): void
    {
        $position_attr = $this->orderable();
        $curr_pos = $this->$position_attr;

        $where = [
            [$position_attr, '=', $curr_pos - 1],
        ];
        $where_groups = $this->whereClauseForGroups();

        $prev_model = self::withTrashed()->where(\array_merge($where, $where_groups))->first();

        if ($prev_model !== null) {
            $this->swapWith($prev_model, $persist);
        }
    }

    /**
     * Squeeze the sequence after resource deleting.
     */
    public function removeAndReorder(): void
    {
        $position_attr = $this->orderable();

        // higher positions than this
        $where = [
            [$position_attr, '>', $this->$position_attr],
        ];
        $where_groups = $this->whereClauseForGroups();

        self::withTrashed()->where(\array_merge($where, $where_groups))->update([
            $position_attr => DB::raw("$position_attr - 1"),
        ]);
    }

    /**
     * @return array
     */
    protected function whereClauseForGroups(): array
    {
        $where_groups = [];
        foreach ($this->orderGroups() as $order_group) {
            $where_groups[] = [$order_group, '=', $this->$order_group];
        }

        return $where_groups;
    }

}
