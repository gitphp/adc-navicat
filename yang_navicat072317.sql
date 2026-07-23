/*
 Navicat Premium Dump SQL

 Source Server         : localhost_3306
 Source Server Type    : MySQL
 Source Server Version : 80046 (8.0.46)
 Source Host           : localhost:3306
 Source Schema         : yang_navicat

 Target Server Type    : MySQL
 Target Server Version : 80046 (8.0.46)
 File Encoding         : 65001

 Date: 23/07/2026 20:47:15
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for ad_click_logs
-- ----------------------------
DROP TABLE IF EXISTS `ad_click_logs`;
CREATE TABLE `ad_click_logs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `ad_position_id` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '广告ID',
  `user_id` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户ID（未登录为空）',
  `session_id` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '会话ID',
  `device_id` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '设备ID',
  `click_ip` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'IP地址',
  `user_agent` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'User-Agent',
  `referer` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '来源页面',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '点击时间',
  `is_valid` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否有效点击：0=无效，1=有效',
  `event_type` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '事件类型：1=展示，2=点击，3=转化',
  `conversion_value` decimal(12, 2) NULL DEFAULT NULL COMMENT '转化价值（如：订单金额）',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_ad_position_id`(`ad_position_id` ASC) USING BTREE,
  INDEX `idx_user_id`(`user_id` ASC) USING BTREE,
  INDEX `idx_click_time`(`created_at` ASC) USING BTREE,
  INDEX `idx_event_type`(`event_type` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 920733863155423246 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '广告点击日志表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ad_click_logs
-- ----------------------------

-- ----------------------------
-- Table structure for ad_positions
-- ----------------------------
DROP TABLE IF EXISTS `ad_positions`;
CREATE TABLE `ad_positions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `ad_title` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '广告标题',
  `subtitle` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '广告副标题/描述',
  `cover_url` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '广告封面图URL（主图）',
  `cover_mobile` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '移动端封面图（适配不同尺寸）',
  `cover_thumb` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '缩略图（列表页展示）',
  `video_url` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '视频广告URL（支持视频广告）',
  `link_type` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '跳转类型：1=站内链接，2=站外链接，3=小程序，4=无跳转（纯展示）',
  `link_url` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '跳转链接地址',
  `link_params` json NULL COMMENT '跳转参数，如：{\"utm_source\":\"home\",\"utm_medium\":\"banner\"}',
  `app_id` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '小程序AppId（link_type=3时使用）',
  `app_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '小程序路径（link_type=3时使用）',
  `position_code` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '广告位编码，如：home_banner_top、home_sidebar_1',
  `platform` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '投放平台：1=全部，2=PC端，3=移动端，4=小程序',
  `device_type` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '设备类型：1=全部，2=iOS，3=Android，4=其他',
  `target_user_type` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户定向：0=全部用户，1=新用户，2=老用户，3=VIP用户，4=指定用户组',
  `target_user_group_ids` json NULL COMMENT '目标用户组ID列表，如：[1,2,3]',
  `target_region` json NULL COMMENT '目标地区，如：{\"province\":[\"广东\",\"浙江\"],\"city\":[\"深圳\",\"杭州\"]}',
  `start_time` datetime NOT NULL COMMENT '投放开始时间',
  `end_time` datetime NOT NULL COMMENT '投放结束时间',
  `show_time_type` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '展示时间类型：0=全天，1=自定义时间段',
  `time_slots` json NULL COMMENT '自定义时间段，如：[{\"start\":\"09:00\",\"end\":\"12:00\"},{\"start\":\"14:00\",\"end\":\"18:00\"}]',
  `weekdays` json NULL COMMENT '投放星期，如：[1,2,3,4,5] 表示周一至周五',
  `sort` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序权重（值越大越靠前）',
  `display_frequency` int UNSIGNED NOT NULL DEFAULT 1 COMMENT '展示频率：1=每人每天1次，2=每人每小时1次，3=无限次',
  `daily_impression_limit` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '每日展示次数限制（全局）',
  `daily_click_limit` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '每日点击次数限制（全局）',
  `budget` decimal(12, 2) NULL DEFAULT NULL COMMENT '预算金额（CPM/CPC模式使用）',
  `cost_type` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '计费方式：1=CPM，2=CPC，3=CPT，4=CPA',
  `bid_price` decimal(10, 2) NULL DEFAULT NULL COMMENT '出价金额（CPM/CPC时使用）',
  `status` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：1=草稿，2=待审核，3=审核通过，4=投放中，5=已结束，6=已暂停，7=审核驳回，8=已下线',
  `audit_status` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '审核状态：0=未提交，1=待审核，2=审核通过，3=审核驳回',
  `reviewer_id` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '审核人ID',
  `reviewed_at` datetime NULL DEFAULT NULL COMMENT '审核时间',
  `reject_reason` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '驳回原因',
  `impression_count` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '展示次数',
  `click_count` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '点击次数',
  `click_rate` decimal(6, 4) NOT NULL DEFAULT 0.0000 COMMENT '点击率（CTR）',
  `daily_stats` json NULL COMMENT '日统计数据缓存，如：{\"2026-07-23\":{\"impression\":1000,\"click\":50}}',
  `created_by` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建人ID',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `deleted_at` datetime NULL DEFAULT NULL COMMENT '软删除时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_position_code`(`position_code` ASC) USING BTREE,
  INDEX `idx_status`(`status` ASC) USING BTREE,
  INDEX `idx_start_end_time`(`start_time` ASC, `end_time` ASC) USING BTREE,
  INDEX `idx_platform`(`platform` ASC) USING BTREE,
  INDEX `idx_sort`(`sort` ASC) USING BTREE,
  INDEX `idx_created_at`(`created_at` ASC) USING BTREE,
  INDEX `idx_deleted_at`(`deleted_at` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 920733863055423259 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '广告位主表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ad_positions
-- ----------------------------
INSERT INTO `ad_positions` VALUES (920733863055423246, '2026年企业数字化转型峰会', '携手行业大咖，共话数字化未来', '/uploads/ad/banner_digital_summit.jpg', '/uploads/ad/mobile/banner_digital_summit.jpg', '/uploads/ad/thumb/banner_digital_summit.jpg', '', 2, 'https://www.example.com/event/digital-summit-2026', '{\"utm_medium\": \"banner_top\", \"utm_source\": \"home\", \"utm_campaign\": \"digital_summit\"}', '', '', 'home_banner_top', 1, 1, 0, NULL, '{\"city\": [\"深圳\", \"广州\", \"北京\", \"上海\", \"杭州\"], \"province\": [\"广东\", \"北京\", \"上海\", \"浙江\"]}', '2026-07-01 00:00:00', '2026-09-30 23:59:59', 0, NULL, '[1, 2, 3, 4, 5, 6, 7]', 100, 1, 0, 0, NULL, 1, NULL, 4, 2, 920733860755423002, '2026-07-01 09:00:00', '', 12580, 368, 0.0293, '{\"2026-07-01\": {\"click\": 15, \"impression\": 520}, \"2026-07-02\": {\"click\": 12, \"impression\": 480}, \"2026-07-03\": {\"click\": 18, \"impression\": 550}}', 920733860755423002, '2026-06-25 10:00:00', '2026-07-23 09:43:18', NULL);
INSERT INTO `ad_positions` VALUES (920733863055423247, '企业官网全新升级 2.0 版本发布', '更流畅的体验，更强大的功能', '/uploads/ad/banner_v2_pc.jpg', '/uploads/ad/mobile/banner_v2_mobile.jpg', '/uploads/ad/thumb/banner_v2_thumb.jpg', '', 1, '/product/version-2-0', '{\"utm_medium\": \"banner_top_mobile\", \"utm_source\": \"home\", \"utm_campaign\": \"v2_launch\"}', '', '', 'home_banner_top', 3, 1, 0, NULL, NULL, '2026-07-15 00:00:00', '2026-08-31 23:59:59', 0, NULL, '[1, 2, 3, 4, 5, 6, 7]', 95, 1, 0, 0, NULL, 1, NULL, 4, 2, 920733860755423002, '2026-07-14 14:30:00', '', 8560, 245, 0.0286, NULL, 920733860755423003, '2026-07-10 09:00:00', '2026-07-23 09:43:18', NULL);
INSERT INTO `ad_positions` VALUES (920733863055423248, '新人专享大礼包', '注册即送价值 500 元优惠券', '/uploads/ad/banner_new_user.jpg', '/uploads/ad/mobile/banner_new_user.jpg', '/uploads/ad/thumb/banner_new_user.jpg', '', 1, '/user/register', '{\"utm_medium\": \"banner_top\", \"utm_source\": \"home\", \"utm_campaign\": \"new_user_gift\"}', '', '', 'home_banner_top', 1, 1, 1, NULL, NULL, '2026-07-01 00:00:00', '2026-08-15 23:59:59', 0, NULL, '[1, 2, 3, 4, 5, 6, 7]', 90, 2, 0, 0, NULL, 1, NULL, 4, 2, 920733860755423002, '2026-07-01 08:00:00', '', 6320, 189, 0.0299, NULL, 920733860755423003, '2026-06-28 16:00:00', '2026-07-23 09:43:18', NULL);
INSERT INTO `ad_positions` VALUES (920733863055423249, '企业级解决方案免费试用', '30天全功能体验，助力企业高效运营', '/uploads/ad/sidebar_free_trial.jpg', '', '/uploads/ad/thumb/sidebar_free_trial.jpg', '', 2, 'https://www.example.com/trial', '{\"utm_medium\": \"sidebar\", \"utm_source\": \"home\", \"utm_campaign\": \"free_trial\"}', '', '', 'home_sidebar', 1, 1, 0, NULL, '{\"province\": [\"广东\", \"北京\", \"上海\"]}', '2026-07-01 00:00:00', '2026-12-31 23:59:59', 1, '[{\"end\": \"18:00\", \"start\": \"09:00\"}]', '[1, 2, 3, 4, 5]', 80, 1, 1000, 50, NULL, 1, NULL, 4, 2, 920733860755423002, '2026-07-01 10:00:00', '', 3240, 98, 0.0302, NULL, 920733860755423004, '2026-06-30 11:00:00', '2026-07-23 09:43:18', NULL);
INSERT INTO `ad_positions` VALUES (920733863055423250, '合作伙伴招募计划', '诚邀优质合作伙伴，共享万亿市场', '/uploads/ad/bottom_partner.jpg', '/uploads/ad/mobile/bottom_partner.jpg', '/uploads/ad/thumb/bottom_partner.jpg', '', 2, 'https://www.example.com/partner', '{\"utm_medium\": \"bottom\", \"utm_source\": \"home\", \"utm_campaign\": \"partner_recruit\"}', '', '', 'home_bottom', 1, 1, 0, NULL, '{\"province\": [\"广东\", \"北京\", \"上海\", \"浙江\"]}', '2026-07-01 00:00:00', '2026-10-31 23:59:59', 0, NULL, '[1, 2, 3, 4, 5, 6, 7]', 70, 3, 0, 0, NULL, 1, NULL, 4, 2, 920733860755423002, '2026-07-01 09:00:00', '', 21500, 645, 0.0300, '{\"2026-07-01\": {\"click\": 36, \"impression\": 1200}, \"2026-07-02\": {\"click\": 33, \"impression\": 1100}}', 920733860755423004, '2026-06-25 14:00:00', '2026-07-23 09:43:18', NULL);
INSERT INTO `ad_positions` VALUES (920733863055423251, '重要通知：系统升级维护公告', '2026年7月25日 02:00-06:00 暂停服务', '/uploads/ad/popup_maintenance.jpg', '/uploads/ad/mobile/popup_maintenance.jpg', '/uploads/ad/thumb/popup_maintenance.jpg', '', 4, '', NULL, '', '', 'home_popup', 1, 1, 0, NULL, NULL, '2026-07-20 00:00:00', '2026-07-25 23:59:59', 1, '[{\"end\": \"23:59\", \"start\": \"00:00\"}]', '[1, 2, 3, 4, 5, 6, 7]', 60, 2, 0, 0, NULL, 1, NULL, 4, 2, 920733860755423002, '2026-07-19 17:00:00', '', 9800, 294, 0.0300, NULL, 920733860755423002, '2026-07-18 09:00:00', '2026-07-23 09:43:18', NULL);
INSERT INTO `ad_positions` VALUES (920733863055423252, '技术干货：PHP 8 新特性详解', '深入剖析 PHP 8 的核心改进与最佳实践', '/uploads/ad/inner_php8.jpg', '/uploads/ad/mobile/inner_php8.jpg', '/uploads/ad/thumb/inner_php8.jpg', '', 1, '/article/php8-new-features', '{\"utm_medium\": \"banner_top\", \"utm_source\": \"inner\", \"utm_campaign\": \"php8\"}', '', '', 'inner_banner_top', 1, 1, 0, NULL, NULL, '2026-07-01 00:00:00', '2026-08-31 23:59:59', 0, NULL, '[1, 2, 3, 4, 5, 6, 7]', 50, 1, 0, 0, NULL, 1, NULL, 4, 2, 920733860755423002, '2026-07-01 10:00:00', '', 4200, 126, 0.0300, NULL, 920733860755423003, '2026-06-28 10:00:00', '2026-07-23 09:43:18', NULL);
INSERT INTO `ad_positions` VALUES (920733863055423253, '扫描二维码关注公众号', '获取更多技术干货和行业资讯', '/uploads/ad/float_qrcode.jpg', '/uploads/ad/mobile/float_qrcode.jpg', '/uploads/ad/thumb/float_qrcode.jpg', '', 4, '', NULL, '', '', 'inner_article_float', 1, 1, 0, NULL, NULL, '2026-07-01 00:00:00', '2026-12-31 23:59:59', 0, NULL, '[1, 2, 3, 4, 5, 6, 7]', 40, 3, 0, 0, NULL, 1, NULL, 4, 2, 920733860755423002, '2026-07-01 08:00:00', '', 15000, 450, 0.0300, NULL, 920733860755423003, '2026-06-30 09:00:00', '2026-07-23 09:43:18', NULL);
INSERT INTO `ad_positions` VALUES (920733863055423254, '新品发布：企业级 AI 智能平台', '赋能企业智能化转型，提升核心竞争力', '/uploads/ad/product_ai_platform.jpg', '/uploads/ad/mobile/product_ai_platform.jpg', '/uploads/ad/thumb/product_ai_platform.jpg', '', 1, '/product/ai-platform', '{\"utm_medium\": \"banner\", \"utm_source\": \"product\", \"utm_campaign\": \"ai_platform\"}', '', '', 'product_banner', 1, 1, 0, NULL, NULL, '2026-07-15 00:00:00', '2026-09-30 23:59:59', 0, NULL, '[1, 2, 3, 4, 5, 6, 7]', 110, 3, 0, 0, NULL, 1, NULL, 4, 2, 920733860755423002, '2026-07-15 09:00:00', '', 3860, 116, 0.0301, NULL, 920733860755423003, '2026-07-14 10:00:00', '2026-07-23 09:43:18', NULL);
INSERT INTO `ad_positions` VALUES (920733863055423255, '待发布：年度品牌宣传片', '敬请期待，即将上线', '/uploads/ad/draft_brand_film.jpg', '/uploads/ad/mobile/draft_brand_film.jpg', '/uploads/ad/thumb/draft_brand_film.jpg', '', 2, 'https://www.example.com/brand', '{\"utm_medium\": \"banner\", \"utm_source\": \"draft\"}', '', '', 'home_banner_top', 1, 1, 0, NULL, NULL, '2026-08-01 00:00:00', '2026-10-31 23:59:59', 0, NULL, '[1, 2, 3, 4, 5, 6, 7]', 20, 1, 0, 0, NULL, 1, NULL, 1, 0, 0, NULL, '', 0, 0, 0.0000, NULL, 920733860755423003, '2026-07-20 14:00:00', '2026-07-23 09:43:18', NULL);
INSERT INTO `ad_positions` VALUES (920733863055423256, '暑期促销活动', '全场商品 8 折起', '/uploads/ad/rejected_summer_sale.jpg', '/uploads/ad/mobile/rejected_summer_sale.jpg', '/uploads/ad/thumb/rejected_summer_sale.jpg', '', 2, 'https://www.example.com/summer-sale', '{\"utm_medium\": \"banner\", \"utm_source\": \"home\", \"utm_campaign\": \"summer_sale\"}', '', '', 'home_banner_top', 1, 1, 0, NULL, NULL, '2026-07-01 00:00:00', '2026-07-31 23:59:59', 0, NULL, '[1, 2, 3, 4, 5, 6, 7]', 0, 1, 0, 0, NULL, 1, NULL, 7, 3, 920733860755423002, '2026-07-01 11:00:00', '广告素材涉及版权问题，请替换图片后重新提交审核', 0, 0, 0.0000, NULL, 920733860755423003, '2026-06-28 16:00:00', '2026-07-23 09:43:18', NULL);
INSERT INTO `ad_positions` VALUES (920733863055423257, '限时优惠：企业版年度会员 5 折', '仅限前 100 名客户，先到先得', '/uploads/ad/paused_enterprise_discount.jpg', '/uploads/ad/mobile/paused_enterprise_discount.jpg', '/uploads/ad/thumb/paused_enterprise_discount.jpg', '', 2, 'https://www.example.com/enterprise-discount', '{\"utm_medium\": \"sidebar\", \"utm_source\": \"home\", \"utm_campaign\": \"enterprise_discount\"}', '', '', 'home_sidebar', 1, 1, 0, NULL, NULL, '2026-07-01 00:00:00', '2026-08-31 23:59:59', 0, NULL, '[1, 2, 3, 4, 5, 6, 7]', 0, 1, 0, 0, NULL, 1, NULL, 6, 2, 920733860755423002, '2026-07-01 09:00:00', '', 1280, 38, 0.0297, NULL, 920733860755423004, '2026-06-30 14:00:00', '2026-07-23 09:43:18', NULL);
INSERT INTO `ad_positions` VALUES (920733863055423258, '618 年中大促活动', '全场满减，限时抢购', '/uploads/ad/ended_618_sale.jpg', '/uploads/ad/mobile/ended_618_sale.jpg', '/uploads/ad/thumb/ended_618_sale.jpg', '', 2, 'https://www.example.com/618-sale', '{\"utm_medium\": \"banner_top\", \"utm_source\": \"home\", \"utm_campaign\": \"618_sale\"}', '', '', 'home_banner_top', 1, 1, 0, NULL, NULL, '2026-06-01 00:00:00', '2026-06-20 23:59:59', 0, NULL, '[1, 2, 3, 4, 5, 6, 7]', 0, 1, 0, 0, NULL, 1, NULL, 5, 2, 920733860755423002, '2026-06-01 08:00:00', '', 25600, 768, 0.0300, NULL, 920733860755423003, '2026-05-28 10:00:00', '2026-07-23 09:43:18', NULL);

-- ----------------------------
-- Table structure for ad_slots
-- ----------------------------
DROP TABLE IF EXISTS `ad_slots`;
CREATE TABLE `ad_slots`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `slot_code` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '广告位编码，如：home_banner_top',
  `slot_name` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '广告位名称，如：首页顶部轮播图',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '广告位描述',
  `width` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '广告位宽度（像素）',
  `height` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '广告位高度（像素）',
  `max_items` int UNSIGNED NOT NULL DEFAULT 1 COMMENT '最大展示数量',
  `is_system` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否系统预设：0=否，1=是',
  `slot_status` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：0=禁用，1=启用',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_code`(`slot_code` ASC) USING BTREE,
  INDEX `idx_status`(`slot_status` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 920733863755423258 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '广告位位置定义表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of ad_slots
-- ----------------------------
INSERT INTO `ad_slots` VALUES (920733863755423246, 'home_banner_top', '首页顶部轮播图', '首页顶部焦点图轮播区域，用于展示品牌形象、重大活动或核心产品，支持多图轮播', 1920, 600, 5, 1, 1, '2026-07-23 09:21:11', '2026-07-23 09:21:11', NULL);
INSERT INTO `ad_slots` VALUES (920733863755423247, 'home_banner_top_mobile', '首页顶部轮播图（移动端）', '移动端首页顶部焦点图轮播区域，适配手机屏幕尺寸', 750, 400, 5, 1, 1, '2026-07-23 09:21:11', '2026-07-23 09:21:11', NULL);
INSERT INTO `ad_slots` VALUES (920733863755423248, 'home_sidebar', '首页侧边栏广告位', '首页右侧边栏广告位，适合展示活动推广、产品推荐或联系方式', 300, 600, 2, 1, 1, '2026-07-23 09:21:11', '2026-07-23 09:21:11', NULL);
INSERT INTO `ad_slots` VALUES (920733863755423249, 'home_bottom', '首页底部横幅', '首页底部通栏横幅广告位，适合展示合作伙伴、友情链接或品牌宣传', 1920, 200, 3, 1, 1, '2026-07-23 09:21:11', '2026-07-23 09:21:11', NULL);
INSERT INTO `ad_slots` VALUES (920733863755423250, 'home_popup', '首页弹窗广告', '进入首页时自动弹出的广告窗口，适合重要活动通知或用户引导', 600, 450, 1, 1, 1, '2026-07-23 09:21:11', '2026-07-23 09:21:11', NULL);
INSERT INTO `ad_slots` VALUES (920733863755423251, 'inner_banner_top', '内页顶部横幅', '内页（文章详情页、产品页等）顶部通栏横幅广告位', 1920, 300, 3, 1, 1, '2026-07-23 09:21:11', '2026-07-23 09:21:11', NULL);
INSERT INTO `ad_slots` VALUES (920733863755423252, 'inner_sidebar', '内页侧边栏广告位', '内页右侧边栏广告位，适合展示相关推荐、热门产品或联系表单', 300, 500, 2, 1, 1, '2026-07-23 09:21:11', '2026-07-23 09:21:11', NULL);
INSERT INTO `ad_slots` VALUES (920733863755423253, 'inner_article_float', '文章详情页悬浮广告', '文章详情页底部悬浮广告位，不干扰阅读体验，适合引导关注或下载', 750, 120, 1, 1, 1, '2026-07-23 09:21:11', '2026-07-23 09:21:11', NULL);
INSERT INTO `ad_slots` VALUES (920733863755423254, 'product_banner', '产品列表页顶部横幅', '产品列表页顶部的品牌/活动宣传横幅，可突出核心产品线', 1920, 350, 3, 1, 1, '2026-07-23 09:21:11', '2026-07-23 09:21:11', NULL);
INSERT INTO `ad_slots` VALUES (920733863755423255, 'product_detail_bottom', '产品详情页底部推荐', '产品详情页底部推荐广告位，展示相关产品或配套服务', 1200, 200, 4, 1, 1, '2026-07-23 09:21:11', '2026-07-23 09:21:11', NULL);
INSERT INTO `ad_slots` VALUES (920733863755423256, 'about_us_banner', '关于我们页横幅', '关于我们页面顶部横幅，用于展示企业使命、愿景或宣传视频', 1920, 450, 2, 1, 1, '2026-07-23 09:21:11', '2026-07-23 09:21:11', NULL);
INSERT INTO `ad_slots` VALUES (920733863755423257, 'job_banner', '招聘页顶部横幅', '招聘页面顶部横幅，展示企业雇主品牌和团队文化', 1920, 350, 2, 1, 1, '2026-07-23 09:21:11', '2026-07-23 09:21:11', NULL);

-- ----------------------------
-- Table structure for article_category
-- ----------------------------
DROP TABLE IF EXISTS `article_category`;
CREATE TABLE `article_category`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `parent_id` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '父级ID，0表示顶级',
  `cat_name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '分类名称',
  `cat_url` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'URL别名，如：company-news',
  `description` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '分类描述',
  `cat_sort` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序权重',
  `status` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：0=禁用，1=启用',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_parent_id`(`parent_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 920733863034423263 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '文章分类表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of article_category
-- ----------------------------
INSERT INTO `article_category` VALUES (920733863034423246, 0, '公司新闻', 'company-news', '发布公司的最新动态、重大事件和公告', 100, 1, '2026-07-23 09:19:35', '2026-07-23 09:19:35', NULL);
INSERT INTO `article_category` VALUES (920733863034423247, 0, '行业动态', 'industry-news', '跟踪行业发展趋势、政策法规和市场变化', 90, 1, '2026-07-23 09:19:35', '2026-07-23 09:19:35', NULL);
INSERT INTO `article_category` VALUES (920733863034423248, 0, '产品资讯', 'product-news', '介绍产品更新、功能迭代和使用教程', 80, 1, '2026-07-23 09:19:35', '2026-07-23 09:19:35', NULL);
INSERT INTO `article_category` VALUES (920733863034423249, 0, '技术分享', 'tech-sharing', '分享技术干货、开发经验和最佳实践', 70, 1, '2026-07-23 09:19:35', '2026-07-23 09:19:35', NULL);
INSERT INTO `article_category` VALUES (920733863034423250, 0, '招聘信息', 'job-info', '发布招聘职位、人才需求和团队文化', 60, 1, '2026-07-23 09:19:35', '2026-07-23 09:19:35', NULL);
INSERT INTO `article_category` VALUES (920733863034423251, 0, '客户案例', 'customer-cases', '展示客户成功案例和合作故事', 50, 1, '2026-07-23 09:19:35', '2026-07-23 09:19:35', NULL);
INSERT INTO `article_category` VALUES (920733863034423252, 0, '企业荣誉', 'company-honor', '展示企业获得的资质、认证和奖项', 40, 1, '2026-07-23 09:19:35', '2026-07-23 09:19:35', NULL);
INSERT INTO `article_category` VALUES (920733863034423253, 920733863034423246, '企业公告', 'company-announcement', '发布企业重要公告和通知', 10, 1, '2026-07-23 09:19:35', '2026-07-23 09:19:35', NULL);
INSERT INTO `article_category` VALUES (920733863034423254, 920733863034423246, '企业活动', 'company-event', '报道企业举办的会议、培训和团建活动', 9, 1, '2026-07-23 09:19:35', '2026-07-23 09:19:35', NULL);
INSERT INTO `article_category` VALUES (920733863034423255, 920733863034423246, '企业荣誉', 'company-honor', '展示企业获得的荣誉和资质认证', 8, 1, '2026-07-23 09:19:35', '2026-07-23 09:19:35', NULL);
INSERT INTO `article_category` VALUES (920733863034423256, 920733863034423247, '行业政策', 'industry-policy', '解读国家和地方相关政策法规', 10, 1, '2026-07-23 09:19:35', '2026-07-23 09:19:35', NULL);
INSERT INTO `article_category` VALUES (920733863034423257, 920733863034423247, '市场趋势', 'market-trend', '分析市场变化和行业发展趋势', 9, 1, '2026-07-23 09:19:35', '2026-07-23 09:19:35', NULL);
INSERT INTO `article_category` VALUES (920733863034423258, 920733863034423248, '产品发布', 'product-release', '介绍新产品发布和重大版本更新', 10, 1, '2026-07-23 09:19:35', '2026-07-23 09:19:35', NULL);
INSERT INTO `article_category` VALUES (920733863034423259, 920733863034423248, '使用教程', 'product-tutorial', '提供产品功能的使用方法和操作指南', 9, 1, '2026-07-23 09:19:35', '2026-07-23 09:19:35', NULL);
INSERT INTO `article_category` VALUES (920733863034423260, 920733863034423249, '后端开发', 'backend-dev', '分享后端技术架构、数据库和性能优化经验', 10, 1, '2026-07-23 09:19:35', '2026-07-23 09:19:35', NULL);
INSERT INTO `article_category` VALUES (920733863034423261, 920733863034423249, '前端开发', 'frontend-dev', '分享前端框架、UI设计和用户体验技巧', 9, 1, '2026-07-23 09:19:35', '2026-07-23 09:19:35', NULL);
INSERT INTO `article_category` VALUES (920733863034423262, 920733863034423249, '运维安全', 'devops-security', '分享运维部署、安全防护和系统监控经验', 8, 1, '2026-07-23 09:19:35', '2026-07-23 09:19:35', NULL);

-- ----------------------------
-- Table structure for articles
-- ----------------------------
DROP TABLE IF EXISTS `articles`;
CREATE TABLE `articles`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID（雪花ID或自增）',
  `title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '文章标题',
  `subtitle` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '副标题/摘要',
  `art_cover` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '封面图URL（支持多图用JSON）',
  `art_content` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL COMMENT '文章正文内容（富文本/Markdown）',
  `content_type` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '内容类型：1=富文本，2=Markdown，3=纯文本',
  `summary` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '文章摘要（自动截取或手动填写）',
  `category_id` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '分类ID（关联 categories 表）',
  `tag_ids` json NULL COMMENT '标签ID列表，如 [1,2,3]（关联 tags 表）',
  `author_id` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '作者用户ID（关联 users 表）',
  `author_name` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '作者姓名（冗余字段，防止用户改名）',
  `source` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '文章来源（如：原创/转载/翻译）',
  `source_url` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '原文链接（转载时使用）',
  `art_status` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：1=草稿，2=待审核，3=审核通过，4=已发布，5=已下线，6=审核驳回，7=回收站',
  `is_top` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否置顶：0=否，1=是',
  `is_original` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否原创：0=否，1=是',
  `is_commentable` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '是否允许评论：0=否，1=是',
  `seo_title` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'SEO标题（为空时取 title）',
  `seo_keywords` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'SEO关键词',
  `seo_description` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'SEO描述',
  `extra_fields` json NULL COMMENT '扩展字段（如：视频链接、下载链接、相关推荐等）',
  `view_count` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '浏览量',
  `like_count` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '点赞量',
  `collect_count` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '收藏量',
  `share_count` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '分享量',
  `comment_count` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '评论量',
  `published_at` datetime NULL DEFAULT NULL COMMENT '发布时间（状态变为“已发布”时记录）',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `deleted_at` datetime NULL DEFAULT NULL COMMENT '软删除时间',
  `reviewer_id` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '审核人ID（关联 users 表）',
  `reviewed_at` datetime NULL DEFAULT NULL COMMENT '审核时间',
  `reject_reason` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT NULL COMMENT '驳回原因（审核驳回时填写）',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_author_id`(`author_id` ASC) USING BTREE,
  INDEX `idx_category_id`(`category_id` ASC) USING BTREE,
  INDEX `idx_status`(`art_status` ASC) USING BTREE,
  INDEX `idx_is_top`(`is_top` ASC) USING BTREE,
  INDEX `idx_published_at`(`published_at` ASC) USING BTREE,
  INDEX `idx_created_at`(`created_at` ASC) USING BTREE,
  INDEX `idx_deleted_at`(`deleted_at` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 11 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '文章主表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of articles
-- ----------------------------
INSERT INTO `articles` VALUES (1, '企业官网全新改版上线，开启数字化新征程', '历时3个月精心打造，全新视觉体验与功能升级', '/uploads/articles/cover/website_redesign.jpg', '<h2>全新官网，全新出发</h2><p>经过3个月的精心设计与开发，企业官网全新改版正式上线！</p><p>本次改版以用户为中心，全新设计了信息架构和视觉风格，并优化了移动端体验，让您随时随地获取最新资讯。</p><p><strong>主要升级亮点：</strong></p><ul><li>全新的品牌视觉形象</li><li>更流畅的页面加载速度</li><li>完善的响应式设计，适配各类设备</li><li>新增在线客服与即时咨询功能</li></ul><p>未来，我们将持续通过官网与大家分享企业动态、行业趋势与技术干货，敬请期待！</p>', 1, '企业官网全新改版正式上线，本次升级历时3个月，带来全新的视觉体验、更流畅的交互和更完善的功能。', 1, '[1, 2]', 920733860755423001, 'admin', '原创', '', 4, 1, 1, 1, '企业官网全新改版上线 - 品牌官网', '官网改版,企业官网,品牌升级,数字化', '企业官网全新改版正式上线，全新视觉与功能升级，开启数字化新征程。', '{\"video_url\": \"https://www.example.com/video/redesign.mp4\"}', 2580, 156, 89, 45, 23, '2026-07-20 09:00:00', '2026-07-15 10:00:00', '2026-07-23 09:47:02', NULL, 920733860755423002, '2026-07-18 14:00:00', '');
INSERT INTO `articles` VALUES (2, '企业荣获2026年度“科技创新示范企业”称号', '以技术创新驱动企业发展，获行业高度认可', '/uploads/articles/cover/tech_award.jpg', '<p>近日，在2026年度科技创新大会上，本公司凭借在数字化领域的技术创新和产业应用，荣获“科技创新示范企业”称号。</p><p>本次评选由行业协会联合多家权威机构共同举办，旨在表彰在技术创新、成果转化和产业带动方面表现突出的企业。经过严格评审，本公司在技术研发投入、专利数量、行业影响力等维度均位列前茅。</p><p>公司CTO表示：“技术创新是公司发展的核心驱动力。未来我们将继续加大研发投入，推动产业数字化升级。” </p>', 1, '公司荣获2026年度“科技创新示范企业”称号，充分体现了行业对公司技术创新能力的认可。', 1, '[3]', 920733860755423002, 'super_admin', '原创', '', 4, 0, 1, 1, '科技创新示范企业 - 企业荣誉', '科技创新,示范企业,企业荣誉,技术奖项', '公司荣获2026年度“科技创新示范企业”称号，彰显技术创新实力。', NULL, 1860, 89, 56, 34, 12, '2026-07-10 10:30:00', '2026-07-08 16:00:00', '2026-07-23 09:47:02', NULL, 920733860755423002, '2026-07-09 09:00:00', '');
INSERT INTO `articles` VALUES (3, '2026年企业数字化发展趋势报告', '深度解读数字化转型的新机遇与新挑战', '/uploads/articles/cover/digital_trend.jpg', '<p>2026年，企业数字化已从“可选项”变为“必选项”。根据最新行业报告，超过80%的企业将数字化转型作为核心战略。</p><p><strong>主要趋势：</strong></p><ul><li><strong>AI 驱动：</strong>人工智能正在重塑企业运营模式，从自动化到智能化</li><li><strong>数据资产：</strong>数据已成为企业最重要的资产之一</li><li><strong>云原生：</strong>云原生技术正在加速企业应用现代化</li><li><strong>安全合规：</strong>数据安全与合规成为企业数字化的重要基石</li></ul><p>报告建议，企业应从战略、组织、技术、文化四个维度全面推进数字化转型。</p>', 1, '深度解读2026年企业数字化发展趋势，从AI驱动、数据资产、云原生到安全合规，为企业提供转型参考。', 2, '[4, 5]', 920733860755423003, 'editor_zhang', '原创', '', 4, 1, 1, 1, '2026年企业数字化发展趋势报告', '企业数字化,数字化转型,AI,云原生,数据资产', '解读2026年企业数字化发展趋势，为企业转型提供参考和指引。', NULL, 3210, 245, 123, 78, 45, '2026-07-05 08:00:00', '2026-07-02 14:00:00', '2026-07-23 09:47:02', NULL, 920733860755423002, '2026-07-03 10:00:00', '');
INSERT INTO `articles` VALUES (4, '产品 3.0 版本正式发布，带来全新功能体验', 'AI智能推荐、多端同步、数据可视化三大核心升级', '/uploads/articles/cover/product_3.0.jpg', '<p>经过半年的研发迭代，产品 3.0 版本正式与大家见面了！</p><h3>三大核心升级：</h3><ul><li><strong>AI 智能推荐：</strong>基于用户行为数据，提供个性化的内容推荐</li><li><strong>多端同步：</strong>Web、App、小程序数据实时同步，无缝切换</li><li><strong>数据可视化：</strong>内置多维度数据分析看板，辅助决策</li></ul><p>本次更新还优化了超过50项用户体验细节，期待为您带来更高效的使用体验。即日起，所有用户可在线升级至最新版本。</p>', 1, '产品3.0版本正式发布，带来AI智能推荐、多端同步、数据可视化三大核心升级。', 3, '[6, 7]', 920733860755423004, 'ops_li', '原创', '', 4, 0, 1, 1, '产品3.0正式发布 - 新品发布', '产品发布,3.0版本,AI推荐,数据可视化', '产品3.0版本正式发布，三大核心升级助力企业高效运营。', '{\"version\": \"3.0.0\", \"download_url\": \"https://www.example.com/download/product-3.0\"}', 4560, 321, 156, 89, 56, '2026-07-18 10:00:00', '2026-07-16 09:00:00', '2026-07-23 09:47:02', NULL, 920733860755423002, '2026-07-17 11:00:00', '');
INSERT INTO `articles` VALUES (5, 'Hyperf 框架高性能实践：从入门到精通', '深入解析 Hyperf 的协程、依赖注入和注解机制', '/uploads/articles/cover/hyperf_best_practice.jpg', '<p>Hyperf 是 Swoole 生态中最流行的 PHP 框架之一，以其高性能和丰富的生态受到开发者的广泛关注。</p><h3>本篇主要分享：</h3><ul><li><strong>协程原理：</strong>理解 Swoole 协程的工作机制，写出高性能代码</li><li><strong>依赖注入：</strong>利用 DI 容器实现松耦合设计</li><li><strong>注解路由：</strong>使用注解简化路由配置，提高开发效率</li><li><strong>性能优化：</strong>常见的性能瓶颈分析与优化策略</li></ul><p>通过实际项目案例，帮助大家快速上手并写出高质量的 Hyperf 应用。</p>', 1, '深入解析 Hyperf 框架的核心机制，包括协程、依赖注入、注解路由和性能优化实践。', 4, '[8, 9]', 920733860755423001, 'admin', '原创', '', 4, 0, 1, 1, 'Hyperf框架高性能实践 - 技术分享', 'Hyperf,PHP,协程,依赖注入,性能优化', '深入解析 Hyperf 框架的核心机制与高性能实践。', NULL, 1980, 134, 67, 45, 28, '2026-07-12 14:30:00', '2026-07-10 11:00:00', '2026-07-23 09:47:02', NULL, 920733860755423002, '2026-07-11 09:00:00', '');
INSERT INTO `articles` VALUES (6, '某大型制造企业数字化转型成功案例', '借助企业数字化平台，实现生产效率提升30%', '/uploads/articles/cover/case_manufacturing.jpg', '<p>某大型制造企业拥有超过5000名员工，业务涵盖产品研发、生产制造、供应链管理等多个环节。</p><p><strong>业务痛点：</strong></p><ul><li>数据孤岛严重，各部门信息不互通</li><li>业务流程效率低，审批周期长</li><li>缺乏数据驱动的决策支持</li></ul><p><strong>解决方案：</strong></p><p>通过企业数字化平台，实现了统一的数据中台、自动化流程引擎和智能分析看板。</p><p><strong>效果：</strong></p><ul><li>生产效率提升 30%</li><li>审批周期缩短 50%</li><li>数据决策效率提升 40%</li></ul>', 1, '某大型制造企业通过企业数字化平台实现生产效率提升30%的数字化转型案例。', 6, '[10]', 920733860755423003, 'editor_zhang', '原创', '', 4, 0, 1, 1, '制造企业数字化转型成功案例', '数字化转型,制造企业,客户案例,效率提升', '某大型制造企业数字化转型成功案例，生产效率提升30%。', NULL, 1560, 98, 45, 23, 15, '2026-07-08 16:00:00', '2026-07-06 15:00:00', '2026-07-23 09:47:02', NULL, 920733860755423002, '2026-07-07 10:00:00', '');
INSERT INTO `articles` VALUES (7, '2026年第三季度产品路线图预告', '即将发布的新功能与改进计划', '/uploads/articles/cover/roadmap_q3.jpg', '<p>本文是2026年Q3产品路线图的草稿版本，正式发布前需要内部审核。</p><p><strong>计划中的主要功能：</strong></p><ul><li>AI 辅助写作功能</li><li>多语言国际化支持</li><li>数据导出增强</li><li>性能优化与稳定性提升</li></ul>', 1, '2026年Q3产品路线图预告，涵盖AI辅助写作、多语言支持等主要功能。', 3, '[11]', 920733860755423004, 'ops_li', '原创', '', 1, 0, 1, 1, '', '', '', NULL, 0, 0, 0, 0, 0, NULL, '2026-07-21 09:00:00', '2026-07-23 09:47:02', NULL, 0, NULL, '');
INSERT INTO `articles` VALUES (8, '企业社会责任报告：2026年度可持续发展', '践行ESG理念，推动可持续发展', '/uploads/articles/cover/csr_2026.jpg', '<p>2026年度企业社会责任报告正式提交审核，涵盖环境保护、员工关怀、社会公益等方面的工作成果。</p><p><strong>主要成果：</strong></p><ul><li>碳排放减少 15%</li><li>员工满意度达 92%</li><li>累计公益投入 500 万元</li></ul>', 1, '2026年度企业社会责任报告，涵盖环境保护、员工关怀、社会公益等方面。', 1, '[12]', 920733860755423005, 'pm_wang', '原创', '', 2, 0, 1, 1, '企业社会责任报告2026 - 可持续发展', '社会责任,ESG,可持续发展,公益', '2026年度企业社会责任报告，践行ESG理念，推动可持续发展。', NULL, 0, 0, 0, 0, 0, NULL, '2026-07-22 11:00:00', '2026-07-23 09:47:02', NULL, 0, NULL, '');
INSERT INTO `articles` VALUES (9, '2025年企业年度总结：砥砺前行，再创佳绩', '回顾2025，展望2026', '/uploads/articles/cover/yearly_2025.jpg', '<p>2025年是公司快速发展的一年，我们取得了以下成绩：</p><ul><li>营收同比增长 35%</li><li>服务客户突破 10,000 家</li><li>团队规模扩展至 500 人</li></ul><p>展望2026，我们将继续深耕行业，为客户创造更大的价值。</p>', 1, '2025年企业年度总结，回顾年度成绩与里程碑。', 1, '[13]', 920733860755423001, 'admin', '原创', '', 5, 0, 1, 1, '企业年度总结2025', '年度总结,企业成就,发展回顾', '2025年企业年度总结，回顾年度成绩与发展历程。', NULL, 8900, 567, 234, 123, 67, '2026-01-01 00:00:00', '2025-12-20 10:00:00', '2026-07-23 09:47:02', NULL, 920733860755423002, '2025-12-25 14:00:00', '');
INSERT INTO `articles` VALUES (10, '2026年行业峰会精彩回顾', '全球行业领袖共话未来', '/uploads/articles/cover/summit_2026.jpg', '<p>本次行业峰会邀请了全球超过50位行业领袖，共同探讨行业发展的机遇与挑战。</p><p>但由于提交的素材涉及部分未经授权的内容，本次审核被驳回。</p>', 1, '2026年行业峰会精彩回顾，全球行业领袖共话未来。', 2, '[14]', 920733860755423006, 'sales_chen', '转载', 'https://www.example.com/source/summit', 6, 0, 0, 1, '', '', '', NULL, 0, 0, 0, 0, 0, NULL, '2026-07-19 14:00:00', '2026-07-23 09:47:02', NULL, 920733860755423002, '2026-07-20 09:00:00', '文章中的图片素材涉及版权问题，请替换后重新提交审核。');

-- ----------------------------
-- Table structure for auth_menus
-- ----------------------------
DROP TABLE IF EXISTS `auth_menus`;
CREATE TABLE `auth_menus`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `parent_id` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '父级菜单ID，0表示顶级菜单',
  `menu_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '菜单名称，如：用户管理',
  `menu_icon` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '菜单图标，如：el-icon-user',
  `menu_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '前端路由路径，如：/user/list',
  `component` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '前端组件路径，如：user/Index',
  `permission_code` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '关联的权限标识，用于按钮级控制',
  `menu_sort` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序权重，值越大越靠前',
  `menu_status` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：0=禁用，1=启用',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `deleted_at` datetime NULL DEFAULT NULL COMMENT '删除时间（软删除）',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_parent_id`(`parent_id` ASC) USING BTREE,
  INDEX `idx_permission_code`(`permission_code` ASC) USING BTREE,
  INDEX `idx_status`(`menu_status` ASC) USING BTREE,
  INDEX `idx_deleted_at`(`deleted_at` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 1920733860755430000 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '菜单/功能表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of auth_menus
-- ----------------------------
INSERT INTO `auth_menus` VALUES (920733860755423248, 0, '首页', 'el-icon-s-home', '/backend/index', 'dashboard/index', 'dashboardview', 10, 1, '2026-07-23 09:58:06', '2026-07-23 10:01:33', NULL);
INSERT INTO `auth_menus` VALUES (920733860755423249, 0, '内容管理', 'el-icon-document', '/backend/content', 'Layout', 'contentview', 2, 1, '2026-07-23 09:58:06', '2026-07-23 10:04:22', NULL);
INSERT INTO `auth_menus` VALUES (920733860755423250, 0, '文章管理', 'el-icon-edit-outlined', '/backend/article', 'content/article/index', 'articleview', 1, 1, '2026-07-23 09:58:06', '2026-07-23 10:49:18', NULL);
INSERT INTO `auth_menus` VALUES (920733860755423251, 920733860755423250, '文章列表', 'el-icon-document', '/backend/article/index', 'content/article/List', 'articlelist', 1, 1, '2026-07-23 09:58:06', '2026-07-23 10:49:12', NULL);
INSERT INTO `auth_menus` VALUES (920733860755423252, 920733860755423250, '添加文章', 'el-icon-plus', '/backend/article/add', 'content/article/Add', 'articleadd', 2, 0, '2026-07-23 09:58:06', '2026-07-23 10:51:27', NULL);
INSERT INTO `auth_menus` VALUES (920733860755423253, 920733860755423249, '文章分类', 'el-icon-folder-opened', '/backend/articlecategory', 'content/articlecategory/index', 'articlecategoryview', 2, 1, '2026-07-23 09:58:06', '2026-07-23 10:07:21', NULL);
INSERT INTO `auth_menus` VALUES (920733860755423254, 920733860755423249, '分类管理', 'el-icon-folder-opened', '/backend/category', 'content/category/index', 'categoryview', 3, 1, '2026-07-23 09:58:06', '2026-07-23 10:07:25', NULL);
INSERT INTO `auth_menus` VALUES (920733860755423255, 0, '运营管理', 'el-icon-present', '/backend/operation', 'Layout', 'operationview', 3, 1, '2026-07-23 09:58:06', '2026-07-23 10:04:03', NULL);
INSERT INTO `auth_menus` VALUES (920733860755423256, 920733860755423255, '广告位管理', 'el-icon-picture', '/backend/adslots', 'operation/adslots/index', 'adslotsview', 1, 1, '2026-07-23 09:58:06', '2026-07-23 10:07:53', NULL);
INSERT INTO `auth_menus` VALUES (920733860755423257, 920733860755423255, '广告管理', 'el-icon-office-building', '/backend/adpositions', 'operation/adpositions/index', 'adpositionsview', 2, 1, '2026-07-23 09:58:06', '2026-07-23 10:07:57', NULL);
INSERT INTO `auth_menus` VALUES (920733860755423258, 920733860755423255, '友情链接', 'el-icon-link', '/backend/friendlinks', 'operation/friendlinks/index', 'friendlinksview', 3, 1, '2026-07-23 09:58:06', '2026-07-23 10:08:01', NULL);
INSERT INTO `auth_menus` VALUES (920733860755423259, 920733860755423255, '用户留言', 'el-icon-chat-line', '/backend/feedbacks', 'operation/feedbacks/index', 'feedbacksview', 4, 1, '2026-07-23 09:58:06', '2026-07-23 10:08:04', NULL);
INSERT INTO `auth_menus` VALUES (920733860755423260, 920733860755423255, '招聘职位', 'el-icon-user', '/backend/bossjob', 'operation/bossjob/index', 'bossjobview', 5, 1, '2026-07-23 09:58:06', '2026-07-23 10:08:08', NULL);
INSERT INTO `auth_menus` VALUES (920733860755423261, 0, '系统管理', 'el-icon-setting', '/backend/system', 'Layout', 'systemview', 4, 1, '2026-07-23 09:58:06', '2026-07-23 10:03:45', NULL);
INSERT INTO `auth_menus` VALUES (920733860755423262, 920733860755423261, '系统设置', 'el-icon-tools', '/backend/siteconfigs', 'system/config/index', 'configview', 1, 1, '2026-07-23 09:58:06', '2026-07-23 12:31:42', NULL);
INSERT INTO `auth_menus` VALUES (920733860755423263, 920733860755423261, '菜单管理', 'el-icon-menu', '/backend/menu', 'system/menu/index', 'menumanage', 2, 1, '2026-07-23 09:58:06', '2026-07-23 10:08:18', NULL);
INSERT INTO `auth_menus` VALUES (920733860755423264, 0, '权限管理', 'el-icon-lock', '/backend/permission', 'system/permission/index', 'permissionmanage', 3, 1, '2026-07-23 09:58:06', '2026-07-23 10:08:43', NULL);
INSERT INTO `auth_menus` VALUES (920733860755423265, 920733860755423264, '权限规则', 'el-icon-key', '/backend/permission/rules', 'system/permission/Rules', 'permissionrules', 1, 1, '2026-07-23 09:58:06', '2026-07-23 10:08:47', NULL);
INSERT INTO `auth_menus` VALUES (920733860755423266, 0, '角色管理', 'el-icon-user', '/backend/permission/role', 'system/permission/Role', 'rolemanage', 2, 1, '2026-07-23 09:58:06', '2026-07-23 10:08:50', NULL);
INSERT INTO `auth_menus` VALUES (920733860755423267, 920733860755423266, '角色列表', 'el-icon-user', '/backend/permission/role/index', 'system/permission/RoleList', 'rolelist', 1, 1, '2026-07-23 09:58:06', '2026-07-23 10:49:06', NULL);
INSERT INTO `auth_menus` VALUES (920733860755423268, 920733860755423266, '角色权限', 'el-icon-key', '/backend/permission/role/perm', 'system/permission/RolePerm', 'roleperm', 2, 1, '2026-07-23 09:58:06', '2026-07-23 10:08:55', NULL);
INSERT INTO `auth_menus` VALUES (920733860755423269, 920733860755423266, '角色菜单', 'el-icon-menu', '/backend/permission/role/menu', 'system/permission/RoleMenu', 'rolemenu', 3, 1, '2026-07-23 09:58:06', '2026-07-23 10:08:58', NULL);
INSERT INTO `auth_menus` VALUES (920733860755423270, 920733860755423261, '用户管理', 'el-icon-user', '/backend/user', 'system/user/index', 'usermanage', 4, 1, '2026-07-23 09:58:06', '2026-07-23 10:09:01', NULL);
INSERT INTO `auth_menus` VALUES (920733860755423271, 920733860755423261, '操作日志', 'el-icon-document', '/backend/operationlog/index', 'system/log/index', 'logview', 5, 1, '2026-07-23 09:58:06', '2026-07-23 10:49:00', NULL);
INSERT INTO `auth_menus` VALUES (920733860755423272, 0, '书签管理', 'el-icon-star-off', '/backend/bookmark', 'bookmark/index', 'bookmarkview', 5, 1, '2026-07-23 09:58:06', '2026-07-23 10:03:15', NULL);
INSERT INTO `auth_menus` VALUES (920733860755423273, 920733860755423272, '书签列表', 'el-icon-star-on', '/backend/bookmark/index', 'bookmark/List', 'bookmarklist', 1, 1, '2026-07-23 09:58:06', '2026-07-23 10:45:39', NULL);
INSERT INTO `auth_menus` VALUES (920733860755423274, 920733860755423272, '我的书签', 'el-icon-collection', '/backend/bookmark/my', 'bookmark/My', 'bookmarkmy', 2, 0, '2026-07-23 09:58:06', '2026-07-23 10:46:27', NULL);

-- ----------------------------
-- Table structure for auth_permissions
-- ----------------------------
DROP TABLE IF EXISTS `auth_permissions`;
CREATE TABLE `auth_permissions`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `parent_id` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '父级权限ID，用于树形结构',
  `per_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '权限名称，如：用户删除',
  `per_code` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '权限唯一标识，如：user:delete',
  `per_type` enum('menu','button','api') CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'api' COMMENT '权限类型：menu=菜单，button=按钮，api=接口',
  `per_path` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT '' COMMENT '前端路由路径或API路径，如：/user/delete',
  `per_method` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT '' COMMENT 'HTTP方法，GET/POST/PUT/DELETE，仅 type=api 时有效',
  `per_icon` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT '' COMMENT '菜单图标，仅 type=menu 时有效',
  `per_sort` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序权重，值越大越靠前',
  `per_status` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：0=禁用，1=启用',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `deleted_at` datetime NULL DEFAULT NULL COMMENT '删除时间（软删除）',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_code`(`per_code` ASC) USING BTREE,
  INDEX `idx_parent_id`(`parent_id` ASC) USING BTREE,
  INDEX `idx_type`(`per_type` ASC) USING BTREE,
  INDEX `idx_status`(`per_status` ASC) USING BTREE,
  INDEX `idx_deleted_at`(`deleted_at` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 920733862755423293 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '权限规则表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of auth_permissions
-- ----------------------------
INSERT INTO `auth_permissions` VALUES (920733862755423246, 0, '首页仪表盘', 'dashboard:view', 'menu', '/dashboard', '', 'el-icon-s-home', 100, 1, '2026-07-23 08:00:35', '2026-07-23 08:00:35', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423247, 0, '内容管理', 'content:view', 'menu', '/content', '', 'el-icon-document-copy', 90, 1, '2026-07-23 08:00:35', '2026-07-23 08:00:35', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423248, 0, '运营管理', 'operation:view', 'menu', '/operation', '', 'el-icon-present', 80, 1, '2026-07-23 08:00:35', '2026-07-23 08:00:35', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423249, 0, '系统管理', 'system:view', 'menu', '/system', '', 'el-icon-setting', 70, 1, '2026-07-23 08:00:35', '2026-07-23 08:00:35', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423250, 0, '书签管理', 'bookmark:view', 'menu', '/bookmark', '', 'el-icon-star-off', 60, 1, '2026-07-23 08:00:35', '2026-07-23 08:00:35', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423251, 920733862755423247, '文章管理', 'article:view', 'menu', '/content/article', '', 'el-icon-edit-outline', 10, 1, '2026-07-23 08:00:35', '2026-07-23 08:00:35', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423252, 920733862755423247, '分类管理', 'category:view', 'menu', '/content/category', '', 'el-icon-folder-opened', 9, 1, '2026-07-23 08:00:35', '2026-07-23 08:00:35', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423253, 920733862755423247, '友情链接', 'friendlink:view', 'menu', '/content/friendlink', '', 'el-icon-link', 8, 1, '2026-07-23 08:00:35', '2026-07-23 08:00:35', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423254, 920733862755423248, '横幅管理', 'banner:view', 'menu', '/operation/banner', '', 'el-icon-picture', 20, 1, '2026-07-23 08:00:35', '2026-07-23 08:00:35', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423255, 920733862755423248, '广告位管理', 'ad:view', 'menu', '/operation/ad', '', 'el-icon-office-building', 19, 1, '2026-07-23 08:00:35', '2026-07-23 08:00:35', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423256, 920733862755423248, '留言管理', 'feedback:view', 'menu', '/operation/feedback', '', 'el-icon-chat-line-round', 18, 1, '2026-07-23 08:00:35', '2026-07-23 08:00:35', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423257, 920733862755423249, '系统设置', 'config:view', 'menu', '/system/config', '', 'el-icon-tools', 30, 1, '2026-07-23 08:00:35', '2026-07-23 08:00:35', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423258, 920733862755423249, '菜单管理', 'menu:view', 'menu', '/system/menu', '', 'el-icon-menu', 29, 1, '2026-07-23 08:00:35', '2026-07-23 08:00:35', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423259, 920733862755423249, '权限管理', 'permission:view', 'menu', '/system/permission', '', 'el-icon-lock', 28, 1, '2026-07-23 08:00:35', '2026-07-23 08:00:35', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423260, 920733862755423249, '用户管理', 'user:view', 'menu', '/system/user', '', 'el-icon-user', 27, 1, '2026-07-23 08:00:35', '2026-07-23 08:00:35', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423261, 920733862755423249, '操作日志', 'log:view', 'menu', '/system/log', '', 'el-icon-document', 26, 1, '2026-07-23 08:00:35', '2026-07-23 08:00:35', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423262, 920733862755423250, '书签列表', 'bookmark:list', 'menu', '/bookmark/list', '', 'el-icon-star-on', 10, 1, '2026-07-23 08:00:35', '2026-07-23 08:00:35', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423263, 920733862755423250, '我的书签', 'bookmark:my', 'menu', '/bookmark/my', '', 'el-icon-collection', 9, 1, '2026-07-23 08:00:35', '2026-07-23 08:00:35', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423264, 920733862755423251, '文章列表', 'article:list', 'button', '/content/article/list', '', '', 10, 1, '2026-07-23 08:00:35', '2026-07-23 08:00:35', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423265, 920733862755423251, '添加文章', 'article:add', 'button', '/content/article/add', '', '', 9, 1, '2026-07-23 08:00:35', '2026-07-23 08:00:35', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423266, 920733862755423251, '编辑文章', 'article:edit', 'button', '/content/article/edit', '', '', 8, 1, '2026-07-23 08:00:35', '2026-07-23 08:00:35', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423267, 920733862755423251, '删除文章', 'article:delete', 'button', '/content/article/delete', '', '', 7, 1, '2026-07-23 08:00:36', '2026-07-23 08:00:36', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423268, 920733862755423252, '添加分类', 'category:add', 'button', '/content/category/add', '', '', 5, 1, '2026-07-23 08:00:36', '2026-07-23 08:00:36', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423269, 920733862755423252, '编辑分类', 'category:edit', 'button', '/content/category/edit', '', '', 4, 1, '2026-07-23 08:00:36', '2026-07-23 08:00:36', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423270, 920733862755423252, '删除分类', 'category:delete', 'button', '/content/category/delete', '', '', 3, 1, '2026-07-23 08:00:36', '2026-07-23 08:00:36', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423271, 920733862755423253, '添加友情链接', 'friendlink:add', 'button', '/content/friendlink/add', '', '', 5, 1, '2026-07-23 08:00:36', '2026-07-23 08:00:36', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423272, 920733862755423253, '编辑友情链接', 'friendlink:edit', 'button', '/content/friendlink/edit', '', '', 4, 1, '2026-07-23 08:00:36', '2026-07-23 08:00:36', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423273, 920733862755423253, '删除友情链接', 'friendlink:delete', 'button', '/content/friendlink/delete', '', '', 3, 1, '2026-07-23 08:00:36', '2026-07-23 08:00:36', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423274, 920733862755423254, '添加横幅', 'banner:add', 'button', '/operation/banner/add', '', '', 5, 1, '2026-07-23 08:00:36', '2026-07-23 08:00:36', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423275, 920733862755423254, '编辑横幅', 'banner:edit', 'button', '/operation/banner/edit', '', '', 4, 1, '2026-07-23 08:00:36', '2026-07-23 08:00:36', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423276, 920733862755423254, '删除横幅', 'banner:delete', 'button', '/operation/banner/delete', '', '', 3, 1, '2026-07-23 08:00:36', '2026-07-23 08:00:36', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423277, 920733862755423255, '添加广告位', 'ad:add', 'button', '/operation/ad/add', '', '', 5, 1, '2026-07-23 08:00:36', '2026-07-23 08:00:36', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423278, 920733862755423255, '编辑广告位', 'ad:edit', 'button', '/operation/ad/edit', '', '', 4, 1, '2026-07-23 08:00:36', '2026-07-23 08:00:36', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423279, 920733862755423255, '删除广告位', 'ad:delete', 'button', '/operation/ad/delete', '', '', 3, 1, '2026-07-23 08:00:36', '2026-07-23 08:00:36', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423280, 920733862755423256, '处理留言', 'feedback:handle', 'button', '/operation/feedback/handle', '', '', 5, 1, '2026-07-23 08:00:36', '2026-07-23 08:00:36', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423281, 920733862755423256, '删除留言', 'feedback:delete', 'button', '/operation/feedback/delete', '', '', 3, 1, '2026-07-23 08:00:36', '2026-07-23 08:00:36', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423282, 0, '角色管理', 'role:view', 'menu', '/system/permission/role', '', '', 10, 1, '2026-07-23 08:04:21', '2026-07-23 08:04:21', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423283, 920733862755423260, '添加用户', 'user:add', 'button', '/system/user/add', '', '', 10, 1, '2026-07-23 08:06:48', '2026-07-23 08:06:48', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423284, 920733862755423283, '添加用户API', 'api:user:add', 'api', '/api/user/add', 'POST', '', 5, 1, '2026-07-23 08:06:48', '2026-07-23 08:06:48', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423285, 920733862755423260, '编辑用户', 'user:edit', 'button', '/system/user/edit', '', '', 9, 1, '2026-07-23 08:06:48', '2026-07-23 08:06:48', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423286, 920733862755423285, '编辑用户API', 'api:user:edit', 'api', '/api/user/edit', 'PUT', '', 4, 1, '2026-07-23 08:06:48', '2026-07-23 08:06:48', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423287, 920733862755423260, '删除用户', 'user:delete', 'button', '/system/user/delete', '', '', 8, 1, '2026-07-23 08:06:48', '2026-07-23 08:06:48', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423288, 920733862755423287, '删除用户API', 'api:user:delete', 'api', '/api/user/delete', 'DELETE', '', 3, 1, '2026-07-23 08:06:48', '2026-07-23 08:06:48', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423289, 920733862755423264, '文章列表API', 'api:article:list', 'api', '/api/article/list', 'GET', '', 10, 1, '2026-07-23 08:07:06', '2026-07-23 08:07:06', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423290, 920733862755423265, '添加文章API', 'api:article:add', 'api', '/api/article/add', 'POST', '', 9, 1, '2026-07-23 08:07:06', '2026-07-23 08:07:06', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423291, 920733862755423266, '编辑文章API', 'api:article:edit', 'api', '/api/article/edit', 'PUT', '', 8, 1, '2026-07-23 08:07:06', '2026-07-23 08:07:06', NULL);
INSERT INTO `auth_permissions` VALUES (920733862755423292, 920733862755423267, '删除文章API', 'api:article:delete', 'api', '/api/article/delete', 'DELETE', '', 7, 1, '2026-07-23 08:07:06', '2026-07-23 08:07:06', NULL);

-- ----------------------------
-- Table structure for auth_role
-- ----------------------------
DROP TABLE IF EXISTS `auth_role`;
CREATE TABLE `auth_role`  (
  `id` bigint NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `role_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '角色名称 如 超级管理员',
  `role_code` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '角色唯一标识（代码鉴权使用，如 finance_admin）',
  `role_type` tinyint(1) NOT NULL DEFAULT 2 COMMENT '角色类型: 1=系统内置 2=用户自定义',
  `role_sort` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序号',
  `data_scope` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '数据权限范围 1全部数据 2本部门及下级 3本部门 4仅本人数据 5自定义指定部门',
  `scope_departments` json NULL COMMENT '指定部门IDs，JSON格式',
  `role_status` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '0禁用 1启用',
  `role_remark` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '角色备注',
  `created_at` datetime NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_role_code`(`role_code` ASC, `deleted_at` ASC) USING BTREE,
  INDEX `idx_status`(`role_status` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 920733860755423257 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '角色信息表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of auth_role
-- ----------------------------
INSERT INTO `auth_role` VALUES (920733860755423247, '超级管理员', 'super_admin', 1, 100, 1, NULL, 1, '拥有系统全部权限，包括系统设置、权限分配等最高级别操作', '2026-07-23 07:58:27', '2026-07-23 07:58:27', NULL);
INSERT INTO `auth_role` VALUES (920733860755423248, '系统管理员', 'system_admin', 1, 90, 1, NULL, 1, '负责系统运维、基础配置、用户管理、菜单权限分配', '2026-07-23 07:58:27', '2026-07-23 07:58:27', NULL);
INSERT INTO `auth_role` VALUES (920733860755423249, '内容管理员', 'content_admin', 1, 80, 2, NULL, 1, '负责内容管理（文章、分类、友情链接等），可管理本部门及下级内容', '2026-07-23 07:58:27', '2026-07-23 07:58:27', NULL);
INSERT INTO `auth_role` VALUES (920733860755423250, '运营管理员', 'operation_admin', 1, 70, 2, NULL, 1, '负责运营管理（横幅、广告位、留言等），可管理本部门及下级数据', '2026-07-23 07:58:27', '2026-07-23 07:58:27', NULL);
INSERT INTO `auth_role` VALUES (920733860755423251, '内容编辑', 'content_editor', 2, 60, 3, NULL, 1, '负责文章内容的编辑、发布、修改，仅可操作本部门数据', '2026-07-23 07:58:27', '2026-07-23 07:58:27', NULL);
INSERT INTO `auth_role` VALUES (920733860755423252, '运营专员', 'operation_specialist', 2, 50, 3, NULL, 1, '负责横幅、广告位、留言等日常运营操作，仅可操作本部门数据', '2026-07-23 07:58:27', '2026-07-23 07:58:27', NULL);
INSERT INTO `auth_role` VALUES (920733860755423253, '访客用户', 'guest_user', 2, 10, 4, NULL, 1, '仅可查看公开内容，无编辑权限，数据仅限本人相关', '2026-07-23 07:58:27', '2026-07-23 07:58:27', NULL);
INSERT INTO `auth_role` VALUES (920733860755423254, '部门经理', 'dept_manager', 2, 40, 2, NULL, 1, '可管理本部门及下级部门的所有数据，包括内容审核、运营审批等', '2026-07-23 07:58:27', '2026-07-23 07:58:27', NULL);
INSERT INTO `auth_role` VALUES (920733860755423255, '人事管理员', 'hr_admin', 2, 30, 2, NULL, 1, '负责招聘管理、用户管理、组织架构等，可管理本部门及下级数据', '2026-07-23 07:58:27', '2026-07-23 07:58:27', NULL);
INSERT INTO `auth_role` VALUES (920733860755423256, '财务管理员', 'finance_admin', 2, 20, 1, NULL, 1, '负责财务相关数据查看与管理，拥有全部财务数据权限', '2026-07-23 07:58:27', '2026-07-23 07:58:27', NULL);

-- ----------------------------
-- Table structure for auth_role_menus
-- ----------------------------
DROP TABLE IF EXISTS `auth_role_menus`;
CREATE TABLE `auth_role_menus`  (
  `role_id` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '角色ID（关联 auth_roles.id）',
  `menu_id` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '菜单ID（关联 auth_menus.id）',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`role_id`, `menu_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '角色-菜单关联表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of auth_role_menus
-- ----------------------------
INSERT INTO `auth_role_menus` VALUES (920733860755423247, 920733860755423001, '2026-07-23 08:25:35');
INSERT INTO `auth_role_menus` VALUES (920733860755423247, 920733860755423002, '2026-07-23 08:25:35');
INSERT INTO `auth_role_menus` VALUES (920733860755423247, 920733860755423003, '2026-07-23 08:25:35');
INSERT INTO `auth_role_menus` VALUES (920733860755423247, 920733860755423004, '2026-07-23 08:25:35');
INSERT INTO `auth_role_menus` VALUES (920733860755423247, 920733860755423005, '2026-07-23 08:25:35');
INSERT INTO `auth_role_menus` VALUES (920733860755423247, 920733860755423006, '2026-07-23 08:25:35');
INSERT INTO `auth_role_menus` VALUES (920733860755423247, 920733860755423007, '2026-07-23 08:25:35');
INSERT INTO `auth_role_menus` VALUES (920733860755423247, 920733860755423008, '2026-07-23 08:25:35');
INSERT INTO `auth_role_menus` VALUES (920733860755423247, 920733860755423009, '2026-07-23 08:25:35');
INSERT INTO `auth_role_menus` VALUES (920733860755423247, 920733860755423010, '2026-07-23 08:25:35');
INSERT INTO `auth_role_menus` VALUES (920733860755423247, 920733860755423011, '2026-07-23 08:25:35');
INSERT INTO `auth_role_menus` VALUES (920733860755423247, 920733860755423012, '2026-07-23 08:25:35');
INSERT INTO `auth_role_menus` VALUES (920733860755423247, 920733860755423013, '2026-07-23 08:25:35');
INSERT INTO `auth_role_menus` VALUES (920733860755423247, 920733860755423014, '2026-07-23 08:25:35');
INSERT INTO `auth_role_menus` VALUES (920733860755423247, 920733860755423015, '2026-07-23 08:25:35');
INSERT INTO `auth_role_menus` VALUES (920733860755423247, 920733860755423016, '2026-07-23 08:25:35');
INSERT INTO `auth_role_menus` VALUES (920733860755423247, 920733860755423017, '2026-07-23 08:25:35');
INSERT INTO `auth_role_menus` VALUES (920733860755423247, 920733860755423018, '2026-07-23 08:25:35');
INSERT INTO `auth_role_menus` VALUES (920733860755423247, 920733860755423019, '2026-07-23 08:25:35');
INSERT INTO `auth_role_menus` VALUES (920733860755423247, 920733860755423020, '2026-07-23 08:25:35');
INSERT INTO `auth_role_menus` VALUES (920733860755423247, 920733860755423021, '2026-07-23 08:25:35');
INSERT INTO `auth_role_menus` VALUES (920733860755423247, 920733860755423022, '2026-07-23 08:25:35');
INSERT INTO `auth_role_menus` VALUES (920733860755423247, 920733860755423023, '2026-07-23 08:25:35');
INSERT INTO `auth_role_menus` VALUES (920733860755423247, 920733860755423024, '2026-07-23 08:25:35');
INSERT INTO `auth_role_menus` VALUES (920733860755423247, 920733860755423025, '2026-07-23 08:25:35');
INSERT INTO `auth_role_menus` VALUES (920733860755423247, 920733860755423026, '2026-07-23 08:25:35');
INSERT INTO `auth_role_menus` VALUES (920733860755423247, 920733860755423027, '2026-07-23 08:25:35');
INSERT INTO `auth_role_menus` VALUES (920733860755423247, 920733860755423028, '2026-07-23 08:25:35');
INSERT INTO `auth_role_menus` VALUES (920733860755423247, 920733860755423029, '2026-07-23 08:25:35');

-- ----------------------------
-- Table structure for auth_role_permissions
-- ----------------------------
DROP TABLE IF EXISTS `auth_role_permissions`;
CREATE TABLE `auth_role_permissions`  (
  `role_id` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '角色ID（关联 auth_roles.id）',
  `permission_id` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '权限ID（关联 auth_permissions.id）',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`role_id`, `permission_id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '角色-权限关联表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of auth_role_permissions
-- ----------------------------
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423246, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423247, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423248, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423249, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423250, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423251, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423252, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423253, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423254, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423255, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423256, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423257, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423258, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423259, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423260, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423261, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423262, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423263, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423264, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423265, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423266, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423267, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423268, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423269, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423270, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423271, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423272, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423273, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423274, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423275, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423276, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423277, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423278, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423279, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423280, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423281, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423282, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423283, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423284, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423285, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423286, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423287, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423288, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423289, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423290, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423291, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423247, 920733862755423292, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423248, 920733862755423246, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423248, 920733862755423249, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423248, 920733862755423257, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423248, 920733862755423258, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423248, 920733862755423259, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423248, 920733862755423260, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423248, 920733862755423261, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423248, 920733862755423282, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423248, 920733862755423283, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423248, 920733862755423284, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423248, 920733862755423285, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423248, 920733862755423286, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423248, 920733862755423287, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423248, 920733862755423288, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423249, 920733862755423247, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423249, 920733862755423251, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423249, 920733862755423252, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423249, 920733862755423253, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423249, 920733862755423264, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423249, 920733862755423265, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423249, 920733862755423266, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423249, 920733862755423267, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423249, 920733862755423268, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423249, 920733862755423269, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423249, 920733862755423270, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423249, 920733862755423271, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423249, 920733862755423272, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423249, 920733862755423273, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423249, 920733862755423289, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423249, 920733862755423290, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423249, 920733862755423291, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423249, 920733862755423292, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423250, 920733862755423248, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423250, 920733862755423254, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423250, 920733862755423255, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423250, 920733862755423256, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423250, 920733862755423274, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423250, 920733862755423275, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423250, 920733862755423276, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423250, 920733862755423277, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423250, 920733862755423278, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423250, 920733862755423279, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423250, 920733862755423280, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423250, 920733862755423281, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423251, 920733862755423251, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423251, 920733862755423264, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423251, 920733862755423265, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423251, 920733862755423266, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423251, 920733862755423267, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423252, 920733862755423254, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423252, 920733862755423255, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423252, 920733862755423256, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423252, 920733862755423274, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423252, 920733862755423275, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423252, 920733862755423277, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423252, 920733862755423278, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423252, 920733862755423280, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423253, 920733862755423246, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423253, 920733862755423264, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423254, 920733862755423247, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423254, 920733862755423248, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423254, 920733862755423251, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423254, 920733862755423252, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423254, 920733862755423253, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423254, 920733862755423254, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423254, 920733862755423255, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423254, 920733862755423256, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423254, 920733862755423264, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423254, 920733862755423265, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423254, 920733862755423266, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423254, 920733862755423267, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423254, 920733862755423268, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423254, 920733862755423269, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423254, 920733862755423270, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423254, 920733862755423271, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423254, 920733862755423272, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423254, 920733862755423273, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423254, 920733862755423274, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423254, 920733862755423275, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423254, 920733862755423276, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423254, 920733862755423277, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423254, 920733862755423278, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423254, 920733862755423279, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423254, 920733862755423280, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423254, 920733862755423281, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423255, 920733862755423260, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423255, 920733862755423283, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423255, 920733862755423285, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423255, 920733862755423287, '2026-07-23 09:49:06');
INSERT INTO `auth_role_permissions` VALUES (920733860755423256, 920733862755423246, '2026-07-23 09:49:06');

-- ----------------------------
-- Table structure for auth_user_role
-- ----------------------------
DROP TABLE IF EXISTS `auth_user_role`;
CREATE TABLE `auth_user_role`  (
  `user_id` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户ID',
  `role_id` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '角色ID',
  `created_at` datetime NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  PRIMARY KEY (`user_id`, `role_id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 920733861755423246 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '用户-角色关联' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of auth_user_role
-- ----------------------------
INSERT INTO `auth_user_role` VALUES (920733860755423002, 920733860755423247, '2026-07-23 09:50:36');
INSERT INTO `auth_user_role` VALUES (920733860755423003, 920733860755423251, '2026-07-23 09:50:36');
INSERT INTO `auth_user_role` VALUES (920733860755423004, 920733860755423252, '2026-07-23 09:50:36');
INSERT INTO `auth_user_role` VALUES (920733860755423005, 920733860755423254, '2026-07-23 09:50:36');
INSERT INTO `auth_user_role` VALUES (920733860755423006, 920733860755423253, '2026-07-23 09:50:36');
INSERT INTO `auth_user_role` VALUES (920733860755423007, 920733860755423256, '2026-07-23 09:50:36');
INSERT INTO `auth_user_role` VALUES (920733860755423008, 920733860755423253, '2026-07-23 09:50:36');
INSERT INTO `auth_user_role` VALUES (934035802554576897, 920733860755423247, '2026-07-23 05:32:44');
INSERT INTO `auth_user_role` VALUES (934035802554576897, 920733860755423248, '2026-07-23 09:50:36');

-- ----------------------------
-- Table structure for book_mark
-- ----------------------------
DROP TABLE IF EXISTS `book_mark`;
CREATE TABLE `book_mark`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键ID',
  `category_id` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '所属分类ID，关联 category 表，0表示未分类/默认书签栏',
  `short_title` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '书签短标题',
  `book_title` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '书签长标题',
  `book_url` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '书签链接地址',
  `book_favicon` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '网站图标URL',
  `book_desc` varchar(1024) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '书签描述/备注',
  `sort_order` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序权重，值越小越靠前',
  `status` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态：0-隐藏，1-正常，2-失效',
  `is_bold` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '显示：0-加粗，1-正常',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `created_by` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '创建人',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_category_sort`(`category_id` ASC, `sort_order` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 934041315296100356 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '书签表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of book_mark
-- ----------------------------
INSERT INTO `book_mark` VALUES (934041315296100352, 935126090643668998, '豆包', '字节-豆包', 'https://www.doubao.com/chat/', '', '', 0, 1, 1, '2026-07-22 02:33:48', 0, '2026-07-22 02:35:35');
INSERT INTO `book_mark` VALUES (934041315296100353, 935126090643668998, 'deepseek', 'Deepseek', 'https://chat.deepseek.com/', '', '', 0, 1, 0, '2026-07-22 02:37:10', 0, '2026-07-22 02:37:10');
INSERT INTO `book_mark` VALUES (934041315296100354, 935126090643668998, '千问', '阿里巴巴-千问', 'https://www.qianwen.com/chat/', '', '', 0, 1, 0, '2026-07-22 02:37:44', 0, '2026-07-22 02:38:13');
INSERT INTO `book_mark` VALUES (934041315296100355, 935126090643668993, '11', '22', 'http://192.168.124.87:8080/backend/category', 'sdsdsds', '2026-07-23 19:12:52', 22, 0, 0, '2026-07-23 19:13:07', 934035802554576897, '2026-07-23 11:13:37');

-- ----------------------------
-- Table structure for boss_job
-- ----------------------------
DROP TABLE IF EXISTS `boss_job`;
CREATE TABLE `boss_job`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `job_title` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '职位名称',
  `department` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '所属部门',
  `workplace` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '工作地点',
  `experience` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT '' COMMENT '经验要求',
  `education` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT '' COMMENT '学历要求',
  `salary_range` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT '' COMMENT '薪资范围',
  `description` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL COMMENT '职位描述',
  `requirements` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL COMMENT '任职要求',
  `benefits` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL COMMENT '福利待遇',
  `is_hot` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '是否急聘',
  `job_status` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '1=待发布，2=发布中，3=已关闭',
  `expire_at` datetime NULL DEFAULT NULL COMMENT '过期时间',
  `view_count` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '浏览量',
  `job_sort` int UNSIGNED NOT NULL DEFAULT 0,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `deleted_at` datetime NULL DEFAULT NULL,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 920733863004423259 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '招聘职位表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of boss_job
-- ----------------------------
INSERT INTO `boss_job` VALUES (920733863004423246, 'PHP高级开发工程师', '技术研发中心', '深圳南山区科技园', '3-5年', '本科', '25K-40K·14薪', '1. 负责公司核心业务系统的架构设计与开发；\n2. 参与技术方案评审，制定技术规范；\n3. 攻克技术难点，优化系统性能；\n4. 指导初中级开发工程师，带领团队完成项目交付。', '1. 本科及以上学历，计算机相关专业，3年以上PHP开发经验；\n2. 精通PHP 8 + Hyperf / ThinkPHP 等主流框架；\n3. 熟悉MySQL数据库设计、索引优化、SQL调优；\n4. 熟悉Redis、RabbitMQ等中间件，有高并发项目经验；\n5. 熟悉Linux环境，掌握Docker容器化部署；\n6. 具备良好的代码规范和团队协作能力。', '五险一金、补充医疗保险、年度体检、弹性工作、餐补交通补、年度旅游、技术培训基金、年终奖', 1, 2, '2026-12-31 23:59:59', 1680, 100, '2026-07-23 09:23:10', '2026-07-23 09:23:10', NULL);
INSERT INTO `boss_job` VALUES (920733863004423247, '前端开发工程师', '技术研发中心', '深圳南山区科技园', '2-4年', '本科', '18K-30K·14薪', '1. 负责公司产品Web端、移动端页面开发与维护；\n2. 与产品、UI、后端团队紧密协作，高效完成功能迭代；\n3. 参与前端技术选型和架构优化；\n4. 关注前端前沿技术，推动团队技术升级。', '1. 本科及以上学历，2年以上前端开发经验；\n2. 精通HTML5、CSS3、JavaScript，熟悉ES6+语法；\n3. 熟悉Vue 3 / React 等主流框架，有实际项目经验；\n4. 熟悉Vite / Webpack等构建工具；\n5. 了解HTTP协议，具备跨端、响应式开发能力；\n6. 有TypeScript、小程序开发经验者优先。', '五险一金、弹性工作、年度体检、餐补交通补、技术培训基金、年终奖', 0, 2, '2026-10-31 23:59:59', 952, 90, '2026-07-23 09:23:10', '2026-07-23 09:23:10', NULL);
INSERT INTO `boss_job` VALUES (920733863004423248, '数据库工程师（DBA）', '技术研发中心', '深圳南山区科技园', '3-5年', '本科', '22K-35K·14薪', '1. 负责公司MySQL数据库的日常运维、性能调优和高可用架构设计；\n2. 制定数据库备份、恢复策略，保障数据安全；\n3. 参与数据库架构设计评审，提供专业建议；\n4. 建立数据库监控体系，提前发现并解决性能瓶颈。', '1. 本科及以上学历，3年以上MySQL DBA经验；\n2. 精通MySQL体系结构，熟悉InnoDB存储引擎；\n3. 掌握主从复制、分库分表、读写分离等架构方案；\n4. 熟悉Linux运维，掌握Shell/Python脚本开发；\n5. 有TiDB、OceanBase等分布式数据库经验者优先。', '五险一金、年度体检、技术培训基金、年终奖、弹性工作', 0, 2, '2026-09-30 23:59:59', 486, 80, '2026-07-23 09:23:10', '2026-07-23 09:23:10', NULL);
INSERT INTO `boss_job` VALUES (920733863004423249, '产品经理', '产品中心', '深圳南山区科技园', '3-5年', '本科', '20K-35K·14薪', '1. 负责公司核心产品的需求调研、产品规划和功能设计；\n2. 撰写产品需求文档（PRD），协调设计、研发、测试团队推进产品迭代；\n3. 跟踪产品上线数据，分析用户行为，持续优化产品体验；\n4. 关注行业动态和竞品分析，制定产品差异化策略。', '1. 本科及以上学历，3年以上产品经理经验；\n2. 具备优秀的逻辑思维和沟通协调能力；\n3. 熟练使用Axure、Figma、XMind等产品设计工具；\n4. 有ToB企业服务或SaaS产品经验者优先；\n5. 具备数据分析能力，能通过数据驱动产品决策。', '五险一金、弹性工作、年度体检、产品学习基金、年终奖', 0, 2, '2026-11-30 23:59:59', 723, 85, '2026-07-23 09:23:10', '2026-07-23 09:23:10', NULL);
INSERT INTO `boss_job` VALUES (920733863004423250, 'UI/UX设计师', '产品中心', '深圳南山区科技园', '2-4年', '本科', '15K-25K·14薪', '1. 负责公司Web端、移动端产品的UI/UX设计工作；\n2. 参与产品需求评审，从设计角度提出用户体验优化方案；\n3. 建立和维护设计规范体系，保证产品视觉一致性；\n4. 跟踪设计落地效果，持续优化产品交互体验。', '1. 本科及以上学历，设计相关专业，2年以上UI/UX设计经验；\n2. 熟练使用Figma、Sketch、Photoshop、Illustrator等设计工具；\n3. 具备良好的视觉设计能力和交互设计思维；\n4. 熟悉Web/iOS/Android等平台设计规范；\n5. 有ToB产品设计经验者优先。', '五险一金、弹性工作、年度体检、设计培训基金、年终奖', 0, 2, '2026-10-15 23:59:59', 634, 80, '2026-07-23 09:23:10', '2026-07-23 09:23:10', NULL);
INSERT INTO `boss_job` VALUES (920733863004423251, '内容运营经理', '市场运营中心', '深圳南山区科技园', '2-4年', '本科', '15K-25K·14薪', '1. 负责公司网站、公众号、视频号等平台的内容规划和运营；\n2. 策划和撰写高质量的行业文章、产品软文和品牌内容；\n3. 制定内容传播策略，提升品牌影响力和用户活跃度；\n4. 跟踪内容数据，不断优化内容方向和运营策略。', '1. 本科及以上学历，2年以上内容运营经验；\n2. 具备优秀的文字功底和内容策划能力；\n3. 熟悉微信公众号、视频号、知乎等主流内容平台玩法；\n4. 有SEO内容运营经验者优先；\n5. 具备数据分析能力，能通过数据优化运营策略。', '五险一金、弹性工作、年度体检、年度旅游、年终奖', 0, 2, '2026-09-30 23:59:59', 458, 70, '2026-07-23 09:23:10', '2026-07-23 09:23:10', NULL);
INSERT INTO `boss_job` VALUES (920733863004423252, '新媒体运营专员', '市场运营中心', '深圳南山区科技园', '1-3年', '大专', '8K-15K·13薪', '1. 负责公司微信、微博、抖音、小红书等新媒体平台日常运营；\n2. 策划并执行新媒体内容选题、撰写和发布；\n3. 与粉丝互动，维护社群活跃度；\n4. 跟踪新媒体数据，定期输出运营分析报告。', '1. 大专及以上学历，1年以上新媒体运营经验；\n2. 熟悉主流新媒体平台规则和玩法；\n3. 具备基础的图文编辑和短视频制作能力；\n4. 思维活跃，有创意，对热点敏感；\n5. 有成功的个人账号或爆款内容案例者优先。', '五险一金、弹性工作、年度体检、年终奖', 0, 2, '2026-08-31 23:59:59', 892, 65, '2026-07-23 09:23:10', '2026-07-23 09:23:10', NULL);
INSERT INTO `boss_job` VALUES (920733863004423253, '大客户销售经理', '销售中心', '深圳南山区科技园', '3-5年', '本科', '15K-30K·提成', '1. 负责公司核心产品的大客户开拓与销售工作；\n2. 制定销售策略，完成年度销售目标；\n3. 维护重点客户关系，挖掘客户深度需求；\n4. 收集市场情报，反馈客户需求，协助产品优化。', '1. 本科及以上学历，3年以上B2B大客户销售经验；\n2. 具备优秀的沟通表达和商务谈判能力；\n3. 有企业服务、SaaS或软件行业客户资源者优先；\n4. 抗压能力强，能适应短期出差。', '五险一金、提成上不封顶、年度体检、交通补贴、通讯补贴、年终奖', 1, 2, '2026-12-31 23:59:59', 1206, 90, '2026-07-23 09:23:10', '2026-07-23 09:23:10', NULL);
INSERT INTO `boss_job` VALUES (920733863004423254, '渠道商务专员', '销售中心', '深圳南山区科技园', '1-3年', '大专', '8K-15K·提成', '1. 负责公司产品渠道合作伙伴的拓展与维护；\n2. 制定渠道合作方案，推进渠道签约和落地执行；\n3. 为合作伙伴提供产品培训和销售支持；\n4. 跟踪渠道业绩，定期输出渠道运营报告。', '1. 大专及以上学历，1年以上渠道或商务拓展经验；\n2. 具备良好的沟通协调能力和团队合作精神；\n3. 有IT、软件、互联网行业渠道经验者优先；\n4. 熟练使用Office办公软件，能独立完成商务方案。', '五险一金、提成上不封顶、年度体检、年终奖', 0, 2, '2026-09-15 23:59:59', 367, 70, '2026-07-23 09:23:10', '2026-07-23 09:23:10', NULL);
INSERT INTO `boss_job` VALUES (920733863004423255, '财务主管', '财务中心', '深圳南山区科技园', '5-8年', '本科', '18K-28K·14薪', '1. 负责公司全盘账务处理，编制财务报表；\n2. 制定和完善财务管理制度和流程；\n3. 负责税务筹划和申报工作；\n4. 参与公司年度预算编制和成本管控；\n5. 配合外部审计和内部审计工作。', '1. 本科及以上学历，财务、会计相关专业，5年以上财务经验；\n2. 持有CPA或中级会计师证书；\n3. 熟悉国家财税法规和企业会计准则；\n4. 熟悉金蝶/用友等财务软件；\n5. 有互联网或科技行业财务经验者优先。', '五险一金、年度体检、弹性工作、年终奖', 0, 2, '2026-08-31 23:59:59', 284, 70, '2026-07-23 09:23:10', '2026-07-23 09:23:10', NULL);
INSERT INTO `boss_job` VALUES (920733863004423256, '招聘专员', '人力资源中心', '深圳南山区科技园', '1-3年', '本科', '8K-14K·13薪', '1. 负责公司各部门岗位的招聘全流程工作；\n2. 开拓和维护招聘渠道，优化人才库；\n3. 参与校园招聘和雇主品牌建设；\n4. 跟踪招聘数据，定期输出招聘分析报告。', '1. 本科及以上学历，人力资源、心理学等相关专业；\n2. 1年以上招聘经验，有互联网或科技行业招聘经验者优先；\n3. 具备良好的沟通能力和面试技巧；\n4. 熟悉招聘平台（BOSS直聘、拉勾、猎聘等）的使用；\n5. 积极主动，具备较强的执行力和抗压能力。', '五险一金、年度体检、弹性工作、年终奖', 0, 2, '2026-09-30 23:59:59', 523, 65, '2026-07-23 09:23:10', '2026-07-23 09:23:10', NULL);
INSERT INTO `boss_job` VALUES (920733863004423257, '行政前台', '行政管理中心', '深圳南山区科技园', '1-3年', '大专', '6K-9K·13薪', '1. 负责公司前台接待、电话接听和访客引导；\n2. 负责办公用品采购、发放和库存管理；\n3. 协助组织公司活动、会议和团建；\n4. 负责公司办公环境维护和日常行政事务处理。', '1. 大专及以上学历，1年以上行政或前台经验；\n2. 形象气质佳，沟通表达能力强；\n3. 熟练使用Office办公软件；\n4. 具备良好的服务意识和团队合作精神。', '五险一金、年度体检、节日福利、年终奖', 0, 2, '2026-08-15 23:59:59', 1086, 60, '2026-07-23 09:23:10', '2026-07-23 09:23:10', NULL);
INSERT INTO `boss_job` VALUES (920733863004423258, 'PHP开发实习生', '技术研发中心', '深圳南山区科技园', '在校生/应届生', '本科', '4K-6K', '1. 参与公司核心业务系统的功能开发和维护；\n2. 在导师指导下完成模块编码、单元测试和文档编写；\n3. 参与技术方案讨论和代码评审；\n4. 学习掌握公司技术栈和开发规范。', '1. 本科及以上学历，计算机相关专业在读或应届毕业生；\n2. 掌握PHP基础语法，了解至少一个主流PHP框架；\n3. 熟悉HTML/CSS/JavaScript基础知识；\n4. 熟悉MySQL基础操作；\n5. 学习能力强，有良好的团队协作精神。', '1对1导师带教、餐补交通补、可转正、实习证明', 0, 2, '2026-08-31 23:59:59', 1432, 55, '2026-07-23 09:23:10', '2026-07-23 09:23:10', NULL);

-- ----------------------------
-- Table structure for category
-- ----------------------------
DROP TABLE IF EXISTS `category`;
CREATE TABLE `category`  (
  `id` bigint UNSIGNED NOT NULL COMMENT '主键(雪花ID)',
  `category_name` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '分类名称',
  `parent_id` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '父级分类ID 0是一级分类',
  `show_type` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '可见性类型 0=全部可见 1=指定客户可见 2=指定客户不可见',
  `cat_status` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '状态 0=隐藏 1=显示',
  `level` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '级别 1一级 2二级 3三级',
  `sort_order` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `description` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '分类描述/SEO说明',
  `cat_remark` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '备注',
  `created_at` datetime NULL DEFAULT NULL COMMENT '创建时间',
  `created_by` bigint UNSIGNED NULL DEFAULT NULL COMMENT '创建人',
  `updated_at` datetime NULL DEFAULT NULL COMMENT '更新时间',
  `updated_by` bigint UNSIGNED NULL DEFAULT NULL COMMENT '更新人',
  `deleted_at` datetime NULL DEFAULT NULL COMMENT '删除时间',
  `deleted_by` bigint UNSIGNED NULL DEFAULT NULL COMMENT '删除人',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `category_parent_id_index`(`parent_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '分类表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of category
-- ----------------------------
INSERT INTO `category` VALUES (935126090643668993, '视频', 0, 0, 1, 1, 0, '视频网站', '视频网站', '2026-07-22 10:26:47', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` VALUES (935126090643668994, '技术栈', 0, 0, 1, 1, 0, '程序开发技术栈', '技术栈', '2026-07-22 10:27:44', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` VALUES (935126090643668995, '电商', 0, 0, 1, 1, 0, '淘宝京东拼多多', '电商网站', '2026-07-22 10:28:46', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` VALUES (935126090643668996, '公司', 0, 0, 1, 1, 0, '自定义公司本地', '公司', '2026-07-22 10:29:40', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` VALUES (935126090643668997, '工具', 0, 0, 1, 1, 0, '工具合集', '工具', '2026-07-22 10:30:33', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` VALUES (935126090643668998, 'AI', 0, 0, 1, 1, 0, 'AI工具', 'AI', '2026-07-22 10:31:04', NULL, NULL, NULL, NULL, NULL);
INSERT INTO `category` VALUES (935126090643668999, '搜索', 0, 0, 1, 1, 0, '各类搜索网盘', '网盘搜索', '2026-07-22 10:31:46', NULL, NULL, NULL, NULL, NULL);

-- ----------------------------
-- Table structure for feedbacks
-- ----------------------------
DROP TABLE IF EXISTS `feedbacks`;
CREATE TABLE `feedbacks`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `fb_name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '联系人姓名',
  `fb_phone` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT '' COMMENT '联系电话',
  `fb_email` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT '' COMMENT '邮箱',
  `fb_company` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL DEFAULT '' COMMENT '公司名称',
  `fb_title` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '留言标题',
  `fb_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL COMMENT '留言内容',
  `fb_status` tinyint UNSIGNED NOT NULL DEFAULT 0 COMMENT '0=未处理，1=已处理',
  `reply_content` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL COMMENT '回复内容',
  `replied_at` datetime NULL DEFAULT NULL COMMENT '回复时间',
  `ip` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'IP地址',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_status`(`fb_status` ASC) USING BTREE,
  INDEX `idx_created_at`(`created_at` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 920733863054423256 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '用户留言表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of feedbacks
-- ----------------------------
INSERT INTO `feedbacks` VALUES (920733863054423246, '张伟', '13812345678', 'zhangwei@qq.com', '深圳科技有限公司', '咨询企业版产品功能与报价', '你好，我公司目前正在选型企业级管理系统，看到贵公司的产品介绍后很感兴趣。请问企业版是否支持多租户？能否提供一份详细的功能清单和报价方案？我们预计采购50个用户，希望能在本月底前完成选型。', 0, NULL, NULL, '192.168.1.100', '2026-07-22 09:30:00', '2026-07-23 09:51:53');
INSERT INTO `feedbacks` VALUES (920733863054423247, '李娜', '15987654321', 'lina@partner.com', '上海云创科技', '寻求渠道合作机会', '我司是专业的IT解决方案服务商，主要服务于华东地区的制造企业。看到贵公司的产品在行业内口碑很好，希望能洽谈渠道代理合作。请安排相关负责人与我联系，谢谢！', 0, NULL, NULL, '10.0.1.50', '2026-07-22 14:20:00', '2026-07-23 09:51:53');
INSERT INTO `feedbacks` VALUES (920733863054423248, '王小明', '13524681357', 'wangxm@tech.com', '北京创新科技', '系统升级后数据无法同步', '我们使用的是贵公司产品3.0版本，上周升级后，发现部分数据无法正常同步到云端。已尝试重启服务但问题依旧，麻烦尽快安排技术人员协助排查，我们这边业务受到了影响。', 0, NULL, NULL, '172.16.0.30', '2026-07-21 16:45:00', '2026-07-23 09:51:53');
INSERT INTO `feedbacks` VALUES (920733863054423249, '陈静', '13698765432', 'chenjing@mail.com', '广州越秀集团', '建议增加多语言支持功能', '我们是跨国企业，目前贵公司产品仅支持中英文，建议在后续版本中增加对日语和韩语的支持，方便我们海外团队使用。如果能提供多语言切换功能就更好了。期待产品越来越好！', 0, NULL, NULL, '192.168.2.80', '2026-07-21 11:00:00', '2026-07-23 09:51:53');
INSERT INTO `feedbacks` VALUES (920733863054423250, '刘洋', '15234567890', 'liuyang@outlook.com', '', '咨询PHP开发岗位招聘信息', '您好！我看到贵公司招聘PHP高级开发工程师，我拥有6年PHP开发经验，熟悉Hyperf和ThinkPHP框架。想了解该岗位是否还在招人？以及面试流程是怎样的？期待您的回复！', 0, NULL, NULL, '10.0.2.15', '2026-07-20 09:00:00', '2026-07-23 09:51:53');
INSERT INTO `feedbacks` VALUES (920733863054423251, '赵磊', '13765432198', 'zhaolei@abc.com', '杭州云创科技', '咨询产品价格与部署方案', '我司正在寻找一套企业级CRM解决方案，想咨询贵公司产品的价格体系和部署方式。同时希望能安排一次在线演示，让我们团队了解一下产品的实际使用体验。', 1, '尊敬的赵先生，您好！感谢您对产品的关注。相关产品资料和报价方案已发送至您的邮箱，并已安排销售顾问于明日10:00与您联系，届时将为您做详细的产品演示。如有其他问题，可随时联系我们的客服热线。祝工作顺利！', '2026-07-21 17:30:00', '192.168.3.20', '2026-07-19 15:00:00', '2026-07-23 09:51:53');
INSERT INTO `feedbacks` VALUES (920733863054423252, '孙婷', '15824681357', 'sunting@qq.com', '成都互联科技', '希望能成为贵公司渠道伙伴', '我们公司在西南地区深耕企业服务多年，拥有超过500家客户资源。希望能成为贵公司在西南地区的渠道合作伙伴，共同开拓市场。请告知合作条件和流程。', 1, '尊敬的孙总，您好！非常欢迎合作意向。我们已安排渠道部负责人在今日内与您联系，详细沟通合作细节。同时，相关合作政策已发至您的邮箱，请您查收。期待我们的合作！', '2026-07-21 10:00:00', '192.168.4.40', '2026-07-19 11:30:00', '2026-07-23 09:51:53');
INSERT INTO `feedbacks` VALUES (920733863054423253, '钱森', '13987654321', 'qiansen@tech.com', '苏州智能制造', '产品使用遇到性能瓶颈', '我们是贵公司的老客户，使用产品已有一年多。最近随着业务量增长，系统响应速度明显变慢。请问是否有性能优化的方案或建议？希望能得到专业指导。', 1, '钱先生，您好！感谢您长期以来的支持。针对您反馈的性能问题，建议从以下几个方面进行优化：1. 检查数据库索引配置；2. 开启缓存功能；3. 升级到最新版本（3.1版本已对性能有较大提升）。我们已在后台为您的账户开启了高级技术支持通道，相关优化文档也已发送至您的邮箱。如有疑问可随时联系我们。', '2026-07-20 14:30:00', '10.0.3.60', '2026-07-18 08:30:00', '2026-07-23 09:51:53');
INSERT INTO `feedbacks` VALUES (920733863054423254, '周玥', '15012345678', 'zhouyue@mail.com', '厦门科技集团', '产品体验很好，特别感谢', '您好！我是贵公司的产品用户，最近使用产品感觉非常好，界面简洁，功能实用，极大提升了我们的工作效率。感谢你们团队做出这么优秀的产品！特此留言表达感谢。', 1, '周女士，您好！非常感谢您的认可和鼓励。客户的满意是我们最大的动力！我们会继续打磨产品，为您提供更好的使用体验。如有任何建议或需求，欢迎随时联系我们。祝您工作愉快！', '2026-07-20 09:00:00', '192.168.5.70', '2026-07-17 16:00:00', '2026-07-23 09:51:53');
INSERT INTO `feedbacks` VALUES (920733863054423255, '吴强', '18965432109', 'wuqiang@tech.com', '武汉高新科技', '咨询产品定制化开发服务', '我公司需要一套定制化的企业管理系统，想咨询贵公司是否接受定制化开发？一般定制周期和费用大概是怎样的？希望能得到专业的解答。', 1, '吴先生，您好！感谢您的咨询。关于定制化开发服务，已有专人整理详细方案并发送至您的邮箱。销售经理会在明日与您电话沟通具体需求细节。请注意查收邮件，感谢您的关注！', '2026-07-19 16:00:00', '172.16.1.25', '2026-07-16 10:30:00', '2026-07-23 09:51:53');

-- ----------------------------
-- Table structure for friend_links
-- ----------------------------
DROP TABLE IF EXISTS `friend_links`;
CREATE TABLE `friend_links`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `link_name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '网站名称',
  `link_url` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '网站链接',
  `link_logo` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '网站Logo',
  `link_desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '网站描述',
  `link_sort` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序越小越前',
  `link_status` tinyint UNSIGNED NOT NULL DEFAULT 1 COMMENT '0=禁用，1=启用',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 920733863055413256 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '友情链接表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of friend_links
-- ----------------------------
INSERT INTO `friend_links` VALUES (920733863055413246, '百度', 'https://www.baidu.com', '/uploads/friendlinks/baidu.png', '全球最大的中文搜索引擎', 1, 1, '2026-07-23 09:24:33', '2026-07-23 09:24:33');
INSERT INTO `friend_links` VALUES (920733863055413247, '腾讯云', 'https://cloud.tencent.com', '/uploads/friendlinks/tencent-cloud.png', '腾讯云 - 产业智变，云启未来', 2, 1, '2026-07-23 09:24:33', '2026-07-23 09:24:33');
INSERT INTO `friend_links` VALUES (920733863055413248, '阿里云', 'https://www.aliyun.com', '/uploads/friendlinks/aliyun.png', '阿里云 - 上云就上阿里云', 3, 1, '2026-07-23 09:24:33', '2026-07-23 09:24:33');
INSERT INTO `friend_links` VALUES (920733863055413249, '华为云', 'https://www.huaweicloud.com', '/uploads/friendlinks/huawei-cloud.png', '华为云 - 选择华为云，让您的业务更上一层楼', 4, 1, '2026-07-23 09:24:33', '2026-07-23 09:24:33');
INSERT INTO `friend_links` VALUES (920733863055413250, 'CSDN', 'https://www.csdn.net', '/uploads/friendlinks/csdn.png', 'CSDN - 专业开发者技术社区', 5, 1, '2026-07-23 09:24:33', '2026-07-23 09:24:33');
INSERT INTO `friend_links` VALUES (920733863055413251, '掘金', 'https://juejin.cn', '/uploads/friendlinks/juejin.png', '掘金 - 一个帮助开发者成长的社区', 6, 1, '2026-07-23 09:24:33', '2026-07-23 09:24:33');
INSERT INTO `friend_links` VALUES (920733863055413252, '开源中国', 'https://www.oschina.net', '/uploads/friendlinks/oschina.png', '开源中国 - 中国最大的开源技术社区', 7, 1, '2026-07-23 09:24:33', '2026-07-23 09:24:33');
INSERT INTO `friend_links` VALUES (920733863055413253, 'SegmentFault 思否', 'https://segmentfault.com', '/uploads/friendlinks/segmentfault.png', 'SegmentFault - 技术问答社区', 8, 1, '2026-07-23 09:24:33', '2026-07-23 09:24:33');
INSERT INTO `friend_links` VALUES (920733863055413254, '牛客网', 'https://www.nowcoder.com', '/uploads/friendlinks/nowcoder.png', '牛客网 - 求职招聘与校招笔试面试平台', 9, 1, '2026-07-23 09:24:33', '2026-07-23 09:24:33');
INSERT INTO `friend_links` VALUES (920733863055413255, '拉勾网', 'https://www.lagou.com', '/uploads/friendlinks/lagou.png', '拉勾网 - 专注互联网招聘的招聘平台', 10, 1, '2026-07-23 09:24:33', '2026-07-23 09:24:33');

-- ----------------------------
-- Table structure for operation_log
-- ----------------------------
DROP TABLE IF EXISTS `operation_log`;
CREATE TABLE `operation_log`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '主键(雪花ID)',
  `operator_id` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '操作人ID',
  `operator_name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '操作人名称',
  `biz_type` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '业务模块类型 product/category/customer',
  `activity_type` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '活动类型如product_created',
  `action` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '操作类型 (INSERT/UPDATE/DELETE/LOGIN)',
  `biz_id` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '目标实体ID',
  `biz_label` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '高亮展示文本',
  `old_value` json NULL COMMENT '修改前的数据快照 (JSON格式)',
  `new_value` json NULL COMMENT '修改后的数据快照 (JSON格式)',
  `operator_status` tinyint NOT NULL DEFAULT 1 COMMENT '操作状态 (0:失败, 1:成功)',
  `error_msg` varchar(2048) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '错误信息 (失败时记录)',
  `client_ip` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '客户端IP',
  `user_agent` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户浏览器/设备信息',
  `request_url` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '触发日志的API URL',
  `method_fun` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '触发日志的方法名',
  `created_at` datetime(6) NULL DEFAULT NULL COMMENT '发生时间',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `merchant_activity_log_operator_id_index`(`operator_id` ASC) USING BTREE,
  INDEX `merchant_activity_log_biz_index`(`biz_type` ASC, `biz_id` ASC) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 935126090643669049 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '用户操作动态表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of operation_log
-- ----------------------------
INSERT INTO `operation_log` VALUES (935126090643668993, 0, '', '', '', '', 0, '', NULL, NULL, 1, '', '', '', '', '', NULL);
INSERT INTO `operation_log` VALUES (935126090643668994, 934035802554576897, '匿名用户', 'backend.index', 'backend.index_view', 'VIEW', 0, '', NULL, '[]', 1, '', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '/backend/index', 'backend.Index::index', '2026-07-23 20:30:15.000000');
INSERT INTO `operation_log` VALUES (935126090643668995, 934035802554576897, '匿名用户', 'backend.index', 'backend.index_view', 'VIEW', 0, '', NULL, '[]', 0, 'success', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '/backend/index/logs', 'backend.Index::logs', '2026-07-23 20:30:16.000000');
INSERT INTO `operation_log` VALUES (935126090643668996, 934035802554576897, '匿名用户', 'backend.index', 'backend.index_view', 'VIEW', 0, '', NULL, '[]', 1, '', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '/backend/index', 'backend.Index::index', '2026-07-23 20:31:45.000000');
INSERT INTO `operation_log` VALUES (935126090643668997, 934035802554576897, '匿名用户', 'backend.index', 'backend.index_view', 'VIEW', 0, '', NULL, '[]', 0, 'success', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '/backend/index/logs', 'backend.Index::logs', '2026-07-23 20:31:46.000000');
INSERT INTO `operation_log` VALUES (935126090643668998, 934035802554576897, '匿名用户', 'backend.bossjob', 'backend.bossjob_view', 'VIEW', 0, '', NULL, '[]', 1, '', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '/backend/bossjob', 'backend.Backendbossjob::index', '2026-07-23 20:31:58.000000');
INSERT INTO `operation_log` VALUES (935126090643668999, 934035802554576897, '匿名用户', 'backend.bossjob', 'backend.bossjob_view', 'VIEW', 0, '', NULL, '{\"page\": \"1\", \"limit\": \"20\"}', 0, 'success', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '/backend/bossjob/list?page=1&limit=20', 'backend.Backendbossjob::list', '2026-07-23 20:31:59.000000');
INSERT INTO `operation_log` VALUES (935126090643669000, 934035802554576897, '匿名用户', 'backend.bossjob', 'backend.bossjob_view', 'VIEW', 0, '', NULL, '[]', 1, '', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '/backend/bossjob/add', 'backend.Backendbossjob::add', '2026-07-23 20:32:00.000000');
INSERT INTO `operation_log` VALUES (935126090643669001, 934035802554576897, '匿名用户', 'backend.bossjob', 'backend.bossjob_view', 'VIEW', 0, '', NULL, '{\"page\": \"1\", \"limit\": \"20\"}', 0, 'success', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '/backend/bossjob/list?page=1&limit=20', 'backend.Backendbossjob::list', '2026-07-23 20:32:03.000000');
INSERT INTO `operation_log` VALUES (935126090643669002, 934035802554576897, '匿名用户', 'backend.index', 'backend.index_view', 'VIEW', 0, '', NULL, '[]', 1, '', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/index', 'backend.Index::index', '2026-07-23 20:32:41.000000');
INSERT INTO `operation_log` VALUES (935126090643669003, 934035802554576897, '匿名用户', 'backend.index', 'backend.index_view', 'VIEW', 0, '', NULL, '[]', 0, 'success', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/index/logs', 'backend.Index::logs', '2026-07-23 20:32:41.000000');
INSERT INTO `operation_log` VALUES (935126090643669004, 934035802554576897, '匿名用户', 'backend.index', 'backend.index_view', 'VIEW', 0, '', NULL, '[]', 1, '', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/index', 'backend.Index::index', '2026-07-23 20:32:46.000000');
INSERT INTO `operation_log` VALUES (935126090643669005, 934035802554576897, '匿名用户', 'backend.index', 'backend.index_view', 'VIEW', 0, '', NULL, '[]', 0, 'success', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/index/sysinfo', 'backend.Index::sysinfo', '2026-07-23 20:32:47.000000');
INSERT INTO `operation_log` VALUES (935126090643669006, 934035802554576897, '匿名用户', 'backend.index', 'backend.index_view', 'VIEW', 0, '', NULL, '[]', 0, 'success', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/index/logs', 'backend.Index::logs', '2026-07-23 20:32:47.000000');
INSERT INTO `operation_log` VALUES (935126090643669007, 934035802554576897, '匿名用户', 'backend.bookmark', 'backend.bookmark_view', 'VIEW', 0, '', NULL, '[]', 1, '', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/bookmark/index', 'backend.Backendbookmark::index', '2026-07-23 20:32:48.000000');
INSERT INTO `operation_log` VALUES (935126090643669008, 934035802554576897, '匿名用户', 'backend.bookmark', 'backend.bookmark_view', 'VIEW', 0, '', NULL, '{\"page\": \"1\", \"limit\": \"10\"}', 0, 'success', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/bookmark/list?page=1&limit=10', 'backend.Backendbookmark::list', '2026-07-23 20:32:49.000000');
INSERT INTO `operation_log` VALUES (935126090643669009, 934035802554576897, '匿名用户', 'backend.user', 'backend.user_view', 'VIEW', 0, '', NULL, '[]', 1, '', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/user', 'backend.Backenduser::index', '2026-07-23 20:33:27.000000');
INSERT INTO `operation_log` VALUES (935126090643669010, 934035802554576897, '匿名用户', 'backend.user', 'backend.user_view', 'VIEW', 0, '', NULL, '{\"page\": \"1\", \"limit\": \"10\"}', 0, 'success', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/user/list?page=1&limit=10', 'backend.Backenduser::list', '2026-07-23 20:33:28.000000');
INSERT INTO `operation_log` VALUES (935126090643669011, 934035802554576897, '匿名用户', 'backend.user', 'backend.user_view', 'VIEW', 0, '', NULL, '{\"page\": \"2\", \"limit\": \"10\"}', 0, 'success', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/user/list?page=2&limit=10', 'backend.Backenduser::list', '2026-07-23 20:34:00.000000');
INSERT INTO `operation_log` VALUES (935126090643669012, 934035802554576897, '匿名用户', 'backend.user', 'backend.user_view', 'VIEW', 0, '', NULL, '{\"page\": \"1\", \"limit\": \"10\"}', 0, 'success', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/user/list?page=1&limit=10', 'backend.Backenduser::list', '2026-07-23 20:34:04.000000');
INSERT INTO `operation_log` VALUES (935126090643669013, 934035802554576897, '匿名用户', 'backend.menu', 'backend.menu_view', 'VIEW', 0, '', NULL, '[]', 1, '', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/menu', 'backend.Menu::index', '2026-07-23 20:34:13.000000');
INSERT INTO `operation_log` VALUES (935126090643669014, 934035802554576897, '匿名用户', 'backend.menu', 'backend.menu_view', 'VIEW', 0, '', NULL, '{\"page\": \"1\", \"limit\": \"10\"}', 0, 'success', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/menu/list?page=1&limit=10', 'backend.Menu::list', '2026-07-23 20:34:14.000000');
INSERT INTO `operation_log` VALUES (935126090643669015, 934035802554576897, '匿名用户', 'backend.index', 'backend.index_view', 'VIEW', 0, '', NULL, '[]', 1, '', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/index', 'backend.Index::index', '2026-07-23 20:35:19.000000');
INSERT INTO `operation_log` VALUES (935126090643669016, 934035802554576897, '匿名用户', 'backend.index', 'backend.index_view', 'VIEW', 0, '', NULL, '[]', 0, 'success', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/index/logs', 'backend.Index::logs', '2026-07-23 20:35:19.000000');
INSERT INTO `operation_log` VALUES (935126090643669017, 934035802554576897, '匿名用户', 'backend.user', 'backend.user_view', 'VIEW', 0, '', NULL, '[]', 1, '', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/user', 'backend.Backenduser::index', '2026-07-23 20:35:24.000000');
INSERT INTO `operation_log` VALUES (935126090643669018, 934035802554576897, '匿名用户', 'backend.user', 'backend.user_view', 'VIEW', 0, '', NULL, '{\"page\": \"1\", \"limit\": \"10\"}', 0, 'success', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/user/list?page=1&limit=10', 'backend.Backenduser::list', '2026-07-23 20:35:26.000000');
INSERT INTO `operation_log` VALUES (935126090643669019, 934035802554576897, '匿名用户', 'backend.menu', 'backend.menu_view', 'VIEW', 0, '', NULL, '[]', 1, '', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/menu', 'backend.Menu::index', '2026-07-23 20:35:34.000000');
INSERT INTO `operation_log` VALUES (935126090643669020, 934035802554576897, '匿名用户', 'backend.menu', 'backend.menu_view', 'VIEW', 0, '', NULL, '{\"page\": \"1\", \"limit\": \"10\"}', 0, 'success', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/menu/list?page=1&limit=10', 'backend.Menu::list', '2026-07-23 20:35:35.000000');
INSERT INTO `operation_log` VALUES (935126090643669021, 934035802554576897, '匿名用户', 'backend.bookmark', 'backend.bookmark_view', 'VIEW', 0, '', NULL, '[]', 1, '', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/bookmark/index', 'backend.Backendbookmark::index', '2026-07-23 20:35:41.000000');
INSERT INTO `operation_log` VALUES (935126090643669022, 934035802554576897, '匿名用户', 'backend.bookmark', 'backend.bookmark_view', 'VIEW', 0, '', NULL, '{\"page\": \"1\", \"limit\": \"10\"}', 0, 'success', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/bookmark/list?page=1&limit=10', 'backend.Backendbookmark::list', '2026-07-23 20:35:42.000000');
INSERT INTO `operation_log` VALUES (935126090643669023, 934035802554576897, '匿名用户', 'backend.index', 'backend.index_view', 'VIEW', 0, '', NULL, '[]', 1, '', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/index', 'backend.Index::index', '2026-07-23 20:35:47.000000');
INSERT INTO `operation_log` VALUES (935126090643669024, 934035802554576897, '匿名用户', 'backend.index', 'backend.index_view', 'VIEW', 0, '', NULL, '[]', 0, 'success', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/index/logs', 'backend.Index::logs', '2026-07-23 20:35:48.000000');
INSERT INTO `operation_log` VALUES (935126090643669025, 934035802554576897, '匿名用户', 'backend.bossjob', 'backend.bossjob_view', 'VIEW', 0, '', NULL, '[]', 1, '', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/bossjob', 'backend.Backendbossjob::index', '2026-07-23 20:35:51.000000');
INSERT INTO `operation_log` VALUES (935126090643669026, 934035802554576897, '匿名用户', 'backend.bossjob', 'backend.bossjob_view', 'VIEW', 0, '', NULL, '{\"page\": \"1\", \"limit\": \"20\"}', 0, 'success', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/bossjob/list?page=1&limit=20', 'backend.Backendbossjob::list', '2026-07-23 20:35:51.000000');
INSERT INTO `operation_log` VALUES (935126090643669027, 934035802554576897, '匿名用户', 'backend.index', 'backend.index_view', 'VIEW', 0, '', NULL, '[]', 1, '', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '/backend/index', 'backend.Index::index', '2026-07-23 20:35:56.000000');
INSERT INTO `operation_log` VALUES (935126090643669028, 934035802554576897, '匿名用户', 'backend.index', 'backend.index_view', 'VIEW', 0, '', NULL, '[]', 0, 'success', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36', '/backend/index/logs', 'backend.Index::logs', '2026-07-23 20:35:57.000000');
INSERT INTO `operation_log` VALUES (935126090643669029, 934035802554576897, '匿名用户', 'backend.index', 'backend.index_view', 'VIEW', 0, '', NULL, '[]', 1, '', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/index', 'backend.Index::index', '2026-07-23 20:36:34.000000');
INSERT INTO `operation_log` VALUES (935126090643669030, 934035802554576897, '匿名用户', 'backend.index', 'backend.index_view', 'VIEW', 0, '', NULL, '[]', 0, 'success', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/index/logs', 'backend.Index::logs', '2026-07-23 20:36:35.000000');
INSERT INTO `operation_log` VALUES (935126090643669031, 934035802554576897, '匿名用户', 'backend.bookmark', 'backend.bookmark_view', 'VIEW', 0, '', NULL, '[]', 1, '', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/bookmark/index', 'backend.Backendbookmark::index', '2026-07-23 20:36:38.000000');
INSERT INTO `operation_log` VALUES (935126090643669032, 934035802554576897, '匿名用户', 'backend.bookmark', 'backend.bookmark_view', 'VIEW', 0, '', NULL, '{\"page\": \"1\", \"limit\": \"10\"}', 0, 'success', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/bookmark/list?page=1&limit=10', 'backend.Backendbookmark::list', '2026-07-23 20:36:38.000000');
INSERT INTO `operation_log` VALUES (935126090643669033, 934035802554576897, '匿名用户', 'backend.user', 'backend.user_view', 'VIEW', 0, '', NULL, '[]', 1, '', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/user', 'backend.Backenduser::index', '2026-07-23 20:36:44.000000');
INSERT INTO `operation_log` VALUES (935126090643669034, 934035802554576897, '匿名用户', 'backend.user', 'backend.user_view', 'VIEW', 0, '', NULL, '{\"page\": \"1\", \"limit\": \"10\"}', 0, 'success', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/user/list?page=1&limit=10', 'backend.Backenduser::list', '2026-07-23 20:36:46.000000');
INSERT INTO `operation_log` VALUES (935126090643669035, 934035802554576897, '匿名用户', 'backend.menu', 'backend.menu_view', 'VIEW', 0, '', NULL, '[]', 1, '', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/menu', 'backend.Menu::index', '2026-07-23 20:36:48.000000');
INSERT INTO `operation_log` VALUES (935126090643669036, 934035802554576897, '匿名用户', 'backend.menu', 'backend.menu_view', 'VIEW', 0, '', NULL, '{\"page\": \"1\", \"limit\": \"10\"}', 0, 'success', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/menu/list?page=1&limit=10', 'backend.Menu::list', '2026-07-23 20:36:49.000000');
INSERT INTO `operation_log` VALUES (935126090643669037, 934035802554576897, '匿名用户', 'backend.bossjob', 'backend.bossjob_view', 'VIEW', 0, '', NULL, '[]', 1, '', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/bossjob', 'backend.Backendbossjob::index', '2026-07-23 20:36:56.000000');
INSERT INTO `operation_log` VALUES (935126090643669038, 934035802554576897, '匿名用户', 'backend.bossjob', 'backend.bossjob_view', 'VIEW', 0, '', NULL, '{\"page\": \"1\", \"limit\": \"20\"}', 0, 'success', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/bossjob/list?page=1&limit=20', 'backend.Backendbossjob::list', '2026-07-23 20:36:57.000000');
INSERT INTO `operation_log` VALUES (935126090643669039, 934035802554576897, '匿名用户', 'backend.index', 'backend.index_view', 'VIEW', 0, '', NULL, '[]', 1, '', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/index', 'backend.Index::index', '2026-07-23 20:39:28.000000');
INSERT INTO `operation_log` VALUES (935126090643669040, 934035802554576897, '匿名用户', 'backend.index', 'backend.index_view', 'VIEW', 0, '', NULL, '[]', 0, 'success', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/index/logs', 'backend.Index::logs', '2026-07-23 20:39:29.000000');
INSERT INTO `operation_log` VALUES (935126090643669041, 934035802554576897, '匿名用户', 'backend.bookmark', 'backend.bookmark_view', 'VIEW', 0, '', NULL, '[]', 1, '', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/bookmark/index', 'backend.Backendbookmark::index', '2026-07-23 20:39:40.000000');
INSERT INTO `operation_log` VALUES (935126090643669042, 934035802554576897, '匿名用户', 'backend.bookmark', 'backend.bookmark_view', 'VIEW', 0, '', NULL, '{\"page\": \"1\", \"limit\": \"10\"}', 0, 'success', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/bookmark/list?page=1&limit=10', 'backend.Backendbookmark::list', '2026-07-23 20:39:41.000000');
INSERT INTO `operation_log` VALUES (935126090643669043, 934035802554576897, '匿名用户', 'backend.index', 'backend.index_view', 'VIEW', 0, '', NULL, '[]', 1, '', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/index', 'backend.Index::index', '2026-07-23 20:41:15.000000');
INSERT INTO `operation_log` VALUES (935126090643669044, 934035802554576897, '匿名用户', 'backend.index', 'backend.index_view', 'VIEW', 0, '', NULL, '[]', 0, 'success', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/index/logs', 'backend.Index::logs', '2026-07-23 20:41:15.000000');
INSERT INTO `operation_log` VALUES (935126090643669045, 934035802554576897, '匿名用户', 'backend.category', 'backend.category_view', 'VIEW', 0, '', NULL, '[]', 1, '', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/category', 'backend.Backendcategory::index', '2026-07-23 20:41:29.000000');
INSERT INTO `operation_log` VALUES (935126090643669046, 934035802554576897, '匿名用户', 'backend.category', 'backend.category_view', 'VIEW', 0, '', NULL, '[]', 0, 'success', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/category/list', 'backend.Backendcategory::list', '2026-07-23 20:41:30.000000');
INSERT INTO `operation_log` VALUES (935126090643669047, 934035802554576897, '匿名用户', 'backend.bookmark', 'backend.bookmark_view', 'VIEW', 0, '', NULL, '[]', 1, '', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/bookmark/index', 'backend.Backendbookmark::index', '2026-07-23 20:42:22.000000');
INSERT INTO `operation_log` VALUES (935126090643669048, 934035802554576897, '匿名用户', 'backend.bookmark', 'backend.bookmark_view', 'VIEW', 0, '', NULL, '{\"page\": \"1\", \"limit\": \"10\"}', 0, 'success', '192.168.124.87', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/150.0.0.0 Safari/537.36 Edg/150.0.0.0', '/backend/bookmark/list?page=1&limit=10', 'backend.Backendbookmark::list', '2026-07-23 20:42:23.000000');

-- ----------------------------
-- Table structure for site_configs
-- ----------------------------
DROP TABLE IF EXISTS `site_configs`;
CREATE TABLE `site_configs`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `conf_group` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'basic' COMMENT '配置分组：basic, seo, contact, social',
  `conf_key` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '配置键名',
  `conf_value` text CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NULL COMMENT '配置值',
  `conf_desc` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '配置说明',
  `input_type` varchar(30) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT 'text' COMMENT '输入类型：text, textarea, image, file, json',
  `conf_sort` int UNSIGNED NOT NULL DEFAULT 0 COMMENT '排序',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 920733863044423255 CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '站点配置表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of site_configs
-- ----------------------------
INSERT INTO `site_configs` VALUES (920733863044423246, 'basic', 'site_name', '企业官网', '站点名称', 'text', 0, '2026-07-23 07:30:43', '2026-07-23 07:30:43');
INSERT INTO `site_configs` VALUES (920733863044423247, 'basic', 'site_title', '企业官网 - 值得信赖的品牌', '站点标题', 'text', 0, '2026-07-23 07:30:43', '2026-07-23 07:30:43');
INSERT INTO `site_configs` VALUES (920733863044423248, 'basic', 'site_keywords', '企业官网,门户网站,品牌', '站点关键词', 'text', 0, '2026-07-23 07:30:43', '2026-07-23 07:30:43');
INSERT INTO `site_configs` VALUES (920733863044423249, 'basic', 'site_description', '企业官网是一家专注于...', '站点描述', 'textarea', 0, '2026-07-23 07:30:43', '2026-07-23 07:30:43');
INSERT INTO `site_configs` VALUES (920733863044423250, 'contact', 'phone', '0755-12345678', '联系电话', 'text', 0, '2026-07-23 07:30:43', '2026-07-23 07:30:43');
INSERT INTO `site_configs` VALUES (920733863044423251, 'contact', 'email', 'info@company.com', '邮箱地址', 'text', 0, '2026-07-23 07:30:43', '2026-07-23 07:30:43');
INSERT INTO `site_configs` VALUES (920733863044423252, 'contact', 'address', '深圳市南山区科技园', '公司地址', 'text', 0, '2026-07-23 07:30:43', '2026-07-23 07:30:43');
INSERT INTO `site_configs` VALUES (920733863044423253, 'social', 'wechat', 'company_wechat', '微信公众号', 'text', 0, '2026-07-23 07:30:43', '2026-07-23 07:30:43');
INSERT INTO `site_configs` VALUES (920733863044423254, 'social', 'weibo', 'company_weibo', '微博地址', 'text', 0, '2026-07-23 07:30:43', '2026-07-23 07:30:43');

-- ----------------------------
-- Table structure for user_account
-- ----------------------------
DROP TABLE IF EXISTS `user_account`;
CREATE TABLE `user_account`  (
  `id` bigint UNSIGNED NOT NULL COMMENT '用户唯一主键ID（雪花ID，不自增，分布式安全）',
  `user_name` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '账号用户名，唯一，可用于登录',
  `user_mobile` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '手机号，唯一索引，登录首选',
  `user_email` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '邮箱，唯一索引，找回密码',
  `password_hash` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'BCrypt/Argon2加密密码，禁止明文存储',
  `password_salt` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '自定义盐值（BCrypt自带盐可留空）',
  `user_status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '账号状态：0-禁用 1-正常 2-冻结 3-注销',
  `lock_reason` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '冻结/封禁原因（风控、违规、人工封禁）',
  `lock_expire_time` datetime NULL DEFAULT NULL COMMENT '限时冻结到期时间，NULL=永久封禁',
  `last_login_ip` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '最后登录IP',
  `last_login_region` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT 'IP归属地',
  `last_login_at` datetime NULL DEFAULT NULL COMMENT '最后登录时间',
  `register_ip` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '注册IP',
  `register_device` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '注册设备标识',
  `register_channel` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'web' COMMENT '注册渠道：web/app/mini/ios/android',
  `real_auth_status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '实名状态：0未实名 1待审核 2已实名 3实名驳回',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  `deleted_at` datetime NULL DEFAULT NULL COMMENT '删除时间（软删除记录）',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_username`(`user_name` ASC) USING BTREE,
  UNIQUE INDEX `uk_mobile`(`user_mobile` ASC) USING BTREE,
  UNIQUE INDEX `uk_email`(`user_email` ASC) USING BTREE,
  INDEX `idx_status_auth`(`user_status` ASC, `real_auth_status` ASC) USING BTREE,
  INDEX `idx_deleted_time`(`created_at` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '用户账号主表｜登录、安全、状态核心数据' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user_account
-- ----------------------------
INSERT INTO `user_account` VALUES (920733860755423001, 'admin123', '13800000001', 'admin@company.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '', 1, '', NULL, '192.168.1.100', '广东深圳', '2026-07-23 09:00:00', '192.168.1.1', 'Chrome/Windows', 'web', 2, '2026-01-01 00:00:00', '2026-07-23 09:00:00', NULL);
INSERT INTO `user_account` VALUES (920733860755423002, 'super_admin', '13800000002', 'super@company.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '', 1, '', NULL, '192.168.1.101', '广东深圳', '2026-07-22 18:00:00', '192.168.1.1', 'Chrome/Mac', 'web', 2, '2026-01-02 00:00:00', '2026-07-22 18:00:00', NULL);
INSERT INTO `user_account` VALUES (920733860755423003, 'editor_zhang', '13800000003', 'zhangwei@company.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '', 1, '123456', NULL, '192.168.1.102', '广东广州', '2026-07-23 10:00:00', '192.168.1.2', 'Safari/iPhone', 'app', 2, '2026-02-01 00:00:00', '2026-07-23 09:27:04', NULL);
INSERT INTO `user_account` VALUES (920733860755423004, 'ops_li', '13800000004', 'liming@company.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '', 1, '123456', NULL, '10.0.0.5', '广东深圳', '2026-07-22 16:00:00', '10.0.0.1', 'Chrome/Windows', 'web', 2, '2026-03-01 00:00:00', '2026-07-23 09:27:05', NULL);
INSERT INTO `user_account` VALUES (920733860755423005, 'pm_wang', '13800000005', 'wangfang@company.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '', 1, '123456', NULL, '172.16.0.10', '上海浦东', '2026-07-21 14:00:00', '172.16.0.1', 'Edge/Windows', 'mini', 1, '2026-04-01 00:00:00', '2026-07-23 09:27:05', NULL);
INSERT INTO `user_account` VALUES (920733860755423006, 'sales_chen', '13800000006', 'chenjun@company.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '', 1, '123456', NULL, '192.168.2.15', '北京朝阳', '2026-07-20 11:00:00', '192.168.2.1', 'Chrome/Mac', 'ios', 0, '2026-05-01 00:00:00', '2026-07-23 09:27:06', NULL);
INSERT INTO `user_account` VALUES (920733860755423007, 'finance_lin', '13800000007', 'linna@company.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '', 1, '123456', NULL, '10.0.1.20', '广东深圳', '2026-07-19 09:30:00', '10.0.1.1', 'Firefox/Windows', 'web', 1, '2026-06-01 00:00:00', '2026-07-23 09:27:06', NULL);
INSERT INTO `user_account` VALUES (920733860755423008, 'intern_huang', '13800000008', 'huangxiao@company.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '', 1, '123456', NULL, '192.168.3.30', '广东广州', '2026-07-18 08:00:00', '192.168.3.1', 'Safari/iPhone', 'app', 0, '2026-07-01 00:00:00', '2026-07-23 09:27:08', NULL);
INSERT INTO `user_account` VALUES (920733860755423009, 'former_zhao', '13800000009', 'zhaolei@company.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '', 2, '因违反公司信息安全规定，账号临时冻结', '2026-08-31 23:59:59', '192.168.4.50', '广东深圳', '2026-07-10 15:00:00', '192.168.4.1', 'Chrome/Windows', 'web', 2, '2026-01-15 00:00:00', '2026-07-10 15:00:00', NULL);
INSERT INTO `user_account` VALUES (920733860755423010, 'former_lu', '13800000010', 'luyang@company.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '', 3, '用户主动注销', NULL, '192.168.5.60', '浙江杭州', '2026-06-01 10:00:00', '192.168.5.1', 'Chrome/Mac', 'web', 2, '2025-12-01 00:00:00', '2026-06-01 10:00:00', '2026-06-01 10:00:00');
INSERT INTO `user_account` VALUES (934035802554576896, 'sunny', '13026661119', 'itpeeg@gmail.com', '$2y$10$RTRidRM2EtMIH6pAhsula..8xqM84yh9CPqN3/5pX5JpKP9vscA9e', 'salt', 1, '', NULL, '', '', NULL, '', '', 'web', 0, '2026-07-22 02:39:20', '2026-07-23 04:50:10', NULL);
INSERT INTO `user_account` VALUES (934035802554576897, 'admin', '13800138001', 'admin@example.com', '$2y$10$RTRidRM2EtMIH6pAhsula..8xqM84yh9CPqN3/5pX5JpKP9vscA9e', 'salt', 1, '', NULL, '192.168.124.87', '', NULL, '', '', 'web', 2, '2026-07-23 02:21:16', '2026-07-23 12:50:24', NULL);
INSERT INTO `user_account` VALUES (934035802554576898, 'testuser', '13800138002', 'test@example.com', '$2y$10$RTRidRM2EtMIH6pAhsula..8xqM84yh9CPqN3/5pX5JpKP9vscA9e', 'salt', 1, '', NULL, '', '', NULL, '', '', 'web', 1, '2026-07-23 02:21:47', '2026-07-23 04:50:13', NULL);

-- ----------------------------
-- Table structure for user_login_record
-- ----------------------------
DROP TABLE IF EXISTS `user_login_record`;
CREATE TABLE `user_login_record`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '用户id',
  `login_type` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1账号密码 2短信验证码 3扫码 4三方登录',
  `login_status` tinyint(1) NOT NULL DEFAULT 1 COMMENT '0失败 1成功',
  `ip_addr` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '登陆ip地址',
  `ip_region` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'ip地域',
  `device_info` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '设备型号、系统版本',
  `user_agent` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '登陆agent',
  `login_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `idx_user_time`(`user_id` ASC, `login_at` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '用户登录日志审计表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user_login_record
-- ----------------------------

-- ----------------------------
-- Table structure for user_profile
-- ----------------------------
DROP TABLE IF EXISTS `user_profile`;
CREATE TABLE `user_profile`  (
  `id` bigint UNSIGNED NOT NULL COMMENT '与user_account.id一一对应，一对一绑定',
  `nick_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户昵称',
  `avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '头像CDN地址',
  `gender` tinyint NOT NULL DEFAULT 0 COMMENT '0未知 1男 2女',
  `birthday` date NULL DEFAULT NULL COMMENT '生日',
  `signature` varchar(512) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '个性签名',
  `country` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '国家',
  `province` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '省份',
  `city` varchar(32) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '城市',
  `background_img` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '个人主页背景图',
  `extra_json` json NULL COMMENT '扩展预留字段（身高、职业、偏好等动态字段）',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_unicode_ci COMMENT = '用户基础资料附表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user_profile
-- ----------------------------

-- ----------------------------
-- Table structure for user_real_info
-- ----------------------------
DROP TABLE IF EXISTS `user_real_info`;
CREATE TABLE `user_real_info`  (
  `id` bigint UNSIGNED NOT NULL,
  `real_name` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'AES加密：真实姓名',
  `id_card_no` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'AES加密：身份证号码',
  `id_card_front` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '身份证正面文件CDN',
  `id_card_back` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '身份证反面文件CDN',
  `audit_admin_id` bigint NOT NULL DEFAULT 0 COMMENT '审核管理员ID',
  `audit_remark` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '审核备注',
  `audit_at` datetime NULL DEFAULT NULL COMMENT '审核时间',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '用户实名认证敏感信息表｜数据加密存储' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user_real_info
-- ----------------------------

-- ----------------------------
-- Table structure for user_third_bind
-- ----------------------------
DROP TABLE IF EXISTS `user_third_bind`;
CREATE TABLE `user_third_bind`  (
  `id` bigint UNSIGNED NOT NULL AUTO_INCREMENT,
  `user_id` bigint UNSIGNED NOT NULL DEFAULT 0 COMMENT '关联用户ID',
  `third_type` varchar(16) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT 'wechat/qq/alipay/douyin',
  `third_openid` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '三方唯一openid',
  `third_unionid` varchar(128) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '微信unionid',
  `third_nickname` varchar(64) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '第三方昵称',
  `third_avatar` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci NOT NULL DEFAULT '' COMMENT '第三方头像',
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted_at` datetime NULL DEFAULT NULL COMMENT '解绑时间',
  PRIMARY KEY (`id`) USING BTREE,
  UNIQUE INDEX `uk_type_openid`(`third_type` ASC, `third_openid` ASC) USING BTREE,
  INDEX `idx_user_id`(`user_id` ASC) USING BTREE
) ENGINE = InnoDB CHARACTER SET = utf8mb4 COLLATE = utf8mb4_0900_ai_ci COMMENT = '第三方账号绑定表' ROW_FORMAT = Dynamic;

-- ----------------------------
-- Records of user_third_bind
-- ----------------------------

SET FOREIGN_KEY_CHECKS = 1;
