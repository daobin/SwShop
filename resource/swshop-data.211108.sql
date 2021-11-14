
/*Data for the table `hd_admin` */

insert  into `hd_admin`(`admin_id`,`shop_id`,`account`,`password`,`created_at`,`created_by`,`updated_at`,`updated_by`) values 
(1,1,'at0103','$2y$10$sFjLcQulG8JTxCWy.VhT6uG96rlaB0eCPYmWurACJJMGslas7EW/S',1633017600,'',1634631453,'at0103'),
(2,1,'zt2655','$2y$10$ZyA2FDFPlRri8xvs28KUBu6B5mTboIahY0suvs4zvDS4qQJ6D7Iaq',1633017600,'',1634632443,'zt2655'),
(4,1,'test','$2y$10$TSfXAPp82E1UuD6YvfClUe3o5odvt2Z8pa929JGnA/DEMh4a9Ziqa',1634781126,'zt2655',1634781126,'zt2655'),
(5,8,'admin2021','$2y$10$taQvX2UOd1Nnvk.gIc7xPO/fcPqHID9IbyQgZTuQsINeR333Zx8By',1634782440,'init',1634782440,'init'),
(6,9,'admin2021','$2y$10$UYYHcLWGS78UkTFVqc3TeurUgxmWwcdyXG5Lg7VtewCYddTI3vW3W',1634784133,'init',1634784133,'init'),
(7,10,'admin2021','$2y$10$rrAnCFT/LotM7lQehRqmxOi75/EPiEI8VAFYlVJ7Z.LOXnOuxgRO2',1634784193,'init',1634784193,'init'),
(8,11,'admin2021','$2y$10$ChBx7TJuu2.i0iroEJrf4OC.fLwn6pEj3YEWip.Xk9b.A/EHabD6G',1634784285,'init',1634784285,'init'),
(9,12,'admin2021','$2y$10$z8/k3yt0SHJiVHehGWhWZ.rwezU6Oxp1YbHjRIslcs8YbHuPAnEvu',1634784450,'init',1634784450,'init'),
(12,15,'admin2021','$2y$10$3a6MN88hxyZib5jCOZ3u3usrOBiNrbmDtWk0GGtoQO5YeywCQfs6y',1634786999,'init',1634786999,'init');

/*Data for the table `hd_banner` */

insert  into `hd_banner`(`banner_id`,`shop_id`,`title`,`code`,`banner_status`,`created_at`,`created_by`,`updated_at`,`updated_by`) values 
(1,1,'首页主轮播图','index_main_loop',1,0,'',1634542702,'at0103'),
(2,15,'首页主轮播图','index_main_loop',0,1634786999,'init',1634786999,'init');

/*Data for the table `hd_banner_image` */

insert  into `hd_banner_image`(`banner_image_id`,`banner_id`,`shop_id`,`image_path`,`image_name`,`sort`,`is_new_window`,`window_link`,`created_at`,`created_by`,`updated_at`,`updated_by`) values 
(4,1,1,'sp_1/banner/20211018','6d9f93cd78468e09f7cf4cd9c0bc6c46_d_d.jpg',0,1,'http://www.baidu.com',1634542702,'at0103',1634542702,'at0103'),
(5,1,1,'sp_1/banner/20211018','39bf7dcb3f4e3811bf5206da73ebf3a6_d_d.jpg',1,0,'',1634542702,'at0103',1634542702,'at0103');

/*Data for the table `hd_config` */

