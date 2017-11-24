
# 项目构建请看这里
* 项目基于 Slim Framework 3 Skeleton 构建 （php composer.phar create-project slim/slim-skeleton [my-app-name]）


# 温馨提示

* composer test  启动phpunit测试代码






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
* https://docs.golaravel.com/docs/4.1/eloquent/

