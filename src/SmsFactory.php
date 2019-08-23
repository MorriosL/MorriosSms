<?php


namespace Morrios\Sms;


use Morrios\Sms\Exception\ClientException;

/**
 * Class SmsFactory
 *
 * @method static \Morrios\Sms\Channel\AlibabaCloud\Application    AlibabaCloud(array $config)
 * @method static \Morrios\Sms\Channel\TencentCloud\Application    TencentCloud(array $config)
 */
class SmsFactory
{
    /**
     * Generating class.
     *
     * @param string $provider
     * @param array  $config
     * @return mixed
     * @throws ClientException
     */
    protected static function make(string $provider, array $config)
    {
        $application = __NAMESPACE__ . "\\Channel\\{$provider}\\Application";

        if (class_exists($application)) {
            return new $application($config);
        }

        throw new ClientException('Provider not found', 500);
    }

    /**
     * Dynamically pass methods to the application.
     *
     * @param $name
     * @param $arguments
     * @return mixed
     * @throws \Exception
     */
    public static function __callStatic($name, $arguments)
    {
        return self::make($name, ...$arguments);
    }
}