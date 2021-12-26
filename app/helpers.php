<?php

function test_helper() {
    return 'OK';
}

function route_class()
{
    return str_replace('.', '-', Route::currentRouteName());
}

function ngrok_url($routeName, $parameters = [])
{
    // 开发环境，并且配置了 NGROK_URL
    if(app()->environment('local') && $url = config('app.ngrok_url')) {
        // route() 函数第三个参数代表是否绝对路径
        return $url.route($routeName, $parameters, false);
    }

    return route($routeName, $parameters);
}

//多租户图片url
  function tenantUrl($path)
    {
        if (URL::isValidUrl($path)) {
            return $path;
        }
        if (tenant()) {
            return tenant_asset($path);
        }

        return $this->getStorage()->url($path);
    }