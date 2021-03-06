<?php
namespace app\pay;
use app\pay\ymfpay\MbPay as MbPay;

class ymfpay extends basepay
{
    public function pay()
    {
        $arr = $this->params;
        $data = array();
        
        $data["apiName"] = $this->get_api_name();//WAP方式：“WAP_PAY_B2C”（手机支付） WEB方式：“WEB_PAY_B2C”（pc浏览器
        $data["apiVersion"] = '1.0.0.1'; //版本号
        $data["platformID"] = $arr['pid'];//商户ID
        $data["merchNo"] = $arr['pid'];//商户账号
        $data["orderNo"] = $arr['order_id'];   //订单号
        $data["tradeDate"] = date("Ymd");//交易日期
        $data["amt"] = $arr['order_money']; //交易金额
        $data["merchUrl"] = $arr['callbackurl'];  //回调地址
        $data["merchParam"] = "虚拟商品";  //参数
        $data["tradeSummary"] = 'pay';  //交易说明
        $data["customerIP"] = $this->get_client_ip();  //客户端IP地址
        
        $data["choosePayType"] = $arr['tcode'];//支付类型
        $cMbPay = new MbPay($arr['pkey'], $arr['purl']);
        // 准备待签名数据
        $str_to_sign = $cMbPay->prepareSign($data);
        // 数据签名
        $sign = $cMbPay->sign($str_to_sign);
        $data['signMsg'] = $sign;
        
        //银联支付需要补充银行类型对应的编码
        if ($data['choosePayType'] == 1 && $arr['pay_type'] != 'BANKWAP') {
            $data['bankCode'] = $this->getBankCode();
        }
        // 提交请求

        $html = $this->submitFormMethod($data, $arr['purl']);
        $this->dd($html);
    }
    
    
    public function check_sign()
    {
        $arr = $this->params;//

        $content="";
        foreach ($_REQUEST as $k => $v) {
            $content .= "<input type='text' name='$k' value='$v' />";
        }
        file_put_contents(dirname(__FILE__) . '/ymfform.html', $content . "\r\n", FILE_APPEND);

        $data = array();
        // 请求数据赋值
     
        $data['apiName'] = $_REQUEST["apiName"];
        // 通知时间
        $data['notifyTime'] = $_REQUEST["notifyTime"];
        // 支付金额(单位元，显示用)
        $data['tradeAmt'] = $_REQUEST["tradeAmt"];
        // 商户号
        $data['merchNo'] = $_REQUEST["merchNo"];
        // 商户参数，支付平台返回商户上传的参数，可以为空
        $data['merchParam'] = $_REQUEST["merchParam"];
        // 商户订单号
        $data['orderNo'] = $_REQUEST["orderNo"];
        // 商户订单日期
        $data['tradeDate'] = $_REQUEST["tradeDate"];
        // 支付系统订单号
        $data['accNo'] = $_REQUEST["accNo"];
        // 支付系统账务日期
        $data['accDate'] = $_REQUEST["accDate"];
        // 订单状态，0-未支付，1-支付成功，2-失败，4-部分退款，5-退款，9-退款处理中
        $data['orderStatus'] = $_REQUEST["orderStatus"];
        // 签名数据
        $data['signMsg'] = $_REQUEST["signMsg"];
        
        // 初始化
        $cMbPay = new MbPay($arr['pkey'], $arr['purl']);
        // 准备准备验签数据
        $str_to_sign = $cMbPay->prepareSign($data);
        // 验证签名
        $resultVerify = $cMbPay->verify($str_to_sign, $data['signMsg']);
        return $resultVerify;
        
    }
    
    //SUCCESS 交易成功,FAILED 交易失败
    public function pay_ok()
    {
        return "1" == $_REQUEST["orderStatus"];
    }
    
    public function transid()
    {
        return $_REQUEST['orderNo'];//系统订单号
    }
    
    public function orderid()
    {
        return $_REQUEST['orderNo'];
    }
    
    
    
    
    
    
    
    
    /*
     *common
     *
     */
    
    
    /**
     * 创建签名
     * @param $data
     * @param $key
     */
    public function create_sign()
    {
    }
    
