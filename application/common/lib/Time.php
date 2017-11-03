<?php
/**
 * Create By guaosi
 * Author guaosi
 * Date: 2017/10/29/0029
 * Time: 13:51
 */
namespace app\common\lib;
class Time
{
    /**
     * 可以用time直接10位，也可以用下面的13位
     */
    public static function get13Time()
    {
        list($t1,$t2)=explode(' ',microtime());
//        t1是毫秒 t2是秒  取t1的3位与t2进行拼接
        $time=$t2.ceil($t1*1000);
        return $time;
    }
}