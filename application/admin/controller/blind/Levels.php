<?php

namespace app\admin\controller\blind;

use app\common\controller\Backend;

/**
 * 盲注级别管理
 *
 * @icon fa fa-circle-o
 */
class Levels extends Backend
{

    /**
     * Levels模型对象
     * @var \app\admin\model\blind\Levels
     */
    protected $model = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\blind\Levels;

        // 获取模板ID参数
        $template_id = $this->request->param('template_id');
        if ($template_id) {
            $this->assign('template_id', $template_id);
            $this->view->assign('template_id', $template_id);
        }
    }



    /**
     * 查看
     */
    public function index()
    {
        // 获取模板ID参数
        $template_id = $this->request->param('template_id');

        if ($this->request->isAjax()) {
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $list = $this->model
                ->where($where)
                ->order($sort, $order)
                ->limit($offset, $limit)
                ->paginate($limit);

            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        }

        // 如果没有指定模板ID，显示模板选择提示
        if (!$template_id) {
            $this->assign('need_template_select', true);
        }

        return $this->view->fetch();
    }

    /**
     * 添加
     */
    public function add()
    {
        // 获取模板ID参数
        $template_id = $this->request->param('template_id');

        if ($this->request->isPost()) {
            $params = $this->request->post("row/a", [], 'strip_tags');
            if ($params) {
                // 如果通过URL传入了模板ID，则设置到数据中
                if ($template_id && !isset($params['template_id'])) {
                    $params['template_id'] = $template_id;
                }

                $result = $this->model->validate()->allowField(true)->save($params);
                if ($result === false) {
                    $this->error($this->model->getError());
                }
                $this->success();
            }
            $this->error();
        }

        // 如果有模板ID，预填充到表单
        if ($template_id) {
            $this->view->assign('template_id', $template_id);
        }

        return $this->view->fetch();
    }

    /**
     * 编辑
     */
    public function edit($ids = null)
    {
        $row = $this->model->get(['id' => $ids]);
        if (!$row) {
            $this->error(__('No Results were found'));
        }

        if ($this->request->isPost()) {
            $params = $this->request->post("row/a", [], 'strip_tags');
            if ($params) {
                $result = $this->model->validate()->allowField(true)->save($params, ['id' => $ids]);
                if ($result === false) {
                    $this->error($this->model->getError());
                }
                $this->success();
            }
            $this->error();
        }

        $this->view->assign("row", $row);
        return $this->view->fetch();
    }

    /**
     * 删除
     */
    public function del($ids = "")
    {
        if (!$this->request->isPost()) {
            $this->error(__("Invalid parameters"));
        }
        $ids = $ids ? $ids : $this->request->post("ids");
        if ($ids) {
            $pk = $this->model->getPk();
            $adminIds = $this->getDataLimitAdminIds();
            if (is_array($adminIds)) {
                $this->model->where($this->dataLimitField, 'in', $adminIds);
            }
            $list = $this->model->where($pk, 'in', $ids)->select();

            $count = 0;
            foreach ($list as $k => $v) {
                $count += $v->delete();
            }
            if ($count) {
                $this->success();
            } else {
                $this->error(__('No rows were deleted'));
            }
        }
        $this->error(__('Parameter %s can not be empty', 'ids'));
    }


}
