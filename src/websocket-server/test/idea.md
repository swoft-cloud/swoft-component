# Idea

## 关于 fd

- `fd` 是TCP客户端连接的标识符，在Server程序中是唯一的
- `fd` 是一个自增数字，范围是1 ～ 1600万，`fd`超过1600万后会自动从1开始进行复用
- `fd` 是复用的，当连接关闭后`fd`会被新进入的连接复用

## design

- websocket 按请求的路由(eg `/echo`)分模块
- 建立连接后，每次消息请求划分不同的handler

```text
                   swoft ws server
                          |
            +--------------------------------+
            |                                |
        echo server module                   |
(path /echo, namespace /echo)                |
                                        chat server module
                                (path /chat, namespace /chat)  
                                             |
                               -------------------
                               |      |       |      ......
                            room 1  room 2  room 3   ......             
```

应用目录结构：

```text
/WebSocket
  EchoModule.php
  ChatModule.php
  ... ...
  Controller/
    EchoController.php
    ChatController.php
    ... ...
```