insert  into `hd_config`(`config_id`,`shop_id`,`config_group`,`config_key`,`config_value`,`value_type`,`config_name`,`created_at`,`created_by`,`updated_at`,`updated_by`) values 
(1,1,'oss','OSS_ACCESS_KEY_ID','US5lLnQuZS5hLmUudC5lLk4uZS5JLmUuSi5lLkUuZS5MLmUueC5lLmEuZS5VLmUuUC5lLlIuZS5WLmUuRi5lLk4uZS5SLmUuVC5lLmguZS5iLmUuVi5lLmQuZS5SLmUuZS5lLmwuZS5WLmUuby5lLmQuZS5GLmUuYS5lLkIuZS5LLmUuTS5lLk0uZS5ZLmUuSS5lLjAuZS5iLmUubC5lLlouZS5nLmUuSi5lLj0tLi4tVy5vLkEuby5TLm8uei5vLlQuby4yLm8uUy5vLnQuby5XLm8ucC5vLjIuby40Lm8uUy5vLk0uby5FLm8uSi5vLlguby5RLm8uai5vLjYuby4zLm8uRi5vLlguby5oLm8uai5vLnEuby5tLm8uMy5vLmsuby5CLm8uMC5vLnAuby4zLm8uMS5vLmouby5sLm8uUy5vLnQuby5HLm8uci5vLlQuby45Lm8uQS5vLj0=','password','OSS 口令',0,'',1626936658,'at0103'),
(2,1,'oss','OSS_ACCESS_KEY_SECRET','US5lLnQuZS5hLmUudC5lLk4uZS5JLmUuSi5lLkUuZS5MLmUueC5lLmEuZS5VLmUuUC5lLlIuZS5lLmUucC5lLlMuZS5sLmUuUS5lLnMuZS5RLmUuYy5lLlYuZS5wLmUuUS5lLlUuZS5aLmUubC5lLmEuZS5WLmUuVi5lLkYuZS5RLmUuUi5lLmEuZS5CLmUuSy5lLk0uZS5NLmUuWS5lLkkuZS4wLmUuYi5lLmwuZS5aLmUuZy5lLkouZS49LS4uLVcuby5BLm8uUy5vLnouby5ULm8uMi5vLlMuby50Lm8uVy5vLnAuby4yLm8uNC5vLlMuby5XLm8uRS5vLjUuby5ILm8uMi5vLlUuby41Lm8uay5vLjIuby5rLm8uai5vLm4uby4wLm8ubC5vLnYuby4xLm8uSC5vLlUuby5MLm8uVC5vLkIuby4wLm8ucC5vLjMuby4xLm8uai5vLmwuby5TLm8udC5vLkcuby5yLm8uVC5vLjkuby5BLm8uPQ==','password','OSS 密钥',0,'',1626936433,'at0103'),
(3,1,'oss','OSS_ENDPOINT','http://oss-cn-hongkong.aliyuncs.com','','OSS 终端',0,'',0,''),
(4,1,'oss','OSS_BUCKET','sw-shop','','OSS Bucket',0,'',1626939198,'at0103'),
(5,1,'redis','REDIS_HOST','127.0.0.1','','Redis 地址',0,'',1626937715,'at0103'),
(10,1,'redis','REDIS_PORT','16379','int','Redis 端口',0,'',0,''),
(11,1,'redis','REDIS_AUTH','US5lLnQuZS5hLmUudC5lLk4uZS5JLmUuSi5lLkUuZS5MLmUueC5lLmEuZS5VLmUuUC5lLlEuZS5OLmUuVS5lLk0uZS5NLmUuUS5lLnQuZS5hLmUudC5lLk4uZS5JLmUuSi5lLkUuZS5MLmUueC5lLmEuZS5VLmUuUC5lLlEtLi4tVy5vLkEuby5TLm8uei5vLlQuby4yLm8uUy5vLnQuby5XLm8ucC5vLjIuby40Lm8uUy5vLnkuby5ULm8udS5vLlQuby56Lm8uVy5vLkEuby5TLm8uei5vLlQuby4yLm8uUy5vLnQuby5XLm8ucC5vLjIuby40Lm8uUy5vLj0=','password','Redis 认证',0,'',1626937719,'at0103'),
(12,1,'redis','REDIS_EXPIRE','1800','int','Redis 过期时间（秒）',0,'',1626937801,'at0103'),
(13,1,'web_info','TIMESTAMP','?202110','','静态资源时间戳',0,'',1634623124,'at0103'),
(15,1,'web_info','WEIGHT_UNIT','{\"\\u5343\\u514b\":\"Kg\",\"\\u514b\":\"g\"}','list','重量单位',0,'',1627443440,'at0103'),
(16,1,'web_info','SIZE_UNIT','{\"m\":\"m\",\"cm\":\"cm\",\"mm\":\"mm\"}','list','尺寸单位',0,'',1627442939,'at0103'),
(19,1,'web_info','INDEX_BOTTOM_TEXT','<div class=\"page-header text-center\">\n            <h2>We Do It For You</h2>\n        </div>\n        <div class=\"jumbotron\">\n            <p>Glarry offers great price and better quality goods and services for music lovers who have ideals,\n                ambitions and make constant efforts to realize their musical dreams!</p>\n<p> </p>\n<p class=\"text-right\">\n<a href=\"/pp-p1.html\" class=\"btn btn-danger\">Our Story</a>\n</p>\n        </div>','','首页底部文案',0,'',1629792147,'at0103'),
(20,1,'paypal_cc','PAYPAL_CC_CHECKOUT_URL','https://www.paypal.com/checkoutweb/signup','','支付页链接',0,'',1635212399,'at0103'),
(21,1,'paypal_cc','PAYPAL_CC_API_URL','https://api-m.paypal.com','','支付 API 链接',0,'',1635212399,'at0103'),
(22,1,'paypal_cc','PAYPAL_CC_API_CLIENT_ID','US5lLnQuZS5hLmUudC5lLk4uZS5JLmUuSi5lLkUuZS5MLmUueC5lLmEuZS5VLmUuUC5lLlIuZS5XLmUuRi5lLlMuZS5sLmUuTi5lLjkuZS5SLmUuZC5lLmQuZS5jLmUuVS5lLmguZS5RLmUudC5lLlMuZS5sLmUuYS5lLk4uZS5ULmUuTS5lLmEuZS5kLmUuTi5lLkYuZS5hLmUuOS5lLlQuZS5KLmUuWS5lLjkuZS5TLmUuVi5lLmEuZS5kLmUuVC5lLlIuZS5RLmUuUi5lLlguZS4xLmUuWS5lLk0uZS5iLmUuSS5lLlouZS5sLmUuVi5lLnguZS5RLmUuSi5lLlUuZS5wLmUuVC5lLkYuZS5RLmUuay5lLmMuZS5VLmUuTi5lLlUuZS5MLmUuMS5lLmEuZS50LmUuTy5lLjAtLi4tVy5vLkEuby5TLm8uei5vLlQuby4yLm8uUy5vLnQuby5XLm8ucC5vLjIuby40Lm8uUy5vLkIuby5tLm8uci5vLkguby5vLm8uRi5vLlYuby5GLm8uNC5vLm0uby4zLm8uRS5vLnQuby5XLm8uYS5vLlUuby52Lm8ubC5vLk0uby5tLm8uei5vLnouby5wLm8uei5vLm8uby5HLm8uQy5vLnouby5RLm8uVS5vLmYuby5ILm8uNC5vLmsuby5ILm8uVy5vLkwuby5sLm8uUy5vLjIuby4zLm8uVy5vLjMuby5WLm8udC5vLjAuby5zLm8uMi5vLlIuby5XLm8uRC5vLkYuby5YLm8uVS5vLnIuby5HLm8uci5vLnouby55Lm8uaS5vLmguby5TLm8ucy5vLlcuby5sLm8uRC5vLms=','password','API 客户端 ID',0,'',1635212399,'at0103'),
(23,1,'paypal_cc','PAYPAL_CC_API_SECRET','US5lLnQuZS5hLmUudC5lLk4uZS5JLmUuSi5lLkUuZS5MLmUueC5lLmEuZS5VLmUuUC5lLlIuZS5TLmUuRi5lLmIuZS50LmUuZC5lLmQuZS5TLmUuTi5lLlYuZS5OLmUuZS5lLjguZS5lLmUuUi5lLlYuZS5aLmUuVC5lLlouZS5NLmUuRi5lLlIuZS5vLmUuUS5lLmguZS5TLmUudC5lLk4uZS5wLmUuUy5lLkkuZS5NLmUuQS5lLlouZS5JLmUuVC5lLkYuZS5RLmUuUi5lLlUuZS5sLmUuZC5lLmwuZS5hLmUudC5lLlEuZS4xLmUuYS5lLjkuZS5RLmUuSS5lLlMuZS5KLmUuVi5lLkYuZS5RLmUuay5lLmMuZS5VLmUuTi5lLlUuZS5MLmUuMS5lLmEuZS50LmUuTy5lLjAtLi4tVy5vLkEuby5TLm8uei5vLlQuby4yLm8uUy5vLnQuby5XLm8ucC5vLjIuby40Lm8uUy5vLkYuby5qLm8uUC5vLjIuby5zLm8uRy5vLmEuby5HLm8uRC5vLlUuby5HLm8uay5vLnouby5FLm8uTi5vLmwuby5pLm8uVS5vLnMuby5WLm8ucC5vLkYuby55Lm8uai5vLjAuby5FLm8uVC5vLlcuby5wLm8uVC5vLnQuby5GLm8uMy5vLjEuby53Lm8uMi5vLmkuby56Lm8uVi5vLmsuby5hLm8uWC5vLjAuby5FLm8ubS5vLmsuby5uLm8uVi5vLkIuby56Lm8uei5vLlUuby4zLm8uVS5vLnIuby5HLm8uci5vLnouby55Lm8uaS5vLmguby5TLm8ucy5vLlcuby5sLm8uRC5vLms=','password','API 密钥',0,'',1635212399,'at0103'),
(24,1,'paypal','PAYPAL_CHECKOUT_URL','https://www.sandbox.paypal.com/checkoutnow','','支付页链接',0,'',1632820042,'at0103'),
(34,1,'paypal','PAYPAL_API_URL','https://api-m.sandbox.paypal.com','','支付 API 链接',0,'',1632820042,'at0103'),
(35,1,'paypal','PAYPAL_API_CLIENT_ID','US5lLnQuZS5hLmUudC5lLk4uZS5JLmUuSi5lLkUuZS5MLmUueC5lLmEuZS5VLmUuUC5lLlIuZS5XLmUuTi5lLlIuZS5GLmUuUi5lLlYuZS5WLmUuRi5lLmMuZS5GLmUuVi5lLmsuZS5PLmUuRi5lLlYuZS50LmUuUy5lLmQuZS5kLmUuaC5lLkwuZS5GLmUuZS5lLkUuZS5kLmUuMS5lLmEuZS5SLmUuVi5lLlkuZS5aLmUuSi5lLlouZS5oLmUuUS5lLmguZS5VLmUuQi5lLlQuZS50LmUuYi5lLjUuZS5NLmUuRi5lLlYuZS5aLmUuVC5lLlYuZS5OLmUuZC5lLlEuZS5FLmUuYi5lLkYuZS5RLmUuay5lLmMuZS5VLmUuTi5lLlUuZS5MLmUuMS5lLmEuZS50LmUuTy5lLjAtLi4tVy5vLkEuby5TLm8uei5vLlQuby4yLm8uUy5vLnQuby5XLm8ucC5vLjIuby40Lm8uUy5vLkIuby5VLm8uby5vLlcuby5FLm8uVS5vLksuby5GLm8uZi5vLjAuby5XLm8ubi5vLjEuby5GLm8uSy5vLjAuby5NLm8uVy5vLkUuby5ULm8uRi5vLlguby5ULm8uSC5vLjAuby5VLm8ucC5vLlUuby53Lm8uVC5vLnkuby5WLm8uTi5vLkYuby5PLm8uay5vLnUuby5YLm8ucy5vLkcuby5NLm8ubS5vLlouby5sLm8uaC5vLmouby4zLm8uai5vLnQuby5GLm8uVy5vLlYuby4yLm8uay5vLnIuby5HLm8uci5vLnouby55Lm8uaS5vLmguby5TLm8ucy5vLlcuby5sLm8uRC5vLms=','password','API 客户端 ID',0,'',1632816189,'at0103'),
(40,1,'paypal','PAYPAL_API_SECRET','US5lLnQuZS5hLmUudC5lLk4uZS5JLmUuSi5lLkUuZS5MLmUueC5lLmEuZS5VLmUuUC5lLlIuZS5SLmUuVi5lLk0uZS5JLmUuZC5lLnguZS5TLmUuaC5lLlIuZS5ZLmUuZC5lLlkuZS5VLmUuUi5lLlMuZS40LmUuYy5lLkIuZS5TLmUuVi5lLk0uZS5kLmUuVC5lLlUuZS5hLmUuSS5lLmUuZS5OLmUuUy5lLjkuZS5XLmUuMS5lLlYuZS45LmUuUy5lLjUuZS5SLmUuaC5lLmEuZS5sLmUuYy5lLkYuZS5ZLmUuVi5lLmMuZS5SLmUuZC5lLlIuZS5ZLmUuWi5lLmEuZS5rLmUuUS5lLkYuZS5RLmUuay5lLmMuZS5VLmUuTi5lLlUuZS5MLmUuMS5lLmEuZS50LmUuTy5lLjAtLi4tVy5vLkEuby5TLm8uei5vLlQuby4yLm8uUy5vLnQuby5XLm8ucC5vLjIuby40Lm8uUy5vLkYuby5sLm8uaC5vLmsuby50Lm8uVy5vLkkuby5YLm8uNS5vLmouby40Lm8uRS5vLjEuby4wLm8uVi5vLjIuby53Lm8uai5vLm0uby5uLm8uUS5vLm0uby5tLm8uVy5vLnguby5HLm8uNC5vLm4uby52Lm8ubC5vLnUuby5tLm8uUS5vLlcuby55Lm8uVS5vLkYuby5WLm8uNS5vLmwuby55Lm8uay5vLnAuby5YLm8udS5vLmsuby5CLm8uMy5vLnYuby56Lm8udi5vLmwuby55Lm8uVS5vLnIuby5HLm8uci5vLnouby55Lm8uaS5vLmguby5TLm8ucy5vLlcuby5sLm8uRC5vLms=','password','API 密钥',0,'',1632816189,'at0103'),
(41,1,'mail','SMTP_HOST','smtp.163.com','','SMTP 服务地址',0,'',1634011036,'at0103'),
(42,1,'mail','SMTP_PORT','994','','SMTP 服务端口',0,'',1634017052,'at0103'),
(43,1,'mail','SMTP_USERNAME','fhd_net@163.com','','SMTP 服务发送邮箱',0,'',1634017164,'at0103'),
(44,1,'mail','SMTP_PASSWORD','US5lLnQuZS5hLmUudC5lLk4uZS5JLmUuSi5lLkUuZS5MLmUueC5lLmEuZS5VLmUuUC5lLlIuZS5XLmUubC5lLlMuZS5KLmUuVy5lLmguZS5SLmUubC5lLlQuZS5WLmUuUS5lLnQuZS5hLmUudC5lLk4uZS5JLmUuSi5lLkUuZS5MLmUueC5lLmEuZS5VLmUuUC5lLlEtLi4tVy5vLkEuby5TLm8uei5vLlQuby4yLm8uUy5vLnQuby5XLm8ucC5vLjIuby40Lm8uUy5vLkouby5rLm8uRi5vLlYuby5WLm8uRi5vLkMuby5VLm8uVi5vLkYuby5TLm8uVy5vLkEuby5TLm8uei5vLlQuby4yLm8uUy5vLnQuby5XLm8ucC5vLjIuby40Lm8uUy5vLj0=','password','SMTP 服务发送密码',0,'',1634011055,'at0103'),
(45,1,'mail','CUSTOMER_SERVICE_EMAIL','1002214592@qq.com','','客服邮箱',0,'',1634008506,'at0103'),
(46,1,'web_info','WEBSITE_NAME','WEB SITE','','站点名称',0,'',1634610297,'at0103'),
(47,1,'oss','OSS_OPEN_CLOSE','close','radio','OSS 开启关闭',0,'',1634546043,'at0103'),
(51,1,'web_info','WEBSITE_LOGO','sp_1/20211019/5cdf67919472d68d43af2c7f8121f9a1_d_d.png','image','站点 LOGO',0,'',1634609991,'at0103'),
(52,1,'oss','FILE_HOST','http://www.swshop.com/','','文件服务地址',0,'',1634288674,'at0103'),
(53,1,'web_info','TKD_TITLE','Best price for you - GG','','站点 Meta Title（SEO 优化）',0,'',1634613981,'at0103'),
(54,1,'web_info','TKD_KEYWORDS',NULL,'','站点 Meta Keywords（SEO 优化）',0,'',0,''),
(56,1,'web_info','TKD_DESCRIPTION',NULL,'','站点 Meta Description（SEO 优化）',0,'',0,''),
(57,1,'web_info','TIMEZONE','America/New_York','','系统时区',0,'',1634635457,'at0103'),
(58,7,'web_info','WEBSITE_NAME','','','站点名称',1634717600,'init',1634717600,'init'),
(59,7,'web_info','WEBSITE_LOGO','','image','站点 LOGO',1634717600,'init',1634717600,'init'),
(60,7,'web_info','TIMEZONE','America/New_York','','系统时区',1634717600,'init',1634717600,'init'),
(61,7,'web_info','TIMESTAMP','?1634717600','','静态资源时间戳',1634717600,'init',1634717600,'init'),
(62,7,'web_info','TKD_TITLE','','','站点 Meta Title（SEO 优化）',1634717600,'init',1634717600,'init'),
(63,7,'web_info','TKD_KEYWORDS','','','站点 Meta Keywords（SEO 优化）',1634717600,'init',1634717600,'init'),
(64,7,'web_info','TKD_DESCRIPTION','','','站点 Meta Description（SEO 优化）',1634717600,'init',1634717600,'init'),
(65,7,'web_info','WEIGHT_UNIT','','list','重量单位',1634717600,'init',1634717600,'init'),
(66,7,'web_info','SIZE_UNIT','','list','尺寸单位',1634717600,'init',1634717600,'init'),
(67,7,'web_info','INDEX_BOTTOM_TEXT','','','首页底部文案',1634717600,'init',1634717600,'init'),
(68,7,'redis','REDIS_HOST','127.0.0.1','','Redis 地址',1634717600,'init',1634717600,'init'),
(69,7,'redis','REDIS_PORT','16379','int','Redis 端口',1634717600,'init',1634717600,'init'),
(70,7,'redis','REDIS_AUTH','US5lLnQuZS5hLmUudC5lLk4uZS5JLmUuSi5lLkUuZS5MLmUueC5lLmEuZS5VLmUuUC5lLlEuZS5OLmUuVS5lLk0uZS5NLmUuUS5lLnQuZS5hLmUudC5lLk4uZS5JLmUuSi5lLkUuZS5MLmUueC5lLmEuZS5VLmUuUC5lLlEtLi4tVy5vLkEuby5TLm8uei5vLlQuby4yLm8uUy5vLnQuby5XLm8ucC5vLjIuby40Lm8uUy5vLnkuby5ULm8udS5vLlQuby56Lm8uVy5vLkEuby5TLm8uei5vLlQuby4yLm8uUy5vLnQuby5XLm8ucC5vLjIuby40Lm8uUy5vLj0=','password','Redis 认证',1634717600,'init',1634717600,'init'),
(71,7,'redis','REDIS_EXPIRE','1800','int','Redis 过期时间（秒）',1634717600,'init',1634717600,'init'),
(72,7,'oss','OSS_ACCESS_KEY_ID','','password','OSS 口令',1634717600,'init',1634717600,'init'),
(73,7,'oss','OSS_ACCESS_KEY_SECRET','','password','OSS 密钥',1634717600,'init',1634717600,'init'),
(74,7,'oss','OSS_ENDPOINT','','','OSS 终端',1634717600,'init',1634717600,'init'),
(75,7,'oss','OSS_BUCKET','','','OSS Bucket',1634717600,'init',1634717600,'init'),
(76,7,'oss','OSS_OPEN_CLOSE','','radio','OSS 开启关闭',1634717600,'init',1634717600,'init'),
(77,7,'oss','FILE_HOST','','','文件服务地址',1634717600,'init',1634717600,'init'),
(78,7,'mail','SMTP_HOST','','','SMTP 服务地址',1634717600,'init',1634717600,'init'),
(79,7,'mail','SMTP_PORT','','','SMTP 服务端口',1634717600,'init',1634717600,'init'),
(80,7,'mail','SMTP_USERNAME','','','SMTP 服务发送邮箱',1634717600,'init',1634717600,'init'),
(81,7,'mail','SMTP_PASSWORD','','password','SMTP 服务发送密码',1634717600,'init',1634717600,'init'),
(82,7,'mail','CUSTOMER_SERVICE_EMAIL','','','客服邮箱',1634717600,'init',1634717600,'init'),
(83,7,'paypal','PAYPAL_CHECKOUT_URL','','','支付页链接',1634717600,'init',1634717600,'init'),
(84,7,'paypal','PAYPAL_API_URL','','','支付 API 链接',1634717600,'init',1634717600,'init'),
(85,7,'paypal','PAYPAL_API_CLIENT_ID','','password','API 客户端 ID',1634717600,'init',1634717600,'init'),
(86,7,'paypal','PAYPAL_API_SECRET','','password','API 密钥',1634717600,'init',1634717600,'init'),
(87,7,'paypal_cc','PAYPAL_CC_CHECKOUT_URL','','','支付页链接',1634717600,'init',1634717600,'init'),
(88,7,'paypal_cc','PAYPAL_CC_API_URL','','','支付 API 链接',1634717600,'init',1634717600,'init'),
(89,7,'paypal_cc','PAYPAL_CC_API_CLIENT_ID','','password','API 客户端 ID',1634717600,'init',1634717600,'init'),
(90,7,'paypal_cc','PAYPAL_CC_API_SECRET','','password','API 密钥',1634717600,'init',1634717600,'init'),
(91,8,'web_info','WEBSITE_NAME','','','站点名称',1634782440,'init',1634782440,'init'),
(92,8,'web_info','WEBSITE_LOGO','','image','站点 LOGO',1634782440,'init',1634782440,'init'),
(93,8,'web_info','TIMEZONE','America/New_York','','系统时区',1634782440,'init',1634782440,'init'),
(94,8,'web_info','TIMESTAMP','?1634782440','','静态资源时间戳',1634782440,'init',1634782440,'init'),
(95,8,'web_info','TKD_TITLE','','','站点 Meta Title（SEO 优化）',1634782440,'init',1634782440,'init'),
(96,8,'web_info','TKD_KEYWORDS','','','站点 Meta Keywords（SEO 优化）',1634782440,'init',1634782440,'init'),
(97,8,'web_info','TKD_DESCRIPTION','','','站点 Meta Description（SEO 优化）',1634782440,'init',1634782440,'init'),
(98,8,'web_info','WEIGHT_UNIT','','list','重量单位',1634782440,'init',1634782440,'init'),
(99,8,'web_info','SIZE_UNIT','','list','尺寸单位',1634782440,'init',1634782440,'init'),
(100,8,'web_info','INDEX_BOTTOM_TEXT','','','首页底部文案',1634782440,'init',1634782440,'init'),
(101,8,'redis','REDIS_HOST','127.0.0.1','','Redis 地址',1634782440,'init',1634782440,'init'),
(102,8,'redis','REDIS_PORT','16379','int','Redis 端口',1634782440,'init',1634782440,'init'),
(103,8,'redis','REDIS_AUTH','US5lLnQuZS5hLmUudC5lLk4uZS5JLmUuSi5lLkUuZS5MLmUueC5lLmEuZS5VLmUuUC5lLlEuZS5OLmUuVS5lLk0uZS5NLmUuUS5lLnQuZS5hLmUudC5lLk4uZS5JLmUuSi5lLkUuZS5MLmUueC5lLmEuZS5VLmUuUC5lLlEtLi4tVy5vLkEuby5TLm8uei5vLlQuby4yLm8uUy5vLnQuby5XLm8ucC5vLjIuby40Lm8uUy5vLnkuby5ULm8udS5vLlQuby56Lm8uVy5vLkEuby5TLm8uei5vLlQuby4yLm8uUy5vLnQuby5XLm8ucC5vLjIuby40Lm8uUy5vLj0=','password','Redis 认证',1634782440,'init',1634782440,'init'),
(104,8,'redis','REDIS_EXPIRE','1800','int','Redis 过期时间（秒）',1634782440,'init',1634782440,'init'),
(105,8,'oss','OSS_ACCESS_KEY_ID','','password','OSS 口令',1634782440,'init',1634782440,'init'),
(106,8,'oss','OSS_ACCESS_KEY_SECRET','','password','OSS 密钥',1634782440,'init',1634782440,'init'),
(107,8,'oss','OSS_ENDPOINT','','','OSS 终端',1634782440,'init',1634782440,'init'),
(108,8,'oss','OSS_BUCKET','','','OSS Bucket',1634782440,'init',1634782440,'init'),
(109,8,'oss','OSS_OPEN_CLOSE','close','radio','OSS 开启关闭',1634782440,'init',1634782440,'init'),
(110,8,'oss','FILE_HOST','','','文件服务地址',1634782440,'init',1634782440,'init'),
(111,8,'mail','SMTP_HOST','','','SMTP 服务地址',1634782440,'init',1634782440,'init'),
(112,8,'mail','SMTP_PORT','','','SMTP 服务端口',1634782440,'init',1634782440,'init'),
(113,8,'mail','SMTP_USERNAME','','','SMTP 服务发送邮箱',1634782440,'init',1634782440,'init'),
(114,8,'mail','SMTP_PASSWORD','','password','SMTP 服务发送密码',1634782440,'init',1634782440,'init'),
(115,8,'mail','CUSTOMER_SERVICE_EMAIL','','','客服邮箱',1634782440,'init',1634782440,'init'),
(116,8,'paypal','PAYPAL_CHECKOUT_URL','','','支付页链接',1634782440,'init',1634782440,'init'),
(117,8,'paypal','PAYPAL_API_URL','','','支付 API 链接',1634782440,'init',1634782440,'init'),
(118,8,'paypal','PAYPAL_API_CLIENT_ID','','password','API 客户端 ID',1634782440,'init',1634782440,'init'),
(119,8,'paypal','PAYPAL_API_SECRET','','password','API 密钥',1634782440,'init',1634782440,'init'),
(120,8,'paypal_cc','PAYPAL_CC_CHECKOUT_URL','','','支付页链接',1634782440,'init',1634782440,'init'),
(121,8,'paypal_cc','PAYPAL_CC_API_URL','','','支付 API 链接',1634782440,'init',1634782440,'init'),
(122,8,'paypal_cc','PAYPAL_CC_API_CLIENT_ID','','password','API 客户端 ID',1634782440,'init',1634782440,'init'),
(123,8,'paypal_cc','PAYPAL_CC_API_SECRET','','password','API 密钥',1634782440,'init',1634782440,'init'),
(124,9,'web_info','WEBSITE_NAME','','','站点名称',1634784133,'init',1634784133,'init'),
(125,9,'web_info','WEBSITE_LOGO','','image','站点 LOGO',1634784133,'init',1634784133,'init'),
(126,9,'web_info','TIMEZONE','America/New_York','','系统时区',1634784133,'init',1634784133,'init'),
(127,9,'web_info','TIMESTAMP','?1634784133','','静态资源时间戳',1634784133,'init',1634784133,'init'),
(128,9,'web_info','TKD_TITLE','','','站点 Meta Title（SEO 优化）',1634784133,'init',1634784133,'init'),
(129,9,'web_info','TKD_KEYWORDS','','','站点 Meta Keywords（SEO 优化）',1634784133,'init',1634784133,'init'),
(130,9,'web_info','TKD_DESCRIPTION','','','站点 Meta Description（SEO 优化）',1634784133,'init',1634784133,'init'),
(131,9,'web_info','WEIGHT_UNIT','','list','重量单位',1634784133,'init',1634784133,'init'),
(132,9,'web_info','SIZE_UNIT','','list','尺寸单位',1634784133,'init',1634784133,'init'),
(133,9,'web_info','INDEX_BOTTOM_TEXT','','','首页底部文案',1634784133,'init',1634784133,'init'),
(134,9,'redis','REDIS_HOST','','','Redis 地址',1634784133,'init',1634784133,'init'),
(135,9,'redis','REDIS_PORT','','int','Redis 端口',1634784133,'init',1634784133,'init'),
(136,9,'redis','REDIS_AUTH','','password','Redis 认证',1634784133,'init',1634784133,'init'),
(137,9,'redis','REDIS_EXPIRE','1800','int','Redis 过期时间（秒）',1634784133,'init',1634784133,'init'),
(138,9,'oss','OSS_ACCESS_KEY_ID','','password','OSS 口令',1634784133,'init',1634784133,'init'),
(139,9,'oss','OSS_ACCESS_KEY_SECRET','','password','OSS 密钥',1634784133,'init',1634784133,'init'),
(140,9,'oss','OSS_ENDPOINT','','','OSS 终端',1634784133,'init',1634784133,'init'),
(141,9,'oss','OSS_BUCKET','','','OSS Bucket',1634784133,'init',1634784133,'init'),
(142,9,'oss','OSS_OPEN_CLOSE','close','radio','OSS 开启关闭',1634784133,'init',1634784133,'init'),
(143,9,'oss','FILE_HOST','','','文件服务地址',1634784133,'init',1634784133,'init'),
(144,9,'mail','SMTP_HOST','','','SMTP 服务地址',1634784133,'init',1634784133,'init'),
(145,9,'mail','SMTP_PORT','','','SMTP 服务端口',1634784133,'init',1634784133,'init'),
(146,9,'mail','SMTP_USERNAME','','','SMTP 服务发送邮箱',1634784133,'init',1634784133,'init'),
(147,9,'mail','SMTP_PASSWORD','','password','SMTP 服务发送密码',1634784133,'init',1634784133,'init'),
(148,9,'mail','CUSTOMER_SERVICE_EMAIL','','','客服邮箱',1634784133,'init',1634784133,'init'),
(149,9,'paypal','PAYPAL_CHECKOUT_URL','','','支付页链接',1634784133,'init',1634784133,'init'),
(150,9,'paypal','PAYPAL_API_URL','','','支付 API 链接',1634784133,'init',1634784133,'init'),
(151,9,'paypal','PAYPAL_API_CLIENT_ID','','password','API 客户端 ID',1634784133,'init',1634784133,'init'),
(152,9,'paypal','PAYPAL_API_SECRET','','password','API 密钥',1634784133,'init',1634784133,'init'),
(153,9,'paypal_cc','PAYPAL_CC_CHECKOUT_URL','','','支付页链接',1634784133,'init',1634784133,'init'),
(154,9,'paypal_cc','PAYPAL_CC_API_URL','','','支付 API 链接',1634784133,'init',1634784133,'init'),
(155,9,'paypal_cc','PAYPAL_CC_API_CLIENT_ID','','password','API 客户端 ID',1634784133,'init',1634784133,'init'),
(156,9,'paypal_cc','PAYPAL_CC_API_SECRET','','password','API 密钥',1634784133,'init',1634784133,'init'),
(157,10,'web_info','WEBSITE_NAME','','','站点名称',1634784193,'init',1634784193,'init'),
(158,10,'web_info','WEBSITE_LOGO','','image','站点 LOGO',1634784193,'init',1634784193,'init'),
(159,10,'web_info','TIMEZONE','America/New_York','','系统时区',1634784193,'init',1634784193,'init'),
(160,10,'web_info','TIMESTAMP','?1634784193','','静态资源时间戳',1634784193,'init',1634784193,'init'),
(161,10,'web_info','TKD_TITLE','','','站点 Meta Title（SEO 优化）',1634784193,'init',1634784193,'init'),
(162,10,'web_info','TKD_KEYWORDS','','','站点 Meta Keywords（SEO 优化）',1634784193,'init',1634784193,'init'),
(163,10,'web_info','TKD_DESCRIPTION','','','站点 Meta Description（SEO 优化）',1634784193,'init',1634784193,'init'),
(164,10,'web_info','WEIGHT_UNIT','','list','重量单位',1634784193,'init',1634784193,'init'),
(165,10,'web_info','SIZE_UNIT','','list','尺寸单位',1634784193,'init',1634784193,'init'),
(166,10,'web_info','INDEX_BOTTOM_TEXT','','','首页底部文案',1634784193,'init',1634784193,'init'),
(167,10,'redis','REDIS_HOST','','','Redis 地址',1634784193,'init',1634784193,'init'),
(168,10,'redis','REDIS_PORT','','int','Redis 端口',1634784193,'init',1634784193,'init'),
(169,10,'redis','REDIS_AUTH','','password','Redis 认证',1634784193,'init',1634784193,'init'),
(170,10,'redis','REDIS_EXPIRE','1800','int','Redis 过期时间（秒）',1634784193,'init',1634784193,'init'),
(171,10,'oss','OSS_ACCESS_KEY_ID','','password','OSS 口令',1634784193,'init',1634784193,'init'),
(172,10,'oss','OSS_ACCESS_KEY_SECRET','','password','OSS 密钥',1634784193,'init',1634784193,'init'),
(173,10,'oss','OSS_ENDPOINT','','','OSS 终端',1634784193,'init',1634784193,'init'),
(174,10,'oss','OSS_BUCKET','','','OSS Bucket',1634784193,'init',1634784193,'init'),
(175,10,'oss','OSS_OPEN_CLOSE','close','radio','OSS 开启关闭',1634784193,'init',1634784193,'init'),
(176,10,'oss','FILE_HOST','','','文件服务地址',1634784193,'init',1634784193,'init'),
(177,10,'mail','SMTP_HOST','','','SMTP 服务地址',1634784193,'init',1634784193,'init'),
(178,10,'mail','SMTP_PORT','','','SMTP 服务端口',1634784193,'init',1634784193,'init'),
(179,10,'mail','SMTP_USERNAME','','','SMTP 服务发送邮箱',1634784193,'init',1634784193,'init'),
(180,10,'mail','SMTP_PASSWORD','','password','SMTP 服务发送密码',1634784193,'init',1634784193,'init'),
(181,10,'mail','CUSTOMER_SERVICE_EMAIL','','','客服邮箱',1634784193,'init',1634784193,'init'),
(182,10,'paypal','PAYPAL_CHECKOUT_URL','','','支付页链接',1634784193,'init',1634784193,'init'),
(183,10,'paypal','PAYPAL_API_URL','','','支付 API 链接',1634784193,'init',1634784193,'init'),
(184,10,'paypal','PAYPAL_API_CLIENT_ID','','password','API 客户端 ID',1634784193,'init',1634784193,'init'),
(185,10,'paypal','PAYPAL_API_SECRET','','password','API 密钥',1634784193,'init',1634784193,'init'),
(186,10,'paypal_cc','PAYPAL_CC_CHECKOUT_URL','','','支付页链接',1634784193,'init',1634784193,'init'),
(187,10,'paypal_cc','PAYPAL_CC_API_URL','','','支付 API 链接',1634784193,'init',1634784193,'init'),
(188,10,'paypal_cc','PAYPAL_CC_API_CLIENT_ID','','password','API 客户端 ID',1634784193,'init',1634784193,'init'),
(189,10,'paypal_cc','PAYPAL_CC_API_SECRET','','password','API 密钥',1634784193,'init',1634784193,'init'),
(190,11,'web_info','WEBSITE_NAME','','','站点名称',1634784285,'init',1634784285,'init'),
(191,11,'web_info','WEBSITE_LOGO','','image','站点 LOGO',1634784285,'init',1634784285,'init'),
(192,11,'web_info','TIMEZONE','America/New_York','','系统时区',1634784285,'init',1634784285,'init'),
(193,11,'web_info','TIMESTAMP','?1634784285','','静态资源时间戳',1634784285,'init',1634784285,'init'),
(194,11,'web_info','TKD_TITLE','','','站点 Meta Title（SEO 优化）',1634784285,'init',1634784285,'init'),
(195,11,'web_info','TKD_KEYWORDS','','','站点 Meta Keywords（SEO 优化）',1634784285,'init',1634784285,'init'),
(196,11,'web_info','TKD_DESCRIPTION','','','站点 Meta Description（SEO 优化）',1634784285,'init',1634784285,'init'),
(197,11,'web_info','WEIGHT_UNIT','','list','重量单位',1634784285,'init',1634784285,'init'),
(198,11,'web_info','SIZE_UNIT','','list','尺寸单位',1634784285,'init',1634784285,'init'),
(199,11,'web_info','INDEX_BOTTOM_TEXT','','','首页底部文案',1634784285,'init',1634784285,'init'),
(200,11,'redis','REDIS_HOST','','','Redis 地址',1634784285,'init',1634784285,'init'),
(201,11,'redis','REDIS_PORT','','int','Redis 端口',1634784285,'init',1634784285,'init'),
(202,11,'redis','REDIS_AUTH','','password','Redis 认证',1634784285,'init',1634784285,'init'),
(203,11,'redis','REDIS_EXPIRE','1800','int','Redis 过期时间（秒）',1634784285,'init',1634784285,'init'),
(204,11,'oss','OSS_ACCESS_KEY_ID','','password','OSS 口令',1634784285,'init',1634784285,'init'),
(205,11,'oss','OSS_ACCESS_KEY_SECRET','','password','OSS 密钥',1634784285,'init',1634784285,'init'),
(206,11,'oss','OSS_ENDPOINT','','','OSS 终端',1634784285,'init',1634784285,'init'),
(207,11,'oss','OSS_BUCKET','','','OSS Bucket',1634784285,'init',1634784285,'init'),
(208,11,'oss','OSS_OPEN_CLOSE','close','radio','OSS 开启关闭',1634784285,'init',1634784285,'init'),
(209,11,'oss','FILE_HOST','','','文件服务地址',1634784285,'init',1634784285,'init'),
(210,11,'mail','SMTP_HOST','','','SMTP 服务地址',1634784285,'init',1634784285,'init'),
(211,11,'mail','SMTP_PORT','','','SMTP 服务端口',1634784285,'init',1634784285,'init'),
(212,11,'mail','SMTP_USERNAME','','','SMTP 服务发送邮箱',1634784285,'init',1634784285,'init'),
(213,11,'mail','SMTP_PASSWORD','','password','SMTP 服务发送密码',1634784285,'init',1634784285,'init'),
(214,11,'mail','CUSTOMER_SERVICE_EMAIL','','','客服邮箱',1634784285,'init',1634784285,'init'),
(215,11,'paypal','PAYPAL_CHECKOUT_URL','','','支付页链接',1634784285,'init',1634784285,'init'),
(216,11,'paypal','PAYPAL_API_URL','','','支付 API 链接',1634784285,'init',1634784285,'init'),
(217,11,'paypal','PAYPAL_API_CLIENT_ID','','password','API 客户端 ID',1634784285,'init',1634784285,'init'),
(218,11,'paypal','PAYPAL_API_SECRET','','password','API 密钥',1634784285,'init',1634784285,'init'),
(219,11,'paypal_cc','PAYPAL_CC_CHECKOUT_URL','','','支付页链接',1634784285,'init',1634784285,'init'),
(220,11,'paypal_cc','PAYPAL_CC_API_URL','','','支付 API 链接',1634784285,'init',1634784285,'init'),
(221,11,'paypal_cc','PAYPAL_CC_API_CLIENT_ID','','password','API 客户端 ID',1634784285,'init',1634784285,'init'),
(222,11,'paypal_cc','PAYPAL_CC_API_SECRET','','password','API 密钥',1634784285,'init',1634784285,'init'),
(223,12,'web_info','WEBSITE_NAME','','','站点名称',1634784450,'init',1634784450,'init'),
(224,12,'web_info','WEBSITE_LOGO','','image','站点 LOGO',1634784450,'init',1634784450,'init'),
(225,12,'web_info','TIMEZONE','America/New_York','','系统时区',1634784450,'init',1634784450,'init'),
(226,12,'web_info','TIMESTAMP','?1634784450','','静态资源时间戳',1634784450,'init',1634784450,'init'),
(227,12,'web_info','TKD_TITLE','','','站点 Meta Title（SEO 优化）',1634784450,'init',1634784450,'init'),
(228,12,'web_info','TKD_KEYWORDS','','','站点 Meta Keywords（SEO 优化）',1634784450,'init',1634784450,'init'),
(229,12,'web_info','TKD_DESCRIPTION','','','站点 Meta Description（SEO 优化）',1634784450,'init',1634784450,'init'),
(230,12,'web_info','WEIGHT_UNIT','','list','重量单位',1634784450,'init',1634784450,'init'),
(231,12,'web_info','SIZE_UNIT','','list','尺寸单位',1634784450,'init',1634784450,'init'),
(232,12,'web_info','INDEX_BOTTOM_TEXT','','','首页底部文案',1634784450,'init',1634784450,'init'),
(233,12,'redis','REDIS_HOST','127.0.0.1','','Redis 地址',1634784450,'init',1634784450,'init'),
(234,12,'redis','REDIS_PORT','16379','int','Redis 端口',1634784450,'init',1634784450,'init'),
(235,12,'redis','REDIS_AUTH','US5lLnQuZS5hLmUudC5lLk4uZS5JLmUuSi5lLkUuZS5MLmUueC5lLmEuZS5VLmUuUC5lLlEuZS5OLmUuVS5lLk0uZS5NLmUuUS5lLnQuZS5hLmUudC5lLk4uZS5JLmUuSi5lLkUuZS5MLmUueC5lLmEuZS5VLmUuUC5lLlEtLi4tVy5vLkEuby5TLm8uei5vLlQuby4yLm8uUy5vLnQuby5XLm8ucC5vLjIuby40Lm8uUy5vLnkuby5ULm8udS5vLlQuby56Lm8uVy5vLkEuby5TLm8uei5vLlQuby4yLm8uUy5vLnQuby5XLm8ucC5vLjIuby40Lm8uUy5vLj0=','password','Redis 认证',1634784450,'init',1634784450,'init'),
(236,12,'redis','REDIS_EXPIRE','1800','int','Redis 过期时间（秒）',1634784450,'init',1634784450,'init'),
(237,12,'oss','OSS_ACCESS_KEY_ID','','password','OSS 口令',1634784450,'init',1634784450,'init'),
(238,12,'oss','OSS_ACCESS_KEY_SECRET','','password','OSS 密钥',1634784450,'init',1634784450,'init'),
(239,12,'oss','OSS_ENDPOINT','','','OSS 终端',1634784450,'init',1634784450,'init'),
(240,12,'oss','OSS_BUCKET','','','OSS Bucket',1634784450,'init',1634784450,'init'),
(241,12,'oss','OSS_OPEN_CLOSE','close','radio','OSS 开启关闭',1634784450,'init',1634784450,'init'),
(242,12,'oss','FILE_HOST','','','文件服务地址',1634784450,'init',1634784450,'init'),
(243,12,'mail','SMTP_HOST','','','SMTP 服务地址',1634784450,'init',1634784450,'init'),
(244,12,'mail','SMTP_PORT','','','SMTP 服务端口',1634784450,'init',1634784450,'init'),
(245,12,'mail','SMTP_USERNAME','','','SMTP 服务发送邮箱',1634784450,'init',1634784450,'init'),
(246,12,'mail','SMTP_PASSWORD','','password','SMTP 服务发送密码',1634784450,'init',1634784450,'init'),
(247,12,'mail','CUSTOMER_SERVICE_EMAIL','','','客服邮箱',1634784450,'init',1634784450,'init'),
(248,12,'paypal','PAYPAL_CHECKOUT_URL','','','支付页链接',1634784450,'init',1634784450,'init'),
(249,12,'paypal','PAYPAL_API_URL','','','支付 API 链接',1634784450,'init',1634784450,'init'),
(250,12,'paypal','PAYPAL_API_CLIENT_ID','','password','API 客户端 ID',1634784450,'init',1634784450,'init'),
(251,12,'paypal','PAYPAL_API_SECRET','','password','API 密钥',1634784450,'init',1634784450,'init'),
(252,12,'paypal_cc','PAYPAL_CC_CHECKOUT_URL','','','支付页链接',1634784450,'init',1634784450,'init'),
(253,12,'paypal_cc','PAYPAL_CC_API_URL','','','支付 API 链接',1634784450,'init',1634784450,'init'),
(254,12,'paypal_cc','PAYPAL_CC_API_CLIENT_ID','','password','API 客户端 ID',1634784450,'init',1634784450,'init'),
(255,12,'paypal_cc','PAYPAL_CC_API_SECRET','','password','API 密钥',1634784450,'init',1634784450,'init'),
(322,15,'web_info','WEBSITE_NAME','','','站点名称',1634786999,'init',1634786999,'init'),
(323,15,'web_info','WEBSITE_LOGO','','image','站点 LOGO',1634786999,'init',1634786999,'init'),
(324,15,'web_info','TIMEZONE','America/New_York','','系统时区',1634786999,'init',1634786999,'init'),
(325,15,'web_info','TIMESTAMP','?1634786999','','静态资源时间戳',1634786999,'init',1634786999,'init'),
(326,15,'web_info','TKD_TITLE','','','站点 Meta Title（SEO 优化）',1634786999,'init',1634786999,'init'),
(327,15,'web_info','TKD_KEYWORDS','','','站点 Meta Keywords（SEO 优化）',1634786999,'init',1634786999,'init'),
(328,15,'web_info','TKD_DESCRIPTION','','','站点 Meta Description（SEO 优化）',1634786999,'init',1634786999,'init'),
(329,15,'web_info','WEIGHT_UNIT','','list','重量单位',1634786999,'init',1634786999,'init'),
(330,15,'web_info','SIZE_UNIT','','list','尺寸单位',1634786999,'init',1634786999,'init'),
(331,15,'web_info','INDEX_BOTTOM_TEXT','','','首页底部文案',1634786999,'init',1634786999,'init'),
(332,15,'redis','REDIS_HOST','127.0.0.1','','Redis 地址',1634786999,'init',1634786999,'init'),
(333,15,'redis','REDIS_PORT','16379','int','Redis 端口',1634786999,'init',1634786999,'init'),
(334,15,'redis','REDIS_AUTH','US5lLnQuZS5hLmUudC5lLk4uZS5JLmUuSi5lLkUuZS5MLmUueC5lLmEuZS5VLmUuUC5lLlEuZS5OLmUuVS5lLk0uZS5NLmUuUS5lLnQuZS5hLmUudC5lLk4uZS5JLmUuSi5lLkUuZS5MLmUueC5lLmEuZS5VLmUuUC5lLlEtLi4tVy5vLkEuby5TLm8uei5vLlQuby4yLm8uUy5vLnQuby5XLm8ucC5vLjIuby40Lm8uUy5vLnkuby5ULm8udS5vLlQuby56Lm8uVy5vLkEuby5TLm8uei5vLlQuby4yLm8uUy5vLnQuby5XLm8ucC5vLjIuby40Lm8uUy5vLj0=','password','Redis 认证',1634786999,'init',1634786999,'init'),
(335,15,'redis','REDIS_EXPIRE','1800','int','Redis 过期时间（秒）',1634786999,'init',1634786999,'init'),
(336,15,'oss','OSS_ACCESS_KEY_ID','','password','OSS 口令',1634786999,'init',1634786999,'init'),
(337,15,'oss','OSS_ACCESS_KEY_SECRET','','password','OSS 密钥',1634786999,'init',1634786999,'init'),
(338,15,'oss','OSS_ENDPOINT','','','OSS 终端',1634786999,'init',1634786999,'init'),
(339,15,'oss','OSS_BUCKET','','','OSS Bucket',1634786999,'init',1634786999,'init'),
(340,15,'oss','OSS_OPEN_CLOSE','close','radio','OSS 开启关闭',1634786999,'init',1634786999,'init'),
(341,15,'oss','FILE_HOST','','','文件服务地址',1634786999,'init',1634786999,'init'),
(342,15,'mail','SMTP_HOST','','','SMTP 服务地址',1634786999,'init',1634786999,'init'),
(343,15,'mail','SMTP_PORT','','','SMTP 服务端口',1634786999,'init',1634786999,'init'),
(344,15,'mail','SMTP_USERNAME','','','SMTP 服务发送邮箱',1634786999,'init',1634786999,'init'),
(345,15,'mail','SMTP_PASSWORD','','password','SMTP 服务发送密码',1634786999,'init',1634786999,'init'),
(346,15,'mail','CUSTOMER_SERVICE_EMAIL','','','客服邮箱',1634786999,'init',1634786999,'init'),
(347,15,'paypal','PAYPAL_CHECKOUT_URL','','','支付页链接',1634786999,'init',1634786999,'init'),
(348,15,'paypal','PAYPAL_API_URL','','','支付 API 链接',1634786999,'init',1634786999,'init'),
(349,15,'paypal','PAYPAL_API_CLIENT_ID','','password','API 客户端 ID',1634786999,'init',1634786999,'init'),
(350,15,'paypal','PAYPAL_API_SECRET','','password','API 密钥',1634786999,'init',1634786999,'init'),
(351,15,'paypal_cc','PAYPAL_CC_CHECKOUT_URL','','','支付页链接',1634786999,'init',1634786999,'init'),
(352,15,'paypal_cc','PAYPAL_CC_API_URL','','','支付 API 链接',1634786999,'init',1634786999,'init'),
(353,15,'paypal_cc','PAYPAL_CC_API_CLIENT_ID','','password','API 客户端 ID',1634786999,'init',1634786999,'init'),
(354,15,'paypal_cc','PAYPAL_CC_API_SECRET','','password','API 密钥',1634786999,'init',1634786999,'init');

