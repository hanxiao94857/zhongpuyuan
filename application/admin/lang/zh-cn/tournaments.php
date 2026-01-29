<?php

return [
    'Id'                     => '主键ID',
    'Name'                   => '比赛名称',
    'Description'            => '比赛描述',
    'Tournament_type'        => '比赛类型：mtt多桌锦标赛,sng单桌锦标赛,satellite卫星赛',
    'Blind_template_id'      => '盲注模板ID',
    'Buy_in_points'          => '买入所需积分',
    'Buy_in_coupon_id'       => '买入所需卡券ID,为空表示不需要卡券',
    'Buy_in_type'            => '报名类型：points_only仅积分,coupon_only仅卡券,points_and_coupon积分+卡券,free免费',
    'Initial_chips'          => '初始筹码量',
    'Max_players'            => '最大玩家数,0表示无限制',
    'Min_players'            => '最小开赛玩家数',
    'Start_time'             => '比赛开始时间',
    'Late_registration_time' => '迟到报名截止时间,为空表示不允许迟到报名',
    'Estimated_duration'     => '预计持续时间(分钟)',
    'Tournament_status'      => '比赛状态',
    'Registration_status'    => '报名状态：closed关闭,open开放,late_open迟到报名开放',
    'Current_level'          => '当前盲注级别',
    'Current_players'        => '当前剩余玩家数',
    'Current_tables'         => '当前桌子数量',
    'Auto_balance'           => '是否自动平衡桌子',
    'Created_at'             => '创建时间',
    'Updated_at'             => '更新时间',

    // 比赛状态
    'Upcoming'               => '即将开始',
    'Running'                => '进行中',
    'Paused'                 => '暂停中',
    'Completed'              => '已完成',
    'Cancelled'              => '已取消',

    // 报名状态
    'Closed'                 => '关闭',
    'Open'                   => '开放',
    'Late_open'              => '迟到报名开放',

    // 页面标题
    'Tournament Detail'      => '比赛详情',
    'Detail'                 => '详情',
    '奖励配置'                => '奖励配置',

    // 奖励配置相关
    'Position'               => '排名',
    'Reward_type'            => '奖励类型',
    'Points_reward'          => '积分奖励',
    'Coupon'                 => '卡券',
    'Min_players'            => '最少玩家数',
    '仅积分'                  => '仅积分',
    '仅卡券'                  => '仅卡券',
    '积分+卡券'               => '积分+卡券',
    '无奖励'                  => '无奖励'
];
