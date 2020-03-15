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
     * @var string
     */
    public $requestId;
}