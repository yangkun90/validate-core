<?php
/* * @Author: 杨坤  */

namespace YkCmsTp\validate;

use think\Validate;
use YkCmsTp\exception\ParamException;
class Param
{
    public $rule;
    public $field;
    public $request;
    public $scene;

    public function __construct($rule,\think\Request $request,array $field = [], string $scene = null)
    {
        $this->request = $request;
        $this->rule = $rule;
        $this->field = $field;
        $this->scene = $scene;
    }

    public function check(){
        if (is_string($this->rule)) {
            $validate = new $this->rule();
            (!empty($this->scene)) && $validate = $validate->scene($this->scene);
        }else{
            $validate = (new Validate())->make($this->rule,[],$this->field);
        }

        $res = $validate->batch()->check($this->request->param());
        if(!$res){
            throw new ParamException([
                'message' => $validate->getError(),
            ]);
        }
        return true;
    }
}