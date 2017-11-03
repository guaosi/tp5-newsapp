<?php
/**
 * Create By guaosi
 * Author guaosi
 * Date: 2017/10/29/0029
 * Time: 15:04
 */
namespace app\api\controller\v1;
use app\api\controller\Common;
class Cat extends Common
{
    public function read()
    {
        $cats=config('cats.lists');
        $result=[
            [
                'catid'=>0,
                'catname'=>'首页'
            ]
        ];
        foreach ($cats as $key => $val)
        {
            $result[]=[
                'catid'=>$key,
                'catname'=>$val
            ];
        }
        return showJson(config('admin.app_success'),'OK',$result);
    }
}