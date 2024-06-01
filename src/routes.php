<?php

use Flute\Core\Admin\Http\Middlewares\HasPermissionMiddleware;
use Flute\Core\Router\RouteGroup;
use Flute\Modules\FAQ\src\Http\Controllers\API\ApiAdminFaqController;
use Flute\Modules\FAQ\src\Http\Controllers\View\FAQView;

$router->group(function (RouteGroup $routeGroup) {
    $routeGroup->middleware(HasPermissionMiddleware::class);

    $routeGroup->group(function (RouteGroup $adminRouteGroup) {
        $adminRouteGroup->get('list', [FAQView::class, 'list']);
        $adminRouteGroup->get('add', [FAQView::class, 'add']);
        $adminRouteGroup->get('edit/{id}', [FAQView::class, 'edit']);
    }, 'faq/');

    $routeGroup->group(function (RouteGroup $adminRouteGroup) {
        $adminRouteGroup->post('add', [ApiAdminFaqController::class, 'store']);
        $adminRouteGroup->delete('{id}', [ApiAdminFaqController::class, 'delete']);
        $adminRouteGroup->put('{id}', [ApiAdminFaqController::class, 'update']);
    }, 'api/faq/');
}, 'admin/');