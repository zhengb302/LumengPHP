创建应用
-------

创建应用很简单，只需要执行几个命令就能完成。应用名称空间最好遵循"VendorName/ProjectName"的形式，
然而并非一定要这么做，只要满足composer的autoload机制即可。这里以创建Apache公司的Blog程序为例，
展示如何创建应用。在这个示例中，VendorName是Apache，ProjectName是Blog。

步骤：
```bash
# 创建并进入应用目录
mkdir blog
cd blog
# 安装框架
composer require lumeng/lumeng-php
# 创建应用
php vendor/lumeng/lumeng-php/installer/install Apache/Blog
# 编辑composer.json，把Apache\Blog名称空间加入到composer的autoload中
"autoload": {
    "psr-4": {
        "Apache\\Blog\\": "src/Apache/Blog/"
    }
}
# 更新autoload
composer dump-autoload
```

完成以上步骤之后，把blog目录下的web目录配置为web服务器的"document root"即可。