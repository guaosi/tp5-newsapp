<?php
/**
 * Create By guaosi
 * Author guaosi
 * Date: 2017/11/2/0002
 * Time: 22:25
 */
namespace app\validate;
class CommentVailate extends BaseVailate
{
    protected $rule=[
        'news_id'=>'require|IdMustPositive',
        'content'=>'require|max:300',
        'to_user_id'=>'IdMustPositive',
    ];
    protected $message=[
        'news_id.require'=>'新闻ID不能为空',
        'news_id.IdMustPositive'=>'新闻ID必须是正整数',
        'content.max'=>'评论内容不能超过300',
        'content.require'=>'评论内容必须填写',
        'to_user_id.IdMustPositive'=>'评论目标用户ID必须是正整数',
    ];
}