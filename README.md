
# 请注意
```$xslt
#################名词解释#####################
接入层：路由直接调用的class
层接管：打通slim预定义、提供的功能
数据层：直接操作数据库的class

###################改动事项#####################
路由：ready（增加了文件和路由一一对应模式）
错误处理：ready （接管了slim内置错误处理&异常的方式，以及定制化了异常，细节暂时未完善）
api接入层：ready（增加了_once，_before，_after api接入层的方法，可以定制前置后置的和只执行一次的方法）
model数据层：30%
配置分离：ready（增加了配置入口和各个模块配置的相关调用，暂未适配online，develop以及local。可扩展）
中间件：ready（细节暂未处理）
核心层：ready（不改动slim3的基础上扩展了框架核心的公用功能，方便后期调用，方便统一处理）
自动加载：ready（可按psr4的方式把自动载入的文件加载到composer提供的autoload中）
其他：相关目录按框架级的功能划分

##################开发须知############################
抛异常请用spl提供的异常，可以选择传参或不传，框架全部接管（http://php.net/manual/zh/book.spl.php）
所有接入层的方法（可用路由调用的）都有三个参数，顺序分别为request对象、response对象以及参数数组（参数为问号后面的）
所有接入层的方法均用return 的方式返回数据，框架会统一处理按格式输出json数据
基本主流框架有的方法，slim都有。如果不确定可以翻源代码或者手册

其他编码规范参照：psr1-psr2
补充编码规范1：namesapce请写在首行，空行后再写use，不要use多个，推荐一行只use一个命名空间。遵循psr4的方式进行目录和文件的命名
补充编码规范2：变量尽量小驼峰，非索引数组下标尽量下划线，class大驼峰，方法小驼峰等等

model数据层等待封装完毕在通知大家

##################待完成的细节########################
日志相关
错误相关
中间件相关
配置相关
以上都是长期补充的工作


############# 其他 #######################################
readme请参照：https://github.com/codesway/slim-api
使用过程中有不顺说或者有bug记得call我

```


# 温馨提示

* composer test  启动phpunit测试代码



# 测试相关
* 暂未适配phpunit


# nginx conf

```$xslt


server {
    listen      80;
    server_name CodeWay.liuxiaoliang.com;
    root your project root;
    index index.php index.html index.htm;
	access_log /data/logs/nginx/your acc log;
	error_log /data/logs/nginx/your err error;
    
    location / {
        try_files $uri $uri/ /index.php$is_args$args;
    }
    fastcgi_split_path_info ^(.+\.php)(/.+)$;
	include /Users/MacBook/orp/opt/nginx/conf/conf.d/php-fpm71;
}

```

# Mysql ROM&查询构造器 Tools

* https://d.laravel-china.org/docs/5.5/eloquent
* https://d.laravel-china.org/docs/5.5/database
* https://docs.golaravel.com/docs/4.1/eloquent/
* https://segmentfault.com/a/1190000002468218



# Slim3 官方组件
https://github.com/slimphp/Slim/wiki/Middleware-for-Slim-Framework-v3.x

# 安装
* composer install
* composer update
* 修改.env所属环境
* 请开始你的表演

# 目录结构
```$xslt

```


# 其他
```$xslt
为生产环境作准备
最后提醒一下，在部署代码到生产环境的时候，别忘了优化一下自动加载：

composer dump-autoload --optimize
安装包的时候可以同样使用--optimize-autoloader。不加这一选项，你可能会发现20%到25%的性能损失。
```
# 关于异常怎么用

* throw new CException(100001, ['aa', 'bb']);  具体替换需要看system配置

# 数据格式
```$xslt
{
    "status": 1,
    "data": {
        "code": "common_exception",
        "desc": "Call to a member function get() on null"
    }
}



success

{
    "status": 0,
    "data": [
        {
            "id": 1,
            "name": "wangwu"
        },
        {
            "id": 2,
            "name": "zhangsan"
        },
        {
            "id": 3,
            "name": "lisi"
        }
    ]
}
```