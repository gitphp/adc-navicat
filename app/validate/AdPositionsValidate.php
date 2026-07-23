<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

/**
 * 广告位主表验证器
 */
class AdPositionsValidate extends Validate
{
    protected $rule = [
        'ad_title' => 'require|max:128',
        'subtitle' => 'max:255',
        'cover_url' => 'max:512',
        'cover_mobile' => 'max:512',
        'cover_thumb' => 'max:512',
        'video_url' => 'max:512',
        'link_type' => 'integer|in:1,2,3,4',
        'link_url' => 'max:512',
        'app_id' => 'max:128',
        'app_path' => 'max:255',
        'position_code' => 'require|max:64',
        'platform' => 'integer|in:1,2,3,4',
        'device_type' => 'integer|in:1,2,3,4',
        'target_user_type' => 'integer|in:0,1,2,3,4',
        'start_time' => 'require|datetime',
        'end_time' => 'require|datetime',
        'show_time_type' => 'integer|in:0,1',
        'sort' => 'integer|min:0',
        'display_frequency' => 'integer|in:1,2,3',
        'daily_impression_limit' => 'integer|min:0',
        'daily_click_limit' => 'integer|min:0',
        'cost_type' => 'integer|in:1,2,3,4',
        'status' => 'integer|in:1,2,3,4,5,6,7,8',
        'audit_status' => 'integer|in:0,1,2,3',
    ];

    protected $message = [
        'ad_title.require' => '广告标题不能为空',
        'ad_title.max' => '广告标题不能超过128个字符',
        'subtitle.max' => '广告副标题不能超过255个字符',
        'cover_url.max' => '封面图URL不能超过512个字符',
        'cover_mobile.max' => '移动端封面图URL不能超过512个字符',
        'cover_thumb.max' => '缩略图URL不能超过512个字符',
        'video_url.max' => '视频URL不能超过512个字符',
        'link_type.integer' => '跳转类型必须为整数',
        'link_type.in' => '跳转类型只能是1、2、3或4',
        'link_url.max' => '跳转链接不能超过512个字符',
        'app_id.max' => '小程序AppId不能超过128个字符',
        'app_path.max' => '小程序路径不能超过255个字符',
        'position_code.require' => '广告位编码不能为空',
        'position_code.max' => '广告位编码不能超过64个字符',
        'platform.integer' => '投放平台必须为整数',
        'platform.in' => '投放平台只能是1、2、3或4',
        'device_type.integer' => '设备类型必须为整数',
        'device_type.in' => '设备类型只能是1、2、3或4',
        'target_user_type.integer' => '用户定向必须为整数',
        'target_user_type.in' => '用户定向只能是0、1、2、3或4',
        'start_time.require' => '投放开始时间不能为空',
        'start_time.datetime' => '投放开始时间格式不正确',
        'end_time.require' => '投放结束时间不能为空',
        'end_time.datetime' => '投放结束时间格式不正确',
        'show_time_type.integer' => '展示时间类型必须为整数',
        'show_time_type.in' => '展示时间类型只能是0或1',
        'sort.integer' => '排序权重必须为整数',
        'sort.min' => '排序权重不能为负数',
        'display_frequency.integer' => '展示频率必须为整数',
        'display_frequency.in' => '展示频率只能是1、2或3',
        'daily_impression_limit.integer' => '每日展示限制必须为整数',
        'daily_impression_limit.min' => '每日展示限制不能为负数',
        'daily_click_limit.integer' => '每日点击限制必须为整数',
        'daily_click_limit.min' => '每日点击限制不能为负数',
        'cost_type.integer' => '计费方式必须为整数',
        'cost_type.in' => '计费方式只能是1、2、3或4',
        'status.integer' => '状态必须为整数',
        'status.in' => '状态值不在允许范围内',
        'audit_status.integer' => '审核状态必须为整数',
        'audit_status.in' => '审核状态只能是0、1、2或3',
    ];

    // 添加场景
    protected $scene = [
        'add' => ['ad_title', 'position_code', 'start_time', 'end_time', 'link_type', 'platform', 'device_type', 'target_user_type', 'show_time_type', 'sort', 'display_frequency', 'cost_type'],
        'edit' => ['ad_title', 'position_code', 'start_time', 'end_time', 'link_type', 'platform', 'device_type', 'target_user_type', 'show_time_type', 'sort', 'display_frequency', 'cost_type', 'status'],
    ];
}