/*Data for the table `hd_country` */

insert  into `hd_country`(`pk_id`,`shop_id`,`country_id`,`country_name`,`iso_code_2`,`iso_code_3`,`sort`,`icon_path`,`is_high_risk`,`currency_code`,`created_at`,`created_by`,`updated_at`,`updated_by`) values 
(3,1,2,'United Kingdom','GB','GBR',0,'',0,'USD',1630398359,'at0103',1630398359,'at0103'),
(5,1,1,'United States','US','USA',0,'',0,'USD',1636352643,'at0103',1636352643,'at0103');

/*Data for the table `hd_currency` */

insert  into `hd_currency`(`currency_id`,`shop_id`,`currency_name`,`currency_code`,`symbol_left`,`symbol_right`,`decimal_point`,`thousands_point`,`value`,`decimal_places`,`icon_path`,`sort`,`created_at`,`created_by`,`updated_at`,`updated_by`) values 
(1,1,'美元','USD','$','','.',',',1.00000000,2,'',0,0,'',1636366671,'at0103'),
(2,8,'美元','USD','$','','.',',',1.00000000,2,'',0,1634782877,'admin2021',1634782877,'admin2021'),
(3,12,'美元','USD','$','','.',',',1.00000000,2,'',0,1634784450,'init',1634784450,'init'),
(4,15,'美元','USD','$','','.',',',1.00000000,2,'',0,1634786999,'init',1634786999,'init');

