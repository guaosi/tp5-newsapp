<?php
/**
 * Create By guaosi
 * Author guaosi
 * Date: 2017/11/2/0002
 * Time: 15:32
 */
namespace app\api\controller\v1;
use app\common\lib\Aes;
use app\common\lib\IAuth;
use app\lib\exception\ParameterException;
use app\validate\UserVailate;
use think\Exception;
use app\admin\model\User as Usermodel;
use think\Request;

class User extends AuthBase
{
    /**
     * 获取用户信息
     * @return \think\response\Json
     */
    public function read()
    {
         return showJson(config('admin.app_success'),'OK',(new Aes())->encrypt($this->user));
    }

    /**
     * 修改用户信息
     * @param Request $request
     * @return \think\response\Json
     * @throws ParameterException
     */
    public function update(Request $request)
    {
        (new UserVailate())->goCheck();
        $data=$request->param();
        $user=$this->user;
        if(empty($data['password']))
        {
//            证明此时是修改基本资料
            if(empty($data['username']))
            {
                throw new ParameterException([
                    'msg'=>'用户名不能为空'
                ]);
            }
            else
            {
                $finduser=Usermodel::where('id','<>',$user->id)->where('username',$data['username'])->find();
                if(!empty($finduser))
                {
                    throw new ParameterException([
                        'msg'=>'用户名已经存在'
                    ]);
                }
                else{
                    $user->username=$data['username'];
                }
                $user->image=$data['image'];
                $user->signature=$data['signature'];
                if(in_array($data['sex'],[0,1,2]))
                {
                    $user->sex=$data['sex'];
                }
            }
        }
        else{
//            证明此时是修改密码
            $user->password=IAuth::haltPassword($data['password']);
        }

            try{
               if($user->save()!==false)
               {
                  return showJson(config('admin.app_success'),'OK',[],202);
               }
               else
               {
                   return showJson(config('admin.app_error'),'更新失败',[],401);
               }
            }catch (\Exception $e)
            {
              return showJson(config('admin.app_error'),$e->getMessage(),[],500);
            }
        

    }
}