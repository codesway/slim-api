
# 项目构建请看这里
* 项目基于 Slim Framework 3 Skeleton 


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
        try_files $uri $uri/ /index.php;
    }
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
|____composer.lock
|____temp
|____phpunit.xml
|____core
| |____handler
| | |____SystemHandler.php
| | |____RequestResponseHandler.php
| | |____CommonHandler.php
| | |____NfoundHandler.php
| | |____InvalidHandler.php
| |____Main.php
| |____include
| | |____routes.php
| | |____relys.php
| | |____middleware.php
| |____component
| | |____FunCom.php
| |____loader
| | |____psr4_autoload.php
| |____base
| | |____AppBase.php
| | |____ModelBase.php
| | |____MiddlewareBase.php
| | |____ExceptionBase.php
| | |____EHandler.php
| | |____DbHandler.php
| | |____ApiBase.php
|____tests
| |____bootstrap.php
| |____Functional
| | |____UserTest.php
| | |____HomepageTest.php
| | |____BaseTestCase.php
|____README.md
|____public
| |____index.php
|____logs
| |____README.md
| |____app.log
|____api
| |____user
| | |____module
| | | |____UserAnnexedModel.php
| | | |____UserBaseModel.php
| | |____UserApi.php
| | |____UserDefine.php
|____codebase
| |____middleware
| | |____InputMiddleware.php
| | |____ExecuteMiddleware.php
| | |____FinishMiddleware.php
| | |____BlackMiddleware.php
| | |____CleanMiddleware.php
| | |____TokenMiddleware.php
| | |____InitMiddleware.php
| | |____ApiMiddleware.php
| |____traits
| |____config
| | |____develop
| | |____example
| | | |____global.php
| | | |____logger.php
| | | |____container.php
| | | |____database.php
| | | |____err_leve.php
| | | |____alarm.php
| | | |____err_code.php
| | | |____base.php
| | | |____middleware.php
| | |____local
| | |____.env
| | |____ConfigDefine.php
| | |____online
| | |____ConfigHandler.php
| |____libs
|____docker-compose.yml
|____composer.json
```