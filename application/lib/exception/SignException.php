<?php
/**
 * Create By guaosi
 * Author guaosi
 * Date: 2017/10/29/0029
 * Time: 12:42
 */
namespace app\lib\exception;

class SignException extends BaseException
{
    public $code=400;
    public $msg="sign不存在";
    public $status=10000;
}