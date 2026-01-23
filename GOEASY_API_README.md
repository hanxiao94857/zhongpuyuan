# GoEasy推送服务API文档

基于FastAdmin框架开发的GoEasy推送服务API，提供完整的推送功能和频道管理。

## API端点

所有API都使用POST方法，基础URL: `https://zpy.ktmall.cc/index.php?s=api/goeasy/`

## 1. 推送消息 (push)

向指定频道推送消息到GoEasy服务。

**URL:** `POST /index.php?s=api/goeasy/push`

**请求参数:**
```json
{
  "channel": "test_channel",    // 必填：推送频道名称
  "content": "Hello World",     // 必填：推送的消息内容
  "appkey": "BC-xxx"           // 可选：GoEasy AppKey，默认使用配置的
}
```

**响应示例:**
```json
{
  "code": 1,
  "msg": "推送成功",
  "data": {
    "code": 200,
    "content": "success"
  }
}
```

**cURL测试:**
```bash
curl -X POST "https://zpy.ktmall.cc/index.php?s=api/goeasy/push" \
  -H "Content-Type: application/json" \
  -d '{
    "channel": "test_channel",
    "content": "Hello from FastAdmin GoEasy API!"
  }'
```

## 2. 批量推送消息 (batchPush)

批量向多个频道推送消息。

**URL:** `POST /index.php?s=api/goeasy/batchPush`

**请求参数:**
```json
{
  "messages": [
    {
      "channel": "test_channel",
      "content": "消息1"
    },
    {
      "channel": "notification",
      "content": "消息2"
    }
  ],
  "appkey": "BC-xxx"  // 可选
}
```

**响应示例:**
```json
{
  "code": 1,
  "msg": "批量推送完成，成功：2，失败：0",
  "data": {
    "total": 2,
    "success": 2,
    "failed": 0,
    "results": [...]
  }
}
```

## 3. 获取频道列表 (channels)

获取系统支持的推送频道列表。

**URL:** `GET /index.php?s=api/goeasy/channels`

**响应示例:**
```json
{
  "code": 1,
  "msg": "获取成功",
  "data": {
    "channels": [
      "test_channel",
      "notification",
      "chat",
      "system",
      "user_123"
    ]
  }
}
```

## 4. 验证AppKey (validateAppKey)

验证GoEasy AppKey格式是否正确。

**URL:** `POST /index.php?s=api/goeasy/validateAppKey`

**请求参数:**
```json
{
  "appkey": "BC-77b872124f62421ba8486a5aed8cc9c9"
}
```

**响应示例:**
```json
{
  "code": 1,
  "msg": "AppKey格式正确",
  "data": {
    "valid": true
  }
}
```

## 配置信息

- **GoEasy AppKey:** `BC-77b872124f62421ba8486a5aed8cc9c9`
- **API端点:** `https://rest-hz.goeasy.io/v2/pubsub/publish`
- **请求超时:** 30秒

## 权限设置

所有API接口都设置为无需登录和鉴权，可以直接调用。

## 文件结构

```
application/
├── api/
│   ├── controller/
│   │   └── Goeasy.php          # API控制器 (注意：文件名必须小写)
│   └── library/
│       └── GoEasyService.php   # 业务逻辑服务类
```

**重要说明：**
- 控制器文件名必须为 `Goeasy.php` (小写)，类名为 `Goeasy`
- 这是由于ThinkPHP/FastAdmin框架对控制器文件大小写的严格要求
- API响应使用直接JSON输出，避免FastAdmin响应方法的数据序列化问题

## 使用示例代码

### PHP调用示例

```php
<?php
// 推送消息
$url = 'https://zpy.ktmall.cc/index.php?s=api/goeasy/push';
$data = [
    'channel' => 'test_channel',
    'content' => 'Hello GoEasy!'
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$result = curl_exec($ch);
curl_close($ch);

echo $result;
```

### JavaScript调用示例

```javascript
// 推送消息
fetch('https://zpy.ktmall.cc/index.php?s=api/goeasy/push', {
  method: 'POST',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    channel: 'test_channel',
    content: 'Hello from JavaScript!'
  })
})
.then(response => response.json())
.then(data => console.log(data));
```

## 错误处理

API返回统一的错误格式：

```json
{
  "code": 0,
  "msg": "错误信息",
  "data": {}
}
```

常见错误：
- `频道名称不能为空`
- `消息内容不能为空`
- `网络请求失败`
- `GoEasy服务返回错误`

## 开发规范

遵循FastAdmin开发规范：
- 使用标准的API响应格式
- 完善的参数验证
- 详细的API文档注释
- 模块化的服务类设计
