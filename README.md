# newsapp
娱乐新闻APP
===========

接口文档地址：[**接口文档**](http://newsapp.mydoc.io/)

安卓体验包与安卓源码下载地址: [**百度云**](http://https://pan.baidu.com/s/1jHWIHOq)


> 声明:APP不是我制作的，我只是写接口


**第三方里使用了七牛云,ueditor(图片自动上传七牛云)，阿里云短信，极光推送.**


**加密方面,登陆的密码都是使用md5加盐加密，sign跟access_user_token使用AES加密.**


> 框架:Thinkphp 5.10

> 支持的 PHP 版本: 5.4.x ～ 5.6.x, 7.0.


**Git后修改如下地方:**


### PHP端


-  修改 application/database.php 里的数据库信息


-  修改 application/extra/setting.php  里的 PASS_SALT(用于md5加盐加密) 和 aeskey(用于aes加

密密钥，需与app端一致，16位)


-  修改 application/extra/qiniu.php  里的accessKey,secretKey,bucket,imgageUrl 分别是七牛云

的accessKey，secretKey，创建的容器名,七牛云绑定的域名.


-  修改 application/extra/jiguang.php  里的 AppKey，Master Secret 分别是极光推送里的AppKey,

Master Secret


-  修改 application/extra/aliyun.php  里的 accessKeyId，accessKeySecret,SignName,Template

Code, 分别是阿里云短信服务里的的accessKeyId，accessKeySecret，短信签名的名称，短信模板的CODE


-  修改/public/static/hadmin/lib/ueditor/1.4.3/php/config.json 里的 uploadQiniuUrl，Chunk

UploadQiniuUrl修改为适合自己地区的七牛上传地址，具体选择请自行参考官网


-  修改/public/static/hadmin/lib/ueditor/1.4.3/php/config.php 里的bucket，host，access_ke

y，secret_key 分别是七牛云创建的容器名，七牛云绑定的域名，accessKey，secretKey.


**注意: 刚开始使用时，请将 application/admin/controller/Base.php 的 _initialize 方法禁用**

**，然后直接登陆 admin/index/index 里加入一个管理员 然后 再启用_initialize 方法**


### APP端


-  在极光推送官网中，推送设置的Android的应用包名设置为com.wiggins.teaching


-  将app/src/main/java/com.wiggins.teaching/network/constant/HttpUrlConstant 里的 BASE_U

RL改为自己的网址，如 http://www.guaosi.com.cn


-  将app/build.gradle 里的JPUSH_APPKEY 修改为极光推送的AppKey，JPUSH_CHANNEL修改为极光推送里

创建的应用名称


-  将app/src/main/java/com.wiggins.teaching/utils/AESHelper 里的password 修改为与服务端一

致的AES秘钥
