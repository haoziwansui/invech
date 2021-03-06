<?php
namespace app\common\validate;
use think\Validate;
class Lottery extends Validate
{
    protected $rule = [
        'type'         => 'require',
        'code'         => 'require',
        'name'         => 'require',
        'codeList'     => 'require',
        'title'        => 'require',
        'onGetNoed'    => 'require',
        'data_ftime'   => 'require|number',
        'groupName'    => 'require',
        'qishu'        => 'require',
        'kaipan'       => 'require',
        'phase'        => 'require',
        'data'         => 'require',
        'groupId'      => 'require',
        'bonusProp'    => 'require',
        'bonusPropBase'=> 'require',
        'selectNum'    => 'require',
        'ruleFun'      => 'require',
        'betCountFun'  => 'require',
        'playedTpl'    => 'require',
        'playedId'     => 'require', 
        'mode'         => 'require',
        'pl_group_id'  => 'require',
        'pl'            => 'require',
     ];

    protected $message = [
        'type.require'  => '请选择类型',
        'code.require'  => '请选择彩票类型',
        'name.require'  => '请输入名称',
        'codeList.require'  => '请输入可选号码',
        'title.require'  => '请输入标题',
        'onGetNoed.require'  => '请期号处理事件',
        'data_ftime.require'  => '请提前封盘时间',
        'data_ftime.number'  => '请输入数字',
        'groupName.require'  => '请输入玩法组名称',
        'qishu.require'  => '请输入开奖期号',
        'kaipan.require'  => '请输入开奖时间',
        'phase.require'  => '请输入开奖期号',
        'data.require'  => '请输入开奖号码',
        'groupId.require'  => '请选择玩法组',
        'bonusProp.require'  => '请输入赔率',
        'bonusPropBase.require'  => '请输入最低赔率',
        'selectNum.require'  => '请选择每注选几个号码',
        'ruleFun.require'  => '请输入中奖规则函数',
        'betCountFun.require'  => '请输入注单数检查',
        'playedTpl.require'  => '请输入玩法页面模板',
        'playedId.require'  => '请输入玩法',
        'mode.require'      => '请输入赔率组模式',
        'pl_group_id.require' =>'请输入赔率组ID',
        'pl.require'       =>'请输入赔率',
     ];

    protected $scene = [
        'index'  => ['type','name','codeList','title','onGetNoed','data_ftime'],
        'playedgroup'  => ['type','groupName'], 
        'time'    => ['code','qishu','kaipan'], 
        'lottery_data'    => ['code','phase','data'], 
        'playedgf'=>  ['name','type','groupId','bonusProp','bonusPropBase','selectNum','ruleFun','betCountFun','playedTpl'], 
        'playedkq'=>  ['name','type','groupId','ruleFun','betCountFun','playedTpl'], 
        'plgroup' =>  ['name','playedId','mode'],
        'pl'      =>  ['pl_group_id','playedId','pl'],
    ];
}