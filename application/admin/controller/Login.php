<?php
/**
 * Create By guaosi
 * Author guaosi
 * Date: 2017/10/14/0014
 * Time: 14:06
 */
namespace app\admin\controller;
use app\admin\model\AdminUser;
use app\common\lib\IAuth;
use app\validate\AdminUserLogin;
use think\Request;

class Login extends Base
{
    public function _initialize()
    {

    }
    public function index()
    {
        if($this->checkLogin())
        {
            $this->redirect('index/index');
        }
        else
        {
            return $this->fetch();
        }

    }
    public function check(Request $request)
    {
        if($request->isPost())
        {
            $data=$request->post();
            if(!captcha_check($data['code'])){
                $this->error("验证码错误");
            }
            else
            {
                if($this->checkUserPass($data))
                {

                    $this->success('登陆成功','index/index');
                }
            }
        }
        else
        {
            $this->error('请求不合法');
        }

    }
    private function checkUserPass($data)
    {
           $validate=new AdminUserLogin();
           if($validate->check($data))
           {
               try{
                   $adminUser=AdminUser::where('username',$data['username'])->find();
               }catch (\Exception $e)
               {
                   $this->error($e->getMessage());
               }


               if(!$adminUser||$adminUser->status!=config('admin.status_normal'))
               {
               $this->error('用户不存在','login/index');
                   exit();
               }
               if($adminUser->password!=IAuth::haltPassword($data['password']))
               {
                   $this->error('密码错误','login/index');
                   exit();
               }
               $this->loginSave($adminUser);
               return true;
           }
           else
           {
               $this->error($validate->getError());
               exit();
           }
    }
    private function loginSave($adminUser)
    {
       $request=Request::instance();
       $adminUser->last_login_ip=$request->ip();
       $adminUser->last_login_time=time();
       try{
           session(config('code.session_user'),$adminUser,config('code.session_scope'));
           $adminUser->save();
       }catch (\Exception $e)
       {
           $this->error($e->getMessage());
       }
    }
    public function logout()
    {
     session(null, config('code.session_scope'));
        $this->redirect('login/index');
    }
    public function toDelete()
    {
    }
}