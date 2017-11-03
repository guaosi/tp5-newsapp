<?php
/**
 * Create By guaosi
 * Author guaosi
 * Date: 2017/11/1/0001
 * Time: 16:39
 */
namespace app\common\lib;
use think\Loader;
use think\Log;

Loader::import('aliyun-dysms-php-sdk.api_demo.SmsDemo', EXTEND_PATH, '.php');

class SendSms
{
    const LOG_TPL='aliyunSms';
    /**
     * 用于指定手机号发送短信
     * @param int $phone
     * @return bool
     */
    public static function sendSms($phone)
    {
        if (empty($phone))
        {
            return false;
        }
        $code=rand(1000,9999);
        try{
            $response = \SmsDemo::sendSms(
                config('aliyun.SignName'), // 短信签名
                config('aliyun.TemplateCode'), // 短信模板编号
                $phone, // 短信接收者
                ['code'=>$code],
                time()   // 流水号,选填
            );
        }catch (\Exception $e)
        {
//            短信发送服务器出错
            Log::write(self::LOG_TPL.'-set--->'.$e->getMessage());
             return false;
        }
         if ($response->Code=='OK')
            {
//                将验证码写入缓存，用于认证
                cache($phone,$code,config('aliyun.check_out_time'));
                return true;
            }
         else
            {
//                短信发送阿里云出错
                Log::write(self::LOG_TPL.'-set--->'.json_encode($response));
                return false;
            }
    }

    /**
     * 用于返回当前存在缓存中的手机号
     * @param $code
     * @return bool
     */
    public static function checkSmsCode($phone)
    {
        if (empty($phone))
        {
            return false;
        }
        return empty(cache($phone))?false:cache($phone);
    }
}