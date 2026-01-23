<?php

namespace app\api\library;

/**
 * GoEasy服务类
 */
class GoEasyService
{
    // GoEasy配置
    private $config = [
        'appkey' => 'BC-77b872124f62421ba8486a5aed8cc9c9',
        'url' => 'https://rest-hz.goeasy.io/v2/pubsub/publish',
        'timeout' => 30
    ];

    /**
     * 构造函数
     */
    public function __construct()
    {
        // 可以从配置文件读取
        // $this->config = config('goeasy') ?? $this->config;
    }

    /**
     * 发送消息到GoEasy
     *
     * @param string $channel 频道名称
     * @param string $content 消息内容
     * @param string $appkey  AppKey（可选）
     * @return array
     */
    public function push($channel, $content, $appkey = null)
    {
        if (empty($channel)) {
            return $this->error('频道名称不能为空');
        }

        if (empty($content)) {
            return $this->error('消息内容不能为空');
        }

        $appkey = $appkey ?: $this->config['appkey'];

        return $this->sendRequest($appkey, $channel, $content);
    }

    /**
     * 发送HTTP请求到GoEasy
     *
     * @param string $appkey
     * @param string $channel
     * @param string $content
     * @return array
     */
    private function sendRequest($appkey, $channel, $content)
    {
        $url = $this->config['url'];

        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_TIMEOUT, $this->config['timeout']);

        $headers = array(
            "Accept: application/json",
            "Content-Type: application/json",
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $data = json_encode([
            'appkey' => $appkey,
            'channel' => $channel,
            'content' => $content
        ]);

        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        $resp = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $error = curl_error($curl);
        curl_close($curl);

        if ($error) {
            return $this->error('网络请求失败：' . $error);
        }

        if ($httpCode != 200) {
            return $this->error('GoEasy服务返回错误，HTTP状态码：' . $httpCode);
        }

        $responseData = json_decode($resp, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            return $this->error('解析响应失败：' . json_last_error_msg());
        }

        // 检查GoEasy返回结果
        if (isset($responseData['code']) && $responseData['code'] == 200) {
            return $this->success('推送成功', $responseData);
        } else {
            $errorMsg = isset($responseData['content']) ? $responseData['content'] : '推送失败';
            return $this->error($errorMsg, $responseData);
        }
    }

    /**
     * 批量推送消息
     *
     * @param array $messages 消息数组，格式：[['channel'=>'xxx', 'content'=>'xxx'], ...]
     * @param string $appkey
     * @return array
     */
    public function batchPush($messages, $appkey = null)
    {
        if (empty($messages) || !is_array($messages)) {
            return $this->error('消息列表不能为空');
        }

        $results = [];
        $successCount = 0;
        $failCount = 0;

        foreach ($messages as $index => $message) {
            if (!isset($message['channel']) || !isset($message['content'])) {
                $results[$index] = $this->error('消息格式错误：缺少channel或content');
                $failCount++;
                continue;
            }

            $result = $this->push($message['channel'], $message['content'], $appkey);
            $results[$index] = $result;

            if ($result['code'] == 1) {
                $successCount++;
            } else {
                $failCount++;
            }
        }

        return [
            'code' => 1, // 总是返回成功，因为至少尝试了推送
            'msg' => "批量推送完成，成功：{$successCount}，失败：{$failCount}",
            'data' => [
                'total' => count($messages),
                'success' => $successCount,
                'failed' => $failCount
                // 移除results数组以避免大数据问题
            ]
        ];
    }

    /**
     * 验证AppKey格式
     *
     * @param string $appkey
     * @return bool
     */
    public function validateAppKey($appkey)
    {
        return preg_match('/^BC-[a-f0-9]{32}$/', $appkey);
    }

    /**
     * 获取频道列表
     *
     * @param int $userId 用户ID
     * @return array
     */
    public function getChannels($userId = null)
    {
        $channels = [
            'test_channel',
            'notification',
            'chat',
            'system'
        ];

        if ($userId) {
            $channels[] = 'user_' . $userId;
            $channels[] = 'private_' . $userId;
        }

        return $channels;
    }

    /**
     * 返回成功结果
     */
    private function success($msg = '操作成功', $data = [])
    {
        return [
            'code' => 1,
            'msg' => $msg,
            'data' => $data
        ];
    }

    /**
     * 返回错误结果
     */
    private function error($msg = '操作失败', $data = [])
    {
        return [
            'code' => 0,
            'msg' => $msg,
            'data' => $data
        ];
    }
}
