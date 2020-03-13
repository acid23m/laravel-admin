<?php
declare(strict_types=1);

namespace SP\Admin\View\Components;

use Illuminate\View\Component;

/**
 * UI alerts.
 *
 * @package SP\Admin\View\Components
 */
class Toast extends Component
{
    /**
     * @var string success|error|info|default
     */
    public string $type;
    /**
     * @var string
     * @link https://fontawesome.com/icons?d=gallery&m=free
     */
    public string $icon;
    /**
     * @var string
     */
    public string $title;

    /**
     * Toast constructor.
     *
     * @param string $type
     * @param string|null $icon
     * @param string|null $title
     */
    public function __construct(string $type = 'default', ?string $icon = null, ?string $title = null)
    {
        $this->type = $type;
        $this->icon = $icon;
        $this->title = $title;
    }

    /**
     * {@inheritDoc}
     */
    public function render()
    {
        switch ($this->type) {
            case 'success':
                $bg_color = 'bg-success';
                $text_color = 'text-light';
                break;
            case 'error':
                $bg_color = 'bg-danger';
                $text_color = 'text-light';
                break;
            case 'info':
                $bg_color = 'bg-info';
                $text_color = 'text-light';
                break;
            case 'default':
                $bg_color = '';
                $text_color = '';
                break;
            default:
                $bg_color = '';
                $text_color = '';
        }

        return view(
            'admin::components.toast',
            \compact('bg_color', 'text_color')
        );
    }

}
