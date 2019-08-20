<?php


namespace Morrios\Sms\Channel;


use Morrios\Sms\Utils\Helpers;

/**
 * Class BaseApplication
 * @package Morrios\Sms\Channels
 */
abstract class BaseApplication
{
    use Helpers;

    /**
     * @var array
     */
    protected $config = [];

    /**
     * @var
     */
    protected $service;

    /**
     * @var string
     */
    protected $logType = 'file';

    /**
     * BaseApplication constructor.
     *
     * @param array $config
     * @throws \Morrios\Sms\Exception\ClientException
     */
    public function __construct(array $config = [])
    {
        // Validate config
        $this->_validate([
            'config' => 'required|config'
        ], [$config]);

        // Inject config
        $this->config = $config;

        // Load service
        $this->loadService();
    }

    /**
     * Load service.
     *
     * @return mixed
     */
    abstract public function loadService();

    /**
     * Send single SMS to achieve.
     *
     * @param string $phone
     * @param string $templateCode
     * @param array  $templateParam
     * @return mixed
     */
    abstract public function send(string $phone, string $templateCode, array $templateParam = []);

    /**
     * Send multiple SMS to achieve.
     *
     * @param array  $phones
     * @param string $templateCode
     * @param array  $templateParam
     * @return mixed
     */
    abstract public function multipleSend(array $phones, string $templateCode, array $templateParam = []);

    /**
     * Make random string.
     * @param int $length
     * @return int
     */
    public function generateRandomString(int $length = 6)
    {
        return rand(pow(10, $length - 1), (pow(10, $length) - 1));
    }
}