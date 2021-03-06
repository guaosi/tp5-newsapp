<?php
/**
 * Create By guaosi
 * Author guaosi
 * Date: 2017/11/2/0002
 * Time: 22:24
 */
namespace app\api\controller\v1;
use app\lib\exception\ParameterException;
use app\validate\CommentVailate;
use app\validate\IdMustPositive;
use think\Request;
use app\admin\model\User;
use app\admin\model\News as NewsModel;
use app\admin\model\Comment as CommentModel;
class Comment extends AuthBase
{
    /**
     * 用户评论
     * @return \think\response\Json
     */
    public function save(Request $request)
    {
        (new CommentVailate())->goCheck();
        $data=$request->param();
        $id=$data['news_id'];
        $news=NewsModel::where('id',$id)->where('status','<>',config('admin.status_delete'))->find();
        if(empty($news))
        {
            return showJson(config('admin.app_error'),'文章不存在',[],404);
        }
        if((empty($data['to_user_id'])&&!isset($data['parent_id']))||(!empty($data['to_user_id'])&&isset($data['parent_id'])))
        {
            if(!empty($data['to_user_id']))
            {
                if(!User::where('id',$data['to_user_id'])->find())
                {
                    return showJson(config('admin.app_error'),'评论目标用户不存在',[],404);
                }
            }
            if(!empty($data['parent_id']))
            {
            if(!CommentModel::where('id',$data['parent_id'])->where('status','<>',config('admin.status_delete'))->find())
            {
                return showJson(config('admin.app_error'),'评论对象不存在',[],404);
            }
            }
            $newData=[
                'content'=>htmlspecialchars($data['content']),
                'user_id'=>$this->user->id,
                'news_id'=>$id,
                'to_user_id'=>empty($data['to_user_id'])?'':$data['to_user_id'],
                'create_time'=>time(),
                'parent_id'=>empty($data['parent_id'])?'':$data['parent_id']
            ];
            try{
              if(CommentModel::create($newData))
              {
                  $news->comment_count=$news->comment_count+1;
                  if($news->save()!==false)
                  {
                      return showJson(config('admin.app_success'),'评论成功',[],201);
                  }
              }
              else
              {
                  return showJson(config('admin.app_error'),'评论失败',[],403);
              }
            }
            catch (\Exception $e)
            {
                return showJson(config('admin.app_error'),'内部错误',[],500);
            }
        }
        else
        {
            throw new ParameterException([
                'msg'=>'参数错误'
            ]);
        }
    }

    /**
     * 获取某篇文章的评论数
     * @param Request $request
     * @return \think\response\Json
     */
    public function getCount(Request $request)
    {
        (new IdMustPositive())->goCheck();
        $data=$request->param();
        $id=$data['id'];
        $news=NewsModel::where('id',$id)->where('status','<>',config('admin.status_delete'))->where('status','<>',config('admin.status_delete'))->find();
        if(empty($news))
        {
            return showJson(config('admin.app_error'),'文章不存在',[],404);
        }
        $where=[
            'news_id'=>$id
        ];
        $count=CommentModel::where($where)->count();
            return showJson(config('admin.app_success'),'OK',['count'=>$count],200);
    }

    /**
     * v1版本，通常的关联分页
     * @param Request $request
     * @return \think\response\Json
     */
//    public function read(Request $request)
//    {
//        (new IdMustPositive())->goCheck();
//        $data=$request->param();
//        $id=$data['id'];
//        $news=NewsModel::where('id',$id)->where('status','<>',config('admin.status_delete'))->find();
//        if(empty($news))
//        {
//            return showJson(config('admin.app_error'),'文章不存在',[],404);
//        }
//        $this->setPageAndSize($data);
//        $list=CommentModel::getNormalCommentsPageByCondition($data,$this->from,$this->pageSize);
//        $totalCount=CommentModel::getNormalCommentsCountsByCondition($data);
//        $pageTotal=ceil($totalCount/$this->pageSize);
//        $result=[
//            'list'=>$list,
//            'total'=>$totalCount,
//            'page_num'=>$pageTotal
//        ];
//        return showJson(config('admin.app_success'),'OK',$result);
//    }
    /**
     * v2版本，先获取主表里的信息，然后根据信息里找从表里的信息，然后整合返回
     * @param Request $request
     * @return \think\response\Json
     */
    public function read(Request $request)
    {
        (new IdMustPositive())->goCheck();
        $data=$request->param();
        $id=$data['id'];
        $news=NewsModel::where('id',$id)->where('status','<>',config('admin.status_delete'))->find();
        if(empty($news))
        {
            return showJson(config('admin.app_error'),'文章不存在',[],404);
        }
        $this->setPageAndSize($data);
        $paramdata=[
            'status'=>config('admin.status_normal'),
            'news_id'=>$data['id']
        ];
        $comments=CommentModel::getListByCondition($paramdata,$this->from,$this->pageSize);
        if(!$comments->isEmpty())
        {
            $totalCount=CommentModel::getCountsByCondition($paramdata);
            $pageTotal=ceil($totalCount/$this->pageSize);
            foreach ($comments as $comment)
            {
                $userIds[]=$comment->user_id;
                if(!empty($comment->to_user_id))
                {
                    $userIds[]=$comment->to_user_id;
                }
            }
            $userIds=array_unique($userIds);
            $users=User::getUserInfoByIds($userIds);
            foreach ($users as $user)
            {
                $newUsers[$user->id]=$user;
            }
            $result=[];
            foreach ($comments as $comment)
            {
                $result[]=[
                    'id'=>$comment->id,
                    'user_id'=>$comment->user_id,
                    'content'=>$comment->content,
                    'to_user_id'=>$comment->to_user_id,
                    'parent_id'=>$comment->parent_id,
                    'create_time'=>$comment->create_time,
                    'username'=>empty($newUsers[$comment->user_id]->username)?"":$newUsers[$comment->user_id]->username,
                    'tousername'=>empty($newUsers[$comment->to_user_id]->username)?"":$newUsers[$comment->to_user_id]->username,
                    'image'=>empty($newUsers[$comment->user_id]->image)?"":$newUsers[$comment->user_id]->image,
                ];
            }
            $newResult=[
            'list'=>$result,
            'total'=>$totalCount,
            'page_num'=>$pageTotal
            ];
            return showJson(config('admin.app_success'),'OK',$newResult);
        }
        else
        {
         $result=[
            'list'=>[],
            'total'=>0,
            'page_num'=>0
        ];
        return showJson(config('admin.app_success'),'OK',$result);
        }
    }
}