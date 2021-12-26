<?php

namespace App\Admin\Controllers;

use App\Models\Domain;
use App\Models\Tenant;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Controllers\AdminController;
use Encore\Admin\Show;

class DomainController extends AdminController
{
    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid0()
    {
        return Grid::make(new Domain('tenant'), function (Grid $grid) {
            $grid->model()->orderByDesc('id');

            $grid->column('id')->sortable();
            $grid->column('domain')->copyable();
            $grid->column('tenant.name', '关联租户');
            $grid->column('created_at');
            $grid->column('updated_at')->sortable();

            $grid->disableCreateButton();
            $grid->disableEditButton();

            $grid->filter(function (Grid\Filter $filter) {
                $filter->equal('id');
                $filter->like('domain');
                $filter->equal('tenant_id', '关联租户')->select(Tenant::pluck('name', 'id'));
            });
        });
    }

    protected function grid()
    {
        $grid = new Grid(new Domain);

        $grid->id('ID')->sortable();
        $grid->domain('域名');
        //$grid->column('tenant.name', '关联租户');
        $grid->disableEditButton();
        $grid->disableCreateButton();

        $grid->actions(function ($actions) {

            $actions->disableEdit();
            $actions->disableDelete();
        });
     

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
        $show = new Show(Domain::findOrFail($id));

        $show->field('id', __('Id'));
        $show->field('domain', '域名');
        //$show->field('tenant.name', '关联租户');
        $show->field('created_at', 'created_at');
        $show->field('updated_at', 'updated_at');
        //$show->disableEditButton();
        return $show;
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        // 保留这个方法,否则删除功能失效.
        return Form::make(new Domain(), function (Form $form) {

        });
    }
}
