# CHANGE LOG

> 日期时间都是大概的时间点

## 2019.02.15

- 一些基础性的调整(@inhere)
- 除了framework, bean 组件外，移除所有组件下的 `Helper/Functions.php` 加载。后面会移除这些文件(@inhere)
- 添加swoole部分核心事件的触发绑定
- 添加一些重要的server事件的触发绑定

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

## 2019.02.02

- 添加http server 响应数据解析器(@stelin)
- 添加基础的db和pool组件结构(@stelin)

## 2019.02.01

- 添加新的http路由实现，基本逻辑不变，做了一些优化(@inhere)
- 改造 console 组件，开始重构它的2.0 (@inhere)
- 添加http server请求数据解析器(@stelin)

## 2019.01.30

- 添加新的http middleware实现(@stelin)

## 2019.01.21

- 添加组件的单元测试配置，支持针对某个组件运行测试(@inhere)

event(@inhere):

- 迁出独立的事件库，调整以适配 2.0 结构
- 新增 `Subscriber` 注解，支持在同一个类里面处理多个事件

## 2019.01.25

- 基础的上下文管理实现(@stelin)
- 添加 http-server 组件包(@stelin)
- 添加 http-message 组件包(@stelin)

## 2019.01.22

- 添加 AOP 核心组件包(@stelin)
  - 重构AOP组件逻辑，基于AST语法分析实现

## 2019.01.21

- 添加 bean 核心组件包(@stelin)
- 添加 config 核心组件包(@stelin)
- 添加 annotation 核心组件包(@stelin)
- 添加 framework 核心组件包(@stelin)
- 新的组件加载逻辑实现(@stelin)

## old ...

- 重构核心，准备开发 2.0
- 重新梳理流程
