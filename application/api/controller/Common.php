<?php
/**
 * Create By guaosi
 * Author guaosi
 * Date: 2017/10/28/0028
 * Time: 15:18
 */
namespace app\api\controller;
use app\common\lib\IAuth;
use app\common\lib\Time;
use think\Controller;
use think\Request;
use app\common\lib\Aes;
use app\lib\exception\SignException;
Class Common extends Controller
{
    protected $header=[];
    /**
     * 分页的当前页数 分页大小以及当前条数
     * @var string
     */
    protected $page='';
    protected $pageSize='';
    protected $from='';
    public function _initialize()
    {
       $this->checkRequestAuth();
//       $this->testAes();
    }

    /**
     * 验证sign是否合法
     * @throws SignException
     */
    public function checkRequestAuth()
    {
        $request=Request::instance();
         $header=$request->header();
        if (empty($header['sign']))
        {
            throw new SignException();
        }
//        if (!in_array($header['app_type'],config('setting.app_type')))
//        {
//            throw new SignException([
//               'msg'=>'相关机型信息不存在'
//            ]);
//        }
        if (empty($header['did']))
        {
            throw new SignException([
                'msg'=>'did为空'
                ]);
        }
        if(!IAuth::checkSign($header))
        {
            throw new SignException([
                'msg'=>'sign验证不合法',
                'code'=>401
            ]);
        }
        $this->header=$header;
//        没有问题，将sign写入缓存，变成唯一性
        cache($header['sign'],1,config('setting.app_sign_cache_time'));
    }
    public function testAes()
    {
         $request=Request::instance();
         $data=$request->post();
        $data=[
            'time'=>Time::get13Time(),
            'did'=>'1DC5FD67F11589FA55A7D95887ED85'
        ];
         dump(IAuth::setSign($data));
         exit();
//         $str="Eb9BO62SqD1cKThX32pUaYH4NDYbWiZGTpSCQvZPNRFu39REqEoXQIPNWewG7LoLsjgVGs5QGw/XQIXD5AoxZA==";
//         dump((new Aes())->decrypt($str));
//         exit();

    }

    /**
     * 通用化将数据加入分类名称通过catid
     * @param $data
     * @return array
     */
    protected function showCatNameById($data)
    {
        if (empty($data))
        {
            return [];
        }
        $cats=config('cats.lists');
        foreach ($data as $key=>$val)
        {
            $data[$key]['catname']=$cats[$val['catid']];
        }
        return $data;
    }
    /**
     * 将分页大小及页数进行赋值
     */
    protected function editphoto($data)
    {
        foreach ($data as $key => $val)
        {
            if(!empty($val['image']))
            {
                $data[$key]['image'].='-test';
            }
        }
    }
    public function setPageAndSize($data)
    {
//        $pagesize=5;
        $this->page=empty($data['page'])?1:$data['page'];
        $this->pageSize=empty($data['size'])?config('paginate.list_rows'):$data['size'];
        $this->from=($this->page-1)*$this->pageSize;
    }
}