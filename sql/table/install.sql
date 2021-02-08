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
	(1, '供应商管理', '', 1, 0, 1, 1, '', '2021-02-07 15:39:30', '2021-02-07 15:39:30'),
	(2, '添加供应商', 'add_provider', 0, 1, 1, 1, 'ProviderController/index', '2021-02-07 15:39:30', '2021-02-07 15:39:30'),
	(3, '商品管理', '', 1, 0, 1, 1, '', '2021-02-07 15:39:30', '2021-02-07 15:39:30'),
	(4, '添加商品', 'add_provider_goods', 0, 3, 1, 1, 'ProviderGoodsController/index', '2021-02-07 15:39:30', '2021-02-07 15:52:42'),
	(5, '商品盘点', 'provider_goods_check', 0, 3, 1, 1, 'ProviderGoodsController/index', '2021-02-07 15:39:51', '2021-02-07 15:52:45'),
	(6, '商品取样', 'provider_goods_sample', 0, 3, 1, 1, 'ProviderGoodsSampleController/index', '2021-02-07 15:40:24', '2021-02-07 15:52:47'),
	(7, '商品关联', 'provider_goods_sku', 0, 3, 1, 1, 'ProviderGoodsSkuController/index', '2021-02-07 15:40:57', '2021-02-07 15:52:48'),
	(8, '销售管理', '', 1, 0, 1, 1, '', '2021-02-07 15:41:22', '2021-02-07 16:18:36'),
	(9, '线下销售', 'goods_sale_offline', 0, 8, 1, 1, 'sale/GoodsSaleOfflineController/index', '2021-02-07 15:41:28', '2021-02-07 15:52:50'),
	(10, '损耗', 'goods_loss', 0, 8, 1, 1, 'sale/GoodsLossController/index', '2021-02-07 15:41:37', '2021-02-07 15:52:52'),
	(11, '进货', 'goods_stock', 0, 8, 1, 1, 'sale/GoodsStockController/index', '2021-02-07 15:41:49', '2021-02-07 15:52:53'),
	(12, '调度', 'goods_change', 0, 8, 1, 1, 'sale/GoodsChangeController/index', '2021-02-07 15:41:58', '2021-02-07 15:52:54'),
	(13, '员工餐', 'goods_staff_meal', 0, 8, 1, 1, 'sale/GoodsStaffMealController/index', '2021-02-07 15:42:11', '2021-02-07 15:52:56'),
	(14, '异常订单', 'goods_exception', 0, 8, 1, 1, 'sale/GoodsExceptionHandleController/index', '2021-02-07 15:42:32', '2021-02-07 15:53:01'),
	(15, '线上销售', '', 0, 0, 1, 1, '', '2021-02-07 15:42:52', '2021-02-07 16:18:34'),
	(16, '销售记录', 'goods_sale_online', 0, 15, 1, 1, 'sale/GoodsSaleOnlineController/index', '2021-02-07 15:42:59', '2021-02-07 15:53:02'),
	(17, '销售合计', 'goods_sale_online_summary', 0, 15, 1, 1, 'sale/GoodsSaleOnlineSummaryController/index', '2021-02-07 15:43:07', '2021-02-07 15:53:06'),
	(18, '销售预测', '', 1, 0, 1, 1, '', '2021-02-07 15:43:17', '2021-02-07 16:18:38'),
	(19, '基于库存', 'base_stock', 0, 18, 1, 1, 'SaleForecastBaseStockController/index', '2021-02-07 15:43:22', '2021-02-07 15:53:07'),
	(20, 'SKU管理', '', 1, 0, 1, 1, '', '2021-02-07 15:43:33', '2021-02-07 16:18:39'),
	(21, 'SKU列表', 'sku_list', 0, 20, 1, 1, 'CoreSkuController/index', '2021-02-07 15:43:37', '2021-02-07 15:53:08'),
	(22, '库存管理', '', 1, 0, 1, 1, '', '2021-02-07 15:43:46', '2021-02-07 16:18:41'),
	(23, '库存列表', 'repertory_list', 0, 22, 1, 1, 'CoreRepertoryController/index', '2021-02-07 15:43:50', '2021-02-07 15:53:10'),
	(24, '财务结算列表', 'finance_account_list', 0, 22, 1, 1, 'FinanceAccountController/index', '2021-02-07 15:43:58', '2021-02-07 15:53:11'),
	(25, '获取供应商列表', '', 0, -1, 0, 1, 'ProviderController/getList/index', '2021-02-07 15:50:20', '2021-02-07 15:53:13');
