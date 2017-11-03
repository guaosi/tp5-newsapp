<?php
/**
 * Create By guaosi
 * Author guaosi
 * Date: 2017/9/28/0028
 * Time: 23:41
 */
namespace app\validate;
use app\lib\exception\ParameterException;
use think\Exception;
use think\Request;
use think\Validate;

class BaseVailate extends Validate
{
    public function goCheck()
    {
        $request=Request::instance();
        $data=$request->param();
        $res=$this->batch()->check($data);
        if ($res)
        {
           return true;
        }
        else
        {
           $e=new ParameterException([
                'msg'=>$this->error
            ]);
            throw $e;
        }
    }

    /**
     * 用于验证是否是正整数
     * @param $value
     * @param string $rule
     * @param string $data
     * @param string $field
     * @return bool
     */
    protected function IdMustPositive($value,$rule='',$data='',$field=''){
        if(is_numeric($value)&&is_int($value+0)&&($value+0>0))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * 用于验证手机号是否合法
     * @param $value
     * @return bool
     */
    protected function isMobile($value)
    {
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule, $value);
        if ($result) {
            return true;
        } else {
            return false;
        }
    }


}
