<?php
/**
 * Create By guaosi
 * Author guaosi
 * Date: 2017/9/28/0028
 * Time: 23:44
 */
namespace app\lib\exception;
class ParameterException extends BaseException
{
    public $code=400;
    public $msg='通用参数错误';
    public $status=10000;
}