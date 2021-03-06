<?php
namespace app\v2\controller;

use app\v2\Login;
use think\Db;
class Activity extends Login
{

    private $rewardArr = array(
        3 => array( // 档次三
            'total' => 100001,
            'item' => array(
                array(
                    'price' => '288.8',
                    'gailv' => '40'
                ),
                array(
                    'price' => '588.8',
                    'gailv' => '45'
                ),
                array(
                    'price' => '888.8',
                    'gailv' => '13'
                ),
                array(
                    'price' => '1888.8',
                    'gailv' => '2'
                ),
                array(
                    'price' => '2888.8',
                    'gailv' => '0'
                ),
                array(
                    'price' => '3888.8',
                    'gailv' => '0'
                ),
                array(
                    'price' => '5888.8',
                    'gailv' => '0'
                ),
                array(
                    'price' => '6888.8',
                    'gailv' => '0'
                ),
                array(
                    'price' => '8888.8',
                    'gailv' => '0'
                ),
                array(
                    'price' => '18888.8',
                    'gailv' => '0'
                )
            )
        ),
        2 => array( // 档次二
            'total' => 10001,
            'item' => array(
                array(
                    'price' => '58.8',
                    'gailv' => '40'
                ),
                array(
                    'price' => '88.8',
                    'gailv' => '50'
                ),
                array(
                    'price' => '188.8',
                    'gailv' => '8'
                ),
                array(
                    'price' => '288.8',
                    'gailv' => '2'
                ),
                array(
                    'price' => '388.8',
                    'gailv' => '0'
                ),
                array(
                    'price' => '588.8',
                    'gailv' => '0'
                ),
                array(
                    'price' => '688.8',
                    'gailv' => '0'
                ),
                array(
                    'price' => '888.8',
                    'gailv' => '0'
                ),
                array(
                    'price' => '1888.8',
                    'gailv' => '0'
                ),
                array(
                    'price' => '2888.8',
                    'gailv' => '0'
                )
            )
        ),
        1 => array( // 档次一
            'total' => 500,
            'item' => array(
                array(
                    'price' => '5.8',
                    'gailv' => '40'
                ),
                array(
                    'price' => '8.8',
                    'gailv' => '50'
                ),
                array(
                    'price' => '18.8',
                    'gailv' => '7'
                ),
                array(
                    'price' => '28.8',
                    'gailv' => '3'
                ),
                array(
                    'price' => '38.8',
                    'gailv' => '0'
                ),
                array(
                    'price' => '58.8',
                    'gailv' => '0'
                ),
                array(
                    'price' => '68.8',
                    'gailv' => '0'
                ),
                array(
                    'price' => '88.8',
                    'gailv' => '0'
                ),
                array(
                    'price' => '188.8',
                    'gailv' => '0'
                ),
                array(
                    'price' => '288.8',
                    'gailv' => '0'
                )
            )
        )
    );

