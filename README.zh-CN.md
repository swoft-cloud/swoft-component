# Swoft Component

这里是swoft基础和核心组件的开发仓库

## [English](README.md)

## 参与贡献

开发组非常欢迎各位向我们提交PR(_Pull Request_)，但是为了保证代码质量和统一的风格，向官方的主仓库 [swoft/swoft](https://github.com/swoft-cloud/swoft) 和 开发仓库贡献代码时需要注意代码和commit格式

### 发起PR时的注意事项

- 请不要提交PR到各个组件子仓库，它们都是 **只读的**
- 核心组件的 _开发仓库_ 是 **[swoft/swoft-component][core]**
- 扩展组件的 _开发仓库_ 是 **[swoft/swoft-ext][ext]**
- 请 `fork` 对应的开发仓库，修改后，请把你的PR提交到对应的开发仓库

> 发布版本时官方会将代码同步到各个子仓库
### Commit Message

- commit message 只能是英文信息
- 请尽量保证commit message是有意义的说明
- 最好以 `add:` `update:` `fix:` 等关键字开头

### 代码风格

- 提交的PHP代码 **必须** 遵循 PSR-2 代码风格
- 合理且有意义的类、方法、变量命名
- 适当的注释，合理的使用空行保持代码的简洁，易于阅读
- 不要包含一些无意义的信息 例如 `@author` 等(_提交者是能够从commit log里看到的_)


[core]: https://github.com/swoft-cloud/swoft-component
[ext]: https://github.com/swoft-cloud/swoft-ext