/*Data for the table `hd_customer` */

insert  into `hd_customer`(`customer_id`,`shop_id`,`email`,`password`,`first_name`,`last_name`,`shipping_address_id`,`billing_address_id`,`ip_number`,`ip_country_iso_code_2`,`host_from`,`device_from`,`customer_type`,`is_guest`,`current_cart_skus`,`logined_failure_count`,`logined_at`,`registered_at`,`created_at`,`created_by`,`updated_at`,`updated_by`) values 
(1,1,'shouwenlai@foxmail.com','$2y$10$J0sBhlDIw3n/68Uq9r79iuPKyouB6ls1zhRjA/PxnfvU8fUXNfKJe','shouwen','lai',9,0,3232239682,'','www.swshop.com','PC','normal',0,NULL,0,0,1626054126,1626054126,'',1635320660,''),
(15,1,'abc@abc.com','$2y$10$eBSHc8nnp8RAyPFYlnRH0eTO3mKhnl6NLgADsHkJunVQ6HIzyxuem','','',11,0,2130706433,'','www.swshop.com','PC','normal',0,NULL,0,0,1634087369,1634087369,'at0103',1634087369,'at0103'),
(17,1,'1002214592@qq.com','$2y$10$464ekZw150GjPpTxSoZdGeFhIjHmp/JTX02zD64EmGwxyoYEJjPI6','','',0,0,2130706433,'','www.swshop.com','PC','normal',0,NULL,0,0,1634183578,1634183578,'',1634183578,'');

/*Data for the table `hd_customer_address` */

insert  into `hd_customer_address`(`customer_address_id`,`shop_id`,`customer_id`,`address_type`,`first_name`,`last_name`,`street_address`,`street_address_sub`,`postcode`,`city`,`zone_id`,`zone_name`,`country_id`,`country_name`,`telephone`,`created_at`,`created_by`,`updated_at`,`updated_by`) values 
(8,1,1,'shipping','Bertha','RR','699 Snider Street','','80112','Englewood',2,'Alabama',1,'United States','17202497522',1631517907,'',1631517907,''),
(9,1,1,'shipping','Lai','daobin','699 Snider Street','','80112','Englewood',1,'New York',1,'United States','17202497522',1631518177,'',1631518177,''),
(10,1,1,'shipping','lai','daobin','4114 Sepulveda Blvd','','90230','fuzhouCulver',1,'New York',1,'United States','0123659874',1631779075,'',1631779075,''),
(11,1,15,'shipping','Bertha','RR','699 Snider Street','','80112','Englewood',1,'New York',1,'United States','17202497522',1634088398,'at0103',1634088398,'at0103');

/*Data for the table `hd_customer_service` */

insert  into `hd_customer_service`(`customer_service_id`,`shop_id`,`customer_id`,`customer_name`,`customer_email`,`service_type`,`order_time`,`order_number`,`question`,`created_at`,`updated_at`) values 
(1,1,0,'Bertha RR','abc@abc.com','pre',0,'','000',1634198186,1634198186),
(2,1,0,'lai daobin','abc@qq.com','pre',0,'','00',1634198741,1634198741),
(3,1,0,'lai daobin','abc@abc.com','pre',0,'','你好，SKU为GOOD001的商品，我很喜欢，我想多买一些，请问下这个商品是否可以批发，批发数量和价格明细是否可以邮件给我?\r\n\r\n3Q',1634198944,1634198944),
(4,1,1,'shouwen lai','shouwenlai@foxmail.com','after',30,'HD202110140623ZYXN','ABC...\r\nWAN!!!\r\n\r\n你懂吗？？',1634204861,1634204861),
(5,1,1,'shouwen lai','shouwenlai@foxmail.com','after',30,'HD202110100904Y0MD','哇靠，太赞了啊！\r\n\r\n嘟嘟嘴~~~',1634205348,1634205348),
(6,1,1,'shouwen lai','shouwenlai@foxmail.com','after',0,'HD202110110347M2U0','什么情况？是我忘记了什么吗？',1634205398,1634205398),
(7,1,1,'shouwen lai','shouwenlai@foxmail.com','after',0,'HD202110110347M2U0','大区',1634205455,1634205455),
(8,1,1,'shouwen lai','shouwenlai@foxmail.com','after',0,'HD202110110347M2U0','OKOK 666',1634205584,1634205584),
(9,1,1,'shouwen lai','shouwenlai@foxmail.com','after',0,'HD202110110347M2U0','Hello dada..',1634205680,1634205680),
(10,1,1,'shouwen lai','shouwenlai@foxmail.com','after',30,'HD202110140604TYZN','111',1634285416,1634285416),
(11,1,1,'shouwen lai2','shouwenlai@foxmail.com','pre',0,'','2222',1634285427,1634285427),
(12,1,1,'shouwen lai2','shouwenlai@foxmail.com','pre',0,'','2222',1634285427,1634285427),
(13,1,1,'shouwen lai','shouwenlai@foxmail.com','pre',0,'','111',1634285705,1634285705);

/*Data for the table `hd_email_tpl` */

