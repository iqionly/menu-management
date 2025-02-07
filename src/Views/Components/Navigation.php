<?php

namespace Iqionly\MenuManagement\Views\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Iqionly\MenuManagement\Interfaces\DriverTreeView;
use Iqionly\MenuManagement\Models\MenuManagementListMenu;

class Navigation extends Component
{
    public $data_menu;

    public $routeActive = false;

    /**
     * Create a new component instance.
     */
    public function __construct(DriverTreeView $treeController)
    {
        $this->data_menu = $treeController::make(app('menu-management')->get_model_menu([
            'base' => true
        ]));
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('menu-management::navigation');
    }
}
