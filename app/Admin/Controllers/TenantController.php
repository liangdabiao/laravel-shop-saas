<?php

namespace App\Admin\Controllers;

use App\Admin\Actions\Grid\SwitchToTenantDashboard;
use App\Models\Tenant;
use App\Models\Domain;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Show;

class TenantController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid0()
    {
        return Grid::make(new Tenant('domains'), function (Grid $grid) {
            $grid->model()->orderByDesc('created_at');

            $grid->column('id')->copyable();
            $grid->column('name');
            $grid->column('level');
            $grid->column('domains', '域名')->display(function ($domains) {
                if (count($domains) == 0) {
                    return '-';
                }
                $domainString = '';
                foreach ($domains as &$domain) {
                    $domainString .= $domain->domain.'<br/>';
                }
                return $domainString;
            });
            $grid->column('expired_at');
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
                $filter->like('name');
            });
            // 添加登陆至租户后台的功能.
            $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->append(new SwitchToTenantDashboard());
            });
        });
    }

    protected function grid()
    {
        $grid = new Grid(new Tenant);

        $grid->id('ID')->sortable();
        $grid->name('租户名称');
        $grid->domains('域名')->display(function ($domains) { 
            if (count($domains) == 0) {
                    return '-';
                }
                $domainString = '';
                foreach ($domains as &$domain) {
                    $domainString .= $domain['domain'].'<br/>';
                }
                return $domainString;
        });
        //$grid->column('expired_at');
        $grid->column('created_at');
        $grid->column('updated_at')->sortable();

        $grid->actions(function ($actions) {
            $actions->disableView();
            $actions->disableDelete();
        });
        $grid->tools(function ($tools) {
            // 禁用批量删除按钮
            $tools->batch(function ($batch) {
                $batch->disableDelete();
            });
        });


        /*$grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
                $filter->like('name');
            });
            // 添加登陆至租户后台的功能.
        $grid->actions(function (Grid\Displayers\Actions $actions) {
                $actions->append(new SwitchToTenantDashboard());
            });*/

        return $grid;
    }

    /**
     * Make a show builder.
     *
     * @param  mixed  $id
     *
     * @return Show
     */
    protected function detail($id)
    {
        return Show::make($id, new Tenant('domains'), function (Show $show) {
            $show->field('id');
            $show->field('name');
            $show->field('level');
            $show->field('expired_at');
            $show->field('created_at');
            $show->field('updated_at');

            $show->relation('domains', '域名', function ($model) {
                $grid = new Grid(new Domain());

                $grid->model()->where('tenant_id', $model->id);

                $grid->setResource('/domain');

                $grid->id();
                $grid->domain('域名');
                $grid->created_at();
                $grid->updated_at();

                $grid->disableRowSelector();
                $grid->disableCreateButton();

                return $grid;
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form0()
    {
        return Form::make(new Tenant('domains'), function (Form $form) {
            $form->display('id');
            $form->text('name')
                ->rules(
                    'required|unique:tenants,name,'.$form->getKey(),
                    [
                        'required' => '请填写租户名称',
                        'unique' => '租户名称已经存在'
                    ]
                )
                ->required();
            $form->hasMany('domains', function (Form\NestedForm $form) {
                $form->text('domain', '域名');
            })->useTable();

            $form->text('level');

            $form->datetime('expired_at')->rules(
                'required|date_format:Y-m-d H:i:s',
                [
                    'required' => '请选择过期时间',
                    'datetime' => '时间日期格式不正确'
                ])
                ->required();

            $form->display('created_at');
            $form->display('updated_at');
        });
    }
    protected function form()
    {
        $form = new Form(new Tenant);

        // 创建一个输入框，第一个参数 title 是模型的字段名，第二个参数是该字段描述
        $form->text('name', '租户名称')->rules('required');
        // 直接添加一对多的关联模型
        $form->hasMany('domains', '域名', function (Form\NestedForm $form) {
            $form->text('domain', '域名');
        })->useTable();

        $form->display('created_at');
        $form->display('updated_at');

        // 定义事件回调，当模型即将保存时会触发这个回调
        $form->saving(function (Form $form) {
            //$form->model()->price = collect($form->input('skus'))->where(Form::REMOVE_FLAG_NAME, 0)->min('price') ?: 0;
        });

        return $form;
    }
}
