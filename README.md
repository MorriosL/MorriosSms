# Morrios Sms


## 安装

* 通过composer，这是推荐的方式，可以使用composer.json 声明依赖，或者运行下面的命令。
```bash
$ composer require morrios/sms
```

## 运行环境

| Morrios Sms版本 | PHP 版本 |
|:--------------------:|:---------------------------:|
|          1.x         |  7.0 + |


## 使用方法

- 目前支持服务商：阿里云、腾讯云
```php
use Morrios\Sms\SmsFactory;

...

// sms配置
$config = [
    // 公共配置
    'signName'     => '测试签名',    // 短信签名，短信服务商后台配置获取

    // 动态配置
    'accessKeyId'  => 'XXX',        // 阿里云：accessKeyId、腾讯云：appid
    'accessSecret' => 'XXX',        // 阿里云：accessSecret、腾讯云：appkey
];

try {

    // 初始化短信应用 <阿里云>
    $app = SmsFactory::AlibabaCloud($config);
    
    // 初始化短信应用 <腾讯云>
    $app = SmsFactory::TencentCloud($config);

    // 获取随机数字验证码
    $code = $app->generateRandomString();

    // 单独发送
    $app->send('RECEIVE_PHONE', 'TEMPLATE_CODE', ['code' => $code]);

    // 批量发送 <单次发送任务仅支持100以内数量手机号码>
    $app->multipleSend(['RECEIVE_PHONE1', 'RECEIVE_PHONE2'], 'TEMPLATE_CODE', ['code' => $code]);

} catch (Exception $exception) {

    // 输出错误信息
    print_r($exception->getMessage());
    
}

...

```


## 联系我们

- 如果发现了Bug， 欢迎提交 [issue](https://github.com/MorriosL/MorriosSms/issues)
- 如果有功能需求，欢迎提交 [issue](https://github.com/MorriosL/MorriosSms/issues)
- 如果要提交代码，欢迎提交 Pull request