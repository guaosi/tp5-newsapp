<?php
/**
 * Create By guaosi
 * Author guaosi
 * Date: 2017/10/29/0029
 * Time: 23:47
 */
namespace app\api\controller\v1;
use app\admin\model\AppActive;
use app\admin\model\News;
use app\admin\model\Version;
use app\api\controller\Common;
use app\lib\exception\RequestNotFoundException;
use think\Log;

class Index extends Common
{
    /**
     * 获取首页头图数据以及列表数据
     * @return array
     */
    public function index()
    {
        $header=News::getIndexHeadNews();
        $header=$this->showCatNameById($header);
         $position=News::getIndexPositionNews();
         $position=$this->showCatNameById($position);
         $result=[
             'heads'=>$header,
             'positions'=>$position
         ];
        return showJson(config('admin.app_success'),'OK',$result);
    }

    /**
     * 获取是否版本升级
     * @return \think\response\Json
     */
    public function init()
    {
        $version = Version::getLastVersionByAppType($this->header['app-type']);
        if (empty($version)){
            throw new RequestNotFoundException();
        }
//        is_update  0 代表无更新 1代表有更新 2代表强制更新
        if($version->version>$this->header['version'])
        {
           $version->is_update=$version->is_force==1?2:1;
        }
        else
        {
           $version->is_update=0;
        }
//        统计用户登录信息情况等等
          $data=[
              'version'=>$this->header['version'],
              'app_type'=>$this->header['app-type'],
              'did'=>$this->header['did']
          ];
        try{
            AppActive::create($data);
        }catch (\Exception $e)
        {
            Log::notice('设备-'.$data['did'].',版本-'.$data['version'].',类型-'.$data['app_type'].'写入数据库失败,时间:'.date('Y-m-d H:i:s').'...原因'.$e->getMessage());
        }
        return showJson('1','OK',$version);
    }
}