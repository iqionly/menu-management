<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Iqionly\MenuManagement\Controllers\MenuManagementController;

Route::namespace('menu-management')
    ->name('menu-management.')
    ->prefix('/menu-management')
    ->middleware('web')
    ->group(function(Router $route) {
    $route->get('/', [MenuManagementController::class, 'index'])->name('index');

    $route->post('/menus', [MenuManagementController::class, 'batch_menu'])->name('batch-menu');
    // $route->post('/menus/{menu?}', [MenuManagementController::class, 'store'])->name('store-menu');
    $route->delete('/menus/{menu}', [MenuManagementController::class, 'delete'])->name('delete-menu');
    $route->get('/list-menu-data', [MenuManagementController::class, 'list_menu_data'])->name('list-menu-data');
    $route->get('/list-routes-data', [MenuManagementController::class, 'list_routes_data'])->name('list-routes-data');


    $route->get('/test', [MenuManagementController::class, 'test'])->name('test');
    $route->post('/test/{param}', [MenuManagementController::class, 'test_param'])->name('test-param');

});