insert  into `hd_email_tpl`(`email_tpl_id`,`shop_id`,`subject`,`template`,`banner_images`,`created_at`,`created_by`,`updated_at`,`updated_by`) values 
(1,1,'Welcome','welcome','[]',0,'',1634192694,'at0103'),
(2,1,'Forgot the password','forgot_password',NULL,0,'',0,''),
(3,1,'Password reset successfully','password_success',NULL,0,'',0,''),
(4,1,'Customer question','customer_service',NULL,0,'',0,''),
(5,1,'Successfully ordered','order_success',NULL,0,'',0,''),
(6,15,'Welcome','welcome','',1634786999,'init',1634786999,'init'),
(7,15,'Forgot the password','forgot_password','',1634786999,'init',1634786999,'init'),
(8,15,'Password reset successfully','password_success','',1634786999,'init',1634786999,'init'),
(9,15,'Customer question','customer_service','',1634786999,'init',1634786999,'init'),
(10,15,'Successfully ordered','order_success','',1634786999,'init',1634786999,'init');

/*Data for the table `hd_forgot_password` */

insert  into `hd_forgot_password`(`forgot_password_id`,`shop_id`,`email`,`token`,`expired`,`status`,`created_at`,`updated_at`) values 
(1,1,'shouwenlai@foxmail.com','HD2021101503370YTK',1634270876,0,1634269076,1634269076),
(2,1,'shouwenlai@foxmail.com','HD202110150338DI2O',1634270920,0,1634269120,1634269120),
(3,1,'shouwenlai@foxmail.com','HD202110150338YXNJ',1634270928,0,1634269128,1634269128),
(4,1,'shouwenlai@foxmail.com','HD202110150339ZYXN',1634270963,0,1634269163,1634269163),
(5,1,'shouwenlai@foxmail.com','HD2021101503422OTM',1634271169,2,1634269369,1634279984),
(6,1,'shouwenlai@foxmail.com','HD202110150343YZND',1634271205,2,1634269405,1634282609),
(7,1,'shouwenlai@foxmail.com','HD202110150344MOTJ',1634271282,2,1634269482,1634282627),
(8,1,'1002214592@qq.com','HD202110150656MTYZ',1634282793,2,1634280993,1634282992),
(9,1,'1002214592@qq.com','HD202110150715KYYT',1634283930,2,1634282130,1634284124),
(10,1,'shouwenlai@foxmail.com','HD202110150749TYZN',1634285953,1,1634284153,1634284812),
(11,1,'shouwenlai@foxmail.com','HD202110270344NTMY',1635322440,1,1635320640,1635320660);

/*Data for the table `hd_language` */

insert  into `hd_language`(`language_id`,`shop_id`,`language_name`,`language_code`,`sort`,`created_at`,`created_by`,`updated_at`,`updated_by`) values 
(1,1,'英文','en',0,1629942999,'at0103',1629943098,'at0103'),
(2,8,'英文','en',0,1634782834,'admin2021',1634782834,'admin2021'),
(3,12,'英文','en',0,1634784450,'init',1634784450,'init'),
(4,15,'英文','en',0,1634786999,'init',1634786999,'init');

/*Data for the table `hd_order` */

insert  into `hd_order`(`order_id`,`shop_id`,`order_number`,`customer_id`,`customer_email`,`customer_name`,`order_status_id`,`shipping_method`,`shipping_code`,`payment_method`,`payment_code`,`currency_code`,`currency_value`,`order_total`,`default_currency_total`,`default_currency_code`,`warehouse_code`,`ip_number`,`ip_country_iso_code_2`,`host_from`,`device_from`,`order_type`,`is_guest`,`pp_token`,`created_at`,`created_by`,`updated_at`,`updated_by`) values 
(3,1,'HD202109260943TYZM',1,'shouwenlai@foxmail.com','shouwen lai',5,'Free Shipping','free','Credit Card','paypal_cc','USD',1.00000000,48.0000,48.0000,'USD','',0,'','','PC','normal',0,'41A23623FV8651253',1632649402,'',1633766979,'at0103'),
(4,1,'HD202109261005A0NW',1,'shouwenlai@foxmail.com','shouwen lai',5,'Free Shipping','free','Credit Card','paypal_cc','USD',1.00000000,48.0000,48.0000,'USD','',0,'','','PC','normal',0,'6RG89087X07037340',1632650717,'',1633766979,'at0103'),
(5,1,'HD202109270142MTYZ',1,'shouwenlai@foxmail.com','shouwen lai',2,'Free Shipping','free','Credit Card','paypal_cc','USD',1.00000000,48.0000,48.0000,'USD','',0,'','','PC','normal',0,'5MS47465FF321960M',1632706950,'',1633766979,'at0103'),
(6,1,'HD202109270924CZND',1,'shouwenlai@foxmail.com','shouwen lai',5,'Free Shipping','free','Credit Card','paypal_cc','USD',1.00000000,110.0000,110.0000,'USD','',0,'','','PC','normal',0,'7FJ57584JE833953T',1632734677,'',1633766979,'at0103'),
(9,1,'HD202109280834A4NT',1,'shouwenlai@foxmail.com','shouwen lai',1,'Free Shipping','free','Paypal','paypal','USD',1.00000000,8.0000,8.0000,'USD','',2130706433,'','www.swshop.com','PC','normal',0,'37D84604TD7925509',1632818085,'',1633766979,'at0103'),
(10,1,'HD202109280835YXNT',1,'shouwenlai@foxmail.com','shouwen lai',3,'Free Shipping','free','Paypal','paypal','USD',1.00000000,8.0000,8.0000,'USD','',2130706433,'','www.swshop.com','PC','normal',0,'82M761316D6994936',1632818146,'',1633766979,'at0103'),
(11,1,'HD202109280840TYZM',1,'shouwenlai@foxmail.com','shouwen lai',3,'Free Shipping','free','Paypal','paypal','USD',1.00000000,8.0000,8.0000,'USD','',2130706433,'','www.swshop.com','PC','normal',0,'83284696KW133222K',1632818457,'',1633766979,'at0103'),
(12,1,'HD202109280842XNTJ',1,'shouwenlai@foxmail.com','shouwen lai',2,'Free Shipping','free','Credit Card','paypal_cc','USD',1.00000000,8.0000,8.0000,'USD','',2130706433,'','www.swshop.com','PC','normal',0,'4LV13458AJ460500M',1632818579,'',1633766979,'at0103'),
(13,1,'HD202109280846MJGX',1,'shouwenlai@foxmail.com','shouwen lai',2,'Free Shipping','free','Credit Card','paypal_cc','USD',1.00000000,2.0000,2.0000,'USD','',2130706433,'','www.swshop.com','PC','normal',0,'6WL69203WF258200J',1632818775,'',1633766979,'at0103'),
(14,1,'HD202109280906MJGX',1,'shouwenlai@foxmail.com','shouwen lai',5,'Free Shipping','free','Paypal','paypal','USD',1.00000000,11.0000,11.0000,'USD','',2130706433,'','www.swshop.com','PC','normal',0,'0B959419MD316560A',1632819967,'',1633766979,'at0103'),
(15,1,'HD202109280907GYMD',1,'shouwenlai@foxmail.com','shouwen lai',3,'Free Shipping','free','Paypal','paypal','USD',1.00000000,11.0000,11.0000,'USD','',2130706433,'','www.swshop.com','M','normal',0,'9P727478A77285826',1632820065,'',1633766979,'at0103'),
(16,1,'HD202110090148NZYX',1,'shouwenlai@foxmail.com','shouwen lai',3,'Free Shipping','free','Credit Card','paypal_cc','USD',1.00000000,2.0000,2.0000,'USD','',2130706433,'','www.swshop.com','PC','normal',0,'5W8578341V4863422',1633744127,'',1633852399,'at0103'),
(17,1,'HD202110100836NTAX',1,'shouwenlai@foxmail.com','shouwen lai',2,'Free Shipping','free','Paypal','paypal','USD',1.00000000,2.0000,2.0000,'USD','',2130706433,'','www.swshop.com','PC','normal',0,'139636855H4809049',1633855014,'',1633855036,''),
(18,1,'HD202110100851MGMZ',1,'shouwenlai@foxmail.com','shouwen lai',2,'Free Shipping','free','Paypal','paypal','USD',1.00000000,1.0000,1.0000,'USD','',2130706433,'','www.swshop.com','PC','normal',0,'1RA94236F07779940',1633855879,'',1633855888,''),
(19,1,'HD202110100853TK4N',1,'shouwenlai@foxmail.com','shouwen lai',2,'Free Shipping','free','Credit Card','paypal_cc','USD',1.00000000,1.0000,1.0000,'USD','',2130706433,'','www.swshop.com','PC','normal',0,'66G33306805800235',1633855986,'',1633856280,''),
(20,1,'HD202110100904Y0MD',1,'shouwenlai@foxmail.com','shouwen lai',2,'Free Shipping','free','Credit Card','paypal_cc','USD',1.00000000,22.0000,22.0000,'USD','',2130706433,'','www.swshop.com','PC','normal',0,'8SK10275F3856735H',1633856640,'',1633856690,''),
(21,1,'HD202110110347M2U0',1,'shouwenlai@foxmail.com','shouwen lai',5,'Free Shipping','free','Paypal','paypal','USD',1.00000000,11.0000,11.0000,'USD','',2130706433,'','www.swshop.com','M','normal',0,'58V12815US876011X',1633924068,'',1633924119,''),
(22,1,'HD202110130126NWQ0',15,'abc@abc.com','',2,'Free Shipping','free','Credit Card','paypal_cc','USD',1.00000000,22.0000,22.0000,'USD','',2130706433,'','www.swshop.com','PC','normal',0,'45792009PC2848046',1634088404,'',1634088548,''),
(23,1,'HD202110130130MJJM',15,'abc@abc.com','',5,'Free Shipping','free','Paypal','paypal','USD',1.00000000,2.0000,2.0000,'USD','',2130706433,'','www.swshop.com','PC','normal',0,'7WN41475AR857170J',1634088636,'',1634088680,''),
(24,1,'HD202110140604TYZN',1,'shouwenlai@foxmail.com','shouwen lai',2,'Free Shipping','free','Paypal','paypal','USD',1.00000000,6.0000,6.0000,'USD','',2130706433,'','www.swshop.com','PC','normal',0,'65S736006S793021B',1634191448,'',1634191488,''),
(25,1,'HD202110140623ZYXN',1,'shouwenlai@foxmail.com','shouwen lai',2,'Free Shipping','free','Paypal','paypal','USD',1.00000000,2.0000,2.0000,'USD','',2130706433,'','www.swshop.com','PC','normal',0,'10C88891U4540144K',1634192587,'',1634192600,''),
(26,1,'HD202110252159MGYY',1,'shouwenlai@foxmail.com','shouwen lai',1,'Free Shipping','free','Credit Card','paypal_cc','USD',1.00000000,0.1900,0.1900,'USD','',2130706433,'','www.swshop.com','PC','normal',0,'6CU02633RY881530C',1635213554,'',1635213554,''),
(27,1,'HD202110270344NTMY',1,'shouwenlai@foxmail.com','shouwen lai',5,'Free Shipping','free','Credit Card','paypal_cc','USD',1.00000000,0.1900,0.1900,'USD','',2130706433,'','www.swshop.com','PC','normal',0,'6VL522431M456642B',1635320684,'',1635320895,''),
(28,1,'HD202111032328MTYZ',1,'shouwenlai@foxmail.com','shouwen lai',1,'Free Shipping','free','Credit Card','paypal_cc','USD',1.00000000,0.1900,0.1900,'USD','',2130706433,'','www.swshop.com','PC','normal',0,'55R22675RH554800D',1635996501,'',1635996501,''),
(29,1,'HD202111032330K5NJ',1,'shouwenlai@foxmail.com','shouwen lai',1,'Free Shipping','free','Credit Card','paypal_cc','USD',1.00000000,0.1900,0.1900,'USD','',2130706433,'','www.swshop.com','PC','normal',0,'0MS9203330729673X',1635996623,'',1635996623,'');

/*Data for the table `hd_order_address` */

insert  into `hd_order_address`(`order_address_id`,`shop_id`,`order_id`,`address_type`,`first_name`,`last_name`,`street_address`,`street_address_sub`,`postcode`,`city`,`zone_id`,`zone_name`,`country_id`,`country_name`,`telephone`,`created_at`,`created_by`) values 
(2,1,3,'shipping','Lai','daobin','699 Snider Street','','80112','Englewood',1,'New York',1,'United States','17202497522',1632649402,''),
(3,1,4,'shipping','Lai','daobin','699 Snider Street','','80112','Englewood',1,'New York',1,'United States','17202497522',1632650717,''),
(4,1,5,'shipping','Lai','daobin','699 Snider Street','','80112','Englewood',1,'New York',1,'United States','17202497522',1632706950,''),
(5,1,6,'shipping','Lai','daobin','699 Snider Street','','80112','Englewood',1,'New York',1,'United States','17202497522',1632734677,''),
(8,1,9,'shipping','Lai','daobin','699 Snider Street','','80112','Englewood',1,'New York',1,'United States','17202497522',1632818085,''),
(9,1,10,'shipping','Lai','daobin','699 Snider Street','','80112','Englewood',1,'New York',1,'United States','17202497522',1632818146,''),
(10,1,11,'shipping','Lai','daobin','699 Snider Street','','80112','Englewood',1,'New York',1,'United States','17202497522',1632818457,''),
(11,1,12,'shipping','Lai','daobin','699 Snider Street','','80112','Englewood',1,'New York',1,'United States','17202497522',1632818579,''),
(12,1,13,'shipping','Lai','daobin','699 Snider Street','','80112','Englewood',1,'New York',1,'United States','17202497522',1632818775,''),
(13,1,14,'shipping','Lai','daobin','699 Snider Street','','80112','Englewood',1,'New York',1,'United States','17202497522',1632819967,''),
(14,1,15,'shipping','Lai','daobin','699 Snider Street','','80112','Englewood',1,'New York',1,'United States','17202497522',1632820065,''),
(15,1,16,'shipping','Lai','daobin','699 Snider Street','','80112','Englewood',1,'New York',1,'United States','17202497522',1633744127,''),
(16,1,17,'shipping','Lai','daobin','699 Snider Street','','80112','Englewood',1,'New York',1,'United States','17202497522',1633855014,''),
(17,1,18,'shipping','Lai','daobin','699 Snider Street','','80112','Englewood',1,'New York',1,'United States','17202497522',1633855879,''),
(18,1,19,'shipping','Lai','daobin','699 Snider Street','','80112','Englewood',1,'New York',1,'United States','17202497522',1633855986,''),
(19,1,20,'shipping','Lai','daobin','699 Snider Street','','80112','Englewood',1,'New York',1,'United States','17202497522',1633856640,''),
(20,1,21,'shipping','Lai','daobin','699 Snider Street','','80112','Englewood',1,'New York',1,'United States','17202497522',1633924068,''),
(21,1,22,'shipping','Bertha','RR','699 Snider Street','','80112','Englewood',1,'New York',1,'United States','17202497522',1634088404,''),
(22,1,23,'shipping','Bertha','RR','699 Snider Street','','80112','Englewood',1,'New York',1,'United States','17202497522',1634088636,''),
(23,1,24,'shipping','Lai','daobin','699 Snider Street','','80112','Englewood',1,'New York',1,'United States','17202497522',1634191448,''),
(24,1,25,'shipping','Lai','daobin','699 Snider Street','','80112','Englewood',1,'New York',1,'United States','17202497522',1634192587,''),
(25,1,26,'shipping','Lai','daobin','699 Snider Street','','80112','Englewood',1,'New York',1,'United States','17202497522',1635213554,''),
(26,1,27,'shipping','Lai','daobin','699 Snider Street','','80112','Englewood',1,'New York',1,'United States','17202497522',1635320684,''),
(27,1,28,'shipping','Lai','daobin','699 Snider Street','','80112','Englewood',1,'New York',1,'United States','17202497522',1635996501,''),
(28,1,29,'shipping','Lai','daobin','699 Snider Street','','80112','Englewood',1,'New York',1,'United States','17202497522',1635996623,'');

