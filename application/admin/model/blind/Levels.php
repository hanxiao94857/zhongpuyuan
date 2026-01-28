<?php

namespace app\admin\model\blind;

use think\Model;


class Levels extends Model
{



    // 表名
    protected $name = 'blind_levels';

    // 关闭自动写入时间戳字段，因为数据库已经通过触发器处理
    protected $autoWriteTimestamp = false;

    // 类型转换
    protected $type = [
        'small_blind' => 'integer',
        'big_blind' => 'integer',
        'ante' => 'integer',
        'break_after' => 'integer',
        'break_duration' => 'integer',
    ];

    // 追加属性
    protected $append = [
        'small_blind_text',
        'big_blind_text',
        'ante_text'
    ];

    public function getSmallBlindTextAttr($value, $data)
    {
        $value = $value ? $value : $data['small_blind'];
        return $value . ' BB';
    }

    public function getBigBlindTextAttr($value, $data)
    {
        $value = $value ? $value : $data['big_blind'];
        return $value . ' BB';
    }

    public function getAnteTextAttr($value, $data)
    {
        $value = $value ? $value : $data['ante'];
        return $value > 0 ? $value . ' BB' : __('No Ante');
    }




}
