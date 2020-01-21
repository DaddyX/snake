/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : 127.0.0.1:3306
Source Database       : watermelon

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-09-16 19:00:31
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for w_articles
-- ----------------------------
DROP TABLE IF EXISTS `w_articles`;
CREATE TABLE `w_articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '文章id',
  `title` varchar(32) NOT NULL DEFAULT '' COMMENT '文章标题',
  `description` varchar(32) NOT NULL DEFAULT '' COMMENT '文章描述',
  `keywords` varchar(32) NOT NULL DEFAULT '' COMMENT '文章关键字',
  `thumbnail` varchar(255) NOT NULL DEFAULT '' COMMENT '文章缩略图',
  `content` text NOT NULL DEFAULT '' COMMENT '文章内容',
  `create_time` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of w_articles
-- ----------------------------
INSERT INTO `w_articles` VALUES ('1', '文章标题', '文章描述', '关键字1,关键字2,关键字3', 'http://img11.360buyimg.com/n1/jfs/t5665/355/8001951003/100517/91123abc/5976aac4Nb3e4b9d3.jpg', '<p><img src=\"http://img11.360buyimg.com/n1/jfs/t5665/355/8001951003/100517/91123abc/5976aac4Nb3e4b9d3.jpg\" title=\"1505555254.png\" alt=\"QQ截图20170916174651.png\"/></p><p>测试文章内容</p><p>测试内容</p>', '0');

-- ----------------------------
-- Table structure for w_node
-- ----------------------------
DROP TABLE IF EXISTS `w_node`;
CREATE TABLE `w_node` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL DEFAULT '0' COMMENT '父级节点id',
  `node_name` varchar(32) NOT NULL DEFAULT '' COMMENT '节点名称',
  `control_name` varchar(32) NOT NULL DEFAULT '' COMMENT '控制器名',
  `action_name` varchar(32) NOT NULL DEFAULT '' COMMENT '方法名',
  `is_menu` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否是菜单项 1不是 2是',
  `icon` varchar(32) NOT NULL DEFAULT '' COMMENT '菜单样式',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of w_node
-- ----------------------------
INSERT INTO `w_node` VALUES ('1', '0', '权限管理', '#', '#', '2', 'fa fa-users');
INSERT INTO `w_node` VALUES ('2', '1', '管理员', 'admin', 'index', '2', '');
INSERT INTO `w_node` VALUES ('3', '2', '添加', 'admin', 'add', '1', '');
INSERT INTO `w_node` VALUES ('4', '2', '编辑', 'admin', 'edit', '1', '');
INSERT INTO `w_node` VALUES ('5', '2', '删除', 'admin', 'del', '1', '');
INSERT INTO `w_node` VALUES ('6', '1', '角色管理', 'role', 'index', '2', '');
INSERT INTO `w_node` VALUES ('7', '6', '添加', 'role', 'add', '1', '');
INSERT INTO `w_node` VALUES ('8', '6', '编辑', 'role', 'edit', '1', '');
INSERT INTO `w_node` VALUES ('9', '6', '删除', 'role', 'del', '1', '');
INSERT INTO `w_node` VALUES ('10', '6', '分配权限', 'role', 'give_access', '1', '');
INSERT INTO `w_node` VALUES ('11', '0', '系统管理', '#', '#', '2', 'fa fa-cogs');
INSERT INTO `w_node` VALUES ('12', '11', '数据备份/还原', 'data', 'index', '2', '');
INSERT INTO `w_node` VALUES ('13', '12', '备份', 'data', 'data_backup', '1', '');
INSERT INTO `w_node` VALUES ('14', '12', '还原', 'data', 'data_restore', '1', '');
INSERT INTO `w_node` VALUES ('15', '1', '节点管理', 'node', 'index', '2', '');
INSERT INTO `w_node` VALUES ('16', '15', '添加', 'node', 'add', '1', '');
INSERT INTO `w_node` VALUES ('17', '15', '编辑', 'node', 'edit', '1', '');
INSERT INTO `w_node` VALUES ('18', '15', '删除', 'node', 'del', '1', '');
INSERT INTO `w_node` VALUES ('19', '0', '文章管理', '#', '#', '2', 'fa fa-book');
INSERT INTO `w_node` VALUES ('20', '19', '文章列表', 'articles', 'index', '2', '');
INSERT INTO `w_node` VALUES ('21', '19', '添加', 'articles', 'add', '1', '');
INSERT INTO `w_node` VALUES ('22', '19', '编辑', 'articles', 'edit', '1', '');
INSERT INTO `w_node` VALUES ('23', '19', '删除', 'articles', 'del', '1', '');
INSERT INTO `w_node` VALUES ('24', '19', '上传图片', 'articles', 'uploadImg', '1', '');
INSERT INTO `w_node` VALUES ('25', '11', '邮箱管理', 'email', 'index', '2', '');
INSERT INTO `w_node` VALUES ('26', '25', '添加', 'email', 'add', '1', '');
INSERT INTO `w_node` VALUES ('27', '25', '编辑', 'email', 'edit', '1', '');
INSERT INTO `w_node` VALUES ('28', '25', '删除', 'email', 'del', '1', '');

-- ----------------------------
-- Table structure for w_role
-- ----------------------------
DROP TABLE IF EXISTS `w_role`;
CREATE TABLE `w_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `name` varchar(32) NOT NULL DEFAULT '' COMMENT '角色名称',
  `rule` varchar(255) NOT NULL DEFAULT '' COMMENT '权限节点数据',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of w_role
-- ----------------------------
INSERT INTO `w_role` VALUES ('1', '超级管理员', '*');
INSERT INTO `w_role` VALUES ('2', '系统维护员', '1,2,3,4,5,6,7,8,9,10');

-- ----------------------------
-- Table structure for w_admin
-- ----------------------------
DROP TABLE IF EXISTS `w_admin`;
CREATE TABLE `w_admin` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(32) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(32) NOT NULL DEFAULT '' COMMENT '密码',
  `login_times` int(11) NOT NULL DEFAULT '0' COMMENT '登陆次数',
  `last_login_ip` varchar(32) NOT NULL DEFAULT '' COMMENT '最后登录IP',
  `last_login_time` int(11) NOT NULL DEFAULT '0' COMMENT '最后登录时间',
  `real_name` varchar(32) NOT NULL DEFAULT '' COMMENT '真实姓名',
  `status` int(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `role_id` int(11) NOT NULL DEFAULT '1' COMMENT '用户角色id',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of w_admin
-- ----------------------------
INSERT INTO `w_admin` VALUES ('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', '41', '127.0.0.1', '1505559479', 'admin', '1', '1');

-- ----------------------------
-- Table structure for w_admin
-- ----------------------------
DROP TABLE IF EXISTS `w_admin_log`;
CREATE TABLE `w_admin_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `aid` int(11) NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `controller` varchar(32) NOT NULL DEFAULT '' COMMENT '控制器',
  `action` varchar(32) NOT NULL DEFAULT '' COMMENT '操作',
  `request` text COMMENT '参数',
  `desc` varchar(32) NOT NULL DEFAULT '' COMMENT '描述',
  `ip` varchar(32) NOT NULL DEFAULT '' COMMENT '操作IP',
  `datetime` varchar(32) NOT NULL DEFAULT '' COMMENT '操作时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='管理员日志';