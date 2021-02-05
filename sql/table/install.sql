CREATE TABLE `vms_manage` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uid` int(11) NOT NULL DEFAULT '0' COMMENT '用户ID',
  `role_id` int(11) NOT NULL DEFAULT '0' COMMENT '角色ID',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 0禁用1启用',
  `is_deleted` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否删除 0否1是',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='管理员表';

CREATE TABLE `vms_manage_perms` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) NOT NULL DEFAULT '' COMMENT '权限名称',
  `identity_code` varchar(255) NOT NULL DEFAULT '' COMMENT '标识码',
  `is_open` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否展开',
  `parent_id` int(11) NOT NULL COMMENT '父级ID',
  `is_show` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否展示在侧边栏',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 0禁用1启用',
  `url` varchar(255) NOT NULL DEFAULT '' COMMENT 'url',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `vms_manage_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(20) NOT NULL DEFAULT '' COMMENT '名称',
  `desc` varchar(255) NOT NULL DEFAULT '' COMMENT '描述',
  `perms` varchar(255) NOT NULL DEFAULT '' COMMENT '权限 英文逗号间隔',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态 0禁用1启用',
  `is_deleted` tinyint(1) NOT NULL COMMENT '是否删除 0否1是',
  `create_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP COMMENT '创建时间',
  `update_time` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色表';



INSERT INTO `vms_manage_perms` (`id`, `name`, `identity_code`, `is_open`, `parent_id`, `is_show`, `status`, `url`, `create_time`, `update_time`)
VALUES
	(1, '供应商管理', '', 1, 0, 1, 1, '', '2021-02-04 15:57:00', '2021-02-04 15:57:20'),
	(2, '添加供应商', 'add_provider', 0, 1, 1, 1, 'ProviderController/index', '2021-02-04 15:57:50', '2021-02-04 15:58:13'),
	(3, '商品管理', '', 1, 0, 1, 1, '', '2021-02-04 15:59:19', '2021-02-04 15:59:19'),
	(4, '添加商品', 'add_goods', 0, 3, 1, 1, '', '2021-02-04 15:59:48', '2021-02-04 15:59:48'),
	(5, '销售管理', '', 1, 0, 1, 1, '', '2021-02-05 10:01:28', '2021-02-05 10:01:32'),
	(6, '线上销售', '', 0, 5, 1, 1, '', '2021-02-05 10:02:04', '2021-02-05 10:02:06'),
	(7, '销售记录', 'goods_sale_online', 0, 6, 1, 1, '', '2021-02-05 10:02:55', '2021-02-05 10:02:55');