/*Data for the table `hd_order_note` */

/*Data for the table `hd_order_product` */

insert  into `hd_order_product`(`order_product_id`,`shop_id`,`order_id`,`product_id`,`product_name`,`sku`,`qty`,`price`,`default_currency_price`,`created_at`) values 
(1,1,3,1,'My Guitar','GT0001',2,2.0000,2.0000,1632649402),
(2,1,3,2,'ABC','ABC003',2,22.0000,22.0000,1632649402),
(3,1,4,1,'My Guitar','GT0001',2,2.0000,2.0000,1632650717),
(4,1,4,2,'ABC','ABC003',2,22.0000,22.0000,1632650717),
(5,1,5,1,'My Guitar','GT0001',2,2.0000,2.0000,1632706950),
(6,1,5,2,'ABC','ABC003',2,22.0000,22.0000,1632706950),
(7,1,6,2,'ABC','ABC003',5,22.0000,22.0000,1632734677),
(14,1,9,3,'DDD','DDD',1,1.0000,1.0000,1632818085),
(15,1,9,1,'My Guitar','GT0001',3,2.0000,2.0000,1632818085),
(16,1,9,1,'My Guitar','GT0002',1,1.0000,1.0000,1632818085),
(17,1,10,3,'DDD','DDD',1,1.0000,1.0000,1632818146),
(18,1,10,1,'My Guitar','GT0001',3,2.0000,2.0000,1632818146),
(19,1,10,1,'My Guitar','GT0002',1,1.0000,1.0000,1632818146),
(20,1,11,3,'DDD','DDD',1,1.0000,1.0000,1632818457),
(21,1,11,1,'My Guitar','GT0001',3,2.0000,2.0000,1632818457),
(22,1,11,1,'My Guitar','GT0002',1,1.0000,1.0000,1632818457),
(23,1,12,3,'DDD','DDD',1,1.0000,1.0000,1632818579),
(24,1,12,1,'My Guitar','GT0001',3,2.0000,2.0000,1632818579),
(25,1,12,1,'My Guitar','GT0002',1,1.0000,1.0000,1632818579),
(26,1,13,1,'My Guitar','GT0001',1,2.0000,2.0000,1632818775),
(27,1,14,4,'Good Guitar for you','GG0001',1,11.0000,11.0000,1632819967),
(28,1,15,4,'Good Guitar for you','GG0001',1,11.0000,11.0000,1632820065),
(29,1,16,1,'My Guitar','GT0001',1,2.0000,2.0000,1633744127),
(30,1,17,1,'My Guitar','GT0001',1,2.0000,2.0000,1633855014),
(31,1,18,3,'DDD','DDD',1,1.0000,1.0000,1633855879),
(32,1,19,3,'DDD','DDD',1,1.0000,1.0000,1633855986),
(33,1,20,2,'ABC','ABC003',1,22.0000,22.0000,1633856640),
(34,1,21,4,'Good Guitar for you','GG0001',1,11.0000,11.0000,1633924068),
(35,1,22,2,'ABC','ABC003',1,22.0000,22.0000,1634088404),
(36,1,23,1,'My Guitar','GT0001',1,2.0000,2.0000,1634088636),
(37,1,24,1,'My Guitar','GT0001',3,2.0000,2.0000,1634191448),
(38,1,25,1,'My Guitar','GT0001',1,2.0000,2.0000,1634192587),
(39,1,26,3,'DDD','DDD',1,0.1900,0.1900,1635213554),
(40,1,27,3,'DDD','DDD',1,0.1900,0.1900,1635320684),
(41,1,28,3,'DDD','DDD',1,0.1900,0.1900,1635996501),
(42,1,29,3,'DDD','DDD',1,0.1900,0.1900,1635996623);

/*Data for the table `hd_order_status_history` */

insert  into `hd_order_status_history`(`order_sh_id`,`shop_id`,`order_id`,`order_status_id`,`is_show`,`comment`,`created_at`,`created_by`) values 
(1,1,3,1,1,'Your order has been created successfully, and the order is waiting for payment.',1632649402,''),
(2,1,3,5,1,'Your order has been cancelled. If you have any questions, please contact the customer service center!',1632649411,''),
(3,1,3,5,0,'The user actively cancels the payment on the Paypal page.',1632649412,''),
(4,1,4,1,1,'Your order has been created successfully, and the order is waiting for payment.',1632650717,''),
(5,1,4,5,1,'Your order has been cancelled. If you have any questions, please contact the customer service center!',1632706350,''),
(6,1,4,5,0,'The user actively cancels the payment on the Paypal page.',1632706350,''),
(7,1,5,1,1,'Your order has been created successfully, and the order is waiting for payment.',1632706950,''),
(8,1,5,2,1,'Your order is being reviewed, at most 10 minutes required.',1632707021,''),
(9,1,5,2,0,'Txn ID: 1P801601GJ603524H<br/>Timestamp: 2021-09-27T01:43:58Z<br/>Payment Status: Authorization Created<br/>Currency: USD<br/>Amount: 48.00',1632707021,''),
(10,1,6,1,1,'Your order has been created successfully, and the order is waiting for payment.',1632734677,''),
(11,1,6,5,1,'Your order has been cancelled. If you have any questions, please contact the customer service center!',1632734690,''),
(12,1,6,5,0,'The user actively cancels the payment on the Paypal page.',1632734690,''),
(13,1,9,1,1,'Your order has been created successfully, and the order is waiting for payment.',1632818085,''),
(14,1,10,1,1,'Your order has been created successfully, and the order is waiting for payment.',1632818146,''),
(15,1,10,3,1,'Your payment has been received and the order is being processed.',1632818192,''),
(16,1,10,3,0,'Txn ID: <br/>Timestamp: <br/>Payment Status: Capture<br/>Currency: <br/>Amount: 0.00',1632818192,''),
(17,1,11,1,1,'Your order has been created successfully, and the order is waiting for payment.',1632818457,''),
(18,1,11,3,1,'Your payment has been received and the order is being processed.',1632818494,''),
(19,1,11,3,0,'Txn ID: <br/>Timestamp: <br/>Payment Status: Capture<br/>Currency: <br/>Amount: 0.00',1632818494,''),
(20,1,12,1,1,'Your order has been created successfully, and the order is waiting for payment.',1632818579,''),
(21,1,12,2,1,'Your order is being reviewed, at most 10 minutes required.',1632818665,''),
(22,1,12,2,0,'Txn ID: 18Y93489BM822852J<br/>Timestamp: 2021-09-28T08:44:42Z<br/>Payment Status: Authorization Created<br/>Currency: USD<br/>Amount: 8.00',1632818665,''),
(23,1,13,1,1,'Your order has been created successfully, and the order is waiting for payment.',1632818775,''),
(24,1,13,2,1,'Your order is being reviewed, at most 10 minutes required.',1632818837,''),
(25,1,13,2,0,'Txn ID: 3P714123CF4462945<br/>Timestamp: 2021-09-28T08:47:34Z<br/>Payment Status: Authorization Created<br/>Currency: USD<br/>Amount: 2.00',1632818837,''),
(26,1,14,1,1,'Your order has been created successfully, and the order is waiting for payment.',1632819967,''),
(27,1,14,5,1,'Your order has been cancelled. If you have any questions, please contact the customer service center!',1632819975,''),
(28,1,14,5,0,'The user actively cancels the payment on the Paypal page.',1632819975,''),
(29,1,15,1,1,'Your order has been created successfully, and the order is waiting for payment.',1632820065,''),
(30,1,15,3,1,'Your payment has been received and the order is being processed.',1632820104,''),
(31,1,15,3,0,'Txn ID: <br/>Timestamp: <br/>Payment Status: Capture<br/>Currency: <br/>Amount: 0.00',1632820104,''),
(32,1,16,1,1,'Your order has been created successfully, and the order is waiting for payment.',1633744127,''),
(33,1,16,2,1,'Your order is being reviewed, at most 10 minutes required.',1633852274,'at0103'),
(34,1,16,3,1,'Your order has been processed',1633852399,'at0103'),
(35,1,17,1,1,'Your order has been created successfully, and the order is waiting for payment.',1633855014,''),
(36,1,17,2,1,'Your order is being reviewed, at most 10 minutes required.',1633855036,''),
(37,1,17,2,0,'Txn ID: <br/>Timestamp: <br/>Payment Status: Capture<br/>Currency: <br/>Amount: 0.00',1633855036,''),
(38,1,18,1,1,'Your order has been created successfully, and the order is waiting for payment.',1633855879,''),
(39,1,18,2,1,'Your order is being reviewed, at most 10 minutes required.',1633855888,''),
(40,1,18,2,0,'Txn ID: 4VG88070VH892405C<br/>Timestamp: 2021-10-10T08:51:21Z<br/>Payment Status: COMPLETED<br/>Currency: USD<br/>Amount: 1.00',1633855888,''),
(41,1,19,1,1,'Your order has been created successfully, and the order is waiting for payment.',1633855986,''),
(42,1,19,2,1,'Your order is being reviewed, at most 10 minutes required.',1633856280,''),
(43,1,19,2,0,'Txn ID: 0GS80549PU1880519<br/>Timestamp: 2021-10-10T08:57:54Z<br/>Payment Status: Authorization Created<br/>Currency: USD<br/>Amount: 1.00',1633856280,''),
(44,1,20,1,1,'Your order has been created successfully, and the order is waiting for payment.',1633856640,''),
(45,1,20,2,1,'Your order is being reviewed, at most 10 minutes required.',1633856690,''),
(46,1,20,2,0,'Txn ID: 52Y90347N9669062Y<br/>Timestamp: 2021-10-10T09:04:43Z<br/>Payment Status: Authorization Created<br/>Currency: USD<br/>Amount: 22.00',1633856690,''),
(47,1,21,1,1,'Your order has been created successfully, and the order is waiting for payment.',1633924068,''),
(48,1,21,5,1,'Your order has been cancelled. If you have any questions, please contact the customer service center!',1633924119,''),
(49,1,21,5,0,'The user actively cancels the payment on the Paypal page.',1633924119,''),
(50,1,22,1,1,'Your order has been created successfully, and the order is waiting for payment.',1634088404,''),
(51,1,22,2,1,'Your order is being reviewed, at most 10 minutes required.',1634088548,''),
(52,1,22,2,0,'Txn ID: 0FY59488NX7142643<br/>Timestamp: 2021-10-13T01:29:02Z<br/>Payment Status: Authorization Created<br/>Currency: USD<br/>Amount: 22.00',1634088548,''),
(53,1,23,1,1,'Your order has been created successfully, and the order is waiting for payment.',1634088636,''),
(54,1,23,5,1,'Your order has been cancelled. If you have any questions, please contact the customer service center!',1634088680,''),
(55,1,23,5,0,'The user actively cancels the payment on the Paypal page.',1634088680,''),
(56,1,24,1,1,'Your order has been created successfully, and the order is waiting for payment.',1634191448,''),
(57,1,24,2,1,'Your order is being reviewed, at most 10 minutes required.',1634191488,''),
(58,1,24,2,0,'Txn ID: 1MU916249V923194L<br/>Timestamp: 2021-10-14T06:04:42Z<br/>Payment Status: COMPLETED<br/>Currency: USD<br/>Amount: 6.00',1634191488,''),
(59,1,25,1,1,'Your order has been created successfully, and the order is waiting for payment.',1634192587,''),
(60,1,25,2,1,'Your order is being reviewed, at most 10 minutes required.',1634192600,''),
(61,1,25,2,0,'Txn ID: 0FD6177224833151C<br/>Timestamp: 2021-10-14T06:23:13Z<br/>Payment Status: COMPLETED<br/>Currency: USD<br/>Amount: 2.00',1634192600,''),
(62,1,26,1,1,'Your order has been created successfully, and the order is waiting for payment.',1635213554,''),
(63,1,27,1,1,'Your order has been created successfully, and the order is waiting for payment.',1635320684,''),
(64,1,27,5,1,'Your order has been cancelled. If you have any questions, please contact the customer service center!',1635320895,''),
(65,1,27,5,0,'The user actively cancels the payment on the Paypal page.',1635320895,''),
(66,1,28,1,1,'Your order has been created successfully, and the order is waiting for payment.',1635996501,''),
(67,1,29,1,1,'Your order has been created successfully, and the order is waiting for payment.',1635996623,'');

/*Data for the table `hd_order_total` */

