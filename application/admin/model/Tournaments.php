<?php

namespace app\admin\model;

use think\Model;


class Tournaments extends Model
{

    

    

    // 表名
    protected $name = 'tournaments';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'tournament_type_text',
        'buy_in_type_text',
        'tournament_status_text',
        'registration_status_text'
    ];
    

    
    public function getTournamentTypeList()
    {
        return ['mtt' => __('Mtt'), 'sng' => __('Sng'), 'satellite' => __('Satellite')];
    }

    public function getBuyInTypeList()
    {
        return ['points_only' => __('Points_only'), 'coupon_only' => __('Coupon_only'), 'points_and_coupon' => __('Points_and_coupon'), 'free' => __('Free')];
    }

    public function getTournamentStatusList()
    {
        return ['upcoming' => __('Upcoming'), 'running' => __('Running'), 'paused' => __('Paused'), 'completed' => __('Completed'), 'cancelled' => __('Cancelled')];
    }

    public function getRegistrationStatusList()
    {
        return ['closed' => __('Closed'), 'open' => __('Open'), 'late_open' => __('Late_open')];
    }


    public function getTournamentTypeTextAttr($value, $data)
    {
        $value = $value ?: ($data['tournament_type'] ?? '');
        $list = $this->getTournamentTypeList();
        return $list[$value] ?? '';
    }


    public function getBuyInTypeTextAttr($value, $data)
    {
        $value = $value ?: ($data['buy_in_type'] ?? '');
        $list = $this->getBuyInTypeList();
        return $list[$value] ?? '';
    }


    public function getTournamentStatusTextAttr($value, $data)
    {
        $value = $value ?: ($data['tournament_status'] ?? '');
        $list = $this->getTournamentStatusList();
        return $list[$value] ?? '';
    }


    public function getRegistrationStatusTextAttr($value, $data)
    {
        $value = $value ?: ($data['registration_status'] ?? '');
        $list = $this->getRegistrationStatusList();
        return $list[$value] ?? '';
    }




}
