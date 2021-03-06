<?php
namespace app\common\model;
use think\Model;

class DataTime extends Base{

    protected $table = 'gygy_data_time';
    //protected $createTime = 'created_at';
    //protected $updateTime = 'updated_at';
    //protected $autoWriteTimestamp = 'datetime';

    public function lottery()
    {        
        return $this->belongsTo('Type','type');
    }
    //----------------后台------------------
    public static function getList(){
        $params = request()->param();
        $query  =   self::order('type')->order('id desc');
        if($params['type']??null){
            $query->where('type',$params['type']);
        }
         return $query->paginate();
    }
}
