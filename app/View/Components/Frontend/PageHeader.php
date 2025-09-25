<?php

namespace App\View\Components\Frontend;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\File;
use Illuminate\View\Component;

class PageHeader extends Component
{
    private array $breadcrumb_items;
    /**
     * Create a new component instance.
     */
    public function __construct(
        public array $breadcrumbs = [],
        public string $image = '',
    ) {
        // Initialize
        $this->breadcrumb_items = [
            ['name' => __('Beranda'), 'url' => route('frontend.home')],
        ];

        // Merge provided breadcrumbs with default ones
        if (!empty($this->breadcrumbs)) {
            $this->breadcrumb_items = array_merge($this->breadcrumb_items, $this->breadcrumbs);
        }

        // Check if image exists
        $this->image = $this->image ?? publicMedia('default.jpg');
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.frontend.page-header', [
            'breadcrumb_items' => $this->breadcrumb_items,
        ]);
    }
}