insert  into `hd_order_total`(`order_total_id`,`shop_id`,`order_id`,`ot_class`,`ot_title`,`ot_text`,`price`,`default_currency_price`,`created_at`) values 
(1,1,3,'subtotal','Subtotal','$48.00',48.0000,48.0000,1632649402),
(2,1,3,'shipping','Shipping Fee','$0.00',0.0000,0.0000,1632649402),
(3,1,3,'total','Total','$48.00',48.0000,48.0000,1632649402),
(4,1,4,'subtotal','Subtotal','$48.00',48.0000,48.0000,1632650717),
(5,1,4,'shipping','Shipping Fee','$0.00',0.0000,0.0000,1632650717),
(6,1,4,'total','Total','$48.00',48.0000,48.0000,1632650717),
(7,1,5,'subtotal','Subtotal','$48.00',48.0000,48.0000,1632706950),
(8,1,5,'shipping','Shipping Fee','$0.00',0.0000,0.0000,1632706950),
(9,1,5,'total','Total','$48.00',48.0000,48.0000,1632706950),
(10,1,6,'subtotal','Subtotal','$110.00',110.0000,110.0000,1632734677),
(11,1,6,'shipping','Shipping Fee','$0.00',0.0000,0.0000,1632734677),
(12,1,6,'total','Total','$110.00',110.0000,110.0000,1632734677),
(13,1,9,'subtotal','Subtotal','$8.00',8.0000,8.0000,1632818085),
(14,1,9,'shipping','Shipping Fee','$0.00',0.0000,0.0000,1632818085),
(15,1,9,'total','Total','$8.00',8.0000,8.0000,1632818085),
(16,1,10,'subtotal','Subtotal','$8.00',8.0000,8.0000,1632818146),
(17,1,10,'shipping','Shipping Fee','$0.00',0.0000,0.0000,1632818146),
(18,1,10,'total','Total','$8.00',8.0000,8.0000,1632818146),
(19,1,11,'subtotal','Subtotal','$8.00',8.0000,8.0000,1632818457),
(20,1,11,'shipping','Shipping Fee','$0.00',0.0000,0.0000,1632818457),
(21,1,11,'total','Total','$8.00',8.0000,8.0000,1632818457),
(22,1,12,'subtotal','Subtotal','$8.00',8.0000,8.0000,1632818579),
(23,1,12,'shipping','Shipping Fee','$0.00',0.0000,0.0000,1632818579),
(24,1,12,'total','Total','$8.00',8.0000,8.0000,1632818579),
(25,1,13,'subtotal','Subtotal','$2.00',2.0000,2.0000,1632818775),
(26,1,13,'shipping','Shipping Fee','$0.00',0.0000,0.0000,1632818775),
(27,1,13,'total','Total','$2.00',2.0000,2.0000,1632818775),
(28,1,14,'subtotal','Subtotal','$11.00',11.0000,11.0000,1632819967),
(29,1,14,'shipping','Shipping Fee','$0.00',0.0000,0.0000,1632819967),
(30,1,14,'total','Total','$11.00',11.0000,11.0000,1632819967),
(31,1,15,'subtotal','Subtotal','$11.00',11.0000,11.0000,1632820065),
(32,1,15,'shipping','Shipping Fee','$0.00',0.0000,0.0000,1632820065),
(33,1,15,'total','Total','$11.00',11.0000,11.0000,1632820065),
(34,1,16,'subtotal','Subtotal','$2.00',2.0000,2.0000,1633744127),
(35,1,16,'shipping','Shipping Fee','$0.00',0.0000,0.0000,1633744127),
(36,1,16,'total','Total','$2.00',2.0000,2.0000,1633744127),
(37,1,17,'subtotal','Subtotal','$2.00',2.0000,2.0000,1633855014),
(38,1,17,'shipping','Shipping Fee','$0.00',0.0000,0.0000,1633855014),
(39,1,17,'total','Total','$2.00',2.0000,2.0000,1633855014),
(40,1,18,'subtotal','Subtotal','$1.00',1.0000,1.0000,1633855879),
(41,1,18,'shipping','Shipping Fee','$0.00',0.0000,0.0000,1633855879),
(42,1,18,'total','Total','$1.00',1.0000,1.0000,1633855879),
(43,1,19,'subtotal','Subtotal','$1.00',1.0000,1.0000,1633855986),
(44,1,19,'shipping','Shipping Fee','$0.00',0.0000,0.0000,1633855986),
(45,1,19,'total','Total','$1.00',1.0000,1.0000,1633855986),
(46,1,20,'subtotal','Subtotal','$22.00',22.0000,22.0000,1633856640),
(47,1,20,'shipping','Shipping Fee','$0.00',0.0000,0.0000,1633856640),
(48,1,20,'total','Total','$22.00',22.0000,22.0000,1633856640),
(49,1,21,'subtotal','Subtotal','$11.00',11.0000,11.0000,1633924068),
(50,1,21,'shipping','Shipping Fee','$0.00',0.0000,0.0000,1633924068),
(51,1,21,'total','Total','$11.00',11.0000,11.0000,1633924068),
(52,1,22,'subtotal','Subtotal','$22.00',22.0000,22.0000,1634088404),
(53,1,22,'shipping','Shipping Fee','$0.00',0.0000,0.0000,1634088404),
(54,1,22,'total','Total','$22.00',22.0000,22.0000,1634088404),
(55,1,23,'subtotal','Subtotal','$2.00',2.0000,2.0000,1634088636),
(56,1,23,'shipping','Shipping Fee','$0.00',0.0000,0.0000,1634088636),
(57,1,23,'total','Total','$2.00',2.0000,2.0000,1634088636),
(58,1,24,'subtotal','Subtotal','$6.00',6.0000,6.0000,1634191448),
(59,1,24,'shipping','Shipping Fee','$0.00',0.0000,0.0000,1634191448),
(60,1,24,'total','Total','$6.00',6.0000,6.0000,1634191448),
(61,1,25,'subtotal','Subtotal','$2.00',2.0000,2.0000,1634192587),
(62,1,25,'shipping','Shipping Fee','$0.00',0.0000,0.0000,1634192587),
(63,1,25,'total','Total','$2.00',2.0000,2.0000,1634192587),
(64,1,26,'subtotal','Subtotal','$0.19',0.1900,0.1900,1635213554),
(65,1,26,'shipping','Shipping Fee','$0.00',0.0000,0.0000,1635213554),
(66,1,26,'total','Total','$0.19',0.1900,0.1900,1635213554),
(67,1,27,'subtotal','Subtotal','$0.19',0.1900,0.1900,1635320684),
(68,1,27,'shipping','Shipping Fee','$0.00',0.0000,0.0000,1635320684),
(69,1,27,'total','Total','$0.19',0.1900,0.1900,1635320684),
(70,1,28,'subtotal','Subtotal','$0.19',0.1900,0.1900,1635996501),
(71,1,28,'shipping','Shipping Fee','$0.00',0.0000,0.0000,1635996501),
(72,1,28,'total','Total','$0.19',0.1900,0.1900,1635996501),
(73,1,29,'subtotal','Subtotal','$0.19',0.1900,0.1900,1635996623),
(74,1,29,'shipping','Shipping Fee','$0.00',0.0000,0.0000,1635996623),
(75,1,29,'total','Total','$0.19',0.1900,0.1900,1635996623);

/*Data for the table `hd_payment_method` */

insert  into `hd_payment_method`(`payment_method_id`,`shop_id`,`method_name`,`method_code`,`sort`,`created_at`,`created_by`,`updated_at`,`updated_by`) values 
(1,1,'Paypal','paypal',1,1631757774,'at0103',1632820042,'at0103'),
(3,1,'Credit Card','paypal_cc',0,1631763860,'at0103',1635212399,'at0103');

/*Data for the table `hd_paypal` */

insert  into `hd_paypal`(`paypal_id`,`shop_id`,`order_id`,`operation`,`ack`,`payment_code`,`payment_status`,`payment_date`,`txn_id`,`currency_code`,`amount`,`payer_email`,`payer_id`,`created_at`) values 
(1,1,5,'AuthorizeOrder','success','paypal_cc','Authorization','2021-09-27 01:43:58','1P801601GJ603524H','USD',48.00000000,'','',1632707021),
(2,1,10,'CaptureOrder','fail','paypal','COMPLETED','0000-00-00 00:00:00','','',0.00000000,'','',1632818192),
(3,1,11,'CaptureOrder','fail','paypal','COMPLETED','0000-00-00 00:00:00','','',0.00000000,'','',1632818494),
(4,1,12,'AuthorizeOrder','success','paypal_cc','Authorization','2021-09-28 08:44:42','18Y93489BM822852J','USD',8.00000000,'','',1632818665),
(5,1,13,'AuthorizeOrder','success','paypal_cc','Authorization','2021-09-28 08:47:34','3P714123CF4462945','USD',2.00000000,'','',1632818837),
(6,1,15,'CaptureOrder','success','paypal','COMPLETED','0000-00-00 00:00:00','','',0.00000000,'','',1632820104),
(7,1,17,'CaptureOrder','success','paypal','COMPLETED','0000-00-00 00:00:00','','',0.00000000,'','',1633855036),
(8,1,18,'CaptureOrder','success','paypal','COMPLETED','2021-10-10 08:51:21','4VG88070VH892405C','USD',1.00000000,'shouwenlai-buyer@foxmail.com','H4GTQ43ZJ7QWA',1633855888),
(9,1,19,'AuthorizeOrder','success','paypal_cc','Authorization','2021-10-10 08:57:54','0GS80549PU1880519','USD',1.00000000,'','',1633856280),
(10,1,20,'AuthorizeOrder','success','paypal_cc','Authorization','2021-10-10 09:04:43','52Y90347N9669062Y','USD',22.00000000,'abc@qq.com','79SMVUQ6JJXD2',1633856690),
(11,1,22,'AuthorizeOrder','success','paypal_cc','Authorization','2021-10-13 01:29:02','0FY59488NX7142643','USD',22.00000000,'abc@qq.com','9BDREQHDR9LXU',1634088548),
(12,1,24,'CaptureOrder','success','paypal','COMPLETED','2021-10-14 06:04:42','1MU916249V923194L','USD',6.00000000,'shouwenlai-buyer@foxmail.com','H4GTQ43ZJ7QWA',1634191488),
(13,1,25,'CaptureOrder','success','paypal','COMPLETED','2021-10-14 06:23:13','0FD6177224833151C','USD',2.00000000,'shouwenlai-buyer@foxmail.com','H4GTQ43ZJ7QWA',1634192600);

/*Data for the table `hd_product` */

insert  into `hd_product`(`product_id`,`shop_id`,`product_category_id`,`product_status`,`product_url`,`sort`,`is_sold_out`,`price`,`weight`,`weight_unit`,`width`,`length`,`height`,`size_unit`,`created_at`,`created_by`,`updated_at`,`updated_by`) values 
(1,1,2,2,'folk-pop-guitar',1,0,2.0000,5.0000,'Kg',0.1000,0.3000,1.0000,'m',1627636036,'at0103',1634622501,'at0103'),
(2,1,3,1,'abc',0,0,22.0000,0.0000,'',0.0000,0.0000,0.0000,'',1629701540,'at0103',1634545362,'at0103'),
(3,1,3,1,'ddd',0,0,0.1900,0.0000,'',0.0000,0.0000,0.0000,'',1629701571,'at0103',1635212479,'at0103'),
(4,1,3,1,'good-guitar-for-you',2,1,10.0000,0.0000,'',0.0000,0.0000,0.0000,'',1629790029,'at0103',1634615514,'at0103');

/*Data for the table `hd_product_category` */

insert  into `hd_product_category`(`product_category_id`,`shop_id`,`parent_id`,`category_url`,`redirect_link`,`sort`,`category_status`,`product_show_size`,`review_show_size`,`created_at`,`created_by`,`updated_at`,`updated_by`) values 
(1,1,0,'guitar','',1,1,20,0,1627293629,'at0103',1629969871,'at0103'),
(2,1,1,'forl-guitar','',10,1,20,0,1627293781,'at0103',1633860055,'at0103'),
(3,1,1,'electric-guitar','',0,1,20,0,1627293815,'at0103',1634614856,'at0103'),
(4,1,0,'music','',0,1,0,0,1629798765,'at0103',1629798765,'at0103'),
(5,1,2,'333','',0,0,0,0,1629962043,'at0103',1629962043,'at0103');

/*Data for the table `hd_product_category_description` */

insert  into `hd_product_category_description`(`product_category_description_id`,`shop_id`,`product_category_id`,`language_code`,`category_name`,`meta_title`,`meta_keywords`,`meta_description`,`category_description`,`category_description_m`,`created_at`,`created_by`,`updated_at`,`updated_by`) values 
(1,1,1,'zh','吉他','吉他','','','好玩','有趣',1627293629,'at0103',1629798803,'at0103'),
(2,1,1,'en','Guitar','Guitar2','','','Fun','有趣',1627293629,'at0103',1629969871,'at0103'),
(3,1,2,'zh','民谣吉他2','民谣吉他','','','','',1627293781,'at0103',1629796280,'at0103'),
(4,1,2,'en','Folk pop guitar','Folk pop guitar','','','','',1627293781,'at0103',1633860055,'at0103'),
(5,1,3,'zh','电吉他','电吉他','','','','',1627293815,'at0103',1629770382,'at0103'),
(6,1,3,'en','Electric guitar','Electric guitar tt','kk','desc','','',1627293815,'at0103',1634614856,'at0103'),
(7,1,3,'ja','エレキギター','电吉他','','','','',1627294324,'at0103',1627294430,'at0103'),
(8,1,1,'ja','ギター','吉他','','','好玩','有趣',1627294356,'at0103',1627294396,'at0103'),
(9,1,2,'ja','フォークポップギター','民谣吉他','','','','',1627294368,'at0103',1628662441,'at0103'),
(10,1,4,'en','Music','Music','','','','',1629798765,'at0103',1629798765,'at0103'),
(11,1,4,'zh','Music','Music','','','','',1629798765,'at0103',1629798765,'at0103'),
(12,1,5,'en','3333','3333','','','','',1629962043,'at0103',1629962043,'at0103');

/*Data for the table `hd_product_description` */

insert  into `hd_product_description`(`product_description_id`,`shop_id`,`product_id`,`language_code`,`product_name`,`meta_title`,`meta_keywords`,`meta_description`,`product_description`,`product_description_m`,`created_at`,`created_by`,`updated_at`,`updated_by`) values 
(1,1,1,'zh','我的木吉他','我的木吉他','','','多少年了，连我自己都已不记得','多少年了，连我自己都已不记得',1627636036,'at0103',1629770444,'at0103'),
(2,1,1,'en','My Guitar','My Guitar','','','How much years?','How much years?',1627636036,'at0103',1634622501,'at0103'),
(3,1,1,'ja','我的木吉他','我的木吉他','','','多少年了，连我自己都已不记得','多少年了，连我自己都已不记得',1627636036,'at0103',1629280430,'at0103'),
(13,1,2,'en','ABC','ABC','','','<h3>Introductions 2:</h3>\n                <p>\n                    The newly upgraded Glarry GP Ⅱ Electric Bass features a premium Basswood body alongside a Hard Maple\n                    neck and fingerboard.\n                    Other improvements include upgraded Split Single-Coil pickup, upgraded bass strings and a Bone nut.\n                    It is an awesome and affordable classic bass guitar priced\n                    for beginners and music lovers. Out of the box, the upgraded GP Ⅱ will have you playing in the\n                    bedroom, the studio or the stage in no time.\n                </p>\n                <p>\n                    <img src=\"https://www.glarrymusic.com/up/f_attachment/product/G17000088/G17000088-16.jpg\"/>\n                </p>\n                <p>\n                    <img src=\"https://www.glarrymusic.com/up/f_attachment/product/G17000088/G17000088-13.jpg\"/>\n                </p>\n                <p>\n                    <img src=\"https://www.glarrymusic.com/up/f_attachment/product/G17000088/G17000088-18.jpg\"/>\n                </p>','<h3>Introductions:</h3>\n                <p>\n                    The newly upgraded Glarry GP Ⅱ Electric Bass features a premium Basswood body alongside a Hard Maple\n                    neck and fingerboard.\n                    Other improvements include upgraded Split Single-Coil pickup, upgraded bass strings and a Bone nut.\n                    It is an awesome and affordable classic bass guitar priced\n                    for beginners and music lovers. Out of the box, the upgraded GP Ⅱ will have you playing in the\n                    bedroom, the studio or the stage in no time.\n                </p>\n                <p>\n                    <img src=\"https://www.glarrymusic.com/up/f_attachment/product/G17000088/G17000088-16.jpg\"/>\n                </p>\n                <p>\n                    <img src=\"https://www.glarrymusic.com/up/f_attachment/product/G17000088/G17000088-13.jpg\"/>\n                </p>\n                <p>\n                    <img src=\"https://www.glarrymusic.com/up/f_attachment/product/G17000088/G17000088-18.jpg\"/>\n                </p>',1629701540,'at0103',1634545362,'at0103'),
(14,1,2,'zh','ABC','ABC','','','','',1629701540,'at0103',1629788954,'at0103'),
(15,1,3,'en','DDD','DDD','','','','',1629701571,'at0103',1635212479,'at0103'),
(16,1,3,'zh','DDD','DDD','','','','',1629701571,'at0103',1629788963,'at0103'),
(17,1,4,'en','Good Guitar for you','Good Guitar for you tt','d k','dd','','',1629790029,'at0103',1634615514,'at0103'),
(18,1,4,'zh','Good Guitar for you','Good Guitar for you','','','','',1629790029,'at0103',1629792428,'at0103');

