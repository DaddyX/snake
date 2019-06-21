SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `xx_role`;
CREATE TABLE `xx_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `role_name` varchar(155) NOT NULL COMMENT '角色名称',
  `rule` varchar(255) DEFAULT '' COMMENT '权限节点数据',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

insert into `xx_role`(`id`,`role_name`,`rule`) values('1','超级管理员','*');
insert into `xx_role`(`id`,`role_name`,`rule`) values('2','系统维护员','1,2,3,4,5,6,7,8,9,10');
