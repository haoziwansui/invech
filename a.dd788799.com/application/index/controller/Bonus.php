<?php
namespace app\index\controller;
use app\index\Base;
use app\common\model\Bonus as b;
class Bonus extends Base{

    //红利列表
    public function index()
    {
        if(IS_AJAX){
            $bonus = new b();
            $list = $bonus->getList(0)->toArray();
            $data = array('result'=>1);
            $promotionList = [];
            foreach ($list as $k => $v){
                $promotionList[$k]['id'] = $v['id'];
                $promotionList[$k]['bigImageData'] = null;
                $promotionList[$k]['bigImageId'] = $v['img'];
                $promotionList[$k]['content'] = $v['content'];
                $promotionList[$k]['createTime'] = intval(strtotime($v['created_at']).'000');
                $promotionList[$k]['enable'] = null;
                $promotionList[$k]['name'] = $v['name'];
                $promotionList[$k]['smallImageData'] = '';
                $promotionList[$k]['smallImageId'] = '';
                $promotionList[$k]['startTime'] = intval(strtotime($v['created_at']).'000');
                $promotionList[$k]['updateTime'] = null;
                $promotionList[$k]['weight'] = null;
            }
            $data['promotionList'] = $promotionList;
            return $data;
        }else{
            return $this->fetch('index');
        }
    }
    
    public function turntable()
    {        
        if(IS_GET){
            return $this->fetch();    
        }    
    }          

    public function get_init_data(){
        if(IS_GET && IS_AJAX){
            $UserModel = new UserModel();
            $ret =  $UserModel->get_bonus_init_data();
            if($ret){
                return $this->success('','',$ret);
            }else{
                return $this->error($UserModel->getError());
            }   
        }
    }

    public function do_turntable(){
        if(IS_POST && IS_AJAX){
            $ret = $this->user->do_turntable();
            if($ret){
                return $this->success('','',$ret);
            }else{
                return $this->error($this->user->getError());
            }       
        }
    }
        


}