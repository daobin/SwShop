# 20211113 product attribute
CREATE TABLE `hd_product_attribute_group` (
  `attr_group_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `shop_id` MEDIUMINT UNSIGNED NOT NULL,
  `created_at` INT UNSIGNED NOT NULL,
  `created_by` VARCHAR (32) NOT NULL DEFAULT '',
  `updated_at` INT UNSIGNED NOT NULL,
  `updated_by` VARCHAR (32) NOT NULL DEFAULT '',
  PRIMARY KEY (`attr_group_id`)
) ENGINE = INNODB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci ;

CREATE TABLE `hd_product_attribute_group_description` (
  `attr_group_description_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `shop_id` MEDIUMINT UNSIGNED NOT NULL,
  `attr_group_id` INT UNSIGNED NOT NULL,
  `language_code` CHAR(2) NOT NULL,
  `group_name` VARCHAR (32) NOT NULL,
  `created_at` INT UNSIGNED NOT NULL,
  `created_by` VARCHAR (32) NOT NULL DEFAULT '',
  `updated_at` INT UNSIGNED NOT NULL,
  `updated_by` VARCHAR (32) NOT NULL DEFAULT '',
  PRIMARY KEY (`attr_group_description_id`)
) ENGINE = INNODB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci ;

CREATE TABLE `hd_product_attribute_value` (
  `attr_value_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `shop_id` MEDIUMINT UNSIGNED NOT NULL,
  `attr_group_id` INT UNSIGNED NOT NULL,
  `created_at` INT UNSIGNED NOT NULL,
  `created_by` VARCHAR (32) NOT NULL DEFAULT '',
  `updated_at` INT UNSIGNED NOT NULL,
  `updated_by` VARCHAR (32) NOT NULL DEFAULT '',
  PRIMARY KEY (`attr_value_id`)
) ENGINE = INNODB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci ;

CREATE TABLE `hd_product_attribute_value_description` (
  `attr_value_description_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `shop_id` MEDIUMINT UNSIGNED NOT NULL,
  `attr_value_id` INT UNSIGNED NOT NULL,
  `language_code` CHAR(2) NOT NULL,
  `value_name` VARCHAR (32) NOT NULL,
  `created_at` INT UNSIGNED NOT NULL,
  `created_by` VARCHAR (32) NOT NULL DEFAULT '',
  `updated_at` INT UNSIGNED NOT NULL,
  `updated_by` VARCHAR (32) NOT NULL DEFAULT '',
  PRIMARY KEY (`attr_value_description_id`)
) ENGINE = INNODB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci ;

CREATE TABLE `hd_product_attribute_bind` (
  `prod_attr_bind_id` INT UNSIGNED NOT NULL AUTO_INCREMENT,
  `shop_id` MEDIUMINT UNSIGNED NOT NULL,
  `product_id` BIGINT UNSIGNED NOT NULL,
  `attr_group_id` INT UNSIGNED NOT NULL,
  `attr_value_id` INT UNSIGNED NOT NULL,
  `created_at` INT UNSIGNED NOT NULL,
  `created_by` VARCHAR (32) NOT NULL DEFAULT '',
  `updated_at` INT UNSIGNED NOT NULL,
  `updated_by` VARCHAR (32) NOT NULL DEFAULT '',
  PRIMARY KEY (`prod_attr_bind_id`),
  KEY `idx_shop_prod` (`shop_id`, `product_id`)
) ENGINE = INNODB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci ;

