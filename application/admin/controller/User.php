<?php
/**
 * Create By guaosi
 * Author guaosi
 * Date: 2017/11/3/0003
 * Time: 13:44
 */
namespace app\admin\controller;
use app\admin\model\User as UserModel;
use think\Request;

class User extends Base
{
    /**
     * 会员列表
     * @param Request $request
     * @return mixed
     */
    public function index(Request $request)
    {
        $data=$request->except('/admin/user/index_html');
//       $list=\app\admin\model\News::getNewsPage($pagesize);
//       将传递过来的数组参数变成url后面的参数形式
//        用于laypage
        $url=http_build_query($data);
        $this->setPageAndSize($data);

        $whereData=[];
        if(!isset($data['status'])||$data['status']==2)
        {
            $whereData['status']=[
                'neq',config('admin.status_delete')
            ];
        }
        else
        {
            $whereData['status']=[
                'eq',$data['status']
            ];
        }
        if(!empty($data['username']))
        {
            $whereData['username']=[
                'like','%'.$data['username'].'%'
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

        $list=UserModel::getUsersByCondition($whereData,$this->from,$this->pageSize);
        $totalCount=UserModel::getUsersByConditionCount($whereData);
//        halt($list->toArray());

        $pageTotal=ceil($totalCount/$this->pageSize);
        return $this->fetch('',[
            'list'=>$list,
            'total'=>$pageTotal,
            'curr'=>$this->page,
            'url'=>$url,
            'status'=>!isset($data['status'])?2:$data['status'],
            'start_time'=>empty($data['start_time'])?'':$data['start_time'],
            'end_time'=>empty($data['end_time'])?'':$data['end_time'],
            'username'=>empty($data['username'])?'':$data['username']
        ]);
    }
}