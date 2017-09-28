# Release Notes

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
