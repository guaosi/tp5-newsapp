<?php
/**
 * Create By guaosi
 * Author guaosi
 * Date: 2017/10/30/0030
 * Time: 8:52
 */
namespace app\validate;
class NewsPageVailate extends BaseVailate
{
    protected $rule=[
        'page'=>'IdMustPositive',
        'size'=>'IdMustPositive',
        'catid'=>'IdMustPositive|in:1,2,3,4'
    ];
    protected $message=[
        'page'=>'page必须是整数',
        'size'=>'size必须是正整数',
        'catid.IdMustPositive'=>'分类ID必须是整数',
        'catid.in'=>'分类ID范围错误'
    ];
}