    public function qiandao()
    {
        date_default_timezone_set('PRC');
        $uid = Session('uid');
        $param = $this->request->param();
        if(1/*$this->request->isAjax() && $param['ajax']*/){
            Db::startTrans();
            $sql = "select * from k_user where uid = {$uid} for update";
            $userinfo = Db::table('k_user')->where('uid','eq',$uid)->find();
            
            if(empty($userinfo)){
                Db::rollback();
                echo '尊敬的玩家,您没有登陆,不能免费签到领红包!';
                exit;    
            }elseif($userinfo['qiandao'] == 1){
                Db::rollback();
                echo '您今天已经进行了签到，请明天登录会员号在进行签到,谢谢您的支持。';
		        exit;
            }else{
                $usermoney = $userinfo['money'];
                $queryflag = Session('queryflag');
                if($queryflag){
                    Db::rollback();
                    echo "请勿频繁操作!";exit();
                }else{
                    Session('queryflag','1');
                }
                $cishu = $userinfo['cishu'];
                $userID = $uid;
                $username = $userinfo["username"];
                $start_Time = date('Y-m-d',time()) . " 00:00:00";
                $end_Time = date('Y-m-d',time()) . " 23:59:59";
                
                
                $start_Time_america = date('Y-m-d 00:00:00',strtotime($start_Time)- 12*3600 );
                $end_Time_america = date('Y-m-d 23:59:59',strtotime($end_Time)- 12*3600) ;
                $liushui_today = 0;
                $sql_temp = "SELECT sum(`bet_money`) as total_bet_money FROM k_bet_cg_group where gid>0 and uid='$userID' and bet_time>='$start_Time' and bet_time<='$end_Time'";
                $where = array(
                    'gid'       => array('>',0),
                    'uid'       => array('=',$userID)
                );
                
                $liushui_today += Db::table('k_bet_cg_group')->where($where)->whereTime('bet_time','today')->sum('bet_money');
                //闯关
                $sql_temp = "SELECT sum(`bet_money`) as total_bet_money from k_bet where lose_ok=1 and uid='$userID' and bet_time>='$start_Time' and bet_time<='$end_Time'";
                unset($where);
                $where = array(
                    'lose_ok'   => array('=',1),
                    'uid'       => array('=',$userID),
                );
                //体育投注
                $liushui_today += Db::table('k_bet') -> where($where) ->whereTime('bet_time','today')->sum('bet_money');
                unset($where);
                $sql_temp = "SELECT sum(`money`) as total_bet_money from c_bet_lt where money>0 and uid='$userID' and addtime>='$start_Time_america' and addtime<='$end_Time_america'";
                $where = array(
                    'uid'       => array('=',$userID),
                    'money'     => array('>',0),
                    
                );
                $liushui_today += Db::table('c_bet_lt')->where($where)->whereTime('addtime','today')->sum('money');
                
                $sql_temp = "SELECT sum(`money`) as total_bet_money from c_bet where money>0 and uid='$userID' and addtime>='$start_Time_america' and addtime<='$end_Time_america'";
                $liushui_today += Db::table('c_bet')->where($where)->whereTime('addtime','today')->sum('money');
                
                $sql_temp = "SELECT sum(`validBetAmount`) as total_bet_money from ag_gameresult where username='$username' and betTime>='$start_Time_america' and betTime<='$end_Time_america'";
                
                unset($where);
                $liushui_today += Db::table('ag_gameresult')
                    ->where('username','eq',$username)
                    ->where('betTime','between',[$start_Time_america,$end_Time_america])
                    ->sum('validBetAmount');
                $liushui_today += Db::table('ag_htresult')
                ->where('playerName','eq',$username)
                ->where('SceneStartTime','between',[$start_Time_america,$end_Time_america])
                ->sum('Cost');
                
                $sql_temp = "SELECT sum(`BetAmount`) as total_bet_money from bbin_gameresult where username='$username' and WagersDate>='$start_Time_america' and WagersDate<='$end_Time_america'";
                unset($where);
                $liushui_today += Db::table('bbin_gameresult')
                ->where('username','eq',$username)
                ->where('WagersDate','between',[$start_Time_america,$end_Time_america])
                    ->sum('BetAmount');
                
                //mg
                //og
                //pt
                //sb
                
                $res = Db::table('allow_qiandao_user')->where('user_id','=',$uid)->find();
                if($res){
                    $arr['flag'] = 1;
                    $arr['add'] = '8.8';
                }else{
                    $arr = $this->jiance($liushui_today, $cishu);
                }
                if($arr['flag'] == 1){
                    $add = $arr['add'];
                    unset($data);
                    $data['cishu'] = ['exp','cishu+1'];
                    $data['qiandao']    = 1;
                    $data['money'] = ['exp','money+'.$add];
                    $cishu = $cishu + 1;
                    Db::table('k_user')->where('uid','eq',$uid)->update($data);
                    if($cishu >=30 ){
                        Db::table('k_user')->where('uid','eq',$uid)->update(['cishu'=>0]);
                    }
                    $msginfo = '尊敬的用户，第'.$cishu.'天签到奖励'.$add.'元已经添加到您的账户中,很遗憾本次签到没有获取8888元暴击红包,加油！';
                    $msg = new \app\model\msg();
                    $msg->msg_add($uid, '系统', '签到奖励', $msginfo);
                    $about =  '签到第'.$cishu.'天获得[管理员结算]';
                    unset ($data);
                    $order = "ZSQD".strtoupper(uniqid());
                    $data['m_order'] = $order;
                    $data['uid']        = $userID;
                    $data['m_value']    = $add;
                    $data['status']     = 1;
                    $data['m_make_time'] = date('Y-m-d H:i:s');
                    $data['about']      = $about;
                    $data['balance'] = $usermoney +$add;
                    $data['assets'] = $usermoney;
                    $data['type']       = 200;
                    Db::table('k_money')->insert($data);
                    echo '恭喜您签到成功，获得了'.$add.'元的奖励,您已经连续签到'.$cishu.'天了,很遗憾本次签到没有获取8888元暴击红包,加油呦！';
                }elseif($arr['flag'] == 2){
                    echo '您目前已连续签到'.$cishu.'天，本次签到需要'.$arr['money'].'投注流水，您目前还没有达到签到要求，请稍后在来！';
                }else{
                    echo '您目前连续签到'.$cishu.'天';
                }
                Session('queryflag',null);
                Db::commit();
            }
        }
    }

