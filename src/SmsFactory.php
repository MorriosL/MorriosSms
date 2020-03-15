<?php


namespace Morrios\Sms;


use Morrios\Base\Constant\Error;
use Morrios\Base\Exception\ParameterException;
use Morrios\Base\Exception\ServerException;
use Morrios\Sms\Channel\AlibabaCloudApplication;
use Morrios\Sms\Channel\BaseApplication;
use Morrios\Sms\Channel\TencentCloudApplication;
use Morrios\Sms\Param\ConfigParam;

/**
 * Class SmsFactory
 *
 * @method static AlibabaCloudApplication AlibabaCloud(ConfigParam $config)
 * @method static TencentCloudApplication TencentCloud(ConfigParam $config)
 */
class SmsFactory
{
    /**
     * Dynamically pass methods to the application.
     *
     * @param $name
     * @param $arguments
     * @return BaseApplication
     * @throws \Exception
     */
    public static function __callStatic($name, $arguments)
    {
        return self::make($name, ...$arguments);
    }

    /**
     * Generating class.
     *
     * @param string      $provider
     * @param ConfigParam $configParam
     * @return BaseApplication
     * @throws ParameterException
     * @throws ServerException
     */
    protected static function make(string $provider, ConfigParam $configParam)
    {
        $application = __NAMESPACE__ . "\\Channel\\{$provider}Application";

        if (class_exists($application)) {
            $application = new $application($configParam);

            if ($application instanceof BaseApplication) return $application;

            throw new ServerException(Error::SERVICE_UNKNOWN_ERROR);
        }

        throw new ParameterException(Error::INVALID_ARGUMENT);
    }
}