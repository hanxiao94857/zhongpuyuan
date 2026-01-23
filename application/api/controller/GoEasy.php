<?php

namespace app\api\controller;

use app\api\library\GoEasyService;
use app\common\controller\Api;

/**
 * GoEasy推送服务接口
 */
class Goeasy extends Api
{

    //无需登录的接口,*表示全部
    protected $noNeedLogin = ['push', 'channels', 'validateAppKey', 'batchPush'];
    //无需鉴权的接口,*表示全部
    protected $noNeedRight = ['push', 'channels', 'validateAppKey', 'batchPush'];

    /**
     * @var GoEasyService
     */
    protected $goEasyService;

    /**
     * 初始化
     */
    protected function _initialize()
    {
        parent::_initialize();
        $this->goEasyService = new GoEasyService();
    }

    /**
     * 推送消息到GoEasy
     */
    public function push()
    {
        $channel = $this->request->param('channel');
        $content = $this->request->param('content');
        $appkey = $this->request->param('appkey');

        // 调用GoEasy服务
        $result = $this->goEasyService->push($channel, $content, $appkey);

        if ($result['code'] == 1) {
            $this->success($result['msg'], $result['data']);
        } else {
            $this->error($result['msg'], $result['data']);
        }
    }

    /**
     * 获取频道列表
     */
    public function channels()
    {
        $userId = $this->auth->isLogin() ? $this->auth->id : null;
        $channels = $this->goEasyService->getChannels($userId);

        $this->success('获取成功', ['channels' => $channels]);
    }

    /**
     * 批量推送消息
     */
    public function batchPush()
    {
        $messages = $this->request->param('messages');
        $appkey = $this->request->param('appkey');

        $result = $this->goEasyService->batchPush($messages, $appkey);

        if ($result['code'] == 1) {
            $this->success($result['msg'], $result['data']);
        } else {
            $this->error($result['msg'], $result['data']);
        }
    }

    /**
     * 验证AppKey
     */
    public function validateAppKey()
    {
        $appkey = $this->request->param('appkey');

        if (empty($appkey)) {
            $this->error('AppKey不能为空');
        }

        $isValid = $this->goEasyService->validateAppKey($appkey);

        if ($isValid) {
            $this->success('AppKey格式正确', ['valid' => true]);
        } else {
            $this->error('AppKey格式不正确', ['valid' => false]);
        }
    }
}