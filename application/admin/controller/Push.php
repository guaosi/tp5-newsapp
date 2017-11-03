<?php
/**
 * Create By guaosi
 * Author guaosi
 * Date: 2017/10/31/0031
 * Time: 20:26
 */
namespace app\admin\controller;
use app\common\lib\Jpush;
use app\validate\IdMustPositive;
use app\validate\PushVailate;
use app\validate\VersionVailate;
use app\admin\model\Version as VersionModel;
use think\Request;

class Push extends Base
{
    public function add(Request $request)
    {
        if ($request->isPost()) {
            $data = $request->post();
            $pushVailate = new PushVailate();
            if ($pushVailate->check($data)) {
                if (!in_array($data['type'], config('jiguang.type'))) {
                    $res = [
                        'code' => 0,
                        'message' => '推送平台错误',
                    ];
                } else {
                    $news_id = $data['news_id'];
                    $news = \app\admin\model\News::where('id', $news_id)->where('status', '<>', config('admin.status_delete'))->find();
                    if (!$news) {
                        $res = [
                            'code' => 0,
                            'message' => '该新闻不存在',
                        ];
                        return json($res);
                    }
                    if (Jpush::pushMsg($data['title'], $data['news_id'], $data['type'])) {
                        $res = [
                            'code' => 1,
                            'message' => '发送成功...',
                            'jump_url' => url('push/add')
                        ];
                    } else {
                        $res = [
                            'code' => 0,
                            'message' => '发送失败',
                        ];
                    }
                }
                return json($res);
            }
          }
        else {
            return $this->fetch('', ['type' => config('jiguang.type')]);
        }
    }
}