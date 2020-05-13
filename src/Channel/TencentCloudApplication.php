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
    protected function loadService()
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
        if (count($multipleSendParam->phones) > 200) throw new ServerException('批量发送单次不能超过200个手机号');

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
        // 定义发送结果清单
        $successList = [];
        $failedList  = [];

        // 根据发送结果填充清单
        foreach ($response['SendStatusSet'] as $sendStatus) {
            if ($sendStatus['Code'] == 'Ok') {
                array_push($successList, $sendStatus);
            } else {
                array_push($failedList, $sendStatus);
            }
        }

        // 统计结果
        $sendCount   = count($response['SendStatusSet']);
        $failedCount = count($failedList);

        // 全量发送失败处理
        if ($failedCount == $sendCount) {
            $sendStatus = current($response['SendStatusSet']);

            $errorMessage = $sendStatus['Code'] . ' - ' . $sendStatus['Message'];
            $failedCount > 1 && '批量发送失败，原因：' . $errorMessage . '等';

            throw new ServerException($errorMessage);
        }

        // 定义响应结果
        $result = [
            'requestId'   => $response['RequestId'],
            'successList' => $successList,
            'failedList'  => $failedList,
        ];

        // 单条发送成功处理
        if ($sendCount == 1 && $failedCount == 0) {
            $result = array_merge($result, [
                'bizId'   => current($response['SendStatusSet'])['SerialNo'],
                'code'    => current($response['SendStatusSet'])['Code'],
                'message' => current($response['SendStatusSet'])['Message'],
            ]);
        }

        return new SendResultParam($result);
    }
}