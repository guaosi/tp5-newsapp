<?php
/**
 * Create By guaosi
 * Author guaosi
 * Date: 2017/9/28/0028
 * Time: 14:20
 */
namespace app\admin\controller;
class Index extends Base
{
    public function index()
    {
//        dump(session(config('code.session_user'),'',config('code.session_scope')));
//        exit();
          return $this->fetch();
    }
    public function welcome(){
        echo 'hello world';
    }
    public function toDelete()
    {

    }
}