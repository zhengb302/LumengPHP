## 应用目录结构

### 未分版本

```
bear-bbs/   
    app/
        Bear/
            BBS/
                Interceptors/
                    UserAuth.php
                    UvStat.php
                Controllers/
                    Home.php
                Models/
                    UserModel.php
                    PostModel.php
                User/
                    UserHelper.php
                Post/
                    PostHelper.php
                AppSetting.php
    config/
    runtime/
        cache/
    vendor/
    web/
        index.php
    composer.json
    composer.lock
```

### 分版本

```
bear-bbs/   
    app/
        v1.0
            Bear/
                BBS/
                    Interceptors/
                        UserAuth.php
                        UvStat.php
                    Controllers/
                        Home.php
                    Models/
                        UserModel.php
                        PostModel.php
                    User/
                        UserHelper.php
                    Post/
                        PostHelper.php
                    AppSetting.php
        v1.1
            Bear/
                BBS/
                    Interceptors/
                        UserAuth.php
                        UvStat.php
                    Controllers/
                        Home.php
                    Models/
                        UserModel.php
                        PostModel.php
                    User/
                        UserHelper.php
                    Post/
                        PostHelper.php
                    AppSetting.php
    config/
    runtime/
        cache/
    vendor/
    web/
        index.php
    composer.json
    composer.lock
```