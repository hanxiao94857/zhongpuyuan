<?php
/**
 * 直接测试控制器
 */

define('APP_PATH', __DIR__ . '/application/');
require __DIR__ . '/thinkphp/start.php';

try {
    // 实例化控制器
    $controller = new \app\api\controller\GoEasy();

    echo "控制器实例化成功\n";

    // 测试validate方法
    $request = \think\Request::instance();
    $request->param(['appkey' => 'BC-77b872124f62421ba8486a5aed8cc9c9']);

    $controller->validate();

} catch (Exception $e) {
    echo "错误: " . $e->getMessage() . "\n";
    echo "文件: " . $e->getFile() . "\n";
    echo "行号: " . $e->getLine() . "\n";
}
