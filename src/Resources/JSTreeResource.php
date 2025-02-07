<?php

namespace Iqionly\MenuManagement\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Iqionly\MenuManagement\Interfaces\DriverTreeView;
use Iqionly\MenuManagement\Models\MenuManagementListMenu;

class JSTreeResource extends ResourceCollection implements DriverTreeView
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $result = [];

        // If the collection is Collection, then break down
        if($this->collection instanceof Collection) {
            $result = $this->breakdown($this->collection);
        }

        return $result;
    }

    /**
     * Breadown array child one by one
     * 
     * @param mixed $collection Collection data for breakdown
     * @return array
     */
    function breakdown(mixed $collection) {
        $result = [];
        foreach ($collection as $key => $value) {
            // Set the value
            $row = [
                'id' => $value->id,
                'text' => $value->name,
                'icon' => $value->icons_path,
                'data' => [
                    'route' => $value->route,
                    'description' => $value->description,
                    'created_at' => $value->created_at instanceof Carbon ? $value->created_at->format('Y-m-d H:i:s') : $value->created_at,
                    'updated_at' => $value->updated_at instanceof Carbon ? $value->updated_at->format('Y-m-d H:i:s') : $value->updated_at
                ],
                'children' => false
            ];

            // Check if this menu have any child, then we need to cycle through again
            if($value->menu_management_list_menus->count() > 0) {
                $row['children'] = $this->breakdown($value->menu_management_list_menus);
            } else {
                unset($row['children']);
            }

            $result[] = $row;
            unset($row);
        }

        return $result;
    }

    // /**
    //  * Scripts for JSTree running
    //  * 
    //  * @return string
    //  */
    // public static function scripts()
    // {
    //     return "menu-management.list-menu-data";
    // }

    /**
     * Assets JS/CSS for JSTree extension
     * 
     * @return string
     */
    public static function assets()
    {
        return '
            <script src="' . asset('vendor/menu-management/jstree-scripts.js') . '"></script>
            <link rel="stylesheet" href="' . asset('vendor/menu-management/jstree-3.3.17/themes/default/style.css') .'">
            <script src="'. asset('vendor/menu-management/jstree-3.3.17/jstree.js') .'"></script>
        ';
    }

    public static function json_decode($object) {
        return self::flattening(json_decode($object), null, 1);
    }

    public static function flattening($object, $parent = null, $priority = 1, $depth = 0) {
        $result = [];
        foreach ($object as $key => $value) {
            $temp = [
                'id' => $value->id,
                'priority' => $priority++,
                'depth' => $depth,
                'name' => $value->text,
                'icons_path' => "$value->icon",
                'description' => $value->data->description,
                'parent_id' => null,
                'route' => $value->data->route,
            ];

            if(!is_numeric($value->id)) {
                $temp['id'] = null;
            }

            if($parent) {
                $temp['parent_id'] = $parent;
            }

            $result[] = $temp;

            if(count($value->children) > 0) {
                $result = array_merge($result, self::flattening($value->children, $value->id, $priority, $depth + 1));
            }
        }

        return $result;
    }
}
