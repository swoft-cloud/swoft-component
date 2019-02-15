# CHANGE LOG

## 2019.02.15

- 一些基础性的调整(@inhere)
- 除了framework, bean 组件外，移除所有组件下的 `Helper/Functions.php` 自动加载。后面会移除这些文件(@inhere)

## 2019.02.13

websocket server(@inhere):

- 重构相关逻辑，细分连接上下文和message通信上下文，更清晰的绑定和销毁
- websocket 按请求的path分模块
- 不同模块可以绑定不同的处理控制器，用以处理message通信请求
- 示例结构参考 `websocket-server/test/Fixture` 目录
- 重构server里的消息发送辅助方法(`send` `sendToAll` ...)
- 新增一些辅助方法可以方便的迭代连接(`each` `pageEach`)，已自动去除无效连接

## 2019.02.10

framework:

- 添加基础的connection级别的上下文管理基础定义类(@inhere)

websocket server(@inhere):

- 新增更多自定注解，提供更灵活的使用方式
- 开始支持message通信阶段的请求调度

## 2019.02.05

- 添加组件的单元测试配置，支持针对某个组件运行测试(@inhere)

http sever:

- 添加新的路由实现，基本逻辑不变，做了一些优化(@inhere)

console(@inhere):

- 迁移 console 组件，开始重构它

## 2019.01.20

event(@inhere):

- 迁出独立的事件库，调整以适配 2.0 结构
- 新增 `Subscriber` 注解，支持在同一个类里面处理多个事件

## old ...

- 重构核心，开发 2.0
