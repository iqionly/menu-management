<?php

if(!function_exists('menu_management_route'))
{
    function menu_management_route($name, $parameters = [], $absolute = true)
    {
        try{
            return route($name, $parameters, $absolute);
        } catch (\InvalidArgumentException $iae) {
            return '/';
        } catch (\Symfony\Component\Routing\Exception\RouteNotFoundException $rnfe) {
            return '/';
        }
    }
}