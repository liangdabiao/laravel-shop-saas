<?php

namespace App\Jobs;

use Dcat\Admin\Models\Menu;
use Stancl\Tenancy\Contracts\Tenant;
use Carbon\Carbon;

class UpdateAdminMenuForTenant
{
    protected $tenant;

    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    public function handle()
    {
        $this->tenant->run(function ($tenant) {

            //\Artisan::call('cache:clear');

            // 更新首页URL
            //Menu::where('id', 1)->update(['uri' => '/dashboard']);


            /*$createdAt = Carbon::now();
            Menu::insert([
            [
                'parent_id' => 0,
                'order' => 8,
                'title' => '内容管理',
                'icon' => 'fa-align-justify',
                'uri' => '',
                'created_at' => $createdAt,
            ],
            [
                'parent_id' => 8,
                'order' => 9,
                'title' => 'category',
                'icon' => 'fa-internet-explorer',
                'uri' => '/categories',
                'created_at' => $createdAt,
            ],
            [
                'parent_id' => 8,
                'order' => 10,
                'title' => 'topic',
                'icon' => 'fa-internet-explorer',
                'uri' => '/topics',
                'created_at' => $createdAt,
            ],
            [
                'parent_id' => 8,
                'order' => 11,
                'title' => 'reply',
                'icon' => 'fa-internet-explorer',
                'uri' => '/replies',
                'created_at' => $createdAt,
            ],
            [
                'parent_id' => 8,
                'order' => 12,
                'title' => 'links',
                'icon' => 'fa-internet-explorer',
                'uri' => '/links',
                'created_at' => $createdAt,
            ]
            ]);*/

        });
    }
}