# PR

- 请不要提交PR到各个组件仓库，它们都是 **只读的**
- 核心组件的 **开发仓库** 是 **[swoft/swoft-component][core]**
- 扩展组件的 **开发仓库** 是 **[swoft/swoft-ext][ext]**
- 请 `fork` 对应的 **开发仓库**，修改后，请把你的PR提交到对应的开发仓库

> 发布版本时官方会将代码同步到各个子仓库。因此，切记不要往子仓库发PR。

## 发起PR时的注意事项

开发组非常欢迎各位向我们提交PR(_Pull Request_)，但是为了保证代码质量和统一的风格，
向官方的主仓库 [swoft/swoft][main] 和 **开发仓库** 贡献代码时需要注意代码和commit格式

### Commit Message

- commit message 只能是英文信息
- 请尽量保证commit message是有意义的说明
- 最好以 `add:` `update:` `fix:` 等关键字开头

### 代码风格

- 提交的PHP代码 **必须** 遵循 PSR-2 代码风格
- 合理且有意义的类、方法、变量命名
- 适当的注释，合理的使用空行保持代码的简洁，易于阅读
- 不要包含一些无意义的信息 例如 `@author` 等(_提交者是能够从commit log里看到的_)

------------------

> English Version (_translate by Google_)

The development team welcomes you to submit PR (_Pull Request_) to us, but to ensure code quality and uniform style, 
go to the official main repository [swoft/swoft][main] and Development repository, Note the code and commit format when contributing code

## Precautions when initiating PR

- Please do not submit PR to each sub-repository, they are all read-only
- The _development repository_ for the core components is **[swoft/swoft-component][core]**
- The _development repository_ for extension components is **[swoft/swoft-ext][ext]**
- Please `fork` the corresponding development warehouse. After modification, please submit your PR to the corresponding development warehouse.

> Officially syncs code to individual sub-warehouses when new versions are released

### Commit Message

- the commit message can only be in English
- Please try to ensure that the commit message is meaningful
- it is best to start with the keyword `add:` `update:` `fix:`

### Code Style

- Submitted PHP code **Must** Follow PSR-2 code style
- Reasonable and meaningful class, method, variable naming
- Appropriate comments, reasonable use of blank lines to keep the code simple and easy to read
- Don't include some meaningless information such as `@author`, etc. (_author is  that can be seen from the commit log_)


[main]: https://github.com/swoft-cloud/swoft
[core]: https://github.com/swoft-cloud/swoft-component
[ext]: https://github.com/swoft-cloud/swoft-ext
