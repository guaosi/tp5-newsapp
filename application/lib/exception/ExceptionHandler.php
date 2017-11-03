<?php
/**
 * Create By guaosi
 * Author guaosi
 * Date: 2017/9/28/0028
 * Time: 23:21
 */
namespace app\lib\exception;

use think\exception\Handle;
use think\Log;
use think\Request;

class ExceptionHandler extends Handle
{
    private $code;
    private $msg;
    private $status;
    private $data=[];
    public function render(\Exception $e)
    {
        if ($e instanceof BaseException)
        {
            $this->code=$e->code;
            $this->msg=$e->msg;
            $this->status=$e->status;
            $this->data=$e->data;
        }
        else
        {
            if (config('app_debug'))
            {
                return parent::render($e);
            }
            else
            {
                $this->code=500;
                $this->msg='服务器内部错误，就不告诉你~';
                $this->status=999;
                $this->recordErrorLog($e);
            }
        }
        $err=[
            'status'=>$this->status,
            'msg'=>$this->msg,
            'url'=>Request::instance()->url(),
            'data'=>$this->data
        ];
        return json($err,$this->code);
    }
    private function recordErrorLog(\Exception $e)
    {
        Log::init([
            'type'=>'File',
            // 日志保存目录
            'path'  => LOG_PATH,
            // 日志记录级别
            'level' => ['error'],
        ]);
        Log::record($e->getMessage(),'error');
    }

}