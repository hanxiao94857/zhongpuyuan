<?php

namespace app\admin\model;

use think\Model;


class Coupons extends Model
{

    

    

    // 表名
    protected $name = 'coupons';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'coupon_type_text',
        'ticket_type_text',
        'valid_type_text',
        'status_text'
    ];
    

    
    public function getCouponTypeList()
    {
        return ['tournament_ticket' => __('Tournament_ticket'), 'gift' => __('Gift'), 'event' => __('Event')];
    }

    public function getTicketTypeList()
    {
        return ['single' => __('Single'), 'multiple' => __('Multiple'), 'unlimited' => __('Unlimited')];
    }

    public function getValidTypeList()
    {
        return ['fixed' => __('Fixed'), 'relative' => __('Relative')];
    }

    public function getStatusList()
    {
        return ['active' => __('Active'), 'inactive' => __('Inactive'), 'expired' => __('Expired')];
    }


    public function getCouponTypeTextAttr($value, $data)
    {
        $value = $value ?: ($data['coupon_type'] ?? '');
        $list = $this->getCouponTypeList();
        return $list[$value] ?? '';
    }


    public function getTicketTypeTextAttr($value, $data)
    {
        $value = $value ?: ($data['ticket_type'] ?? '');
        $list = $this->getTicketTypeList();
        return $list[$value] ?? '';
    }


    public function getValidTypeTextAttr($value, $data)
    {
        $value = $value ?: ($data['valid_type'] ?? '');
        $list = $this->getValidTypeList();
        return $list[$value] ?? '';
    }


    public function getStatusTextAttr($value, $data)
    {
        $value = $value ?: ($data['status'] ?? '');
        $list = $this->getStatusList();
        return $list[$value] ?? '';
    }




}
