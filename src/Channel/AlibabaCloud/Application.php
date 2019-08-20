<?php


namespace Morrios\Sms\Channel\AlibabaCloud;


use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
use Morrios\Sms\Channel\BaseApplication;
use Morrios\Sms\Exception\ClientException as MorriosClientException;
use Morrios\Sms\Utils\Helpers;

class Application extends BaseApplication
{
    use Helpers;

    /**
     * loadService
     * @return mixed|void
     * @throws ClientException
     */
    public function loadService()
    {
        // Init AlibabaCloud
        AlibabaCloud::accessKeyClient($this->config['accessKeyId'], $this->config['accessSecret'])
            ->regionId('cn-hangzhou')
            ->asDefaultClient();

        $this->service = AlibabaCloud::class;
    }

    /**
     * Send single SMS to achieve.
     *
     * @param string $phone
     * @param string $templateCode
     * @param array  $templateParam
     * @return mixed
     * @throws MorriosClientException
     */
    public function send(string $phone, string $templateCode, array $templateParam = [])
    {
        // validate params
        $this->_validate([
            'phone'         => 'required|string|phone',
            'templateCode'  => 'required|string',
            'templateParam' => 'required',
        ], func_get_args());

        return $this->_sendAction($phone, $templateCode, $templateParam);
    }

    /**
     * Send multiple SMS to achieve.
     *
     * @param array  $phones
     * @param string $templateCode
     * @param array  $templateParam
     * @return mixed
     * @throws MorriosClientException
     */
    public function multipleSend(array $phones, string $templateCode, array $templateParam = [])
    {
        // validate params
        $this->_validate([
            'phones'        => 'required|array|max:100|phones',
            'templateCode'  => 'required|string',
            'templateParam' => 'required',
        ], func_get_args());

        // transfer phone to string
        $phones = implode(',', $phones);

        return $this->_sendAction($phones, $templateCode, $templateParam);
    }

    /**
     * Send Sms Action.
     *
     * @param string $phones
     * @param string $templateCode
     * @param array  $templateParam
     * @return mixed
     * @throws MorriosClientException
     */
    private function _sendAction(string $phones, string $templateCode, array $templateParam = [])
    {
        // Send sms action
        try {
            $result = $this->service::rpc()
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
                        'SignName'      => $this->config['signName'],
                        'TemplateCode'  => $templateCode,
                        'TemplateParam' => json_encode($templateParam),
                    ],
                ])
                ->request();

            return $result->toArray();
        } catch (ClientException $e) {
            throw new MorriosClientException($e->getErrorMessage(), 500);
        } catch (ServerException $e) {
            throw new MorriosClientException($e->getErrorMessage(), 500);
        }
    }
}