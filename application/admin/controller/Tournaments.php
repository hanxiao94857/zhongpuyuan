<?php

namespace app\admin\controller;

use app\common\controller\Backend;

/**
 * 德州扑克MTT比赛管理
 *
 * @icon fa fa-circle-o
 */
class Tournaments extends Backend
{

    /**
     * 无需登录的方法,同时也就不需要鉴权了
     * @var array
     */
    protected $noNeedLogin = [];

    /**
     * 无需鉴权的方法,但需要登录
     * @var array
     */
    protected $noNeedRight = ['selectpage', 'prizes', 'prizesadd', 'prizesedit', 'prizesdel'];

    /**
     * Tournaments模型对象
     * @var \app\admin\model\Tournaments
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Tournaments;
        $this->view->assign("tournamentTypeList", $this->model->getTournamentTypeList());
        $this->view->assign("buyInTypeList", $this->model->getBuyInTypeList());
        $this->view->assign("tournamentStatusList", $this->model->getTournamentStatusList());
        $this->view->assign("registrationStatusList", $this->model->getRegistrationStatusList());
    }

    public function add()
    {
        // 盲注模板下拉框
        $blindTemplateList = \app\admin\model\blind\Templates::column('id,name');
        $this->view->assign('blindTemplateList', build_select('row[blind_template_id]', $blindTemplateList, [], ['class' => 'form-control selectpicker']));

        // 卡券下拉框
        $couponList = \app\admin\model\Coupons::column('id,name');
        $this->view->assign('couponList', build_select('row[buy_in_coupon_id]', $couponList, [], ['class' => 'form-control selectpicker']));

        return parent::add();
    }

    public function edit($ids = null)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }

        // 盲注模板下拉框
        $blindTemplateList = \app\admin\model\blind\Templates::column('id,name');
        $this->view->assign('blindTemplateList', build_select('row[blind_template_id]', $blindTemplateList, $row['blind_template_id'], ['class' => 'form-control selectpicker']));

        // 卡券下拉框
        $couponList = \app\admin\model\Coupons::column('id,name');
        $this->view->assign('couponList', build_select('row[buy_in_coupon_id]', $couponList, $row['buy_in_coupon_id'], ['class' => 'form-control selectpicker']));

        return parent::edit($ids);
    }

    public function detail($ids = null)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }

        // 获取关联数据
        if ($row['blind_template_id']) {
            $blindTemplate = \app\admin\model\blind\Templates::find($row['blind_template_id']);
            $this->view->assign('blindTemplate', $blindTemplate);
        }

        if ($row['buy_in_coupon_id']) {
            $coupon = \app\admin\model\Coupons::find($row['buy_in_coupon_id']);
            $this->view->assign('coupon', $coupon);
        }

        $this->view->assign('row', $row);
        return $this->view->fetch();
    }

    public function prizes()
    {
        // 获取比赛ID参数
        $tournament_id = $this->request->param('tournament_id');

        // 获取比赛信息
        $tournament = $this->model->find($tournament_id);
        if (!$tournament) {
            $this->error('比赛不存在');
        }
        $this->view->assign('tournament', $tournament);

        // 设置页面变量
        $this->assign('tournament_id', $tournament_id);
        $this->assign('jsname', 'tournaments');

        if ($this->request->isAjax()) {
            // 使用buildparams来处理bootstrap-table的请求参数
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            // 获取奖励配置列表
            $prizes = \think\Db::name('tournament_prizes')
                ->where('tournament_id', $tournament_id)
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->select();

            // 获取总数
            $total = \think\Db::name('tournament_prizes')
                ->where('tournament_id', $tournament_id)
                ->where($where)
                ->count();

            // 获取卡券名称映射
            $coupons = \app\admin\model\Coupons::column('id,name');

            // 格式化数据
            foreach ($prizes as &$prize) {
                $prize['position_text'] = '第' . $prize['position'] . '名';
                $prize['reward_type_text'] = $this->getRewardTypeText($prize['reward_type']);
                $prize['points_reward_text'] = $prize['points_reward'] > 0 ? $prize['points_reward'] : '-';
                $prize['coupon_text'] = $prize['coupon_id'] ? ($coupons[$prize['coupon_id']] ?? '未知卡券') : '-';
                $prize['min_players_text'] = $prize['min_players'] > 0 ? $prize['min_players'] . '+' : '无要求';
                $prize['description'] = $prize['description'] ?: '-';
            }

            return json(['total' => $total, 'rows' => $prizes]);
        }

        return $this->view->fetch();
    }

    // 奖励配置添加
    public function prizesadd()
    {
        // 获取比赛ID参数
        $tournament_id = $this->request->param('tournament_id');

        // 获取比赛信息
        $tournament = $this->model->find($tournament_id);
        if (!$tournament) {
            $this->error('比赛不存在');
        }

        if ($this->request->isPost()) {
            $data = $this->request->post('row/a');
            $data['tournament_id'] = $tournament_id;

            $result = \think\Db::name('tournament_prizes')->insert($data);
            if ($result !== false) {
                $this->success('添加成功');
            } else {
                $this->error('添加失败');
            }
        }

        // 获取卡券列表
        $coupons = \app\admin\model\Coupons::column('id,name');
        $this->view->assign('coupons', $coupons);

        // 设置tournament_id到视图
        $this->view->assign('tournament_id', $tournament_id);

        return $this->view->fetch('prizes_add');
    }

    // 奖励配置编辑
    public function prizesedit($ids = null)
    {
        $row = \think\Db::name('tournament_prizes')->find($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }

        // 获取比赛ID参数
        $tournament_id = $this->request->param('tournament_id') ?: $row['tournament_id'];

        // 获取比赛信息
        $tournament = $this->model->find($tournament_id);
        if (!$tournament) {
            $this->error('比赛不存在');
        }

        if ($this->request->isPost()) {
            $data = $this->request->post('row/a');
            $result = \think\Db::name('tournament_prizes')->where('id', $ids)->update($data);
            if ($result !== false) {
                $this->success('更新成功');
            } else {
                $this->error('更新失败');
            }
        }

        // 获取卡券列表
        $coupons = \app\admin\model\Coupons::column('id,name');
        $this->view->assign('coupons', $coupons);
        $this->view->assign('row', $row);
        $this->view->assign('tournament_id', $tournament_id);

        return $this->view->fetch('prizes_edit');
    }

    // 奖励配置删除
    public function prizesdel($ids = '')
    {
        if (!$this->request->isPost()) {
            $this->error(__("Invalid parameters"));
        }

        $ids = $ids ? $ids : $this->request->post('ids');
        if ($ids) {
            $result = \think\Db::name('tournament_prizes')->where('id', 'in', $ids)->delete();
            if ($result) {
                $this->success('删除成功');
            } else {
                $this->error('删除失败');
            }
        } else {
            $this->error('请选择要删除的记录');
        }
    }

    private function getRewardTypeText($type)
    {
        $types = [
            'points_only' => '仅积分',
            'coupon_only' => '仅卡券',
            'points_and_coupon' => '积分+卡券',
            'none' => '无奖励'
        ];
        return $types[$type] ?? '未知';
    }



    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */

    /**
     * Selectpage的实现方法
     * 当前方法只是一个比较通用的Selectpage实现
     * 如果有一些特殊的Selectpage需求，可以复制该方法到子类中进行重写
     */
    public function selectpage()
    {
        // 设置主键
        $this->model->setPk('id');

        // 设置查询字段
        $this->request->request([
            'keyField' => 'id',
            'textField' => 'name'
        ]);

        return parent::selectpage();
    }


}
