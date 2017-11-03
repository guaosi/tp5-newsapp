<?php
/**
 * Create By guaosi
 * Author guaosi
 * Date: 2017/9/28/0028
 * Time: 23:44
 */
namespace app\lib\exception;
class IsLoginException extends BaseException
{
    public $code=401;
    public $msg='您还没有登陆';
    public $status=10000;
}