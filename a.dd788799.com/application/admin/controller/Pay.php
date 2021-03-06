<?php
namespace app\admin\controller;
use app\admin\Login;
use think\Cache;
use app\common\model\PaySet as PaySetModel;
use app\common\model\PayWay as PayWayModel;
use app\common\model\PayThird as PayThirdModel;
use app\common\model\PayChannel as PayChannelModel;
use app\common\model\ActionLog as LogModel;
class Pay extends Login{
   
  	 /**
     *菜单入口:支付通道分组
    */
    public function set(){
        $this->view->page_header = '支付类别';
		$request = request();
        $list = PaySetModel::getList($request);
        $this->assign('list',$list);
        return $this->fetch();
    }

    public function set_edit(){
        $request = request();
        $params  = $request->param();
        if(request()->isGet()){
            if(!empty($params['id'])){
                $info   =  PaySetModel::get(['id'=>$params['id']]);
                $this->assign('info',$info);  
            }
            $paylist   =  PaySetModel::PAY_SET_TYPE;
            $this->assign('paylist',$paylist); 
            return view();                
        }else{
            $id   =  !empty($params['id'])? $params['id']:'';
            if($id){
                $payset = PaySetModel::get(intval($id));
            }else{
                $payset = new PaySetModel($params);
            }

            $list = $payset->validate('Pay.set')->save($params);

            if($list){
                cache('gygy_pay_set','');
                LogModel::log(LogModel::BUSINESS_TYPE_EDIT,$payset,LogModel::BUSINESS_TYPES[LogModel::BUSINESS_TYPE_EDIT]);
                return $this->success('操作成功');
            }else{
                return $this->error($payset->getError());
            }
        }
    }

    public function way(){
        $this->view->page_header = '支付方式';
        $request = request();
        $list = PayWayModel::getList($request);
        $setlist = PaySetModel::getAll();
        $this->assign('setlist',$setlist);
        $this->assign('list',$list);
        return $this->fetch();
    }

    public function way_edit(){
        $request = request();
        $params = $request->param();
        if(request()->isGet()){
            if(!empty($params['id'])){
                $info   =  PayWayModel::get(['id'=>$params['id']]);
                $this->assign('info',$info);  
            }
            $setlist = PaySetModel::getAll();
            $this->assign('setlist',$setlist);
            return view();
        }else{
            $id   =  !empty($params['id'])? $params['id']:'';
           if($id){
                $payway = PayWayModel::get(intval($id));   
            }else{
                $payway = new PayWayModel($params);
            }
            $list = $payway->validate('Pay.way')->save($params);
            if($list){
                LogModel::log(LogModel::BUSINESS_TYPE_EDIT,$payway,LogModel::BUSINESS_TYPES[LogModel::BUSINESS_TYPE_EDIT]);
                return $this->success('操作成功');
            }else{
                return $this->error($payway->getError());
            }
        }
    }

    public function third(){
        $this->view->page_header = '支付方式';
        $request = request();
        $list = PayThirdModel::getList($request);
        $this->assign('list',$list);
        return $this->fetch();
    }

    public function third_edit(){
        $request = request();
        $params = $request->param();
        if(request()->isGet()){
            if(!empty($params['id'])){
                $info =  PayThirdModel::get(['id'=>$params['id']]);
                $this->assign('info',$info);  
            }
            return view();
        }else{
            $id =  !empty($params['id'])? $params['id']:'';
            if($id){
                $paythird   =   PayThirdModel::get(intval($id));  
            }else{
                $paythird   =   new PayThirdModel($params);
            }
            $list = $paythird->validate('Pay.third')->save($params);
            if($list){
                cache('gygy_pay_third','');
                LogModel::log(LogModel::BUSINESS_TYPE_EDIT,$paythird,LogModel::BUSINESS_TYPES[LogModel::BUSINESS_TYPE_EDIT]);
                return $this->success('操作成功');
            }else{
                return $this->error($paythird->getError());
            }
        }
    }

