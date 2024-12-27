<?php

namespace App\View\Components;

use Illuminate\View\Component;

class DeleteAction extends Component
{
    public string $route;

    public string $id;

    public string $title;

    public string $model;

    public function __construct($route, $id, $model, $title = null)
    {
        $this->route = $route;
        $this->id = $id;
        $this->title = $title;
        $this->model = class_basename($model);
    }

    public function render(): \Illuminate\View\View
    {
        return view('components.delete-action');
    }
}
