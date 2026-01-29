<?php

return [
    'Id'               => '主键ID',
    'Name'             => '卡券名称',
    'Description'      => '卡券描述',
    'Coupon_type'      => '卡券类型：tournament_ticket比赛门票,gift礼品券,event活动券',
    'Tournament_id'    => '关联比赛ID,为空表示通用门票',
    'Ticket_type'      => '门票类型：single单次使用,multiple多次使用,unlimited无限使用',
    'Usage_limit'      => '每人使用次数限制',
    'Total_limit'      => '总发放数量限制',
    'Used_count'       => '已使用次数',
    'Valid_type'       => '有效期类型：fixed固定时间,relative相对时间',
    'Valid_start_time' => '固定开始时间',
    'Valid_end_time'   => '固定结束时间',
    'Valid_days'       => '相对有效天数',
    'Status'           => '状态：active激活,inactive停用,expired过期',
    'Sort_order'       => '排序',
    'Image'            => '卡券图片',
    'Notes'            => '备注',
    'Created_at'       => '创建时间',
    'Updated_at'       => '更新时间'
];