    public function zhuanpan()
    {
        date_default_timezone_set('PRC');
        $param = $this->request->param();
        $uid = Session('uid');
        $userinfo = Db::table('k_user')->where('uid', 'eq', $uid)->find();
        if (isset($param['ajax'])) {
            //var_dump($userinfo['lunpan'] == 1);
            //var_dump(empty($userinfo['lunpan_price']));
            if ($userinfo['lunpan'] == 1) {
                exit('fail');
            }
            if (empty($userinfo['lunpan_price'])) {
                exit('fail');
            }
            $price = 0;
            if (preg_match("/^(\d+)_([\d\.]+)$/", $userinfo['lunpan_price'], $array)) {
                $price = (float) $array[2];
            } else {
                echo 'fail';
                exit();
            }
            $order = "ZSLP".strtoupper(uniqid());
            $data['m_order'] = $order;
            $data['uid'] = $uid;
            $data['m_value'] = $price;
            $data['status'] = 1;
            $data['m_make_time'] = date('Y-m-d H:i:s');
            $data['about'] = '轮盘奖励[管理员结算]';
            $data['type'] = 200;
            Db::table('k_money')->insert($data);
            unset($data);
            $data = array();
            $data['lunpan'] = 1;
            $data['money'] = array(
                'exp',
                "money+$price"
            );
            Db::table('k_user')->where('uid', 'eq', $uid)->update($data);
            $info = '尊敬的用户，轮盘奖励' . $price . '元已经添加到您的账户中,订单号:'.$order.'!';
            $title = '轮盘奖励';
            $from = '系统';
            $msgModel = new \app\model\msg();
            $msgModel->msg_add($uid, $from, $title, $info);
            
            echo 'ok';
            exit();
        } else {
            $start_Time = date('Y-m-d', time()) . " 00:00:00";
            $end_Time = date('Y-m-d', time()) . " 23:59:59";
            $sql = "select max(case when type=1 or type=100 then m_value else 0 end) as ck from k_money where uid=" . $uid . " and status=1 and `about` not like '%管理员结算%' and m_make_time>='$start_Time' and m_make_time<='$end_Time'";
            $rs_m = Db::query($sql)[0];
            //$query = $mysqli->query($sql);
            //$rs_m = $query->fetch_array();
            
            $sql_h = "select max(money) as hk from huikuan where uid=" . $uid . " and status=1 and adddate>='$start_Time' and adddate<='$end_Time'";
            $rs_h = Db::query($sql_h)[0];
            //$query_h = $mysqli->query($sql_h);
            //$rs_h = $query_h->fetch_array();
            
            if ($rs_m['ck'] > $rs_h['hk']) {
                $total = round($rs_m['ck'], 2);
            } else {
                $total = round($rs_h['hk'], 2);
            }
            
            // $total = round($rs_m['ck'] + $rs_h['hk'],2);
            
            $step = $this->get_step_by_total($total);
            $temp = $this->get_price($step);
            $price = $temp['price'];
            $index = $temp['index'];
            
            if (empty($index)) {
                $index = 0;
            }
            
            // 已经领取过了
            if ($userinfo['lunpan'] == 1) {
                $price = - 1;
            } else {
                $user_lunpan_price = $userinfo['lunpan_price'];
                
                // 如果达到了新的充值等级， 而且没有领取过的情况下， 需要更新lunpan_price字段
                if (preg_match("/^(\d+)_([\d\.]+)$/", $user_lunpan_price, $array)) {
                    if ($array[1] < $step) {
                        $lunpan_price = $step . '_' . $price;
                        //$sql = "update k_user set lunpan_price='$lunpan_price' where uid='$uid'";
                        Db::table('k_user')->where('uid','eq',$uid)->update(array('lunpan_price'=>$lunpan_price));
                        //$mysqli->query($sql);
                    } else {
                        // 没有达到新的充值等级， 就固定不变
                        $price = $array[2];
                    }
                } else {
                    $lunpan_price = $step . '_' . $price;
                    Db::table('k_user') -> where('uid','eq',$uid) ->update(array('lunpan_price'=>$lunpan_price));
                    //$sql = "update k_user set lunpan_price='$lunpan_price' where uid='$uid'";
                    //$mysqli->query($sql);
                }
            }
            if (empty($step)) {
            	$step_index = 1;
            } else {
            	$step_index = $step;
            }
            
            $temp1 = array();
            $temp2 = array();
            foreach ($this->rewardArr[$step_index]['item'] as $key => $sub_item) {
            	$temp1[] = '"'.$sub_item['price'].'"';
            	
            	if ($key%2 == 0) {
            		$temp2[] = '"#FF8584"';
            	} else {
            		$temp2[] = '"#FFEE7B"';
            	}
            }
            $tmp1 = implode(',',$temp1);
            $tmp2 = implode(',',$temp2);
            $this->assign('step',$step);
            $this->assign('total',$total);
            $this->assign('tmp1',$tmp1);
            $this->assign('tmp2',$tmp2);
            $this->assign('index',$index);
            $this->assign('price',$price);
            return $this->fetch();
        }
        
    }

