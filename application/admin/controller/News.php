<?php
/**
 * Create By guaosi
 * Author guaosi
 * Date: 2017/10/15/0015
 * Time: 10:41
 */
namespace app\admin\controller;
use app\common\lib\Upload;
use app\admin\model\News as NewsModel;
use app\validate\IdMustPositive;
use app\validate\NewsVailate;
use think\Exception;
use think\Request;

class News extends Base
{
   public function index(Request $request)
   {
       $data=$request->except('/admin/news/index_html');
//       $list=\app\admin\model\News::getNewsPage($pagesize);
//       将传递过来的数组参数变成url后面的参数形式
//       逆函数 parse_str($data,$arr);
       $url=http_build_query($data);

       $this->setPageAndSize($data);
       $whereData=[];
             $whereData['status']=[
             'neq',config('admin.status_delete')
      ];
       if(!empty($data['catid']))
       {
           $whereData['catid']=[
               'eq',$data['catid']
           ];
       }
       if(!empty($data['title']))
       {
           $whereData['title']=[
             'like',"%".$data['title']."%"
           ];
       }
       if(!empty($data['start_time'])&&!empty($data['end_time']))
       {
           $start_time=strtotime($data['start_time']);
           $end_time=strtotime($data['end_time']);
           if($start_time<$end_time)
           {
              $whereData['create_time']=[
                 ['gt',$start_time],
                  ['lt',$end_time]
              ];
           }
       }
       elseif (!empty($data['start_time']))
       {
           $whereData['create_time']=[
               'gt',strtotime($data['start_time'])
           ];
       }
       elseif(!empty($data['end_time']))
       {
           $whereData['create_time']=[
               'lt',strtotime($data['end_time'])
           ];
       }

        $list=NewsModel::getNewsByCondition($whereData,$this->from,$this->pageSize);
        $totalCount=NewsModel::getNewsByConditionCount($whereData);
        $pageTotal=ceil($totalCount/$this->pageSize);
       return $this->fetch('',[
           'list'=>$list,
           'total'=>$pageTotal,
           'curr'=>$this->page,
           'cats'=>config('cats.lists'),
           'url'=>$url,
           'catsselect'=>empty($data['catid'])?0:$data['catid'],
           'start_time'=>empty($data['start_time'])?'':$data['start_time'],
           'end_time'=>empty($data['end_time'])?'':$data['end_time'],
           'title'=>empty($data['title'])?'':$data['title']
       ]);
   }
   public function add(Request $request)
   {
       if ($request->isPost())
       {
              $data=$request->post();
              $newVailate=new NewsVailate();
              if($newVailate->check($data))
              {
                   try
                   {
                       if(NewsModel::create($data))
                       {
                           $res=[
                               'code'=>1,
                               'message'=>'添加成功...',
                               'jump_url'=>url('News/index')
                           ];
                       }
                       else
                       {
                           throw new Exception();
                       }
                   }
                   catch (\Exception $e)
                   {
                       $res=[
                           'code'=>0,
                           'message'=>'添加失败...',
                       ];
                   }
              }
              else
              {
                  $res=[
                      'code'=>0,
                      'message'=>$newVailate->getError(),
                  ];
              }
              return json($res);
       }
       else
       {
           return $this->fetch('',['cats'=>config('cats.lists')]);
       }

   }
   private function uploadToMe()
   {

       $file = Request::instance()->file('file');
       $info = $file->move(ROOT_PATH . 'public' . DS . 'uploads');
       $data=[
           'code'=>0,
           'message'=>'error',
       ];
       if($info) {
//           'url'=>'/uploads/'.$info->getSaveName()
           $data=[
               'code'=>100,
               'message'=>'ok',
               'url'=>'http://shopyiip.guaosi.com.cn/599c3be0de6d9'
           ];
       }
       return json($data);
   }
   public function upload()
   {
       $result=Upload::UploadByQiniu();
       if ($result)
       {
           $data=[
               'code'=>100,
               'message'=>'ok',
               'url'=>config('qiniu.imgageUrl').'/'.$result
           ];
       }
       else
       {
           $data=[
               'code'=>0,
               'message'=>'error',
           ];
       }
       return json($data);
   }
   public function edit(Request $request)
   {
       if($request->isGet())
       {
           $data=$request->param();
           $vailate=new IdMustPositive();
           if(!$vailate->check($data))
           {
               $this->error($vailate->getError());
               exit();
           }
           $whereData=[];
           $whereData['id']=[
               'eq',$data['id']
           ];
           $whereData['status']=[
               'neq',config('admin.status_delete')
           ];
           $news=NewsModel::where($whereData)->find();

           if(!$news)
           {
               $this->error("该记录不存在...");
           }

           return $this->fetch('',[
               'cats'=>config('cats.lists'),
               'news'=>$news
           ]);

       }
       else if ($request->isPost())
       {
           $data=$request->except(['/admin/news/edit_html']);
           $vailate=new IdMustPositive();
           $result=[];
           if(!$vailate->check($data))
           {
              $result=[
                  'code'=>0,
                  'message'=>$vailate->getError()
              ];
              return $result;
           }

           $whereData['id']=[
               'eq',$data['id']
           ];
           $whereData['status']=[
               'neq',config('admin.status_delete')
           ];

           $news=NewsModel::where($whereData)->find();
           if(!$news)
           {
               $result=[
                   'code'=>0,
                   'message'=>'该记录不存在...'
               ];
               return $result;
           }

           $newsVailate=new NewsVailate();
           if(!$newsVailate->check($data))
           {
               $result=[
                   'code'=>0,
                   'message'=>$newsVailate->getError()
               ];
               return $result;
           }

           if($data['image']!=$news->image)
           {
//               说明不是同一个图片
               $imageName=str_replace(config('qiniu.imgageUrl').'/','',$news->image);
               Upload::deleteByQiniu($imageName);
           }
           $data['update_time']=time();
           $data['is_allowcomments']=empty($data['is_allowcomments'])?0:1;
           $data['is_head_figure']=empty($data['is_head_figure'])?0:1;
           $data['is_position']=empty($data['is_position'])?0:1;
           try{
           if(NewsModel::update($data)!==false)
           {
               $result=[
                   'code'=>1,
                   'message'=>'更新成功...',
                   'jump_url'=>url('News/index')
               ];
           }else
           {
               throw new Exception('数据更新失败');
           }
           }catch (\Exception $e)
           {
               $result=[
                   'code'=>0,
                   'message'=>$e->getMessage()
               ];
           }
           return json($result);
       }


   }
}