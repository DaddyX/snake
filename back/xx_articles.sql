SET FOREIGN_KEY_CHECKS=0;
DROP TABLE IF EXISTS `xx_articles`;
CREATE TABLE `xx_articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '文章id',
  `title` varchar(155) NOT NULL COMMENT '文章标题',
  `description` varchar(255) NOT NULL COMMENT '文章描述',
  `keywords` varchar(155) NOT NULL COMMENT '文章关键字',
  `thumbnail` varchar(255) NOT NULL COMMENT '文章缩略图',
  `content` text NOT NULL COMMENT '文章内容',
  `add_time` datetime NOT NULL COMMENT '发布时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

insert into `xx_articles`(`id`,`title`,`description`,`keywords`,`thumbnail`,`content`,`add_time`) values('2','文章标题','文章描述','关键字1,关键字2,关键字3','/upload/20170916/1e915c70dbb9d3e8a07bede7b64e4cff.png','<p><br/></p><p>测试文章内容</p><p>测试内容xxxxx111</p>','2017-09-16 17:47:44');
insert into `xx_articles`(`id`,`title`,`description`,`keywords`,`thumbnail`,`content`,`add_time`) values('3','测试文字2211','测试描述22','彭思琪,我爱你,周璇','/upload/20181214/62549b480523fd90d71f6a82da68254d.jpg','<p>马上就睡了，好的<br/></p>','2018-12-14 00:11:56');
