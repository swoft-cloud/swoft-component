# record

## websocket status

```php
WEBSOCKET_STATUS_CONNECTION = 1，连接进入等待握手
WEBSOCKET_STATUS_HANDSHAKE = 2，正在握手
WEBSOCKET_STATUS_FRAME = 3，已握手成功等待浏览器发送数据帧
```

- 拒绝连接 比如需要认证，限定路由，限定ip，限定domain等