    private function get_step_by_total($total)
    {
        $rewardArr = $this->rewardArr;
        $step = 0;
        foreach ($rewardArr as $key => $value) {
            if ($total >= $value['total']) {
                return $key;
            }
        }
        return $step;
    }

    private function get_price($step)
    { // 概率获取奖励
        $rewardArr = $this->rewardArr;
        $gailv = mt_rand(1, 100);
        
        $current_gailv_total = 0;
        if (isset($rewardArr[$step]['item']) && ! empty($rewardArr[$step]['item'])) {
            foreach ($rewardArr[$step]['item'] as $key => $value) {
                $current_gailv_total += $value['gailv'];
                if ($current_gailv_total >= $gailv) {
                    return array(
                        'price' => $value['price'],
                        'index' => ($key + 1)
                    );
                }
            }
        }
        return 0;
    }
    
    /*
     如何让会员进行签到
     首先点击签到按钮 检测该会员qiandao是否为1 如为1提示下方2
     次检测会员签到cishu，检测对应cishu的流水要求以及对应的奖励金额，如果流水达到增加对应奖励金额提示下方1 如不达到提示下方3
    
     1.恭喜您签到成功，获得了X元的奖励,您已经连续签到X天了,加油呦！
    
     2.您今天已经进行了签到，请明天登录会员号在进行签到,谢谢您的支持。
    
     3.您目前已连续签到X天，本次签到需要X投注流水，您目前还没有达到签到要求，请稍后在来！
     */
   private function jiance($money,$cishu) {
        $arr = array(
            array('days'=>0,'money'=>100,'add'=>1.8),
            array('days'=>1,'money'=>300,'add'=>8.8),
            array('days'=>2,'money'=>500,'add'=>8.8),
            array('days'=>3,'money'=>800,'add'=>8.8),
            array('days'=>4,'money'=>1000,'add'=>8.8),
            array('days'=>5,'money'=>1000,'add'=>18),
            array('days'=>6,'money'=>1000,'add'=>18),
            array('days'=>7,'money'=>2000,'add'=>18),
            array('days'=>8,'money'=>2000,'add'=>18),
            array('days'=>9,'money'=>2000,'add'=>18),
            array('days'=>10,'money'=>2000,'add'=>28),
            array('days'=>11,'money'=>3000,'add'=>28),
            array('days'=>12,'money'=>3000,'add'=>28),
            array('days'=>13,'money'=>3000,'add'=>28),
            array('days'=>14,'money'=>3000,'add'=>28),
            array('days'=>15,'money'=>3000,'add'=>38),
            array('days'=>16,'money'=>4000,'add'=>38),
            array('days'=>17,'money'=>4000,'add'=>38),
            array('days'=>18,'money'=>4000,'add'=>38),
            array('days'=>19,'money'=>4000,'add'=>38),
            array('days'=>20,'money'=>5000,'add'=>58),
            array('days'=>21,'money'=>5000,'add'=>58),
            array('days'=>22,'money'=>5000,'add'=>58),
            array('days'=>23,'money'=>5000,'add'=>88),
            array('days'=>24,'money'=>5000,'add'=>88),
            array('days'=>25,'money'=>6000,'add'=>88),
            array('days'=>26,'money'=>7000,'add'=>98),
            array('days'=>27,'money'=>8000,'add'=>108),
            array('days'=>28,'money'=>10000,'add'=>188),
            array('days'=>29,'money'=>18888,'add'=>288),
        );
    
        foreach ($arr as $key => $value) {
            if ($value['days'] == $cishu) {
                if ($money >= $value['money']) {
                    return array('flag'=>'1','add'=>$value['add']);
                } else {
                    return array('flag'=>'2','money'=>$value['money']);
                }
            }
        }
        return array('flag'=>'3');
    }
    
}
