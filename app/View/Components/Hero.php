<?php

namespace App\View\Components;

use Illuminate\View\Component;

class Hero extends Component
{
    public string $title;

    public ?string $subtitle;

    public array $breadcrumbs;

    public bool $merchantDashboard;

    public function __construct(
        string $title,
        ?string $subtitle = null,
        array $breadcrumbs = [],
        ?bool $merchantDashboard = false
    ) {
        $this->title = $title;
        $this->subtitle = $subtitle;
        $this->breadcrumbs = $breadcrumbs;
        $this->merchantDashboard = $merchantDashboard;
    }

    public function render()
    {
        return view('components.hero');
    }
}
