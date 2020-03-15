<?php
/**
 * SendParam.php
 *
 * @author  Carl <morrios@163.com>
 * @ctime   2020/3/9 4:51 下午
 */

namespace Morrios\Sms\Param;


use Morrios\Base\Param\MorriosParam;

/**
 * Class SendParam
 *
 * @package Morrios\Sms\Param
 */
class SendParam extends MorriosParam
{
    /**
     * @var string
     */
    public $templateCode;

    /**
     * @var array
     */
    public $templateParam = [];
}