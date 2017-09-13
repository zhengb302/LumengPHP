## HTTP应用

### 属性注入注解

属性注入注解表

注解名称 | 说明
-------- | ----
@get     | 从`$_GET`中获取HTTP请求参数
@post    | 从`$_POST`中获取HTTP请求参数

语法示例：
```php
   /**
    * @var string 用户名
    * @get(name)
    */
   private $username;
```