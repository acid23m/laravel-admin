<?php
declare(strict_types=1);

namespace SP\Admin\View\Components;

use Illuminate\Support\Str;
use Illuminate\View\Component;

/**
 * Sortable grid for models.
 *
 * @package SP\Admin\View\Components
 */
class ModelSort extends Component
{
    /**
     * ModelSort constructor.
     *
     * @param string $inputName
     * @param int $gridColumns
     * @param string $action
     */
    public function __construct(
        public string $inputName = 'sorted_ids',
        public int $gridColumns = 6,
        public string $action = ''
    ) {
    }

    /**
     * {@inheritDoc}
     */
    public function render()
    {
        return view('admin::components.modelsort', [
            'component_id' => Str::random(8),
        ]);
    }

}
