创建应用
-------

创建应用很简单，只需要执行几个命令就能完成。这里以创建博客程序为例。

步骤：
```bash
# 创建并进入应用目录
mkdir blog
cd blog
# 安装框架
composer require lumeng/lumeng-php
# 创建应用
php vendor/lumeng/lumeng-php/installer/install Apache/Blog
# 把Apache\Blog名称空间加入composer的autoload中
"autoload": {
    "psr-4": {
        "Apache\\Blog\\": "src/Apache/Blog/"
    }
}
# 更新autoload
composer dump-autoload
```

完成以上步骤之后，把应用目录下的web目录配置为web服务器的"document root"即可。