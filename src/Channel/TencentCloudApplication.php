<?php
/**
 * TencentCloudApplication.php
 *
 * @author  Carl <morrios@163.com>
 * @ctime   2020/3/9 3:31 下午
 */

namespace Morrios\Sms\Channel;


use Morrios\Base\Constant\Error;
use Morrios\Base\Exception\ServerException;
use Morrios\Sms\Param\MultipleSendParam;
use Morrios\Sms\Param\SendResultParam;
use Morrios\Sms\Param\SingleSendParam;
use Qcloud\Sms\SmsMultiSender;
use Qcloud\Sms\SmsSingleSender;

/**
 * Class TencentCloudApplication
 *
 * @package Morrios\Sms\Channel
 */
class TencentCloudApplication extends BaseApplication
{
    /**
     * @inheritDoc
     */
    public function loadService()
    {

    }

    /**
     * @inheritDoc
     */
    public function send(SingleSendParam $sendSmsParams)
    {
        try {
            $this->service = new SmsSingleSender($this->config->accessKeyId, $this->config->accessSecret);

            $result = $this->service
                ->sendWithParam('86', $sendSmsParams->phone, $sendSmsParams->templateCode, $sendSmsParams->templateParam, $this->config->signName);
            $result = json_decode($result, true);

            if ($result['result'] != 0) throw new ServerException($result['errmsg'] ?? Error::SERVICE_UNKNOWN_ERROR);

            return $this->transformResponse($result);
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @inheritDoc
     */
    public function multipleSend(MultipleSendParam $multipleSendParam)
    {
        try {
            $this->service = new SmsMultiSender($this->config->accessKeyId, $this->config->accessSecret);

            $result = $this->service
                ->sendWithParam('86', $multipleSendParam->phones, $multipleSendParam->templateCode, $multipleSendParam->templateParam, $this->config->signName);
            $result = json_decode($result, true);

            if ($result['result'] != 0) throw new ServerException($result['errmsg'] ?? Error::SERVICE_UNKNOWN_ERROR);

            return $this->transformResponse($result);
        } catch (\Exception $e) {
            throw new ServerException($e->getMessage());

        }
    }

    /**
     * @inheritDoc
     */
    protected function transformResponse(array $response)
    {
        return new SendResultParam([
            'requestId' => $response['RequestId'],
        ]);
    }
}