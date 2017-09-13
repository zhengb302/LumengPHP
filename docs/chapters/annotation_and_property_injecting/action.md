## 动作注解

`动作`注解用于执行某些特定的动作，实现某些特定的功能。动作注解目前只有`@keepDefault`注解。

`@keepDefault`注解：当源数据不存在时（即源中不存在对应的key），且设置了此注解时，保持属性的原值。

### 示例

从`$_POST`中获取`age`参数，并转化为`int`类型，注入到`age`属性中：
```php
   /**
    * @var int 年龄
    * @post
    * @keepDefault
    */
   private $age = 18;
```
> 说明：如果`$_POST`中不存在`age`参数，因为设置了`@keepDefault`注解的缘故，`age`属性的值将不会被改变，仍然为`18`。
如果此时不设置`@keepDefault`注解，则`age`属性的值会被注入为`0`。