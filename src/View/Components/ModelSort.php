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
     * @var string The name of the input tag with IDs list
     */
    public string $inputName;
    /**
     * @var int Number of columns in grid
     */
    public int $gridColumns;
    /**
     * @var string Url to sorting handler (controller\'s action)
     */
    public string $action;

    /**
     * ModelSort constructor.
     *
     * @param string $inputName
     * @param int $gridColumns
     * @param string $action
     */
    public function __construct(string $inputName = 'sorted_ids', int $gridColumns = 6, string $action = '')
    {
        $this->inputName = $inputName;
        $this->gridColumns = $gridColumns;
        $this->action = $action;
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