/*Data for the table `hd_product_image` */

insert  into `hd_product_image`(`product_image_id`,`shop_id`,`sku`,`image_path`,`image_name`,`sort`,`created_at`,`created_by`,`updated_at`,`updated_by`) values 
(15,1,'GT0001','sp_1/prod_img/skuabc/20211018','5cca9f4f0701acbbe99de13236b249dc_d_d.jpg',0,1628217750,'at0103',1634622501,'at0103'),
(20,1,'DDD','sp_1/prod_img/skuddd/20211018','83031e774a8cdf53a9c3e050bdaa593a_d_d.jpg',0,1629701571,'at0103',1635212479,'at0103'),
(33,1,'GG0001','sp_1/prod_img/skuabc/20211018','5cca9f4f0701acbbe99de13236b249dc_d_d.jpg',0,1629790029,'at0103',1634615514,'at0103'),
(37,1,'ABC003','sp_1/prod_img/skuabc/20211018','188b474567e876e0f7cc0ed41c031497_d_d.jpg',0,1629957401,'at0103',1634545362,'at0103'),
(39,1,'ABC006','sp_1/prod_img/skuabc/20211018','12b8e40ed6a3681593f1f4b37b004f17_d_d.jpg',0,1629957401,'at0103',1634545362,'at0103'),
(40,1,'GT0002','sp_1/prod_img/skuabc/20211019','7e85d77b18a22f68e2e84e318fe8ea6a_d_d.jpg',0,1629957633,'at0103',1634622501,'at0103');

/*Data for the table `hd_product_qty_price` */

insert  into `hd_product_qty_price`(`product_qty_price_id`,`shop_id`,`product_id`,`sku`,`warehouse_code`,`qty`,`price`,`list_price`,`created_at`,`created_by`,`updated_at`,`updated_by`) values 
(18,1,2,'ABC003','-',10,22.0000,33.0000,1629957401,'at0103',1634545362,'at0103'),
(19,1,2,'ABC006','-',1,12.0000,0.0000,1629957401,'at0103',1634545362,'at0103'),
(20,1,3,'DDD','-',10,0.1900,0.0000,1629957446,'at0103',1635212479,'at0103'),
(21,1,1,'GT0001','-',19,2.0000,0.0000,1629957497,'at0103',1634622501,'at0103'),
(22,1,4,'GG0001','-',0,10.0000,0.0000,1629957599,'at0103',1634615514,'at0103'),
(23,1,1,'GT0002','-',1,1.0000,0.0000,1629957633,'at0103',1634622501,'at0103');

/*Data for the table `hd_product_sku` */

insert  into `hd_product_sku`(`product_sku_id`,`shop_id`,`product_id`,`sku`,`sort`,`created_at`,`created_by`,`updated_at`,`updated_by`) values 
(1,1,1,'GT0001',0,1627636036,'at0103',1634542914,'at0103'),
(8,1,3,'DDD',0,1629701571,'at0103',1634545309,'at0103'),
(10,1,4,'GG0001',0,1629790029,'at0103',1634547366,'at0103'),
(14,1,2,'ABC006',1,1629701540,'at0103',1634542878,'at0103'),
(15,1,1,'GT0002',1,1627636036,'at0103',1634542914,'at0103'),
(16,1,2,'ABC003',0,1629701540,'at0103',1634542878,'at0103');

/*Data for the table `hd_shipping_method` */

insert  into `hd_shipping_method`(`shipping_method_id`,`shop_id`,`method_name`,`method_code`,`note`,`sort`,`created_at`,`created_by`,`updated_at`,`updated_by`) values 
(1,1,'Free Shipping','free','3 - 15 days',0,1631759640,'at0103',1636341620,'at0103');

/*Data for the table `hd_shop_template` */

/*Data for the table `hd_shopping_cart` */

insert  into `hd_shopping_cart`(`shopping_cart_id`,`shop_id`,`customer_id`,`product_id`,`sku`,`qty`,`price`,`created_at`,`updated_at`) values 
(7,1,15,1,'GT0001',1,2.0000,1634088603,1634088603),
(11,1,1,3,'DDD',1,0.1900,1635212484,1636339802),
(12,1,1,2,'ABC003',6,22.0000,1636339407,1636339802);

/*Data for the table `hd_sys_admin` */

insert  into `hd_sys_admin`(`admin_id`,`account`,`password`,`created_at`,`created_by`,`updated_at`,`updated_by`) values 
(1,'zt2655','$2y$10$ZyA2FDFPlRri8xvs28KUBu6B5mTboIahY0suvs4zvDS4qQJ6D7Iaq',1634695329,'',1634713356,'zt2655'),
(2,'at0103','$2y$10$v/VgLfTt5VM3c6r9ITiTfOO2hBjkFahAsTUhXd0e0.sHrRLUnAoS.',1634700925,'',1634713373,'zt2655');

/*Data for the table `hd_sys_country` */

insert  into `hd_sys_country`(`country_id`,`country_name`,`iso_code_2`,`iso_code_3`,`sort`,`icon_path`,`is_high_risk`,`currency_code`,`created_at`,`created_by`,`updated_at`,`updated_by`) values 
(1,'United States','US','USA',0,'',0,'USD',0,'',1636365927,'zt2655'),
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

/*Data for the table `hd_sys_shop` */

insert  into `hd_sys_shop`(`shop_id`,`shop_name`,`shop_domain`,`shop_domain2`,`shop_domain2_redirect_code`,`shop_status`,`created_at`,`created_by`,`updated_at`,`updated_by`) values 
(1,'SW Shop','swshop.com','','0',1,1634695329,'',1636342303,'zt2655'),
(8,'Hello Test','sw-shop.com','','0',1,1634782440,'at0103',1634782515,'at0103'),
(15,'test.shop','test.shop','','0',1,1634786999,'at0103',1634787064,'at0103');

/*Data for the table `hd_sys_warehouse` */

insert  into `hd_sys_warehouse`(`warehouse_id`,`warehouse_code`,`warehouse_name`,`sort`,`created_at`,`created_by`,`updated_at`,`updated_by`) values 
(1,'CN','中国仓',0,0,'',0,''),
(2,'US','美国仓',0,0,'',0,''),
(3,'US_E','美东仓',0,0,'',0,''),
(4,'US_W','美西仓',0,0,'',0,'');

/*Data for the table `hd_sys_zone` */

insert  into `hd_sys_zone`(`zone_id`,`country_id`,`zone_name`,`zone_code`,`sort`,`created_at`,`created_by`,`updated_at`,`updated_by`) values 
(1,1,'New York ','NY',0,0,'',1636364117,'zt2655'),
(2,1,'Alabama ','AL',0,0,'',1636364116,'zt2655'),
(3,1,'Alaska ','AK',0,1636363595,'zt2655',1636364116,'zt2655'),
(4,1,'Arizona ','AZ',0,1636363595,'zt2655',1636364116,'zt2655'),
(5,1,'Arkansas ','AR',0,1636363595,'zt2655',1636364116,'zt2655'),
(6,1,'California ','CA',0,1636363595,'zt2655',1636364116,'zt2655'),
(7,1,'Colorado ','CO',0,1636363595,'zt2655',1636364116,'zt2655'),
(8,1,'Connecticut ','CT',0,1636363595,'zt2655',1636364116,'zt2655'),
(9,1,'Delaware ','DE',0,1636363595,'zt2655',1636364116,'zt2655'),
(10,1,'District of Columbia ','DC',0,1636363595,'zt2655',1636364116,'zt2655'),
(11,1,'Florida ','FL',0,1636363596,'zt2655',1636364116,'zt2655'),
(12,1,'Georgia ','GA',0,1636363596,'zt2655',1636364116,'zt2655'),
(13,1,'Hawaii ','HI',0,1636363596,'zt2655',1636364116,'zt2655'),
(14,1,'Idaho ','ID',0,1636363596,'zt2655',1636364116,'zt2655'),
(15,1,'Illinois ','IL',0,1636363596,'zt2655',1636364117,'zt2655'),
(16,1,'Indiana ','IN',0,1636363596,'zt2655',1636364117,'zt2655'),
(17,1,'Iowa ','IA',0,1636363596,'zt2655',1636364117,'zt2655'),
(18,1,'Kansas ','KS',0,1636363596,'zt2655',1636364117,'zt2655'),
(19,1,'Kentucky ','KY',0,1636363596,'zt2655',1636364117,'zt2655'),
(20,1,'Louisiana ','LA',0,1636363596,'zt2655',1636364117,'zt2655'),
(21,1,'Maine ','ME',0,1636363596,'zt2655',1636364117,'zt2655'),
(22,1,'Maryland ','MD',0,1636363596,'zt2655',1636364117,'zt2655'),
(23,1,'Massachusetts ','MA',0,1636363596,'zt2655',1636364117,'zt2655'),
(24,1,'Michigan ','MI',0,1636363596,'zt2655',1636364117,'zt2655'),
(25,1,'Minnesota ','MN',0,1636363596,'zt2655',1636364117,'zt2655'),
(26,1,'Mississippi ','MS',0,1636363596,'zt2655',1636364117,'zt2655'),
(27,1,'Missouri ','MO',0,1636363596,'zt2655',1636364117,'zt2655'),
(28,1,'Montana ','MT',0,1636363596,'zt2655',1636364117,'zt2655'),
(29,1,'Nebraska ','NE',0,1636363596,'zt2655',1636364117,'zt2655'),
(30,1,'Nevada ','NV',0,1636363596,'zt2655',1636364117,'zt2655'),
(31,1,'New Hampshire ','NH',0,1636363596,'zt2655',1636364117,'zt2655'),
(32,1,'New Jersey ','NJ',0,1636363596,'zt2655',1636364117,'zt2655'),
(33,1,'New Mexico ','NM',0,1636363596,'zt2655',1636364117,'zt2655'),
(34,1,'North Carolina ','NC',0,1636363596,'zt2655',1636364117,'zt2655'),
(35,1,'North Dakota ','ND',0,1636363596,'zt2655',1636364117,'zt2655'),
(36,1,'Ohio ','OH',0,1636363596,'zt2655',1636364117,'zt2655'),
(37,1,'Oklahoma ','OK',0,1636363596,'zt2655',1636364117,'zt2655'),
(38,1,'Oregon ','OR',0,1636363596,'zt2655',1636364117,'zt2655'),
(39,1,'Pennsylvania ','PA',0,1636363596,'zt2655',1636364117,'zt2655'),
(40,1,'Rhode Island ','RI',0,1636363596,'zt2655',1636364117,'zt2655'),
(41,1,'South Carolina ','SC',0,1636363596,'zt2655',1636364117,'zt2655'),
(42,1,'South Dakota ','SD',0,1636363596,'zt2655',1636364117,'zt2655'),
(43,1,'Tennessee ','TN',0,1636363596,'zt2655',1636364117,'zt2655'),
(44,1,'Texas ','TX',0,1636363596,'zt2655',1636364117,'zt2655'),
(45,1,'Utah ','UT',0,1636363596,'zt2655',1636364117,'zt2655'),
(46,1,'Vermont ','VT',0,1636363596,'zt2655',1636364117,'zt2655'),
(47,1,'Virginia ','VA',0,1636363596,'zt2655',1636364117,'zt2655'),
(48,1,'Washington ','WA',0,1636363596,'zt2655',1636364117,'zt2655'),
(49,1,'West Virginia ','WV',0,1636363596,'zt2655',1636364117,'zt2655'),
(50,1,'Wisconsin ','WI',0,1636363597,'zt2655',1636364117,'zt2655'),
(51,1,'Wyoming ','WY',0,1636363597,'zt2655',1636364117,'zt2655');

/*Data for the table `hd_upload_file` */

insert  into `hd_upload_file`(`upload_file_id`,`shop_id`,`origin_name`,`oss_object`,`file_class`,`folder`,`created_at`,`created_by`,`updated_at`,`updated_by`) values 
(1,1,'1300x480.jpg','sp_1/banner/20211018/6d9f93cd78468e09f7cf4cd9c0bc6c46_d_d.jpg','image','banner',1634544097,'at0103',1634544097,'at0103'),
(2,1,'1300X480-2.jpg','sp_1/banner/20211018/39bf7dcb3f4e3811bf5206da73ebf3a6_d_d.jpg','image','banner',1634544097,'at0103',1634544097,'at0103'),
(3,1,'G32000142-1.jpg','sp_1/prod_img/skuddd/20211018/83031e774a8cdf53a9c3e050bdaa593a_d_d.jpg','image','skuddd',1634545266,'at0103',1634545266,'at0103'),
(4,1,'g17000471_1-0.jpg','sp_1/prod_img/skuabc/20211018/5cca9f4f0701acbbe99de13236b249dc_d_d.jpg','image','skuabc',1634547283,'at0103',1634547283,'at0103'),
(5,1,'g17000471_10.jpg','sp_1/prod_img/skuabc/20211019/7e85d77b18a22f68e2e84e318fe8ea6a_d_d.jpg','image','skuabc',1634622496,'at0103',1634622496,'at0103'),
(6,1,'G32000142-111.jpg','sp_1/prod_img/skuabc/20211018/188b474567e876e0f7cc0ed41c031497_d_d.jpg','image','skuabc',1634545352,'at0103',1634545352,'at0103'),
(7,1,'G32000142-11.jpg','sp_1/prod_img/skuabc/20211018/12b8e40ed6a3681593f1f4b37b004f17_d_d.jpg','image','skuabc',1634545352,'at0103',1634545352,'at0103');

/*Data for the table `hd_warehouse` */

/*Data for the table `hd_zone` */

insert  into `hd_zone`(`pk_id`,`shop_id`,`zone_id`,`country_id`,`zone_name`,`zone_code`,`sort`,`created_at`,`created_by`,`updated_at`,`updated_by`) values 
(5,1,1,1,'New York','',0,1636352643,'at0103',1636352643,'at0103'),
(6,1,2,1,'Alabama','',0,1636352643,'at0103',1636352643,'at0103');

