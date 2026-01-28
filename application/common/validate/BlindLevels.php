<?php

namespace app\common\validate;

use think\Validate;

class BlindLevels extends Validate
{
    /**
     * 验证规则
     */
    protected $rule = [
        'template_id'   => 'require|integer|gt:0',
        'level_number'  => 'require|integer|gt:0',
        'small_blind'   => 'require|integer|egt:0',
        'big_blind'     => 'require|integer|egt:0',
        'ante'          => 'integer|egt:0',
        'duration'      => 'require|integer|gt:0',
        'break_after'   => 'in:0,1',
        'break_duration' => 'integer|egt:0',
    ];
    /**
     * 提示消息
     */
    protected $message = [
        'template_id.require'   => '请选择模板',
        'template_id.integer'   => '模板ID必须是整数',
        'template_id.gt'        => '请选择有效的模板',
        'level_number.require'  => '请输入级别编号',
        'level_number.integer'  => '级别编号必须是整数',
        'level_number.gt'       => '级别编号必须大于0',
        'small_blind.require'   => '请输入小盲注',
        'small_blind.integer'   => '小盲注必须是整数',
        'small_blind.egt'       => '小盲注不能为负数',
        'big_blind.require'     => '请输入大盲注',
        'big_blind.integer'     => '大盲注必须是整数',
        'big_blind.egt'         => '大盲注不能为负数',
        'ante.integer'          => '前注必须是整数',
        'ante.egt'              => '前注不能为负数',
        'duration.require'      => '请输入持续时间',
        'duration.integer'      => '持续时间必须是整数',
        'duration.gt'           => '持续时间必须大于0',
        'break_after.in'        => '休息设置只能是0或1',
        'break_duration.integer' => '休息时间必须是整数',
        'break_duration.egt'    => '休息时间不能为负数',
    ];
    /**
     * 验证场景
     */
    protected $scene = [
        'add'  => ['template_id', 'level_number', 'small_blind', 'big_blind', 'ante', 'duration', 'break_after', 'break_duration'],
        'edit' => ['template_id', 'level_number', 'small_blind', 'big_blind', 'ante', 'duration', 'break_after', 'break_duration'],
    ];

}
