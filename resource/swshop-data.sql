
/*Data for the table `hd_sys_country` */

insert  into `hd_sys_country`(`country_id`,`country_name`,`iso_code_2`,`iso_code_3`,`sort`,`icon_path`,`is_high_risk`,`currency_code`,`created_at`,`created_by`,`updated_at`,`updated_by`) values 
(1,'United States','US','USA',0,'',0,'USD',0,'',0,''),
(2,'United Kingdom','GB','GBR',0,'',0,'USD',0,'',0,'');

/*Data for the table `hd_sys_currency` */

insert  into `hd_sys_currency`(`currency_id`,`currency_name`,`currency_code`,`symbol_left`,`symbol_right`,`decimal_point`,`thousands_point`,`value`,`decimal_places`,`icon_path`,`sort`,`created_at`,`created_by`,`updated_at`,`updated_by`) values 
(1,'美元','USD','$','','.',',',1.00000000,2,'',0,0,'',0,''),
(2,'人民币','RMB','￥','','.',',',6.00000000,2,'',0,0,'',0,'');

/*Data for the table `hd_sys_language` */

insert  into `hd_sys_language`(`language_id`,`language_name`,`language_code`,`sort`,`created_at`,`created_by`,`updated_at`,`updated_by`) values 
(1,'中文','zh',1,0,'',0,''),
(2,'英文','en',0,0,'',0,'');

/*Data for the table `hd_sys_order_status` */

insert  into `hd_sys_order_status`(`pk_id`,`language_code`,`status_name`,`order_status_id`,`sort`,`created_at`,`created_by`,`updated_at`,`updated_by`) values 
(1,'en','Waitting',1,0,0,'',0,''),
(2,'en','Pending',2,0,0,'',0,''),
(3,'en','In Process',3,0,0,'',0,''),
(4,'en','Shipped',4,0,0,'',0,''),
(5,'en','Canceled',5,0,0,'',0,''),
(6,'zh','待支付',1,0,0,'',0,''),
(7,'zh','待处理',2,0,0,'',0,''),
(8,'zh','处理中',3,0,0,'',0,''),
(9,'zh','已发货',4,0,0,'',0,''),
(10,'zh','已取消',5,0,0,'',0,'');

/*Data for the table `hd_sys_payment_method` */

insert  into `hd_sys_payment_method`(`payment_method_id`,`method_name`,`method_code`,`method_status`,`sort`,`created_at`,`created_by`,`updated_at`,`updated_by`) values 
(1,'Paypal','paypal',1,1,0,'',0,''),
(2,'Credit Card','paypal_cc',1,0,0,'',0,'');

/*Data for the table `hd_sys_shipping_method` */

insert  into `hd_sys_shipping_method`(`shipping_method_id`,`method_name`,`method_code`,`method_status`,`sort`,`created_at`,`created_by`,`updated_at`,`updated_by`) values 
(1,'Free Shipping','free',0,0,0,'',0,'');

/*Data for the table `hd_sys_warehouse` */

insert  into `hd_sys_warehouse`(`warehouse_id`,`warehouse_code`,`warehouse_name`,`sort`,`created_at`,`created_by`,`updated_at`,`updated_by`) values 
(1,'CN','中国仓',0,0,'',0,''),
(2,'US','美国仓',0,0,'',0,''),
(3,'US_E','美东仓',0,0,'',0,''),
(4,'US_W','美西仓',0,0,'',0,'');

/*Data for the table `hd_sys_zone` */

insert  into `hd_sys_zone`(`zone_id`,`country_id`,`zone_name`,`zone_code`,`sort`,`created_at`,`created_by`,`updated_at`,`updated_by`) values 
(1,1,'New York','NY',0,0,'',0,''),
(2,1,'Alabama','AL',0,0,'',0,'');

