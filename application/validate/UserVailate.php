<?php
/**
 * Create By guaosi
 * Author guaosi
 * Date: 2017/10/30/0030
 * Time: 8:52
 */
namespace app\validate;
class UserVailate extends BaseVailate
{
    protected $rule=[
        'username'=>'max:20',
        'image'=>'max:200',
        'sex'=>'in:0,1,2',
        'signature'=>'max:200',
        'password'=>'max:20'
    ];
    protected $message=[
        'username.max'=>'用户名过长',
        'username.unique'=>'用户名已存在',
        'image'=>'图片地址不正确',
        'sex'=>'性别范围错误',
        'signature'=>'个性签名过长',
        'password'=>'密码过长'
    ];
}