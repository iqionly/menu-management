<?php

namespace Iqionly\MenuManagement\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Collection;
use Iqionly\MenuManagement\Facades\MenuManagement;
use Iqionly\MenuManagement\Interfaces\DriverTreeView;
use Iqionly\MenuManagement\Models\MenuManagementListMenu;
use Iqionly\MenuManagement\Resources\JSTreeResource;

class MenuManagementController extends Controller
{

    protected DriverTreeView $treeController;

    protected MenuManagementListMenu $model;

    protected array $routes;

    public function __construct(DriverTreeView $treeController, MenuManagementListMenu $model)
    {
        $this->treeController = $treeController;
        $this->model = $model;

        $this->routes = MenuManagement::get_routes_list();
    }

    function index() {
        return view('menu-management::index');
    }

    function store(Request $request)
    {
        if($this->model->create([
            'name' => $request->name,
            'icon_path' => $request->icon,
            'description' => $request->desctiption
        ])) {
            return response()->json(['message' => trans('menu-management::messages.menu_created')]);
        }

        return response()->json(['message' => trans('menu-management::messages.menu_create_failed')]);
    }

    function update( Request $request, MenuManagementListMenu $menu)
    {
        $this->model->create($request->all());
        return response()->json(['message' => trans('menu-management::messages.menu_created')]);
    }

    function delete(Request $request, MenuManagementListMenu $menu)
    {
        if($menu->delete()) {
            return response()->json(['message' => trans('menu-management::messages.menu_deleted')]);
        }
        return response()->json(['message' => trans('menu-management::messages.menu_delete_failed')]);
    }

    function batch_menu(Request $request) {
        $result = JSTreeResource::json_decode($request->menus);

        $arrayIds = new Collection($result);

        if($arrayIds->count() > 0) {
            $arrayIds = $arrayIds->filter(function($array) {
                return $array['id'] != null;
            })
            ->pluck('id')
            ->toArray();
        } else {
            $arrayIds = [];
        }

        $all = MenuManagementListMenu::select('id')->withTrashed()
            ->when(!empty($arrayIds), function ($query) use ($arrayIds) {
                return $query->whereNotIn('id', $arrayIds);
            })
            ->orderBy('parent_id', 'DESC')
            ->get();

        if(MenuManagementListMenu::upsert($result, ['id'], ['parent_id', 'priority', 'depth', 'route', 'name', 'icons_path', 'description'])) {
            foreach ($all as $key => $value) {
                MenuManagementListMenu::forceDestroy($value->id);
            }
            
            return response()->json([
                'message' => trans('menu-management::messages.menu_updated')
            ]);
        }

        return response()->json([
            'message' => trans('menu-management::messages.menu_update_failed')
        ], 504);
    }

    function list_menu_data(Request $request)
    {
        return $this->treeController::make(app('menu-management')->get_model_menu($request));
    }

    function list_routes_data(Request $request)
    {
        return response()->json($this->routes);
    }

    public function test() {
        return response()->json([
            'message' => trans('menu-management::messages.test_route')
        ]);
    }

    public function test_param(string $param) {
        return response()->json([
            'message' => trans('menu-management::messages.test_route_with_param', ['param' => $param])
        ]);
    }
}