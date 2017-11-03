<?php
/**
 * Create By guaosi
 * Author guaosi
 * Date: 2017/11/3/0003
 * Time: 13:25
 */
namespace app\common\lib;
class Jpush{
    public static function pushMsg($title='',$newsId=0,$type='android')
    {
//        目前只能推送安卓

       try{
           $client = new \JPush\Client(config('jiguang.AppKey'), config('jiguang.Master Secret'));
           $client->push()
               ->setPlatform('all')
               ->addAllAudience()
               ->setNotificationAlert($title)
               ->androidNotification($title, array(
                   'title' => $title,
                   'extras' => array(
                       'id' => $newsId,
                   ),
               ))
               ->send();
       }catch (\Exception $e)
       {
           return false;
       }
       return true;
    }
}