    public function third_key_view(){
        $request    =   request();
        $params     =   $request->param();
        $id         =   $params['id'];
        $type       =   $params['type'];
        if(!$id){
            return $this->error("id参数不能为空！");
        }
        if(!in_array($type, ['pubkey','prikey'])){
            return $this->error("type参数不合法！");
        }
        $thirdpay   =   PayThirdModel::get(intval($id));
        $path       =   ROOT_PATH . 'houtai' . DS . 'uploads' . DS;        
        $file       =   $path . $thirdpay->$type;
        if(!is_file($file)){
            return $this->error("文件不存在！");
        }
        $content    =   file_get_contents($file);
        $content    =   iconv('GB2312', 'UTF-8', $content);
        $content    =   str_replace('\r\n', '<br>', $content);
        return $this->success('操作成功','',$content);
    }

    public function channel(){
      	$this->view->page_header = '支付渠道';
        $request    = request();
        $list       = PayChannelModel::getList($request);
        $setlist    = PaySetModel::getAll();
        $thirdlist  = PayThirdModel::getAll();
        $this->assign('setlist',$setlist);
        $this->assign('thirdlist',$thirdlist);
        $this->assign('list',$list);
        return $this->fetch();
    }

    public function channel_edit(){
        $request = request();
        $params = $request->param();
        if(request()->isGet()){
            if(!empty($params['id'])){
                $info =  PayChannelModel::get(['id'=>$params['id']]);
                $this->assign('info',$info);  
            }
            $setlist    = PaySetModel::getAll();
            $thirdlist  = PayThirdModel::getAll();
            $this->assign('setlist',$setlist);
            $this->assign('thirdlist',$thirdlist);
            return view();
        }else{
            $id =  !empty($params['id'])? $params['id']:'';
            if($id){
                $paychannel   =   PayChannelModel::get(intval($id));  
            }else{
                $paychannel   =   new PayChannelModel($params);
            }
            $list = $paychannel->validate('Pay.channel')->save($params);
            if($list){
                LogModel::log(LogModel::BUSINESS_TYPE_EDIT,$paychannel,LogModel::BUSINESS_TYPES[LogModel::BUSINESS_TYPE_EDIT]);
                return $this->success('操作成功');
            }else{
                return $this->error($paychannel->getError());
            }
        }
      
    }

    public function getwayBysetid(){
        $request        =   request();
        $params         =   $request->param();
        $info           =   PaySetModel::get(intval($params['id']));
        $waylist        =   $info->payWays;
        $waylist        =   $waylist->toArray();
        if($waylist){
            echo json_encode($waylist);
        }
            
    } 

    public function upload(){
        $file = request()->file('file');
        //$path = "uploads/";
        $path = ROOT_PATH . 'houtai' . DS . 'uploads';
        $info = $file->move($path);
        if($info){
            //dump($info);return;
            $type = input('itemtype');
            $ext = $info->getExtension();
            if(!$type){//上传图片
                if(!in_array($ext,['png','PNG','jpg','gif','jpeg','bmp'])){
                     echo json_encode(['code'=>0,'msg'=>'图片扩展名有误!','data'=>[]]);
                }
                $image_domain = config('image_domain');
                $url = $image_domain .DS. $info->getSaveName();
                $res = [];
                $res['url'] = $url;
                $res['html'] = '<img src="' . $url . '"  class="preview">';         
                echo json_encode(['code'=>1,'msg'=>'上传成功','data'=>$res]);
             
            }
            if(in_array($type, ['pubkey','prikey'])){//上传公私密钥
                if(!in_array($ext,['txt','pem'])){
                      echo json_encode(['code'=>0,'msg'=>'文件扩展名有误','data'=>[]]);
                }
                
                $id = input('itemid');
                if(!$id){
                      echo json_encode(['code'=>0,'msg'=>'id参数错误','data'=>[]]);
                }
                //$content = file_get_contents($filename);
                $filename = $info->getSaveName();

                $data = [$type=>$filename];
                $paythird = PayThirdModel::get(intval($id));
                $list = $paythird->save($data);
                if( $list){
                    echo json_encode(['code'=>1,'msg'=>'上传成功','data'=>[$filename]]);
                }else{
                    echo json_encode(['code'=>0,'msg'=>'上传失败','data'=>[]]);
                }
               
            }
        }else{
             echo json_encode(['code'=>0,'msg'=>'上传失败','data'=>[]]);
        }
        
      
    }
    
    
}