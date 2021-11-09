
DROP TABLE IF EXISTS `hd_admin`;

CREATE TABLE `hd_admin` (
  `admin_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` mediumint(8) unsigned NOT NULL,
  `account` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `updated_at` int(10) unsigned NOT NULL,
  `updated_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `idx_shop_account` (`shop_id`,`account`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_banner` */

DROP TABLE IF EXISTS `hd_banner`;

CREATE TABLE `hd_banner` (
  `banner_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` mediumint(8) unsigned NOT NULL,
  `title` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `code` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `banner_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '广告状态：0-关闭，1-开启',
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_at` int(10) unsigned NOT NULL,
  `updated_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`banner_id`),
  KEY `idx_shop_id` (`shop_id`),
  KEY `idx_code` (`code`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_banner_image` */

DROP TABLE IF EXISTS `hd_banner_image`;

CREATE TABLE `hd_banner_image` (
  `banner_image_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `banner_id` int(10) unsigned NOT NULL,
  `shop_id` mediumint(8) unsigned NOT NULL,
  `image_path` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '从小到大排序',
  `is_new_window` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否打开新窗口：0-否，1-是',
  `window_link` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `updated_at` int(10) unsigned NOT NULL,
  `updated_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`banner_image_id`),
  KEY `idx_shop_banner` (`shop_id`,`banner_id`),
  KEY `idx_sort` (`sort`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_config` */

DROP TABLE IF EXISTS `hd_config`;

CREATE TABLE `hd_config` (
  `config_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` mediumint(8) unsigned NOT NULL,
  `config_group` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `config_key` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `config_value` text COLLATE utf8mb4_unicode_ci,
  `value_type` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `config_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `updated_at` int(10) unsigned NOT NULL,
  `updated_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`config_id`),
  UNIQUE KEY `idx_shop_key` (`shop_id`,`config_key`),
  KEY `idx_shop_group` (`shop_id`,`config_group`)
) ENGINE=InnoDB AUTO_INCREMENT=355 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_country` */

DROP TABLE IF EXISTS `hd_country`;

CREATE TABLE `hd_country` (
  `pk_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '仅作为主键需要，无实际业务意义',
  `shop_id` mediumint(8) unsigned NOT NULL,
  `country_id` smallint(5) unsigned NOT NULL,
  `country_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `iso_code_2` char(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '国家两位编码',
  `iso_code_3` char(3) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '国家三位编码',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '从小到大排序',
  `icon_path` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '国旗图标路径',
  `is_high_risk` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `currency_code` char(4) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD' COMMENT '国家使用货币',
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `updated_at` int(10) unsigned NOT NULL,
  `updated_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`pk_id`),
  UNIQUE KEY `idx_shop_country` (`shop_id`,`country_id`),
  KEY `idx_code2` (`iso_code_2`),
  KEY `idx_sort` (`sort`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_currency` */

DROP TABLE IF EXISTS `hd_currency`;

CREATE TABLE `hd_currency` (
  `currency_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` mediumint(8) unsigned NOT NULL,
  `currency_name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_code` char(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `symbol_left` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '币种符号，位于数字左边的',
  `symbol_right` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '币种符号，位于数字右边的',
  `decimal_point` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '.' COMMENT '小数点符号，默认为点号',
  `thousands_point` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ',' COMMENT '千位点符号，默认为逗号',
  `value` decimal(16,8) unsigned NOT NULL,
  `decimal_places` tinyint(3) unsigned NOT NULL COMMENT '小数点保留位置',
  `icon_path` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '币种图标路径',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '从小到大排序',
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `updated_at` int(10) unsigned NOT NULL,
  `updated_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`currency_id`),
  KEY `idx_shop_code` (`shop_id`,`currency_code`),
  KEY `idx_sort` (`sort`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_customer` */

DROP TABLE IF EXISTS `hd_customer`;

CREATE TABLE `hd_customer` (
  `customer_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` mediumint(8) unsigned NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `first_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `last_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `shipping_address_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `billing_address_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `ip_number` int(10) unsigned NOT NULL COMMENT '用户IP地址，转成数字保存',
  `ip_country_iso_code_2` char(2) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '用户IP所属国家的两位编码',
  `host_from` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户来源站点 HOST',
  `device_from` char(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '用户来源终端设备：PC-电脑端，M-移动端，BG-后台',
  `customer_type` char(8) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal' COMMENT '用户类型：normal-正常用户，testing-测试用户',
  `is_guest` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否为游客：0-否，1-是',
  `current_cart_skus` text COLLATE utf8mb4_unicode_ci COMMENT '用户当前购物车商品SKU数组序列',
  `logined_failure_count` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `logined_at` int(10) unsigned NOT NULL DEFAULT '0',
  `registered_at` int(10) unsigned NOT NULL DEFAULT '0',
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `updated_at` int(10) unsigned NOT NULL,
  `updated_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`customer_id`),
  UNIQUE KEY `idx_shop_email` (`shop_id`,`email`),
  KEY `idx_customer_type` (`customer_type`),
  KEY `idx_is_guest` (`is_guest`),
  KEY `idx_registered` (`registered_at`)
) ENGINE=InnoDB AUTO_INCREMENT=18 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci CHECKSUM=1;

/*Table structure for table `hd_customer_address` */

DROP TABLE IF EXISTS `hd_customer_address`;

CREATE TABLE `hd_customer_address` (
  `customer_address_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` mediumint(8) unsigned NOT NULL,
  `customer_id` bigint(20) unsigned NOT NULL,
  `address_type` char(8) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '地址类型：shipping-货运地址，billing-账单地址',
  `first_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `last_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `street_address` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `street_address_sub` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `postcode` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `city` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `zone_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `zone_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `country_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `updated_at` int(10) unsigned NOT NULL,
  `updated_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`customer_address_id`),
  KEY `idx_customer_id` (`customer_id`),
  KEY `idx_shop_id` (`shop_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_customer_service` */

DROP TABLE IF EXISTS `hd_customer_service`;

CREATE TABLE `hd_customer_service` (
  `customer_service_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` mediumint(8) unsigned NOT NULL,
  `customer_id` bigint(20) unsigned NOT NULL DEFAULT '0',
  `customer_name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `service_type` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '服务类型：pre-售前，after-售后',
  `order_time` tinyint(3) unsigned NOT NULL DEFAULT '0',
  `order_number` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `question` text COLLATE utf8mb4_unicode_ci,
  `created_at` int(10) unsigned NOT NULL,
  `updated_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`customer_service_id`),
  KEY `idx_shop_service` (`shop_id`,`service_type`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_email_tpl` */

DROP TABLE IF EXISTS `hd_email_tpl`;

CREATE TABLE `hd_email_tpl` (
  `email_tpl_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` mediumint(8) unsigned NOT NULL,
  `subject` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `template` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `banner_images` text COLLATE utf8mb4_unicode_ci,
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `updated_at` int(10) unsigned NOT NULL,
  `updated_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`email_tpl_id`),
  KEY `idx_shop_tpl` (`shop_id`,`template`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_forgot_password` */

DROP TABLE IF EXISTS `hd_forgot_password`;

CREATE TABLE `hd_forgot_password` (
  `forgot_password_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` mediumint(8) unsigned NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `expired` int(10) unsigned NOT NULL,
  `status` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '口令状态：0-待处理，1-已处理，2-已失效，3-未知错',
  `created_at` int(10) unsigned NOT NULL,
  `updated_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`forgot_password_id`),
  KEY `idx_shop_token` (`shop_id`,`token`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_language` */

DROP TABLE IF EXISTS `hd_language`;

CREATE TABLE `hd_language` (
  `language_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` mediumint(8) unsigned NOT NULL,
  `language_name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `language_code` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '从小到大排序',
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `updated_at` int(10) unsigned NOT NULL,
  `updated_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`language_id`),
  UNIQUE KEY `idx_shop_lang` (`shop_id`,`language_code`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_order` */

DROP TABLE IF EXISTS `hd_order`;

CREATE TABLE `hd_order` (
  `order_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` mediumint(8) unsigned NOT NULL,
  `order_number` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_id` bigint(20) unsigned NOT NULL,
  `customer_email` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `customer_name` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_status_id` tinyint(3) unsigned NOT NULL,
  `shipping_method` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `shipping_code` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_code` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_code` char(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_value` decimal(16,8) unsigned NOT NULL,
  `order_total` decimal(16,4) unsigned NOT NULL,
  `default_currency_total` decimal(16,4) unsigned NOT NULL,
  `default_currency_code` char(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `warehouse_code` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip_number` int(10) unsigned NOT NULL COMMENT '下单IP地址，转成数字保存',
  `ip_country_iso_code_2` char(2) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '下单IP所属国家的两位编码',
  `host_from` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '下单来源站点 HOST',
  `device_from` char(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '下单来源终端设备：PC-电脑端，M-移动端，BG-后台',
  `order_type` char(8) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'normal' COMMENT '订单类型：normal-正常订单，testing-测试订单',
  `is_guest` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否为游客订单：0-否，1-是',
  `pp_token` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `updated_at` int(10) unsigned NOT NULL,
  `updated_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`order_id`),
  UNIQUE KEY `idx_shop_order` (`shop_id`,`order_number`),
  KEY `idx_shop_customer_id` (`shop_id`,`customer_id`),
  KEY `idx_shop_email` (`shop_id`,`customer_email`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_pp_token` (`pp_token`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_order_address` */

DROP TABLE IF EXISTS `hd_order_address`;

CREATE TABLE `hd_order_address` (
  `order_address_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` mediumint(8) unsigned NOT NULL,
  `order_id` bigint(20) unsigned NOT NULL,
  `address_type` char(8) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '地址类型：shipping-货运地址，billing-账单地址',
  `first_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `last_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `street_address` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `street_address_sub` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `postcode` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `city` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `zone_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `zone_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country_id` smallint(5) unsigned NOT NULL DEFAULT '0',
  `country_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`order_address_id`),
  KEY `idx_shop_id` (`shop_id`),
  KEY `idx_order_id` (`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_order_note` */

DROP TABLE IF EXISTS `hd_order_note`;

CREATE TABLE `hd_order_note` (
  `order_note_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` mediumint(8) unsigned NOT NULL,
  `order_id` bigint(20) unsigned NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci COMMENT '订单备注',
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`order_note_id`),
  KEY `idx_shop_order` (`shop_id`,`order_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_order_product` */

DROP TABLE IF EXISTS `hd_order_product`;

CREATE TABLE `hd_order_product` (
  `order_product_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` mediumint(8) unsigned NOT NULL,
  `order_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `product_name` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sku` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` mediumint(8) unsigned NOT NULL,
  `price` decimal(16,4) unsigned NOT NULL,
  `default_currency_price` decimal(16,4) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`order_product_id`),
  KEY `idx_shop_order` (`shop_id`,`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_order_status_history` */

DROP TABLE IF EXISTS `hd_order_status_history`;

CREATE TABLE `hd_order_status_history` (
  `order_sh_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` mediumint(8) unsigned NOT NULL,
  `order_id` bigint(20) unsigned NOT NULL,
  `order_status_id` tinyint(3) unsigned NOT NULL,
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否展示：0-否，1-是',
  `comment` text COLLATE utf8mb4_unicode_ci COMMENT '订单状态变更说明',
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`order_sh_id`),
  KEY `idx_shop_order` (`shop_id`,`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=68 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_order_total` */

DROP TABLE IF EXISTS `hd_order_total`;

CREATE TABLE `hd_order_total` (
  `order_total_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` mediumint(8) unsigned NOT NULL,
  `order_id` bigint(20) unsigned NOT NULL,
  `ot_class` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ot_title` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ot_text` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `price` decimal(16,4) unsigned NOT NULL,
  `default_currency_price` decimal(16,4) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`order_total_id`),
  KEY `idx_shop_order` (`shop_id`,`order_id`)
) ENGINE=InnoDB AUTO_INCREMENT=76 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_payment_method` */

DROP TABLE IF EXISTS `hd_payment_method`;

CREATE TABLE `hd_payment_method` (
  `payment_method_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` mediumint(8) unsigned NOT NULL,
  `method_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method_code` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '从小到大排序',
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `updated_at` int(10) unsigned NOT NULL,
  `updated_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`payment_method_id`),
  KEY `idx_shop_method` (`shop_id`,`method_code`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_paypal` */

DROP TABLE IF EXISTS `hd_paypal`;

CREATE TABLE `hd_paypal` (
  `paypal_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` mediumint(8) unsigned NOT NULL,
  `order_id` bigint(20) unsigned NOT NULL,
  `operation` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ack` varchar(16) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_code` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_status` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_date` datetime NOT NULL,
  `txn_id` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_code` char(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(16,8) unsigned NOT NULL,
  `payer_email` varchar(128) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payer_id` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`paypal_id`),
  KEY `idx_shop_order` (`shop_id`,`order_id`),
  KEY `idx_shop_txn` (`shop_id`,`txn_id`),
  KEY `idx_ack` (`ack`),
  KEY `idx_status` (`payment_status`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_product` */

DROP TABLE IF EXISTS `hd_product`;

CREATE TABLE `hd_product` (
  `product_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` mediumint(8) unsigned NOT NULL,
  `product_category_id` int(10) unsigned NOT NULL,
  `product_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '产品状态：0-待处理，1-上架，2-下架',
  `product_url` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '从小到大排序',
  `is_sold_out` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否缺货：0-否，1-是',
  `price` decimal(16,4) unsigned NOT NULL,
  `weight` decimal(16,4) unsigned NOT NULL DEFAULT '0.0000',
  `weight_unit` char(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `width` decimal(16,4) unsigned NOT NULL DEFAULT '0.0000',
  `length` decimal(16,4) unsigned NOT NULL DEFAULT '0.0000',
  `height` decimal(16,4) unsigned NOT NULL DEFAULT '0.0000',
  `size_unit` char(10) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `updated_at` int(10) unsigned NOT NULL,
  `updated_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`product_id`),
  KEY `idx_prod_cate_id` (`product_category_id`),
  KEY `idx_prod_status` (`product_status`),
  KEY `idx_shop_id` (`shop_id`),
  KEY `idx_sort` (`sort`),
  KEY `idx_price` (`price`),
  KEY `idx_sold_out` (`is_sold_out`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_product_category` */

DROP TABLE IF EXISTS `hd_product_category`;

CREATE TABLE `hd_product_category` (
  `product_category_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` mediumint(8) unsigned NOT NULL,
  `parent_id` int(10) unsigned NOT NULL DEFAULT '0',
  `category_url` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `redirect_link` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '跳转链接，该值不为空时产品类目URL无效',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '从小到大排序',
  `category_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '产品类目状态：0-禁用，1-启用',
  `product_show_size` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '每页列表展示产品个数',
  `review_show_size` tinyint(2) unsigned NOT NULL DEFAULT '0' COMMENT '每页列表展示评论个数',
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `updated_at` int(10) unsigned NOT NULL,
  `updated_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`product_category_id`),
  KEY `idx_shop_id` (`shop_id`),
  KEY `idx_parent_id` (`parent_id`),
  KEY `idx_category_status` (`category_status`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_product_category_description` */

DROP TABLE IF EXISTS `hd_product_category_description`;

CREATE TABLE `hd_product_category_description` (
  `product_category_description_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` mediumint(8) unsigned NOT NULL,
  `product_category_id` int(10) unsigned NOT NULL,
  `language_code` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `category_name` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_title` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `meta_keywords` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `meta_description` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `category_description` text COLLATE utf8mb4_unicode_ci,
  `category_description_m` text COLLATE utf8mb4_unicode_ci,
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `updated_at` int(10) unsigned NOT NULL,
  `updated_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`product_category_description_id`),
  KEY `idx_shop_id` (`shop_id`),
  KEY `idx_prod_cate_id` (`product_category_id`),
  KEY `idx_lang_code` (`language_code`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_product_description` */

DROP TABLE IF EXISTS `hd_product_description`;

CREATE TABLE `hd_product_description` (
  `product_description_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` mediumint(8) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `language_code` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `product_name` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL,
  `meta_title` varchar(300) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `meta_keywords` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `meta_description` varchar(1000) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `product_description` text COLLATE utf8mb4_unicode_ci,
  `product_description_m` text COLLATE utf8mb4_unicode_ci,
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `updated_at` int(10) unsigned NOT NULL,
  `updated_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`product_description_id`),
  KEY `idx_shop_id` (`shop_id`),
  KEY `idx_product_id` (`product_id`),
  KEY `idx_lang_code` (`language_code`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_product_image` */

DROP TABLE IF EXISTS `hd_product_image`;

CREATE TABLE `hd_product_image` (
  `product_image_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` mediumint(8) unsigned NOT NULL,
  `sku` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_path` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `image_name` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '从小到大排序',
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `updated_at` int(10) unsigned NOT NULL,
  `updated_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`product_image_id`),
  KEY `idx_sort` (`sort`),
  KEY `idx_shop_sku` (`shop_id`,`sku`)
) ENGINE=InnoDB AUTO_INCREMENT=43 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_product_qty_price` */

DROP TABLE IF EXISTS `hd_product_qty_price`;

CREATE TABLE `hd_product_qty_price` (
  `product_qty_price_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` mediumint(8) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `sku` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `warehouse_code` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` mediumint(8) unsigned NOT NULL,
  `price` decimal(16,4) unsigned NOT NULL,
  `list_price` decimal(16,4) unsigned NOT NULL DEFAULT '0.0000',
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `updated_at` int(10) unsigned NOT NULL,
  `updated_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`product_qty_price_id`),
  KEY `idx_shop_sku` (`shop_id`,`sku`),
  KEY `idx_shop_prod_id` (`shop_id`,`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_product_sku` */

DROP TABLE IF EXISTS `hd_product_sku`;

CREATE TABLE `hd_product_sku` (
  `product_sku_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` mediumint(8) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `sku` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '从小到大排序，最小的一个为默认展示商品',
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `updated_at` int(10) unsigned NOT NULL,
  `updated_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`product_sku_id`),
  UNIQUE KEY `idx_shop_sku` (`shop_id`,`sku`),
  KEY `idx_shop_prod_id` (`shop_id`,`product_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='商品联合表，相当于SPU的概念';

/*Table structure for table `hd_shipping_method` */

DROP TABLE IF EXISTS `hd_shipping_method`;

CREATE TABLE `hd_shipping_method` (
  `shipping_method_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` mediumint(8) unsigned NOT NULL,
  `method_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method_code` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '从小到大排序',
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `updated_at` int(10) unsigned NOT NULL,
  `updated_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`shipping_method_id`),
  KEY `idx_shop_method` (`shop_id`,`method_code`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_shop_template` */

DROP TABLE IF EXISTS `hd_shop_template`;

CREATE TABLE `hd_shop_template` (
  `shop_template_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` mediumint(8) unsigned NOT NULL,
  `tpl_path` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '店铺模板路径',
  `tpl_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '模板状态：0-禁用，1-启用',
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `updated_at` int(10) unsigned NOT NULL,
  `updated_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`shop_template_id`),
  KEY `idx_shop_id` (`shop_id`),
  KEY `idx_tpl_status` (`tpl_status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_shopping_cart` */

DROP TABLE IF EXISTS `hd_shopping_cart`;

CREATE TABLE `hd_shopping_cart` (
  `shopping_cart_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` mediumint(8) unsigned NOT NULL,
  `customer_id` bigint(20) unsigned NOT NULL,
  `product_id` bigint(20) unsigned NOT NULL,
  `sku` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `qty` mediumint(8) unsigned NOT NULL,
  `price` decimal(16,4) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  `updated_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`shopping_cart_id`),
  KEY `idx_shop_customer` (`shop_id`,`customer_id`),
  KEY `idx_sku` (`sku`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_sys_admin` */

DROP TABLE IF EXISTS `hd_sys_admin`;

CREATE TABLE `hd_sys_admin` (
  `admin_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `account` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `updated_at` int(10) unsigned NOT NULL,
  `updated_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`admin_id`),
  UNIQUE KEY `idx_account` (`account`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_sys_country` */

DROP TABLE IF EXISTS `hd_sys_country`;

CREATE TABLE `hd_sys_country` (
  `country_id` smallint(5) unsigned NOT NULL AUTO_INCREMENT,
  `country_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `iso_code_2` char(2) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '国家两位编码',
  `iso_code_3` char(3) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '国家三位编码',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '从小到大排序',
  `icon_path` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '国旗图标路径',
  `is_high_risk` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `currency_code` char(4) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'USD' COMMENT '国家使用货币',
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `updated_at` int(10) unsigned NOT NULL,
  `updated_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`country_id`),
  KEY `idx_code2` (`iso_code_2`),
  KEY `idx_sort` (`sort`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_sys_currency` */

DROP TABLE IF EXISTS `hd_sys_currency`;

CREATE TABLE `hd_sys_currency` (
  `currency_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `currency_name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `currency_code` char(4) COLLATE utf8mb4_unicode_ci NOT NULL,
  `symbol_left` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '币种符号，位于数字左边的',
  `symbol_right` varchar(8) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '币种符号，位于数字右边的',
  `decimal_point` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '.' COMMENT '小数点符号，默认为点号',
  `thousands_point` char(1) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT ',' COMMENT '千位点符号，默认为逗号',
  `value` decimal(16,8) unsigned NOT NULL,
  `decimal_places` tinyint(3) unsigned NOT NULL COMMENT '小数点保留位置',
  `icon_path` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '币种图标路径',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '从小到大排序',
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `updated_at` int(10) unsigned NOT NULL,
  `updated_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`currency_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_sys_language` */

DROP TABLE IF EXISTS `hd_sys_language`;

CREATE TABLE `hd_sys_language` (
  `language_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `language_name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `language_code` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '从小到大排序',
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `updated_at` int(10) unsigned NOT NULL,
  `updated_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`language_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_sys_order_status` */

DROP TABLE IF EXISTS `hd_sys_order_status`;

CREATE TABLE `hd_sys_order_status` (
  `pk_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '仅作为主键需要，无实际业务意义',
  `language_code` char(2) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status_name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `order_status_id` tinyint(3) unsigned NOT NULL,
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '从小到大排序',
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `updated_at` int(10) unsigned NOT NULL,
  `updated_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`pk_id`),
  UNIQUE KEY `idx_lang_status` (`language_code`,`order_status_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_sys_payment_method` */

DROP TABLE IF EXISTS `hd_sys_payment_method`;

CREATE TABLE `hd_sys_payment_method` (
  `payment_method_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `method_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method_code` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '支付方式状态：0-禁用，1-启用',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '从小到大排序',
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `updated_at` int(10) unsigned NOT NULL,
  `updated_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`payment_method_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_sys_shipping_method` */

DROP TABLE IF EXISTS `hd_sys_shipping_method`;

CREATE TABLE `hd_sys_shipping_method` (
  `shipping_method_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `method_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method_code` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '状态：0-禁用，1-启用',
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '从小到大排序',
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `updated_at` int(10) unsigned NOT NULL,
  `updated_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`shipping_method_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_sys_shop` */

DROP TABLE IF EXISTS `hd_sys_shop`;

CREATE TABLE `hd_sys_shop` (
  `shop_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `shop_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `shop_domain` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '店铺网站主域名',
  `shop_domain2` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '店铺网站第2主域名，用于多个主域名指向同一站点的场景',
  `shop_domain2_redirect_code` char(3) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '第2主域名重定向编码：301、302，值为0或空代表不跳转',
  `shop_status` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '店铺状态：0-关闭，1-开启',
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `updated_at` int(10) unsigned NOT NULL,
  `updated_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`shop_id`),
  KEY `idx_domain` (`shop_domain`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_sys_warehouse` */

DROP TABLE IF EXISTS `hd_sys_warehouse`;

CREATE TABLE `hd_sys_warehouse` (
  `warehouse_id` tinyint(3) unsigned NOT NULL AUTO_INCREMENT,
  `warehouse_code` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `warehouse_name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '从小到大排序',
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `updated_at` int(10) unsigned NOT NULL,
  `updated_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`warehouse_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_sys_zone` */

DROP TABLE IF EXISTS `hd_sys_zone`;

CREATE TABLE `hd_sys_zone` (
  `zone_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `country_id` smallint(5) unsigned NOT NULL,
  `zone_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zone_code` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '从小到大排序',
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `updated_at` int(10) unsigned NOT NULL,
  `updated_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`zone_id`),
  KEY `idx_country_id` (`country_id`),
  KEY `idx_code` (`zone_code`),
  KEY `idx_sort` (`sort`)
) ENGINE=InnoDB AUTO_INCREMENT=52 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_upload_file` */

DROP TABLE IF EXISTS `hd_upload_file`;

CREATE TABLE `hd_upload_file` (
  `upload_file_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` mediumint(8) unsigned NOT NULL,
  `origin_name` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `oss_object` varchar(500) COLLATE utf8mb4_unicode_ci NOT NULL,
  `file_class` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `folder` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `updated_at` int(10) unsigned NOT NULL,
  `updated_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`upload_file_id`),
  KEY `idx_shop_class` (`shop_id`,`file_class`),
  KEY `idx_shop_file` (`shop_id`,`origin_name`),
  KEY `idx_shop_folder` (`shop_id`,`folder`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_warehouse` */

DROP TABLE IF EXISTS `hd_warehouse`;

CREATE TABLE `hd_warehouse` (
  `warehouse_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `shop_id` mediumint(8) unsigned NOT NULL,
  `warehouse_code` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `warehouse_name` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort` smallint(5) NOT NULL DEFAULT '0' COMMENT '从小到大排序',
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `updated_at` int(10) unsigned NOT NULL,
  `updated_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`warehouse_id`),
  UNIQUE KEY `idx_shop_warehouse` (`shop_id`,`warehouse_code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

/*Table structure for table `hd_zone` */

DROP TABLE IF EXISTS `hd_zone`;

CREATE TABLE `hd_zone` (
  `pk_id` int(10) unsigned NOT NULL AUTO_INCREMENT COMMENT '仅作为主键需要，无实际业务意义',
  `shop_id` mediumint(8) unsigned NOT NULL,
  `zone_id` mediumint(8) unsigned NOT NULL,
  `country_id` smallint(5) unsigned NOT NULL,
  `zone_name` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `zone_code` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '从小到大排序',
  `created_at` int(10) unsigned NOT NULL,
  `created_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `updated_at` int(10) unsigned NOT NULL,
  `updated_by` varchar(32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`pk_id`),
  UNIQUE KEY `idx_shop_zone` (`shop_id`,`zone_id`),
  KEY `idx_shop_country` (`shop_id`,`country_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

