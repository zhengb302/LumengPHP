# Release Notes

## Unreleased

### Added
- 新增队列接口
- 支持队列化的事件
- 新增一些系统自带的控制台应用命令，如清理runtime目录、清理缓存等
- 新增多个快捷函数：app_context、config、service_container、service等

### Changed
- 事件全部使用`类`来描述
- ConsoleAppSettingInterface::getCmdMapping更名为ConsoleAppSettingInterface::getCmds

### Removed
- 去掉了“filter_field”、“index2map”函数，使用PHP自带的“array_column”代替

## [v0.2.8] - 2017-09-28

### Fixed
- 修复了在初始化`属性注入器`时，获取尚未注册的`事件管理器`服务的BUG

## [v0.2.7] - 2017-09-28

### Fixed
- 修复了在`服务容器`上调用了错误的方法的BUG

## [v0.2.6] - 2017-09-28

### Added
- `Result`类增加`more`属性

## [v0.2.5] - 2017-09-28

### Fixed
- 修复`控制台应用`未正确返回`事件配置`的BUG

## [v0.2.4] - 2017-09-28

### Added
- 新增`事件系统`

## [v0.2.3] - 2017-09-27

### Fixed
- 修复调用`register`方法注册服务时，传递的参数是匿名函数，获取服务时返回的是`Closure`对象的BUG

## [v0.2.2] - 2017-09-27

### Fixed
- 修复`SimpleRouter`重命名为`DefaultRouter`后，HTTP服务配置中未同步修改类名称的BUG

## [v0.2.1] - 2017-09-27

### Fixed
- 修复未把`拦截器链`对象注册为服务的BUG

## [v0.2.0] - 2017-09-26

### Added
- 增加了`拦截器链`的支持
- `RouterInterface`增加了`getPathInfo`方法

### Changed
- 拦截器配置的格式变为：拦截器类全限定名称 => 拦截模式
