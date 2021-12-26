<?php

namespace App\Admin\Forms;

use Dcat\Admin\Contracts\LazyRenderable;
use Dcat\Admin\Traits\LazyWidget;
use Dcat\Admin\Widgets\Alert;
use Dcat\Admin\Widgets\Form;
use Illuminate\Support\Arr;
use App\Admin\Actions\ShowCurrentAdminUser;

class AdminSetting extends Form implements LazyRenderable
{
    use LazyWidget;

    /**
     * 处理表单请求.
     *
     * @param  array  $input
     *
     * @return mixed
     */
    public function handle(array $input)
    {
        foreach (Arr::dot($input) as $k => $v) {
            admin_setting([$k => $v]);
        }

        return $this->response()->success('设置成功');
    }

    /**
     * 构建表单.
     */
    public function form()
    {
        $this->confirm('确认提交设置吗?', '请确认您所填写的配置项数据都是正确的');

        $this->tab('基础配置', function () {
            $site_name_now = admin_setting('site_name');
            $alert = new Alert('系统配置测试site_name_now:'.$site_name_now);
            $this->html($alert->warning());
            $this->text('site_name')->required()->help('网站名称');
            $this->email('contact_email')->rules('email');
            $this->text('seo_description');
            $this->text('seo_keyword');
              
        });

        $this->tab('我是谁', function () { 
            $this->html(ShowCurrentAdminUser::make());
            
            
        });
 
    }

    /**
     * 设置接口保存成功后的回调JS代码.
     *
     * 1.2秒后刷新整个页面.
     *
     * @return string|void
     */
    public function savedScript()
    {
        return <<<'JS'
    if (data.status) {
        setTimeout(function () {
          location.reload()
        }, 1200);
    }
JS;
    }

    /**
     * 返回表单数据.
     *
     * @return array
     */
    public function default()
    {
        return admin_setting()->toArray();
    }
}
