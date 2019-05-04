# CHANGE LOG

> 日期时间都是大概的时间点

## 2019.04.29

- ws server 模块路由支持变量(@inhere)
- ws server message 调度逻辑调整(@inhere)

## 2019.04.14

- 完成错误处理组件(@inhere)
- 调整ws server的错误处理(@inhere)

## 2019.03.29

- 调整部分 websocket server 逻辑
- session bean ID 调整可以使用字符串

## 2019.03.26

- 开始重构 RPC 功能(@stelin)
  - 默认使用 JSONRPC 协议

## 2019.03.25

- 完成websocket server基本的事件和消息处理流程 (@inhere)

## 2019.03.15

- 完成 log 组件(@stelin)
- 优化http server处理性能: 延迟解析request URI信息 (@inhere)

## 2019.03.14

- 优化bean获取性能 (@inhere)
- 添加一些快速获取内部bean的方法(@inhere)
- 优化http server处理性能(@inhere)
  - 优化 http request, response 初始化流程
  - 优化 http 调用逻辑

## 2019.03.09

- bean container 属性值注入时，将会首先尝试使用 setter(@inhere)

## 2019.03.08

- console 组件调整(@inhere)
  - 支持添加独立命令Handler
  - `Command` 所在class支持通过 `@example` 设置命令组的使用示例
  - `CommandMapping` 所在命令方法上支持通过 `@example` 设置命令的使用示例

## 2019.03.05

- 优化log日志组件代码(@inhere)
- 优化framework组件代码(@inhere)

## 2019.03.04

- 优化AOP性能

## 2019.03.03

- 优化事件调用性能(@inhere)
- 容器组件基本支出 `REQUEST` `SESSION` 级别的bean(@stelin)

## 2019.02.25

- 完成console组件基本运行逻辑(@inhere)
  - 命令匹配，调度运行
  - 应用，命令组，命令帮助信息渲染

## 2019.02.23

framework(@inhere):

- 基础调整：允许用户禁用启动流程里的 Processor
- 基础调整：允许用户禁用指定的组件
  - 整个组件的注解都不会被扫描加载
  - 组件的基本信息仍然会收集起来

## 2019.02.21

console(@inhere):

- 加入console到组件仓库
- 重构console应用的路由信息搜集和命令匹配
- console组件新增注解
  - 参数 `CommandArgument` 
  - 选项 `CommandOption`
  - 示例参考 `console/test/case/Fixture`

## 2019.02.20

db(@stelin)

- 整理Model
- 整理Builder
- 整理HasAttributes

## 2019.02.17

db(@stelin)

- 修改Model 删除无用代码

## 2019.02.17

bean(@stelin)

- 新增trait Prototype/PrototypeInterface
- 统一使用prototype bean对象，Xxx::new()

## 2019.02.16

db(@stelin)

- 修改QueryBuilder
- 修改connection

framework(@inhere):

- 调整重命名connection级别的上下文管理基础定义类为session
  - 可以管理 TCP,WS 连接生命周期内的上下文数据
  - 可以管理(类似于浏览器中)一个用户认证后的会话生命周期内的数据管理(HTTP-SESSION)
  - 基于一个唯一ID(比如FD，session_id)隔离和管理数据，是可以夸请求的

## 2019.02.15

- 一些基础性的调整(@inhere)
- 除了基础的 bean() config() 等几个方法外，移除其他的全局辅助方法(@inhere)
  - 除了framework, bean 组件外，移除所有组件下的 `Helper/Functions.php` 加载
  - 后面会移除这些文件，即后面将不能使用 request() 这样的方法
- 添加swoole部分核心事件的触发绑定(@inhere)
- 添加一些重要的server事件的触发绑定(@inhere)

## 2019.02.13

websocket server(@inhere):

- 重构相关逻辑，细分连接上下文和message通信上下文，更清晰的绑定和销毁
- websocket 按请求的path分模块
- 不同模块可以绑定不同的处理控制器，用以处理message通信请求
- 示例结构参考 `websocket-server/test/case/Fixture` 目录
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

- 添加 stdlib 组件包(@inhere)
- 基础的请求上下文管理实现(@stelin)
- 添加 http-server 组件包(@stelin)
- 添加 http-message 组件包(@stelin)
- 添加 server, tcp-server 核心组件包(@stelin)

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
