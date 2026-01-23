<?php
/**
 * GoEasy API测试脚本
 */

// 设置应用路径
define('APP_PATH', __DIR__ . '/application/');

// 加载框架
require __DIR__ . '/thinkphp/start.php';

// 测试推送API
echo "=== 测试GoEasy推送API ===\n\n";

try {
    // 测试1: 验证AppKey
    echo "测试1: 验证AppKey\n";
    $url = 'https://zpy.ktmall.cc/api/goeasy/validate';
    $data = ['appkey' => 'BC-77b872124f62421ba8486a5aed8cc9c9'];

    $result = makeRequest($url, $data, 'POST');
    echo "结果: " . json_encode($result, JSON_UNESCAPED_UNICODE) . "\n\n";

    // 测试2: 获取频道列表
    echo "测试2: 获取频道列表\n";
    $url = 'https://zpy.ktmall.cc/api/goeasy/channels';

    $result = makeRequest($url, [], 'GET');
    echo "结果: " . json_encode($result, JSON_UNESCAPED_UNICODE) . "\n\n";

    // 测试3: 推送消息
    echo "测试3: 推送消息\n";
    $url = 'https://zpy.ktmall.cc/api/goeasy/push';
    $data = [
        'channel' => 'test_channel',
        'content' => 'Hello from FastAdmin GoEasy API! ' . date('Y-m-d H:i:s')
    ];

    $result = makeRequest($url, $data, 'POST');
    echo "结果: " . json_encode($result, JSON_UNESCAPED_UNICODE) . "\n\n";

    // 测试4: 批量推送
    echo "测试4: 批量推送\n";
    $url = 'https://zpy.ktmall.cc/api/goeasy/batchPush';
    $data = [
        'messages' => [
            [
                'channel' => 'test_channel',
                'content' => '批量消息1: ' . date('Y-m-d H:i:s')
            ],
            [
                'channel' => 'notification',
                'content' => '批量消息2: ' . date('Y-m-d H:i:s')
            ]
        ]
    ];

    $result = makeRequest($url, $data, 'POST');
    echo "结果: " . json_encode($result, JSON_UNESCAPED_UNICODE) . "\n\n";

} catch (Exception $e) {
    echo "测试出错: " . $e->getMessage() . "\n";
}

/**
 * 发送HTTP请求
 */
function makeRequest($url, $data = [], $method = 'GET')
{
    $ch = curl_init();

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept: application/json'
        ]);
    } elseif (strtoupper($method) === 'GET' && !empty($data)) {
        $url .= '?' . http_build_query($data);
    }

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($ch, CURLOPT_TIMEOUT, 30);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);

    curl_close($ch);

    if ($error) {
        return ['error' => $error, 'http_code' => $httpCode];
    }

    return json_decode($response, true);
}

echo "=== 测试完成 ===\n";
