<?php
/**
 * Create By guaosi
 * Author guaosi
 * Date: 2017/10/14/0014
 * Time: 12:57
 */
namespace app\admin\controller;
use app\admin\model\AdminUser as UserModel;
use app\common\lib\IAuth;
use app\validate\AdminUser;
use think\Request;

class Admin extends Base{
    public function add(Request $request)
    {
        if($request->isPost())
        {
            $validate=new AdminUser();
            $data=$request->post();
            if($validate->check($data))
            {
               try{
                   $user=UserModel::create([
                       'username'=>$data['username'],
                       'password'=>IAuth::haltPassword($data['password']),
                   ]);
               } catch (\Exception $e)
               {
                   $this->error($e->getMessage());
               }


               if($user->id)
               {
                  $this->success('用户新增成功...');
               }
               else
               {
                   $this->error('用户新增失败...');
               }
            }
            else
            {
                $this->error($validate->getError());
            }
        }
        else
        {
            return $this->fetch();
        }

    }
}