<?php
/**
 * Create By guaosi
 * Author guaosi
 * Date: 2017/9/28/0028
 * Time: 23:26
 */
namespace app\lib\exception;
use think\Exception;
use Throwable;

class BaseException extends Exception
{
    public $code=400;
    public $msg="通用参数错误";
    public $status=10000;
    public $data=[];
    public function __construct($param=[])
    {
       if (!is_array($param))
       {
           return;
       }
       if (array_key_exists('msg',$param))
       {
           $this->msg=$param['msg'];
       }
        if (array_key_exists('errorCode',$param))
        {
            $this->errorCode=$param['errorCode'];
        }
        if (array_key_exists('code',$param))
        {
            $this->code=$param['code'];
        }
    }


}