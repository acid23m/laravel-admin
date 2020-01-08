<?php
declare(strict_types=1);

namespace SP\Admin\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Predefined scopes.
 *
 * @package SP\Admin\Traits
 */
trait ModelScopes
{
    /**
     * Shows only active items.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('active', true);
    }

    /**
     * Shows only not active items.
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeNotActive(Builder $query): Builder
    {
        return $query->where('active', false);
    }

    /**
     * Shows items ordered by creation time.
     *
     * @param Builder $query
     * @param string $direction desc|asc
     * @return Builder
     */
    public function scopeOrderByCreatedAt(Builder $query, $direction = 'desc'): Builder
    {
        return $query->orderBy('created_at', $direction);
    }

    /**
     * Shows items ordered by it position.
     *
     * @param Builder $query
     * @param string $direction asc|desc
     * @return Builder
     */
    public function scopeOrderByPosition(Builder $query, $direction = 'asc'): Builder
    {
        return $query->orderBy('position', $direction);
    }

}
