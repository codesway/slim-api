## Config目录介绍

### .env
* 配置当前选择环境

### develop|example|local|online
* example提供示例
* 其余的是当前环境的对应目录


### 目录中的文件解释
* alarm 预警相关配置
* base 核心代码层，不牵扯业务的通用配置
* database 数据库相关配置
* error 错误|异常相关配置
* global 业务级，不牵扯框架以及核心代码层的全局配置
* logger 日志相关配置
* middleware 中间件相关配置
* slim slim的配置，不包含核心代码层和业务级配置
* ...后续还会增加其他类型的配置等