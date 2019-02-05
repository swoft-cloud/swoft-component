# Idea

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