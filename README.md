<div style="text-align: center;">
  <img height="100" src="https://cdn.morrios.top/assets/logo-sms.png" alt="MorriosPayment"/>
</div>

## 目录

- [目录](#%e7%9b%ae%e5%bd%95)
- [说明](#%e8%af%b4%e6%98%8e)
- [安装](#%e5%ae%89%e8%a3%85)
  * [依赖要求](#%e4%be%9d%e8%b5%96%e8%a6%81%e6%b1%82)
  * [composer安装方式](#composer%e5%ae%89%e8%a3%85%e6%96%b9%e5%bc%8f)
- [使用方法](#%e4%bd%bf%e7%94%a8%e6%96%b9%e6%b3%95)
  * [说明](#%e8%af%b4%e6%98%8e)
  * [实例化短信实例](#%e5%ae%9e%e4%be%8b%e5%8c%96%e7%9f%ad%e4%bf%a1%e5%ae%9e%e4%be%8b)
  * [更新短信实例配置](#%e6%9b%b4%e6%96%b0%e7%9f%ad%e4%bf%a1%e5%ae%9e%e4%be%8b%e9%85%8d%e7%bd%ae)
  * [发送短信](#%e5%8f%91%e9%80%81%e7%9f%ad%e4%bf%a1)
- [参数类说明](#%e5%8f%82%e6%95%b0%e7%b1%bb%e8%af%b4%e6%98%8e)
  * [配置参数类（ConfigParam）](#%e9%85%8d%e7%bd%ae%e5%8f%82%e6%95%b0%e7%b1%bb)
  * [单条发送参数类（SingleSendParam）](#%e5%8d%95%e6%9d%a1%e5%8f%91%e9%80%81%e5%8f%82%e6%95%b0%e7%b1%bb)
  * [批量发送参数类（MultipleSendParam）](#%e6%89%b9%e9%87%8f%e5%8f%91%e9%80%81%e5%8f%82%e6%95%b0%e7%b1%bb)
  * [发送结果参数类（SendResultParam）](#%e5%8f%91%e9%80%81%e7%bb%93%e6%9e%9c%e5%8f%82%e6%95%b0%e7%b1%bb)
- [贡献指南](#%e8%b4%a1%e7%8c%ae%e6%8c%87%e5%8d%97)
- [LICENSE](#license)

## 说明

- Morrios工具包短信库，简化短信发送步骤，为系统快速的集成短信发送功能提供支持。

## 安装

### 依赖要求

| Morrios SMS版本 | PHP 版本 | PHP 拓展 |
|:--------------------:|:---------------------------:|:---------------------------:|
|          1.x         |  7.0 + |  `ext-json` |

### composer安装方式

- 通过composer，这是推荐的方式，可以使用composer.json 声明依赖，或者运行下面的命令

```shell
composer require morrios/sms
```

## 使用方法

### 说明

- 目前支持服务商：阿里云、腾讯云

### 实例化短信实例

- 配置参数类`ConfigParam`详见 [说明](#%e9%85%8d%e7%bd%ae%e5%8f%82%e6%95%b0%e7%b1%bb)

```php
use Morrios\Base\Exception\ParameterException;
use Morrios\Base\Exception\ServerException;
use Morrios\Sms\Param\ConfigParam;
use Morrios\Sms\SmsFactory;
   
try {

    // 实例化配置参数类
    $config = new ConfigParam([
        // 公共配置
        'signName'     => '测试签名',    // 短信签名，短信服务商后台配置获取
        
        // 动态配置
        'accessKeyId'  => 'XXX',        // 阿里云：accessKeyId、腾讯云：appid
        'accessSecret' => 'XXX',        // 阿里云：accessSecret、腾讯云：appkey
    ]);

    // 根据短信渠道需要实例化不同的支付实例
    $instance = SmsFactory::AlibabaCloud($config); // 阿里云实例
    $instance = SmsFactory::TencentCloud($config); // 腾讯云实例

} catch (ParameterException $parameterException) {

    // 配置参数错误，根据业务自行处理
    // ...

} catch (ServerException $serverException) {

    // 实例初始化失败，根据业务自行处理
    // ...

}
```

### 更新短信实例配置

- 配置参数类`ConfigParam`详见 [说明](#%e9%85%8d%e7%bd%ae%e5%8f%82%e6%95%b0%e7%b1%bb)

```php
use Morrios\Sms\Param\ConfigParam;

// 实例化配置参数类，按需传入需要更新的字段
$config = new ConfigParam([
    // 公共配置
    'signName'     => '测试签名',    // 短信签名，短信服务商后台配置获取
    
    // 动态配置
    'accessKeyId'  => 'XXX',        // 阿里云：accessKeyId、腾讯云：appid
    'accessSecret' => 'XXX',        // 阿里云：accessSecret、腾讯云：appkey
]);

// 支持更新实例中的配置 例：动态更新短信签名等场景
$instance->updateConfig($config);
```

### 发送短信

- 单条发送参数类`SingleSendParam`详见 [说明](#%e5%8d%95%e6%9d%a1%e5%8f%91%e9%80%81%e5%8f%82%e6%95%b0%e7%b1%bb)
- 批量发送参数类`MultipleSendParam`详见 [说明](#%e6%89%b9%e9%87%8f%e5%8f%91%e9%80%81%e5%8f%82%e6%95%b0%e7%b1%bb)
- 发送结果参数类`SendResultParam`详见 [说明](#%e6%89%b9%e9%87%8f%e5%8f%91%e9%80%81%e5%8f%82%e6%95%b0%e7%b1%bb)

```php
use Morrios\Base\Exception\ServerException;
use Morrios\Sms\Param\MultipleSendParam;
use Morrios\Sms\Param\SingleSendParam;

try {

    // 获取随机数字验证码
    $code = $instance->generateRandomNumber();

    // 单条发送
    $result = $instance->send(new SingleSendParam([
        'phone'         => 'RECEIVE_PHONE',
        'templateCode'  => 'TEMPLATE_CODE',
        'templateParam' => [],
    ]));

    // 批量发送
    $result = $instance->multipleSend(new MultipleSendParam([
        'phones'        => ['RECEIVE_PHONE1', 'RECEIVE_PHONE2'],
        'templateCode'  => 'TEMPLATE_CODE',
        'templateParam' => [],
    ]));

    // $result为SendResultParam实例
    // 根据业务自行处理 eg:
    // Log::info('短信发送成功，requestId：' . $result->requestId);

} catch (ServerException $exception) {

    // 根据业务自行处理
    // ...

}
```

## 参数类说明

### 配置参数类

| 属性  | 类型  | 是否必传  | 说明  | 备注  |
| ------------ | ------------ | ------------ | ------------ | ------------ |
| signName  | string  | 是  | 短信签名  | 短信服务商后台配置获取  |
| accessKeyId  | string  | 是  | 访问应用ID  | 阿里云：accessKeyId、腾讯云：appid |
| accessSecret  | string  | 是  | 访问密钥  | 阿里云：accessSecret、腾讯云：appkey  |

### 单条发送参数类

| 属性  | 类型  | 是否必传  | 说明  | 备注  |
| ------------ | ------------ | ------------ | ------------ | ------------ |
| phone  | string  | 是  | 手机号  | 支持国内手机号  |
| templateCode  | string  | 是  | 短信模版  | 阿里云或腾讯云后台获取 |
| templateParam  | array  | 是  | 模版参数  | 短信模版中所需参数  |

### 批量发送参数类

| 属性  | 类型  | 是否必传  | 说明  | 备注  |
| ------------ | ------------ | ------------ | ------------ | ------------ |
| phones  | array  | 是  | 手机号  | 支持国内手机号  |
| templateCode  | string  | 是  | 短信模版  | 阿里云或腾讯云后台获取 |
| templateParam  | array  | 是  | 模版参数  | 短信模版中所需参数  |

### 发送结果参数类

| 属性  | 类型  | 是否必传  | 说明  | 备注  |
| ------------ | ------------ | ------------ | ------------ | ------------ |
| requestId  | string  | 是  | 请求ID  | 阿里云/腾讯云短信发送请求ID  |
| bizId  | string  | 是  | 发送回执ID  | 阿里云发送回执ID/腾讯云发送流水号  |
| code  | string  | 是  | 请求状态码  | 阿里云/腾讯云短信发送请求状态码  |
| message  | string  | 是  | 状态码的描述  | 阿里云/腾讯云短信发送请求状态码的描述  |
| successList  | array  | 是  | 发送成功列表 - 腾讯云响应  | 腾讯云SendStatusSet  |
| failedList  | array  | 是  | 发送失败列表 - 腾讯云响应  | 腾讯云SendStatusSet  |

## 贡献指南

- 如果发现了Bug， 欢迎提交 [Issue](https://github.com/MorriosL/MorriosSms/issues)
- 如果要提交代码，欢迎提交 Pull request

## LICENSE

[MIT LICENSE](LICENSE) &copy; morrios