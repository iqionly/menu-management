<?php

namespace Iqionly\MenuManagement;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Iqionly\MenuManagement\Interfaces\DriverTreeView;
use Iqionly\MenuManagement\Models\MenuManagementListMenu;

class MenuManagement
{
    /**
     * Application Laravel
     * @var Illuminate\Foundation\Application
     */
    protected $app;

    /**
     * DriverTreeView for editor menu management
     * @var Iqionly\MenuManagement\Interfaces\DriverTreeView
     */
    public DriverTreeView $tree;

    protected MenuManagementListMenu $model;

    private $menu_data = null;

    // Build your next great package.
    public function __construct(Application $app, MenuManagementListMenu $menu)
    {
        $this->app = $app;
        $this->tree = $app->make(DriverTreeView::class);
        $this->model = $menu;
    }

    /**
     * This packages path
     *
     * @return string
     * 
     */
    public function vendor_path()
    {
        return __DIR__ . '/..';
    }

    /**
     * Get Path assets of tree editor 
     *
     * @return string
     * 
     */
    public function assets_path()
    {
        return $this->vendor_path() . '/resources/assets';
    }

    /**
     * Tree editor Scripts, like event editor, ajax, much more
     *
     * @return string
     * 
     */
    public function get_tree_scripts()
    {
        return '
        <script src="' . asset('vendor/menu-management/jquery-3.7.1/jquery-3.7.1.js') .'"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
            integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
        </script>
        <script>
            const menu_management = {
                ajax: {
                    store_menu: \''. route('menu-management.batch-menu') .'\',
                    delete_menu: \''. route('menu-management.delete-menu', ':id') .'\',
                    list_menu_data: \''. route('menu-management.list-menu-data') .'\',
                    list_routes_data: \''. route('menu-management.list-routes-data') .'\',
                    batch_menu: \''. route('menu-management.batch-menu') .'\',
                },
                state: "loaded",
                language: {
                    confirmation_add_menu: "' . trans('menu-management::messages.confirmation_add_menu') . '",
                    add_new_menu: "' . trans('menu-management::editor.add_new_menu') . '",
                    adding_menu: "' . trans('menu-management::messages.adding_menu') . '",
                    edit_menu: "' . trans('menu-management::editor.edit_menu') . '",
                    confirmation_delete_menu: "' . trans('menu-management::messages.confirmation_delete_menu') . '",
                    selected: "' . trans('menu-management::editor.selected') . '",
                },
            }
        </script>
        
        <script src="'. asset('vendor/menu-management/jselect.js') .'"></script>
        <script src="'. asset('vendor/menu-management/jform.js') .'"></script>';
    }

    /**
     * Tree editor assets, like their js or css
     *
     * @return [type]
     * 
     */
    public function get_tree_assets()
    {
        return $this->tree->assets();
    }

    public function get_routes_list()
    {
        $result = [];

        $routes = Route::getRoutes();
        foreach ($routes as $key => $value) {
            $defaultKey = $value->getName();
            if(empty($defaultKey)) {
                $defaultKey = $value->uri();
            }
            $result[$defaultKey] = $value->uri();
        }

        return $result;
    }

    public function get_model_menu(Request|array $request = null, $lazy = false)
    {
        if(!!$this->menu_data) {
            return $this->menu_data;
        }

        $this->menu_data = $this->model
            ->orderBy('priority', 'ASC')
            ->orderBy('parent_id', 'ASC')
            ->when($request != null, function ($query) use ($request) {
                $query->when(is_array($request), function ($query) use ($request) {
                    if((isset($request['id']) && $request['id'] == '#') || (isset($request['base']) && $request['base'] == true)) {
                        $query->whereNull('parent_id');
                    }
                });
                $query->when(is_a($request, Request::class), function($query) use ($request) {
                    $query->when($request['id'] == '#' || !$request->has('id') || $request->id == '#', function ($query) {
                        $query->whereNull('parent_id');
                    })
                    ->when($request->has('id') && $request->id !== '#', function($query) {
                        return $query->where('parent_id', request('id'));
                    });
                });
            })
            ->with('menu_management_list_menus')
            ->get();

        return $this->menu_data;
    }
}
