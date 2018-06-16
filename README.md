基于ThinkPHP5编写娱乐新闻APP接口
===========

> 声明:APP不是我制作的，我只是写接口

## 特性

- RESTful API

- AES加密处理

- 接入 七牛云,ueditor(图片自动上传七牛云)

- 接入 阿里云短信，极光推送

- 接口文档编写

接口文档地址：[**接口文档**](http://newsapp.mydoc.io/)

安卓体验包与安卓源码下载地址: [**百度云**](https://pan.baidu.com/s/1jHWIHOq)

> 加密方面,登陆的密码都是使用md5加盐加密，sign跟access_user_token使用AES加密.

## 要求

| 依赖 | 说明 |
| -------- | -------- |
| PHP| >=`5.4` |
| Thinkphp| `5.0.10` |
| MySQL| >=`5.5` |
| nginx |用于网址代理解析|
| 集成环境[可选的] | LNMP`>=1.5` |

## 注意

1. 做了一次网站迁移上传的,应该没有什么问题,有关问题下面会说明.

## 安装

通过[Github](https://github.com/guaosi/tp5-newsapp),fork到自己的项目下
```
git clone git@github.com:<你的用户名>/tp5-newsapp.git
```

## 数据库

> 创建newsapp数据库，自行导入`newsapp.sql`文件

## 配置

### PHP端

>  修改 application/database.php 里的数据库信息

>  修改 application/extra/setting.php  里的 PASS_SALT(用于md5加盐加密) 和 aeskey(用于aes加密密钥，需与app端一致，16位)

>  修改 application/extra/qiniu.php  里的accessKey,secretKey,bucket,imgageUrl 分别是七牛云的accessKey，secretKey，创建的容器名,七牛云绑定的域名.

>  修改 application/extra/jiguang.php  里的 AppKey，Master Secret 分别是极光推送里的AppKey,Master Secret

>  修改 application/extra/aliyun.php  里的 accessKeyId，accessKeySecret,SignName,TemplateCode, 分别是阿里云短信服务里的的accessKeyId，accessKeySecret，短信签名的名称，短信模板的CODE

>  修改/public/static/hadmin/lib/ueditor/1.4.3/php/config.json 里的 uploadQiniuUrl，ChunkUploadQiniuUrl修改为适合自己地区的七牛上传地址，具体选择请自行参考官网

>  修改/public/static/hadmin/lib/ueditor/1.4.3/php/config.php 里的bucket，host，access_key，secret_key 分别是七牛云创建的容器名，七牛云绑定的域名，accessKey，secretKey.

### APP端

>  在极光推送官网中，推送设置的Android的应用包名设置为com.wiggins.teaching

>  将app/src/main/java/com.wiggins.teaching/network/constant/HttpUrlConstant 里的 BASE_URL改为自己的网址，如 http://www.guaosi.com

>  将app/build.gradle 里的JPUSH_APPKEY 修改为极光推送的AppKey，JPUSH_CHANNEL修改为极光推送里创建的应用名称

>  将app/src/main/java/com.wiggins.teaching/utils/AESHelper 里的password 修改为与服务端一致的AES秘钥

## Nginx配置

这个需求应该不大，就不写了.

## 测试账号与密码

以上都完成后
后台登录账号密码

> admin  a123654

后台地址:
> http://newsapp.guaosi.com/admin/login/index.html