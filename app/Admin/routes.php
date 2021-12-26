<?php


use App\Features\UserImpersonation;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;
use Stancl\Tenancy\Middleware\ScopeSessions;
use Stancl\Tenancy\Middleware\CheckTenantForMaintenanceMode;

Admin::routes();

/**
 * 租户管理员可以通过此路由进入租户后台.
 */
Route::middleware([
    'web','admin',
    CheckTenantForMaintenanceMode::class,
    ScopeSessions::class,
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])
    ->prefix(config('admin.route.prefix'))
    ->namespace(config('admin.route.namespace'))
    ->group(function (Router $router) {
        $router->get('/god/{token}', function ($token) {
            return UserImpersonation::makeResponse($token);
        });

        $router->get('/', 'HomeController@index');
        $router->get('users', 'UsersController@index');
        $router->get('products', 'ProductsController@index');
        $router->get('products/create', 'ProductsController@create');
        $router->post('products', 'ProductsController@store');
        $router->get('products/{id}/edit', 'ProductsController@edit');
        $router->put('products/{id}', 'ProductsController@update');
        $router->get('orders', 'OrdersController@index')->name('admin.orders.index');
        $router->get('orders/{order}', 'OrdersController@show')->name('admin.orders.show');
        $router->post('orders/{order}/ship', 'OrdersController@ship')->name('admin.orders.ship');
        $router->post('orders/{order}/refund', 'OrdersController@handleRefund')->name('admin.orders.handle_refund');
        $router->get('coupon_codes', 'CouponCodesController@index');
        $router->post('coupon_codes', 'CouponCodesController@store');
        $router->get('coupon_codes/create', 'CouponCodesController@create');
        $router->get('coupon_codes/{id}/edit', 'CouponCodesController@edit');
        $router->put('coupon_codes/{id}', 'CouponCodesController@update');
        $router->delete('coupon_codes/{id}', 'CouponCodesController@destroy');

    });
 

/**
 * 超级管理员可以通过此路由进入租户后台.
 */
Route::group([
    'prefix'        => config('admin.route.prefix'),
    'namespace'     => config('admin.route.namespace'),
    'middleware'    => config('admin.route.middleware'),
    'domain'        => config('tenancy.central_domains')[0],
], function (Router $router) {

    // 租户管理
    $router->resource('/tenant', 'TenantController');
    // 域名管理
    $router->resource('/domain', 'DomainController')->only(['index', 'destroy', 'show']);
    

    $router->get('/', 'HomeController@index');
    $router->get('users', 'UsersController@index');
    $router->get('products', 'ProductsController@index');
    $router->get('products/create', 'ProductsController@create');
    $router->post('products', 'ProductsController@store');
    $router->get('products/{id}/edit', 'ProductsController@edit');
    $router->put('products/{id}', 'ProductsController@update');
    $router->get('orders', 'OrdersController@index')->name('admin.orders.index');
    $router->get('orders/{order}', 'OrdersController@show')->name('admin.orders.show');
    $router->post('orders/{order}/ship', 'OrdersController@ship')->name('admin.orders.ship');
    $router->post('orders/{order}/refund', 'OrdersController@handleRefund')->name('admin.orders.handle_refund');
    $router->get('coupon_codes', 'CouponCodesController@index');
    $router->post('coupon_codes', 'CouponCodesController@store');
    $router->get('coupon_codes/create', 'CouponCodesController@create');
    $router->get('coupon_codes/{id}/edit', 'CouponCodesController@edit');
    $router->put('coupon_codes/{id}', 'CouponCodesController@update');
    $router->delete('coupon_codes/{id}', 'CouponCodesController@destroy');

});
