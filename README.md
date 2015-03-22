# Dependency

[yaf](https://github.com/laruence/php-yaf)

[SeasLog](https://github.com/Neeke/SeasLog)

[Redis](https://github.com/owlient/phpredis)

[PDO MySQL](http://php.net/manual/en/ref.pdo-mysql.php)

[imagick](https://github.com/mkoppanen/imagick/)

# 项目目录结构说明

```
├── application         # 主项目
│   ├── actions         # 单独分出来的 action
│   ├── controllers     # controller 入口
│   ├── library         # 公共库文件, yaf 会自动加载
│   ├── models          # 模型层,需要按 yaf 全名规则来命名才能自动加载
│   ├── plugins         # 插件模块
│   ├── service         # 业务逻辑层,建议分出此层,以便控制业务复杂
│   └── views           # 模板
├── conf                # 配置文件
├── contrib             # 第三方库
├── data                # 项目私有数据,不进行版本控制
└── static              # 静态资源
```

