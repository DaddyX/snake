/*
Navicat MySQL Data Transfer

Source Server         : localhost
Source Server Version : 50553
Source Host           : 127.0.0.1:3306
Source Database       : snake

Target Server Type    : MYSQL
Target Server Version : 50553
File Encoding         : 65001

Date: 2017-09-16 19:00:31
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for snake_articles
-- ----------------------------
DROP TABLE IF EXISTS `snake_articles`;
CREATE TABLE `snake_articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '文章id',
  `title` varchar(155) NOT NULL COMMENT '文章标题',
  `description` varchar(255) NOT NULL COMMENT '文章描述',
  `keywords` varchar(155) NOT NULL COMMENT '文章关键字',
  `thumbnail` varchar(255) NOT NULL COMMENT '文章缩略图',
  `content` text NOT NULL COMMENT '文章内容',
  `add_time` datetime NOT NULL COMMENT '发布时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- ----------------------------
-- Records of snake_articles
-- ----------------------------
INSERT INTO `snake_articles` VALUES ('2', '文章标题', '文章描述', '关键字1,关键字2,关键字3', '/upload/20170916/1e915c70dbb9d3e8a07bede7b64e4cff.png', '<p><img src=\"/upload/image/20170916/1505555254.png\" title=\"1505555254.png\" alt=\"QQ截图20170916174651.png\"/></p><p>测试文章内容</p><p>测试内容</p>', '2017-09-16 17:47:44');

-- ----------------------------
-- Table structure for snake_node
-- ----------------------------
DROP TABLE IF EXISTS `snake_node`;
CREATE TABLE `snake_node` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL COMMENT '父级节点id',
  `node_name` varchar(155) NOT NULL DEFAULT '' COMMENT '节点名称',
  `control_name` varchar(155) NOT NULL DEFAULT '' COMMENT '控制器名',
  `action_name` varchar(155) NOT NULL COMMENT '方法名',
  `is_menu` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否是菜单项 1不是 2是',
  `icon` varchar(155) DEFAULT '' COMMENT '菜单样式',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=25 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of snake_node
-- ----------------------------
INSERT INTO `snake_node` VALUES ('1', '0', '权限管理', '#', '#', '2', 'fa fa-users');
INSERT INTO `snake_node` VALUES ('2', '1', '管理员管理', 'admin', 'index', '2', '');
INSERT INTO `snake_node` VALUES ('3', '2', '添加管理员', 'admin', 'add', '1', '');
INSERT INTO `snake_node` VALUES ('4', '2', '编辑管理员', 'admin', 'edit', '1', '');
INSERT INTO `snake_node` VALUES ('5', '2', '删除管理员', 'admin', 'del', '1', '');
INSERT INTO `snake_node` VALUES ('6', '1', '角色管理', 'role', 'index', '2', '');
INSERT INTO `snake_node` VALUES ('7', '6', '添加角色', 'role', 'add', '1', '');
INSERT INTO `snake_node` VALUES ('8', '6', '编辑角色', 'role', 'edit', '1', '');
INSERT INTO `snake_node` VALUES ('9', '6', '删除角色', 'role', 'del', '1', '');
INSERT INTO `snake_node` VALUES ('10', '6', '分配权限', 'role', 'giveaccess', '1', '');
INSERT INTO `snake_node` VALUES ('11', '0', '系统管理', '#', '#', '2', 'fa fa-cogs');
INSERT INTO `snake_node` VALUES ('12', '11', '数据备份/还原', 'data', 'index', '2', '');
INSERT INTO `snake_node` VALUES ('13', '12', '备份数据', 'data', 'importdata', '1', '');
INSERT INTO `snake_node` VALUES ('14', '12', '还原数据', 'data', 'backdata', '1', '');
INSERT INTO `snake_node` VALUES ('15', '1', '节点管理', 'node', 'index', '2', '');
INSERT INTO `snake_node` VALUES ('16', '15', '添加节点', 'node', 'add', '1', '');
INSERT INTO `snake_node` VALUES ('17', '15', '编辑节点', 'node', 'edit', '1', '');
INSERT INTO `snake_node` VALUES ('18', '15', '删除节点', 'node', 'del', '1', '');
INSERT INTO `snake_node` VALUES ('19', '0', '文章管理', 'articles', 'index', '2', 'fa fa-book');
INSERT INTO `snake_node` VALUES ('20', '19', '文章列表', 'articles', 'index', '2', '');
INSERT INTO `snake_node` VALUES ('21', '19', '添加文章', 'articles', 'add', '1', '');
INSERT INTO `snake_node` VALUES ('22', '19', '编辑文章', 'articles', 'edit', '1', '');
INSERT INTO `snake_node` VALUES ('23', '19', '删除文章', 'articles', 'del', '1', '');
INSERT INTO `snake_node` VALUES ('24', '19', '上传图片', 'articles', 'uploadImg', '1', '');
INSERT INTO `snake_node` VALUES ('25', '11', '邮箱管理', 'email', 'index', '2', '');
INSERT INTO `snake_node` VALUES ('26', '25', '添加邮箱', 'email', 'add', '1', '');
INSERT INTO `snake_node` VALUES ('27', '25', '编辑邮箱', 'email', 'edit', '1', '');
INSERT INTO `snake_node` VALUES ('28', '25', '删除邮箱', 'email', 'del', '1', '');

-- ----------------------------
-- Table structure for snake_role
-- ----------------------------
DROP TABLE IF EXISTS `snake_role`;
CREATE TABLE `snake_role` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `role_name` varchar(155) NOT NULL COMMENT '角色名称',
  `rule` varchar(255) DEFAULT '' COMMENT '权限节点数据',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=DYNAMIC;

-- ----------------------------
-- Records of snake_role
-- ----------------------------
INSERT INTO `snake_role` VALUES ('1', '超级管理员', '*');
INSERT INTO `snake_role` VALUES ('2', '系统维护员', '1,2,3,4,5,6,7,8,9,10');

-- ----------------------------
-- Table structure for snake_admin
-- ----------------------------
DROP TABLE IF EXISTS `snake_admin`;
CREATE TABLE `snake_admin` (
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
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin;

-- ----------------------------
-- Records of snake_admin
-- ----------------------------
INSERT INTO `snake_admin` VALUES ('1', 'admin', '21232f297a57a5a743894a0e4a801fc3', '41', '127.0.0.1', '1505559479', 'admin', '1', '1');
