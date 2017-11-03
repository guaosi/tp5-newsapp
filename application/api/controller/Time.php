<?php
/**
 * Create By guaosi
 * Author guaosi
 * Date: 2017/10/29/0029
 * Time: 14:27
 */
namespace app\api\controller;
use think\Controller;
class Time extends Controller
{
    public function getTime()
    {
        return showJson('1','server time',[time()]);
    }
}