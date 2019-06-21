SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `xx_admin`;
CREATE TABLE `xx_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '密码',
  `login_times` int(11) NOT NULL DEFAULT '0' COMMENT '登陆次数',
  `last_login_ip` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '最后登录IP',
  `last_login_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `real_name` varchar(255) COLLATE utf8_bin NOT NULL DEFAULT '' COMMENT '真实姓名',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `role_id` int(11) NOT NULL DEFAULT '1' COMMENT '用户角色id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

insert into `xx_admin`(`id`,`username`,`password`,`login_times`,`last_login_ip`,`last_login_time`,`real_name`,`status`,`role_id`) values('1','admin','21232f297a57a5a743894a0e4a801fc3','52','127.0.0.1','1544796931','admin','1','1');
insert into `xx_admin`(`id`,`username`,`password`,`login_times`,`last_login_ip`,`last_login_time`,`real_name`,`status`,`role_id`) values('2','zhouxuan','e10adc3949ba59abbe56e057f20f883e','0','','0','周璇','1','2');
insert into `xx_admin`(`id`,`username`,`password`,`login_times`,`last_login_ip`,`last_login_time`,`real_name`,`status`,`role_id`) values('3','pengsiqi','01c401bb1b2ba502df5a854be02ea4e9','0','','0','彭思琪2','1','2');
