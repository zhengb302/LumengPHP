## 动作注解

`动作`注解用于执行某些特定的动作，实现某些特定的功能。

注解         | 作用对象  | 说明
------------ | -------- | ----
@keepDefault | 类属性   | 当源数据不存在时（即源中不存在对应的key），且设置了此注解时，保持属性的原值。
@queued      | 事件类   | 当事件类应用了此注解时，事件将会被异步触发。支持可选的参数，用于指定Job队列名称；如果未提供参数，则使用默认的Job队列。
@queue       | Job类    | 用于设置Job的队列名称，参数必填。如果Job类没有应用此注解，则此Job使用默认的Job队列。

### 示例

1，从`$_POST`中获取`age`参数，并转化为`int`类型，注入到`age`属性中：
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

2，异步触发`用户登录事件`：
```php
namespace Bear\BBS\Events;

/**
 * 用户登录事件
 *
 * @queued(userEventQueue)
 */
class UserLogined {
    //...
}
```

3，设置`发送邮件任务`的任务队列名称：
```php
namespace Bear\BBS\Jobs;

/**
 * 发送邮件任务
 *
 * @queue(sendEmailQueue)
 */
class SendEmail {
    //...
}
```