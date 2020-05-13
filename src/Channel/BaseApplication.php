<?php


namespace Morrios\Sms\Channel;


use Morrios\Base\Exception\ServerException;
use Morrios\Sms\Param\ConfigParam;
use Morrios\Sms\Param\MultipleSendParam;
use Morrios\Sms\Param\SendResultParam;
use Morrios\Sms\Param\SingleSendParam;

/**
 * Class BaseApplication
 *
 * @package Morrios\Sms\Channels
 */
abstract class BaseApplication
{
    /**
     * @var ConfigParam
     */
    protected $config;

    /**
     * @var mixed
     */
    protected $service;

    /**
     * BaseApplication constructor.
     *
     * @param ConfigParam $config
     * @throws ServerException
     */
    public function __construct(ConfigParam $config)
    {
        $this->config = $config;

        $this->loadService();
    }

    /**
     * Load service.
     *
     * @return void
     * @throws ServerException
     */
    abstract protected function loadService();

    /**
     * Send single SMS to achieve.
     *
     * @param SingleSendParam $sendSmsParams
     * @return SendResultParam
     * @throws ServerException
     */
    abstract public function send(SingleSendParam $sendSmsParams);

    /**
     * Send multiple SMS to achieve.
     *
     * @param MultipleSendParam $multipleSendParam
     * @return SendResultParam
     * @throws ServerException
     */
    abstract public function multipleSend(MultipleSendParam $multipleSendParam);

    /**
     * Transform response.
     *
     * @param array $response
     * @return SendResultParam
     */
    abstract protected function transformResponse(array $response);

    /**
     * Make random number.
     *
     * @param int $length
     * @return int
     */
    public function generateRandomNumber(int $length = 6)
    {
        return rand(pow(10, $length - 1), (pow(10, $length) - 1));
    }
}