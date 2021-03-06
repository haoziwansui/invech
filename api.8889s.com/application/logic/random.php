<?php
namespace app\logic;
/*
功能：时时彩官方玩法随机一注函数
username : sky
data : 2018.1.29
 */
class random {
	 static private	$ssczx_count =[1, 3, 6, 10, 15, 21, 28, 36, 45, 55, 63, 69, 73, 75, 75, 73, 69, 63, 55, 45, 36, 28, 21, 15, 10, 6, 3, 1];
    static private  $ssc3xkd_comut =  [10, 54, 96, 126, 144, 150, 144, 126, 96, 54];
    static private  $ssc2zx_count = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 9, 8, 7, 6, 5, 4, 3, 2, 1];
    static private  $ssc2xkd_comut = [10, 18, 16, 14, 12, 10, 8, 6, 4, 2];
    static private  $ssc2zux_count = [1,1,2,2,3,3,4,4,5,4,4,3,3,2,2,1,1];
	static private  $count = 1;
	static private  $yiyweishu = 0;
	static private  $n;
	static private  $suiji = 1;
	 /*获取位数函数 
       $len:几位数
	 */
     public static function getWeishu($len){
    	 $weishu  =[16,8,4,2,1];
          $str = 0;
          for($i=0;$i<$len;$i++){
                $key =  array_rand($weishu,1);
                $str +=  $weishu[$key];
                unset($weishu[$key]);
            }
            
        return $str;
	 }

	 /*获取任选大小单双随机数
	   $len:几位数
	*/
    public static function getRandomrxdxds($len){
		$ran = [1,2,3,4,5];//用来站位
		$ranlist =[];
		$dxds =['大', '小', '单', '双'];
		for($j=0;$j<$len;$j++){
			$key =  array_rand($ran,1);
			$ranlist[]=  $ran[$key];
            unset($ran[$key]);
		}
		$str = '';
    	for($i=1;$i<=5;$i++){
    		if(in_array($i,$ranlist)){
    			$ran = rand(0,3);
    			$number = $dxds[$ran];
    			if($i == 5){
    				$str .= $number;
    			}else{
    				$str .= $number.',';
    			}
    		}else{
    			if($i == 5){
    				$str .= "-";
    			}else{
    				$str .= "-,";
    			}
    			
    		}
    	}
        return $str;
    }

 	 /*获取大小单双随机数
 	  $len:几位数
	*/
    public static function getRandomdxds($len){
       $str ='';
        $dxds = ['大', '小', '单', '双'];
       for($i=1;$i<=$len;$i++ ){
       		 $wr = rand(0,3);
       		if($i==$len){
       			 $str .= $dxds[$wr];
       		}else {
       			 $str .=  $dxds[$wr].',';
       		}
       }

        return $str;
    }

    /*获取特殊号码随机数
 	*/
   public static function getRandomtshm()
    {
        $w = ['豹子', '顺子', '对子'];
        $wr = rand(0,2);
       	$str = $w[$wr];
        return $str;
    }
    /*获取总和大小单双随机数
 	*/
     public static function getRandomzhdxds()
    {
        $w = ['总和大', '总和小','总和单','总和双'];
        $wr = rand(0, 3);
    	$str = $w[$wr];
        return $str;
    }
   	
   	 /**获取龙虎和随机数*/
    public static function getRandomlhh()
    {

		$dxds = ['龙', '虎','和'];
		$str = $dxds[mt_rand(0, 1)];
		return $str;
    }
    /*--占位函数*/

    public static function getRandomzw($number,$start,$end){
            $ran = rand($start,$end);
            $str = '';
            for($i=1;$i<=$end;$i++){
                if($i == $ran){
                    if($i == $end){
                        $str .= $number;
                    }else{
                        $str .= $number.',';
                    }
                }else{
                    if($i == $end){
                        $str .= "-";
                    }else{
                        $str .= "-,";
                    }
                    
                }
            }
            return  $str;
    }

    /* 空格分割函数*/
     public static function getRandomkgstr($number){
            $len = mb_strlen($number);
            $strlist = [];
            for($i=0;$i<$len;$i++){
                 $strlist[] = $number[$i];
            }
            $str = implode(" ", $strlist);
            return $str;

     }


     /*获取一般玩法随机选号          位数 选号个数 起止 是否可以相同 */
    public  static function getRandom($row, $start, $end, $diff)
    {
        $number = "";
        $array =[];
        $countnum = 0;
      	if ($diff == 1) {
          	 for ($i = 0; $i < $row; $i++) {
                    $number .= rand($start, $end);
                    if ($i != $row - 1) {
                        $number .= ",";
                    }
            }
        } else {
        	while ($countnum < $row) {
	            $array[] = mt_rand($start, $end);
				$array = array_flip(array_flip( $array));
				$countnum = count( $array);
       		 }
        	 $number = implode("", $array);
            
        }
		return $number;

    }

    /*
    pk10
     */
    public static function getRandomPk10($row, $start, $end, $diff){
        $number = "";
        $array =[];
        $countnum = 0;
        if ($diff == 1) {
             for ($i = 0; $i < $row; $i++) {
                    $randnum =  rand($start, $end);
                    if(strlen( $randnum)==1){
                        $number .= '0'.$randnum;
                    }else{
                        $number .= $randnum;
                    }
                    
                    if ($i != $row - 1) {
                        $number .= ",";
                    }
            }
        } else {
            while ($countnum < $row) {
                 $randnum = mt_rand($start, $end);
                if(strlen( $randnum)==1){
                        $array[]= '0'.$randnum;
                }else{
                     $array[] = $randnum;
                }
                 $array = array_flip(array_flip( $array));
                $countnum = count( $array);
             }
             $number = implode(",", $array);
            
        }
        return $number;
    }
    
         /**获取任选复式单式随机数*/
    public static function getRandomrxfds($row){
            $ran = array(1,2,3,4,5);//用来站位
            $ranlist =array();
            $dxds = array(0,1,2,3,4,5,6,7,8,9);
            for($j=0;$j<$row;$j++){
                $key =  array_rand($ran,1);
                $ranlist[]=  $ran[$key];
                unset($ran[$key]);
            }

            $str = '';
            for($i=1;$i<=5;$i++){
                if(in_array($i,$ranlist)){
                    $ran = rand(0,9);
                    $number = $dxds[$ran];
                    if($i == 5){
                        $str .= $number;
                    }else{
                        $str .= $number.',';
                    }
                }else{
                    if($i == 5){
                        $str .= "-";
                    }else{
                        $str .= "-,";
                    }
                    
                }
            }
        return $str;
    }

    //返回结果函数
    public static function jieguo(){

    	return ['actionData'=>self::$n,'actionNum'=>self::$count,'yiyweishu'=>self::$yiyweishu,'suiji'=>self::$suiji];
    }

    //五星直选
 	public static function  ssczx($selectNum,$weishu=0){
 		
         self::$n = self::getRandom($selectNum, 0, 9, 1);
        return self::jieguo();
 	}
   
   //组选120
 	public static function  ssczx120($selectNum,$weishu=0){
     	self::$n = self::getRandom( $selectNum, 0, 9,0);
        self::$yiyweishu = $weishu?self::getWeishu($weishu) : 0;
        return self::jieguo();
 	}

    //组选60
     public static function  ssczx60($selectNum,$weishu=0){
        self::$n = self::getRandom( $selectNum, 0, 9,0);
        self::$n =substr_replace(self::$n,",",1,0);
        self::$yiyweishu = $weishu?self::getWeishu($weishu) : 0;
        return self::jieguo();
    }

     //组选30
     public static function  ssczx30($selectNum,$weishu=0){
        self::$n = self::getRandom( $selectNum, 0, 9,0);
        self::$n =substr_replace(self::$n,",",2,0);
        return self::jieguo();
    }

    //特殊龙虎和
     public static function  ssczlhh($selectNum,$weishu=0){
        self::$n = self::getRandomlhh();
         return self::jieguo();
    }
     //总和大小单双
    public static function  ssczhdxds($selectNum,$weishu=0){
        self::$n = self::getRandomzhdxds();
        return self::jieguo();
    }

    //组三复式
    public static function  sscz3fs($selectNum,$weishu=0){
        self::$n = self::getRandom( $selectNum, 0, 9,0);
        self::$yiyweishu = $weishu?self::getWeishu($weishu) : 0;
        self::$count = 2;
        return self::jieguo();
    }

    //直选和值0-27
    public static function  ssczxhz27($selectNum,$weishu=0){
        self::$n = self::getRandom( $selectNum, 0,27,0);
        self::$count =self::$ssczx_count[intval(self::$n)];
        self::$yiyweishu = $weishu?self::getWeishu($weishu) : 0;
        return self::jieguo();
    }

     //直选跨度0-9
    public static function  ssczxkd($selectNum,$weishu=0){
        self::$n = self::getRandom( $selectNum, 0,9,0);
        self::$count =self::$ssc3xkd_comut[intval(self::$n)];
        return self::jieguo();
    }

       //直选3星特殊号
    public static function  ssc3xts($selectNum,$weishu=0){
        self::$n = self::getRandomtshm();
        return self::jieguo();
    }

    //直选和值0-18
    public static function  ssczxhz18($selectNum,$weishu=0){
        self::$n = self::getRandom( $selectNum, 0,18,0);
        self::$count =self::$ssc2zx_count[intval(self::$n)];
        self::$yiyweishu = $weishu?self::getWeishu($weishu) : 0;
        return self::jieguo();
    }
      //2星直选跨度0-9
    public static function  ssc2xzxkd($selectNum,$weishu=0){
        self::$n = self::getRandom( $selectNum, 0,9,0);
        self::$count =self::$ssc2xkd_comut[intval(self::$n)];
        return self::jieguo();
    }

     //2星组选和值1-17
    public static function  ssczxhz17($selectNum,$weishu=0){
        self::$n = self::getRandom( $selectNum,1,17,0);
        self::$count =self::$ssc2zux_count[intval(self::$n)-1];
        self::$yiyweishu = $weishu?self::getWeishu($weishu) : 0;
        return self::jieguo();
    }
    // 2星组选包胆
   public static function  ssczxbd($selectNum,$weishu=0){
        self::$n = self::getRandom( $selectNum,0,9,0);
        self::$count =9;
        return self::jieguo();
    }

    //定胆位
    public static function sscddw($selectNum,$weishu=0){
        self::$n = self::getRandom( $selectNum,0,9,0);
        self::$n = self::getRandomzw(self::$n,1,5);
       
        return self::jieguo();
    }

     //不定胆位 
    public static function sscbddw($selectNum,$weishu=0){
        self::$n = self::getRandom( $selectNum,0,9,0);
        self::$n = self::getRandomkgstr( self::$n);
        return self::jieguo();
    }

    //大小单双不任选
    public static function sscdxds(  $selectNum,$weishu=0){
        self::$n = self::getRandomdxds($selectNum);
        return self::jieguo();
    }
     //大小单双任选
     public static function sscrxdxds(  $selectNum,$weishu=0){
        self::$n = self::getRandomrxdxds($selectNum);
        return self::jieguo();
    }

    //任选复式单式
     public static function sscrxfsds(  $selectNum,$weishu=0){
        self::$n = self::getRandomrxfds($selectNum);
        self::$yiyweishu = $weishu?self::getWeishu($weishu) : 0;
        return self::jieguo();
    }

    //pk10
    public static function pk10zx( $selectNum,$weishu=0){
        self::$n = self::getRandomPk10($selectNum,1,10,0);
        return self::jieguo();

    }
    //  pk10定胆位
    public static function pk10dd($selectNum,$weishu=0){
        self::$n = self::getRandomPk10($selectNum,1,10,0);
         self::$n = self::getRandomzw(self::$n,1,10);
        return self::jieguo();
    }

}