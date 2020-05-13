<?php
/**
 * AlibabaCloudApplication.php
 *
 * @author  Carl <morrios@163.com>
 * @ctime   2020/3/9 3:31 下午
 */

namespace Morrios\Sms\Channel;


use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use Morrios\Base\Exception\ServerException;
use Morrios\Sms\Param\MultipleSendParam;
use Morrios\Sms\Param\SendResultParam;
use Morrios\Sms\Param\SingleSendParam;

/**
 * Class AlibabaCloudApplication
 *
 * @package Morrios\Sms\Channel
 */
class AlibabaCloudApplication extends BaseApplication
{
    /**
     * @inheritDoc
     */
    protected function loadService()
    {
        try {
            AlibabaCloud::accessKeyClient($this->config->accessKeyId, $this->config->accessSecret)
                ->regionId('cn-hangzhou')
                ->asDefaultClient();
        } catch (ClientException $e) {
            throw new ServerException($e->getMessage());
        }
    }

    /**
     * @inheritDoc
     */
    public function send(SingleSendParam $singleSendParam)
    {
        return $this->_sendAction($singleSendParam->phone, $singleSendParam->templateCode, $singleSendParam->templateParam);
    }


    /**
     * @inheritDoc
     */
    public function multipleSend(MultipleSendParam $multipleSendParam)
    {
        if (count($multipleSendParam->phones) > 1000) throw new ServerException('批量发送单次不能超过1000个手机号');

        $phones = implode(',', $multipleSendParam->phones);

        return $this->_sendAction($phones, $multipleSendParam->templateCode, $multipleSendParam->templateParam);
    }

    /**
     * @inheritDoc
     */
    protected function transformResponse(array $response)
    {
        if ($response['Code'] != 'OK') throw new ServerException($response['Code'] . ' - ' . $response['Message']);

        return new SendResultParam([
            'bizId'     => $response['BizId'],
            'code'      => $response['Code'],
            'message'   => $response['Message'],
            'requestId' => $response['RequestId'],
        ]);
    }

    /**
     * Send Sms Action.
     *
     * @param string $phones
     * @param string $templateCode
     * @param array  $templateParam
     * @return SendResultParam
     * @throws ServerException
     */
    private function _sendAction(string $phones, string $templateCode, array $templateParam = [])
    {
        try {
            $result = AlibabaCloud::rpc()
                ->product('Dysmsapi')
                ->scheme('https')
                ->version('2017-05-25')
                ->action('SendSms')
                ->method('POST')
                ->host('dysmsapi.aliyuncs.com')
                ->options([
                    'query' => [
                        'RegionId'      => 'default',
                        'PhoneNumbers'  => $phones,
                        'SignName'      => $this->config->signName,
                        'TemplateCode'  => $templateCode,
                        'TemplateParam' => json_encode((object)$templateParam),
                    ],
                ])
                ->request();

            return $this->transformResponse($result->toArray());
        } catch (\Exception $exception) {
            throw new ServerException($exception->getMessage());
        }
    }
}