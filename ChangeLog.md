# Release Notes

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
