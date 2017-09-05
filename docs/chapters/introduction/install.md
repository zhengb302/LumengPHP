## 安装

### 系统要求

LumengPHP框架对系统几乎没什么特别的要求，只需要满足以下要求即可：

* PHP >= 5.6.0
* PDO扩展

### 创建应用

假设现在要为Bear公司创建一个BBS应用。创建应用很简单，只需要执行一条命令就能完成：
```bash
composer create-project --prefer-dist lumeng/lumeng-php-skeleton bear-bbs

# 进入bear-bbs目录
cd bear-bbs
```

### 配置

#### Web服务器配置

完成以上步骤之后，把Web服务器的“document root”或“web root”指向bear-bbs目录下的web目录即可。
web目录下的“index.php”文件作为HTTP应用的入口文件。关于应用目录结构，见“[应用目录结构](app-directory-structure.md)”

#### URL重写

Apache：
```
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews
    </IfModule>

    RewriteEngine On

    # Redirect Trailing Slashes If Not A Folder...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)/$ /$1 [L,R=301]

    # Handle Front Controller...
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]

    # Handle Authorization Header
    RewriteCond %{HTTP:Authorization} .
    RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
</IfModule>
```

Nginx：
```
location / {
    try_files $uri $uri/ /index.php?$query_string;
}
```

#### 应用配置类

Bear/BBS/AppSetting.php

### Hello World

