<?php
/**
 * SendResultParam.php
 *
 * @author  Carl <morrios@163.com>
 * @ctime   2020/3/9 4:55 下午
 */

namespace Morrios\Sms\Param;


use Morrios\Base\Param\MorriosParam;

/**
 * Class SendResultParam
 *
 * @package Morrios\Sms\Param
 */
class SendResultParam extends MorriosParam
{
    /**
     * 请求ID
     *
     * @var string
     */
    public $requestId;

    /**
     * 发送回执ID
     *
     * @var string
     */
    public $bizId;

    /**
     * 请求状态码
     *
     * @var string
     */
    public $code;

    /**
     * 状态码的描述
     *
     * @var string
     */
    public $message;

    /**
     * 发送成功列表 - 腾讯云响应
     *
     * @var array
     */
    public $successList = [];

    /**
     * 发送失败列表 - 腾讯云响应
     *
     * @var array
     */
    public $failedList = [];
}