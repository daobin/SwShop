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

ALTER TABLE `hd_product_sku`
  ADD `attr_group_id` INT UNSIGNED NOT NULL AFTER `sku`,
  ADD `attr_value_id` INT UNSIGNED NOT NULL AFTER `attr_group_id`;

CREATE TABLE `hd_product_sku_attribute` (
  `product_sku_attribute_id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `shop_id` MEDIUMINT UNSIGNED NOT NULL,
  `product_id` BIGINT (20) UNSIGNED NOT NULL,
  `sku` VARCHAR (100) NOT NULL,
  `attr_group_id` INT (10) UNSIGNED NOT NULL,
  `attr_value_id` INT (10) UNSIGNED NOT NULL,
  `created_at` INT (10) UNSIGNED NOT NULL,
  `created_by` VARCHAR (32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `updated_at` INT (10) UNSIGNED NOT NULL,
  `updated_by` VARCHAR (32) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`product_sku_attribute_id`),
  KEY `idx_shop_prod_id` (`shop_id`, `product_id`),
  KEY `idx_shop_sku` (`shop_id`, `sku`)
) ENGINE = INNODB DEFAULT CHARSET = utf8mb4 COLLATE = utf8mb4_unicode_ci;

# 20220104
ALTER TABLE `hd_product_sku_attribute` MODIFY `attr_value_id` INT UNSIGNED NOT NULL DEFAULT 0;
ALTER TABLE `hd_product_sku_attribute` ADD `attr_value_name` VARCHAR(64) NOT NULL AFTER `attr_value_id`;

# 20220117
DROP TABLE `hd_product_attribute_value`;
DROP TABLE `hd_product_attribute_value_description`;
ALTER TABLE `hd_product_sku_attribute` DROP `attr_value_id`;

# 20220126
TRUNCATE TABLE `hd_product_image`;
ALTER TABLE `hd_product_image` CHANGE `sku` `product_id` BIGINT NOT NULL;
ALTER TABLE `hd_product_sku_attribute`
  ADD `image_path` VARCHAR (500) NOT NULL DEFAULT '' AFTER `attr_value_name`,
  ADD `image_name` VARCHAR (100) NOT NULL DEFAULT '' AFTER `image_path`;



