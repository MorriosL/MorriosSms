<?php
/**
 * ConfigParam.php
 *
 * @author  Carl <morrios@163.com>
 * @ctime   2020/3/9 3:33 下午
 */

namespace Morrios\Sms\Param;


use Morrios\Base\Param\MorriosParam;

/**
 * Class ConfigParam
 *
 * @package Morrios\Sms\Param
 */
class ConfigParam extends MorriosParam
{
    /**
     * @var string
     */
    public $signName;

    /**
     * @var string
     */
    public $accessKeyId;

    /**
     * @var string
     */
    public $accessSecret;

}