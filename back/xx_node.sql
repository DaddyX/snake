SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `xx_node`;
CREATE TABLE `xx_node` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父级节点id',
  `node_name` varchar(155) NOT NULL DEFAULT '' COMMENT '节点名称',
  `control_name` varchar(155) NOT NULL DEFAULT '' COMMENT '控制器名',
  `action_name` varchar(155) NOT NULL COMMENT '方法名',
  `is_menu` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否是菜单项 1不是 2是',
  `icon` varchar(155) DEFAULT '' COMMENT '菜单样式',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

insert into `xx_node`(`id`,`pid`,`node_name`,`control_name`,`action_name`,`is_menu`,`icon`) values('1','0','权限管理','#','#','2','fa fa-users');
insert into `xx_node`(`id`,`pid`,`node_name`,`control_name`,`action_name`,`is_menu`,`icon`) values('2','1','管理员管理','admin','index','2','');
insert into `xx_node`(`id`,`pid`,`node_name`,`control_name`,`action_name`,`is_menu`,`icon`) values('3','2','添加管理员','admin','add','1','');
insert into `xx_node`(`id`,`pid`,`node_name`,`control_name`,`action_name`,`is_menu`,`icon`) values('4','2','编辑管理员','admin','edit','1','');
insert into `xx_node`(`id`,`pid`,`node_name`,`control_name`,`action_name`,`is_menu`,`icon`) values('5','2','删除管理员','admin','del','1','');
insert into `xx_node`(`id`,`pid`,`node_name`,`control_name`,`action_name`,`is_menu`,`icon`) values('6','1','角色管理','role','index','2','');
insert into `xx_node`(`id`,`pid`,`node_name`,`control_name`,`action_name`,`is_menu`,`icon`) values('7','6','添加角色','role','add','1','');
insert into `xx_node`(`id`,`pid`,`node_name`,`control_name`,`action_name`,`is_menu`,`icon`) values('8','6','编辑角色','role','edit','1','');
insert into `xx_node`(`id`,`pid`,`node_name`,`control_name`,`action_name`,`is_menu`,`icon`) values('9','6','删除角色','role','del','1','');
insert into `xx_node`(`id`,`pid`,`node_name`,`control_name`,`action_name`,`is_menu`,`icon`) values('10','6','分配权限','role','giveaccess','1','');
insert into `xx_node`(`id`,`pid`,`node_name`,`control_name`,`action_name`,`is_menu`,`icon`) values('11','0','系统管理','#','#','2','fa fa-cogs');
insert into `xx_node`(`id`,`pid`,`node_name`,`control_name`,`action_name`,`is_menu`,`icon`) values('12','11','数据备份/还原','data','index','2','');
insert into `xx_node`(`id`,`pid`,`node_name`,`control_name`,`action_name`,`is_menu`,`icon`) values('13','12','备份数据','data','importdata','1','');
insert into `xx_node`(`id`,`pid`,`node_name`,`control_name`,`action_name`,`is_menu`,`icon`) values('14','12','还原数据','data','backdata','1','');
insert into `xx_node`(`id`,`pid`,`node_name`,`control_name`,`action_name`,`is_menu`,`icon`) values('15','1','节点管理','node','index','2','');
insert into `xx_node`(`id`,`pid`,`node_name`,`control_name`,`action_name`,`is_menu`,`icon`) values('16','15','添加节点','node','add','1','');
insert into `xx_node`(`id`,`pid`,`node_name`,`control_name`,`action_name`,`is_menu`,`icon`) values('17','15','编辑节点','node','edit','1','');
insert into `xx_node`(`id`,`pid`,`node_name`,`control_name`,`action_name`,`is_menu`,`icon`) values('18','15','删除节点','node','del','1','');
insert into `xx_node`(`id`,`pid`,`node_name`,`control_name`,`action_name`,`is_menu`,`icon`) values('19','0','文章管理','articles','index','2','fa fa-book');
insert into `xx_node`(`id`,`pid`,`node_name`,`control_name`,`action_name`,`is_menu`,`icon`) values('20','19','文章列表','articles','index','2','');
insert into `xx_node`(`id`,`pid`,`node_name`,`control_name`,`action_name`,`is_menu`,`icon`) values('21','19','添加文章','articles','add','1','');
insert into `xx_node`(`id`,`pid`,`node_name`,`control_name`,`action_name`,`is_menu`,`icon`) values('22','19','编辑文章','articles','edit','1','');
insert into `xx_node`(`id`,`pid`,`node_name`,`control_name`,`action_name`,`is_menu`,`icon`) values('23','19','删除文章','articles','del','1','');
insert into `xx_node`(`id`,`pid`,`node_name`,`control_name`,`action_name`,`is_menu`,`icon`) values('24','19','上传图片','articles','uploadImg','1','');
insert into `xx_node`(`id`,`pid`,`node_name`,`control_name`,`action_name`,`is_menu`,`icon`) values('25','11','邮箱管理','email','index','2','');
insert into `xx_node`(`id`,`pid`,`node_name`,`control_name`,`action_name`,`is_menu`,`icon`) values('26','25','添加邮箱','email','add','1','');
insert into `xx_node`(`id`,`pid`,`node_name`,`control_name`,`action_name`,`is_menu`,`icon`) values('27','25','编辑邮箱','email','edit','1','');
insert into `xx_node`(`id`,`pid`,`node_name`,`control_name`,`action_name`,`is_menu`,`icon`) values('28','25','删除邮箱','email','del','1','');
