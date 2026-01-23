<?php
/**
 * GoEasy API 测试脚本
 */

// API基础URL
$baseUrl = 'https://zpy.ktmall.cc/index.php?s=api/goeasy/';

echo "=== GoEasy API 测试脚本 ===\n\n";

// 测试函数
function testApi($url, $data = [], $method = 'POST') {
    $ch = curl_init($url);

    if ($method === 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        if (!empty($data)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        }
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    }

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

// 测试1: 验证AppKey
echo "测试1: 验证AppKey\n";
$result = testApi($baseUrl . 'validateAppKey', ['appkey' => 'BC-77b872124f62421ba8486a5aed8cc9c9']);
echo "结果: " . json_encode($result, JSON_UNESCAPED_UNICODE) . "\n\n";

// 测试2: 获取频道列表
echo "测试2: 获取频道列表\n";
$result = testApi($baseUrl . 'channels', [], 'GET');
echo "结果: " . json_encode($result, JSON_UNESCAPED_UNICODE) . "\n\n";

// 测试3: 推送消息
echo "测试3: 推送消息\n";
$result = testApi($baseUrl . 'push', [
    'channel' => 'test_channel',
    'content' => 'Hello from FastAdmin GoEasy API! ' . date('Y-m-d H:i:s')
]);
echo "结果: " . json_encode($result, JSON_UNESCAPED_UNICODE) . "\n\n";

// 测试4: 批量推送
echo "测试4: 批量推送\n";
$result = testApi($baseUrl . 'batchPush', [
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
]);
echo "结果: " . json_encode($result, JSON_UNESCAPED_UNICODE) . "\n\n";

echo "=== 测试完成 ===\n";
echo "\n注意: 如果API返回404错误，可能是服务器配置问题。\n";
echo "请检查nginx配置确保正确处理PHP路由。\n";
