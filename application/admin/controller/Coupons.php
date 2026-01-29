<?php

namespace app\admin\controller;

use app\common\controller\Backend;

/**
 * 卡券管理
 *
 * @icon fa fa-circle-o
 */
class Coupons extends Backend
{

    /**
     * 无需登录的方法,同时也就不需要鉴权了
     * @var array
     */
    protected $noNeedLogin = [];


    /**
     * Coupons模型对象
     * @var \app\admin\model\Coupons
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Coupons;
        $this->view->assign("couponTypeList", $this->model->getCouponTypeList());
        $this->view->assign("ticketTypeList", $this->model->getTicketTypeList());
        $this->view->assign("validTypeList", $this->model->getValidTypeList());
        $this->view->assign("statusList", $this->model->getStatusList());
    }

    public function add()
    {
        // 比赛下拉框
        $tournamentList = \app\admin\model\Tournaments::column('id,name');
        $this->view->assign('tournamentList', build_select('row[tournament_id]', $tournamentList, [], ['class' => 'form-control selectpicker']));

        return parent::add();
    }

    public function edit($ids = null)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }

        // 比赛下拉框
        $tournamentList = \app\admin\model\Tournaments::column('id,name');
        $this->view->assign('tournamentList', build_select('row[tournament_id]', $tournamentList, $row['tournament_id'], ['class' => 'form-control selectpicker']));

        return parent::edit($ids);
    }



    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */



}
