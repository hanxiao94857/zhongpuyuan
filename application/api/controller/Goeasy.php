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
        try {
            // 获取原始POST数据
            $rawData = file_get_contents('php://input');
            if (empty($rawData)) {
                echo json_encode(['code' => 0, 'msg' => '请求体为空', 'data' => null, 'time' => time()]);
                exit;
            }

            $postData = json_decode($rawData, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo json_encode(['code' => 0, 'msg' => 'JSON解析失败：' . json_last_error_msg(), 'data' => null, 'time' => time()]);
                exit;
            }

            $channel = $postData['channel'] ?? '';
            $content = $postData['content'] ?? '';
            $appkey = $postData['appkey'] ?? null;

            // 调用GoEasy服务
            $result = $this->goEasyService->push($channel, $content, $appkey);

            echo json_encode([
                'code' => $result['code'],
                'msg' => $result['msg'],
                'data' => $result['data'],
                'time' => time()
            ]);
            exit;
        } catch (\Exception $e) {
            echo json_encode([
                'code' => 0,
                'msg' => '推送异常：' . $e->getMessage(),
                'data' => null,
                'time' => time()
            ]);
            exit;
        }
    }

    /**
     * 获取频道列表
     */
    public function channels()
    {
        try {
            $userId = $this->auth->isLogin() ? $this->auth->id : null;
            $channels = $this->goEasyService->getChannels($userId);

            echo json_encode([
                'code' => 1,
                'msg' => '获取成功',
                'data' => ['channels' => $channels],
                'time' => time()
            ]);
            exit;
        } catch (\Exception $e) {
            echo json_encode([
                'code' => 0,
                'msg' => '获取频道列表异常：' . $e->getMessage(),
                'data' => null,
                'time' => time()
            ]);
            exit;
        }
    }

    /**
     * 批量推送消息
     */
    public function batchPush()
    {
        try {
            // 获取原始POST数据
            $rawData = file_get_contents('php://input');
            if (empty($rawData)) {
                echo json_encode(['code' => 0, 'msg' => '请求体为空', 'data' => null, 'time' => time()]);
                exit;
            }

            $postData = json_decode($rawData, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo json_encode(['code' => 0, 'msg' => 'JSON解析失败：' . json_last_error_msg(), 'data' => null, 'time' => time()]);
                exit;
            }

            $messages = $postData['messages'] ?? [];
            if (empty($messages) || !is_array($messages)) {
                echo json_encode(['code' => 0, 'msg' => '消息列表不能为空且必须是数组格式', 'data' => null, 'time' => time()]);
                exit;
            }

            if (count($messages) > 10) {
                echo json_encode(['code' => 0, 'msg' => '批量推送最多支持10条消息', 'data' => null, 'time' => time()]);
                exit;
            }

            $appkey = $postData['appkey'] ?? null;

            // 调用批量推送服务
            $result = $this->goEasyService->batchPush($messages, $appkey);

            // 返回结果
            echo json_encode([
                'code' => 1,
                'msg' => $result['msg'],
                'data' => [
                    'total' => $result['data']['total'] ?? 0,
                    'success' => $result['data']['success'] ?? 0,
                    'failed' => $result['data']['failed'] ?? 0
                ],
                'time' => time()
            ]);
            exit;
        } catch (\Exception $e) {
            echo json_encode([
                'code' => 0,
                'msg' => '批量推送异常：' . $e->getMessage(),
                'data' => null,
                'time' => time()
            ]);
            exit;
        }
    }

    /**
     * 验证AppKey
     */
    public function validateAppKey()
    {
        try {
            // 获取原始POST数据
            $rawData = file_get_contents('php://input');
            if (empty($rawData)) {
                echo json_encode(['code' => 0, 'msg' => '请求体为空', 'data' => null, 'time' => time()]);
                exit;
            }

            $postData = json_decode($rawData, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                echo json_encode(['code' => 0, 'msg' => 'JSON解析失败：' . json_last_error_msg(), 'data' => null, 'time' => time()]);
                exit;
            }

            $appkey = $postData['appkey'] ?? '';
            if (empty($appkey)) {
                echo json_encode(['code' => 0, 'msg' => 'AppKey不能为空', 'data' => null, 'time' => time()]);
                exit;
            }

            $isValid = $this->goEasyService->validateAppKey($appkey);

            echo json_encode([
                'code' => 1,
                'msg' => $isValid ? 'AppKey格式正确' : 'AppKey格式不正确',
                'data' => ['valid' => $isValid],
                'time' => time()
            ]);
            exit;
        } catch (\Exception $e) {
            echo json_encode([
                'code' => 0,
                'msg' => '验证异常：' . $e->getMessage(),
                'data' => null,
                'time' => time()
            ]);
            exit;
        }
    }
}