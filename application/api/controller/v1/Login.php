<?php
/**
 * Create By guaosi
 * Author guaosi
 * Date: 2017/11/1/0001
 * Time: 17:36
 */
namespace app\api\controller\v1;
use app\admin\model\User;
use app\api\controller\Common;
use app\common\lib\Aes;
use app\common\lib\IAuth;
use app\common\lib\SendSms;
use app\lib\exception\ParameterException;
use app\validate\Loginvailate;
use think\Request;

class Login extends Common
{
    /**
     * 用于登陆 返回加密后的token
     * @param Request $request
     * @return \think\response\Json
     * @throws ParameterException
     */
    public function save(Request $request)
    {
        if($request->isPost())
        {
//            参数验证
            //短信进行解密
            (new Loginvailate())->goCheck();
            $data=$request->post();
//            客户端最好是把验证码加密发送过来  由于此次客户端未加密，所以无需解密
//            $code=(new Aes())->decrypt($data['code']);
            $code=empty($data['code'])?'':$data['code'];
            $password=empty($data['password'])?'':$data['password'];
            $phone=$data['phone'];
//            手机加验证码方式
            if ($code)
            {
                //            手机号与验证码的验证
                if(SendSms::checkSmsCode($phone)!=$code)
                {
                    return showJson(config('admin.app_error'),'验证码错误,请重新再试',[],403);
                }
                else
                {
                    $user=User::where('phone',$phone)->find();
                }
            }
            //            手机加密码方式
            else{
                $user=User::where('phone',$phone)->find();
                if(empty($user))
                {
                    return showJson(config('admin.app_error'),'手机号不存在',[],403);
                }
                else{
                    if($user->password!=IAuth::haltPassword($password))
                    {
                        return showJson(config('admin.app_error'),'密码错误，请重试',[],403);
                    }
                }
            }


//            验证码通过 第一次增加用户，第二次修改token,修改过期时间

            $token = IAuth::setUserToken($phone);
            $token_out_time_day = config('appsetting.token_out_time_day');
            if (!$user) {
//               新增
                try {
                    $user = User::create([
                        'username' => config('appsetting.prefix_name').$phone,
                        'phone' => $phone,
                        'token' => $token,
                        'time_out' => time()+24 * 3600 * $token_out_time_day,
                    ]);
                } catch (\Exception $e) {
                    return showJson(config('admin.app_error'), $e->getMessage(), [], 500);
                }
            }
            else
            {
                try{
                     $user->token=$token;
                     $user->time_out=time()+24 * 3600 * $token_out_time_day;
                     $user->save();
                }catch (\Exception $e)
                {
                    return showJson(config('admin.app_error'), $e->getMessage(), [], 500);
                }
            }
              $result=[
               'token'=>(new Aes())->encrypt($token.'||'.$user->id)
              ];
            return showJson(config('admin.app_success'),'OK',$result,201);
         }
        else
        {
            return showJson(config('admin.app_error'),'禁止访问',[],403);
        }
    }
}