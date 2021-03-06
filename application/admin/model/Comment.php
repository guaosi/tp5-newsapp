<?php
/**
 * Create By guaosi
 * Author guaosi
 * Date: 2017/11/2/0002
 * Time: 22:30
 */
namespace app\admin\model;
use think\Db;
use think\Model;
class Comment extends Model
{
    protected $insert = ['status' => 0];
    public function user()
    {
        return $this->hasOne('User','id','user_id');
    }
    public function news(){
        return $this->hasOne('News','id','news_id');
    }
    public function toUser()
    {
        return $this->hasOne('User','id','to_user_id');
    }
    /**
     * 传统
     * @param array $param
     * @return int|string
     */
    public static function getNormalCommentsCountsByCondition($param=[])
    {
        return Db::table('news_comment')
            ->alias('a')
            ->join('news_user b','a.user_id=b.id','LEFT')
            ->where('news_id',$param['id'])
            ->count();
    }
    public static function getNormalCommentsPageByCondition($param=[],$nowPage=0,$size=5)
    {
        return Db::table('news_comment')
            ->alias('a')
            ->join('news_user b','a.user_id=b.id')
            ->where('news_id',$param['id'])
            ->limit($nowPage,$size)
            ->order(['a.id'=>'desc'])
            ->select();
    }

    /**
     * 新颖
     * @param array $params
     * @return int|string
     */
    public static function getCountsByCondition($params=[])
    {
        return self::field(['id'])
            ->where($params)
            ->count();
    }
    public static function getListByCondition($param=[],$nowPage=0,$size=5)
    {
        return self::field(['id','user_id','content','to_user_id','parent_id','create_time'])
            ->where($param)
            ->limit($nowPage,$size)
            ->order('id','desc')
            ->select();
    }

    /**
     *用关联模型
     */
    public static function getListByTp5Model($param=[],$nowPage=0,$size=5)
    {
       return self::with(['toUser'])
           ->where($param)
           ->limit($nowPage,$size)
           ->order('id','desc')
           ->select();
    }
}