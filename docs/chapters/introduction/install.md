## 安装

### 系统要求

LumengPHP框架对系统几乎没什么特别的要求，只需要满足以下要求即可：

* PHP >= 5.6.0
* PDO扩展

### 创建应用

假设现在要为Bear公司创建一个BBS应用。本手册的全部示例都是围绕Bear公司的BBS应用进行的。

创建应用很简单，只需要执行一条命令就能完成：
```bash
composer create-project --prefer-dist lumeng/lumeng-php-skeleton bear-bbs
```

### 目录结构

应用创建成功之后，会生成一个完整的目录结构。开发者只需根据自身的项目需要做相应的调整，就可以开始进行开发。

默认生成的目录结构如下：
```
bear-bbs/                                //应用根目录
    app/
        Bear/
            BBS/
                Interceptors/            //拦截器
                    UserAuth.php
                    UvStat.php
                Controllers/             //控制器
                    Home.php
                Models/                  //数据库model
                    UserModel.php
                    PostModel.php
                User/                    //应用相关的类
                    UserHelper.php
                Post/                    //应用相关的类
                    PostHelper.php
                AppSetting.php           //应用配置类
    config/
        config.php                       //配置文件入口
        database.php                     //数据库配置
        env                              //环境配置，该文件应该被版本控制系统忽略
        env.example                      //环境配置示例
    runtime/                             //运行时目录，该目录应该被版本控制系统忽略
        cache/
    vendor/                              //第三方依赖
    web/
        index.php                        //HTTP应用入口
    composer.json
    composer.lock
```

LumengPHP使用Composer管理依赖。默认情况下应用相关的类都放在app目录下面。

建议应用类的根名称空间遵循“组织名称\项目名称”的形式，名称使用驼峰格式，每个单词的首字母大写，
单词之间不要加入下划线。当然特殊的单词可以采用全部大写的形式。
例如本手册中的Bear公司的BBS项目就是“Bear\BBS”。

### 配置

#### Web服务器配置

完成以上步骤之后，把Web服务器的“DocumentRoot”(Apache)或“root”(Nginx)指向bear-bbs目录下的web目录即可。
web目录下的“index.php”文件作为HTTP应用的入口文件。

#### URL重写

重写之后的URL通常更优雅，同时也隐藏了后端所使用的技术架构。所以LumengPHP默认的路由组件只支持重写之后的URL。
当然，开发者可以根据应用的路由策略，使用自定义的路由组件。

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

#### 应用配置

LumengPHP支持多种类型的配置：配置类、配置文件、环境配置。优先级：环境配置 > 配置文件，配置类用于配置框架及那些变化很少的配置。
Bear BBS的应用配置类为：Bear\BBS\AppSetting，在这里可以配置路由、拦截器、服务等。应用配置文件入口为：config/config.php，
环境配置文件为：config/env。

Bear BBS的应用配置类的路由配置为：
```php
public function getRoutingConfig() {
    return [
        '/helloWorld' => \Bear\BBS\Controllers\HelloWorld::class,
        '/user/greetUser' => \Bear\BBS\Controllers\User\GreetUser::class,
        '/user/showUser' => \Bear\BBS\Controllers\User\ShowUser::class,
    ];
}
```

### Hello World

从[应用配置](#应用配置)小节可以看到，Bear BBS已经自带了一个“Hello World”