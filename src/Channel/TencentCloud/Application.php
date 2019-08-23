<?php


namespace Morrios\Sms\Channel\TencentCloud;


use Morrios\Sms\Channel\BaseApplication;
use Morrios\Sms\Exception\ClientException;
use Morrios\Sms\Utils\Helpers;
use Qcloud\Sms\SmsMultiSender;
use Qcloud\Sms\SmsSingleSender;

class Application extends BaseApplication
{
    use Helpers;

    /**
     * loadService
     * @return mixed|void
     */
    public function loadService()
    {

    }

    /**
     * Send single SMS to achieve.
     *
     * @param string $phone
     * @param string $templateCode
     * @param array  $templateParam
     * @return mixed|void
     * @throws ClientException
     */
    public function send(string $phone, string $templateCode, array $templateParam = [])
    {
        try {
            // validate params
            $this->_validate([
                'phone'         => 'required|string|phone',
                'templateCode'  => 'required|string',
                'templateParam' => 'required',
            ], func_get_args());

            // send action
            $this->service = new SmsSingleSender($this->config['accessKeyId'], $this->config['accessSecret']);
            $result        = $this->service->sendWithParam('86', $phone, $templateCode, $templateParam, $this->config['signName']);

            // error handle
            if ($result['result'] != 0) throw new ClientException($result['errmsg'], 500);

            return json_decode($result, true);
        } catch (\Exception $e) {
            throw new ClientException($e->getMessage(), 500);
        }
    }

    /**
     * Send multiple SMS to achieve.
     *
     * @param array  $phones
     * @param string $templateCode
     * @param array  $templateParam
     * @return mixed|void
     * @throws ClientException
     */
    public function multipleSend(array $phones, string $templateCode, array $templateParam = [])
    {
        try {
            // validate params
            $this->_validate([
                'phones'        => 'required|array|max:100|phones',
                'templateCode'  => 'required|string',
                'templateParam' => 'required',
            ], func_get_args());

            // send action
            $this->service = new SmsMultiSender($this->config['accessKeyId'], $this->config['accessSecret']);
            $result        = $this->service->sendWithParam('86', $phones, $templateCode, $templateParam, $this->config['signName']);

            // error handle
            if ($result['result'] != 0) throw new ClientException($result['errmsg'], 500);

            return json_decode($result, true);
        } catch (\Exception $e) {
            throw new ClientException($e->getMessage(), 500);
        }
    }
}