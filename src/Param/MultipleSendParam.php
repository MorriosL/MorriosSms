<?php
/**
 * MultipleSendParam.php
 *
 * @author  Carl <morrios@163.com>
 * @ctime   2020/3/9 4:49 下午
 */

namespace Morrios\Sms\Param;


/**
 * Class MultipleSendParam
 *
 * @package Morrios\Sms\Param
 */
class MultipleSendParam extends SendParam
{
    /**
     * @var array
     */
    public $phones;
}