<?php
/**
 * Create By guaosi
 * Author guaosi
 * Date: 2017/11/1/0001
 * Time: 17:36
 */
namespace app\api\controller\v1;
use app\api\controller\Common;
use app\common\lib\SendSms;
use app\validate\IdentityVailate;
use think\Request;

class Identify extends Common
{
    public function save(Request $request)
    {
        if($request->isPost())
        {
            (new IdentityVailate())->goCheck();
            $data=$request->post();
            $phone=$data['id'];
            if(SendSms::sendSms($phone))
            {
                return showJson(config('admin.app_success'),'OK',[],201);
            }else{
                return showJson(config('admin.app_error'),'短信发送失败',[],403);
            }
        }
        else
        {
            return showJson(config('admin.app_error'),'禁止访问',[],403);
        }
    }
}