    /**
     * 返回本地请求地址
     */
    public function getLocalRequestUrl()
    {
        $pay_url = '';
        //记录支付处理地址
        if (!$_SERVER['HTTP_REFERER']) {
            $pay_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];
        } else {
            $pay_url = $_SERVER['HTTP_REFERER'];
        }
        return $pay_url;
    }
    
    /*
     * 获取银行简码
     */
    public function getBankCode()
    {
        $bank = $_REQUEST['bank'];
        $bankInfo['962'] = 'CNCB'; //中信银行
        $bankInfo['963'] = 'BOC'; //中国银行
        $bankInfo['964'] = 'ABC'; //中国农业银行
        $bankInfo['965'] = 'CCB'; //中国建设银行
        $bankInfo['967'] = 'ICBC'; //中国工商银行
        $bankInfo['968'] = 'CZSB'; //浙商银行
        $bankInfo['970'] = 'CMB'; //招商银行
        $bankInfo['971'] = 'PSBC'; //中国邮政储蓄银行
        $bankInfo['972'] = 'CIB'; //兴业银行
        $bankInfo['975'] = 'BOSH'; //上海银行
        $bankInfo['977'] = 'SPDB'; //浦发银行
        $bankInfo['978'] = 'PAB'; //平安银行
        $bankInfo['980'] = 'CMBC'; //中国民生银行
        $bankInfo['981'] = 'COMM'; //交通银行
        $bankInfo['982'] = 'HXB'; //华夏银行
        $bankInfo['985'] = 'GDB'; //广发银行
        $bankInfo['986'] = 'CEB'; //中国光大银行
        $bankInfo['988'] = 'CBHB'; //渤海银行
        $bankInfo['989'] = 'BOBJ'; //北京银行
        
        if (!isset($bankInfo[$bank])) {
            exit("<script>alert('很抱歉,暂不支持该行...');history.go(-1);</script>");
        }
        return $bankInfo[$bank];
    }
    
    /**
     * POST访问
     * @param $url
     * @param $data
     * @return mixed|string
     */
    public function submitPostData($url, $data)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (compatible; MSIE 5.01; Windows NT 5.0)');
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $tmpInfo = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        return $tmpInfo;
    }
    
    /**
     * 表单方式
     */
    public function submitFormMethod($params, $gateway, $method = 'post', $charset = 'utf-8')
    {
        header("Cache-Control: no-cache");
        header("Pragma: no-cache");
        header("Content-type:text/html;charset={$charset}");
        $sHtml = "<form id='paysubmit' name='paysubmit' action='{$gateway}' method='{$method}'>";
        
        foreach ($params as $k => $v) {
            $sHtml .= "<input type=\"hidden\" name=\"{$k}\" value=\"{$v}\" />\n";
        }
        
        $sHtml = $sHtml . "</form>正在提交支付数据 ...";
        
        $sHtml = $sHtml . "<script>document.forms['paysubmit'].submit();</script>";
        
        return $sHtml;
    }
    
    //WAP方式：“WAP_PAY_B2C”（手机支付） WEB方式：“WEB_PAY_B2C”（pc浏览器
    public function get_api_name()
    {
        $apiName = '';
        if ($this->isMobile()) {
            $apiName = 'WAP_PAY_B2C';
        } else {
            $apiName = 'WEB_PAY_B2C';
        }
        return $apiName;
    }
    
    
    public function isMobile()
    {
        // 如果有HTTP_X_WAP_PROFILE则一定是移动设备
        if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
            return true;
        }
        // 如果via信息含有wap则一定是移动设备,部分服务商会屏蔽该信息
        if (isset($_SERVER['HTTP_VIA'])) {
            // 找不到为flase,否则为true
            return stristr($_SERVER['HTTP_VIA'], "wap") ? true : false;
        }
        // 脑残法，判断手机发送的客户端标志,兼容性有待提高。其中'MicroMessenger'是电脑微信
        if (isset($_SERVER['HTTP_USER_AGENT'])) {
            $clientkeywords = array('nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile', 'MicroMessenger');
            // 从HTTP_USER_AGENT中查找手机浏览器的关键字
            if (preg_match("/(" . implode('|', $clientkeywords) . ")/i", strtolower($_SERVER['HTTP_USER_AGENT']))) {
                return true;
            }
        }
        // 协议法，因为有可能不准确，放到最后判断
        if (isset ($_SERVER['HTTP_ACCEPT'])) {
            // 如果只支持wml并且不支持html那一定是移动设备
            // 如果支持wml和html但是wml在html之前则是移动设备
            if ((strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false) && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html')))) {
                return true;
            };
        }
        return false;
    }
    
    public function get_client_ip($type = 0)
    {
        $type = $type ? 1 : 0;
        static $ip = NULL;
        if ($ip !== NULL)
            return $ip[$type];
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos = array_search('unknown', $arr);
                if (false !== $pos)
                    unset($arr[$pos]);
                    $ip = trim($arr[0]);
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
            // IP地址合法验证
            $long = sprintf("%u", ip2long($ip));
            $ip = $long ? array(
                $ip,
                $long
            ) : array(
                '0.0.0.0',
                0
            );
            return $ip[$type];
    }
    
    public function dd($arr)
    {
        echo '<pre>';
        print_r($arr);
        die;
    }
}