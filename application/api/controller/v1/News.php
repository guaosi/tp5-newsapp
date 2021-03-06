<?php
/**
 * Create By guaosi
 * Author guaosi
 * Date: 2017/10/30/0030
 * Time: 8:38
 */
namespace app\api\controller\v1;
use app\api\controller\Common;
use app\lib\exception\RequestNotFoundException;
use app\validate\IdMustPositive;
use app\validate\NewsPageVailate;
use think\Exception;
use think\Request;
use app\admin\model\News as NewsModel;
class News extends Common
{
    /**
     * app首页头图以及推荐位接口
     * @param Request $request
     * @return \think\response\Json
     */
    public function index(Request $request)
    {

        (new NewsPageVailate())->goCheck();
        $data=$request->get();
        $this->setPageAndSize($data);
        $where['status']=[
            'eq',config('admin.status_normal')
        ];
        if (empty($data['catid'])&&!empty($data['key']))
        {
            $where['title']=[
               'like','%'.$data['key'].'%'
            ];

        }


        else if (empty($data['catid']))
        {
            $where['catid']=[
                'eq',1
            ];
        }else if (!empty($data['catid']))
        {
            $where['catid']=[
                'eq',$data['catid']
            ];
        }

        $news=NewsModel::getNewsByCondition($where,$this->from,$this->pageSize);
        $total=NewsModel::getNewsByConditionCount($where);
//        $this->editphoto($news);
        $result=[
            'total'=>$total,
            'page_num'=>ceil($total/$this->pageSize),
            'list'=>$this->showCatNameById($news->visible(['id','title','catid','image','read_count','create_time','status','update_time','is_position','read_count'])->toArray()),
        ];
        return showJson(config('admin.app_success'),'ok',$result);
    }

    /**
     * app文章详情接口
     * @param Request $request
     * @return array
     */
    public function read(Request $request)
    {
        (new IdMustPositive())->goCheck();
        $data=$request->param();
        $where=[
            'id'=>$data['id'],
            'status'=>config('admin.status_normal')
        ];
        $news=NewsModel::field(['id','catid','title','small_title','image','content','create_time','read_count','upvote_count','comment_count'])->where($where)->find();
        if(!$news)
        {
            throw  new RequestNotFoundException();
        }
        try{
          $news->read_count=$news->read_count+1;
          $news->save();
        }catch (\Exception $e)
        {
              throw new Exception($e->getMessage());
        }
        $cats=config('cats.lists');
        $news->catname=$cats[$news->catid];
        return showJson(config('admin.app_success'),'ok',$news);
    }

}