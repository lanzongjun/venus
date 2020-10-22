ALTER TABLE `vms_core_repertory` CHANGE `cr_num` `cr_num` DECIMAL(12,4)  NOT NULL  DEFAULT '0.00'  COMMENT '库存数量';
ALTER TABLE `vms_core_repertory_daily` CHANGE `crd_num` `crd_num` DECIMAL(12,4)  NOT NULL  DEFAULT '0.00'  COMMENT '数量';
ALTER TABLE `vms_core_repertory_record` CHANGE `crr_num` `crr_num` DECIMAL(12,4)  NOT NULL  DEFAULT '0.00'  COMMENT '变化数量';
ALTER TABLE `vms_core_sku` CHANGE `cs_description` `cs_description` VARCHAR(1000)  CHARACTER SET utf8mb4  COLLATE utf8mb4_general_ci  NOT NULL  DEFAULT ''  COMMENT '商品描述';
ALTER TABLE `vms_goods_change` CHANGE `gc_num` `gc_num` DECIMAL(12,4)  NOT NULL  DEFAULT '0.00'  COMMENT '数量';
ALTER TABLE `vms_goods_exception_handle` CHANGE `geh_num` `geh_num` DECIMAL(12,4)  NOT NULL  DEFAULT '0.00'  COMMENT '数量';
ALTER TABLE `vms_goods_loss` CHANGE `gl_num` `gl_num` DECIMAL(12,4)  NOT NULL  DEFAULT '0.00'  COMMENT '数量(个)';
ALTER TABLE `vms_goods_sale_offline` CHANGE `gso_num` `gso_num` DECIMAL(12,4)  NOT NULL  DEFAULT '0.00'  COMMENT '销量';
ALTER TABLE `vms_goods_staff_meal` CHANGE `gsm_num` `gsm_num` DECIMAL(12,4)  NOT NULL  DEFAULT '0.00'  COMMENT '数量';
ALTER TABLE `vms_goods_stock` CHANGE `gs_num` `gs_num` DECIMAL(12,4)  NOT NULL  DEFAULT '0.00'  COMMENT '数量';
ALTER TABLE `vms_provider_goods_check_detail` CHANGE `pgcd_num` `pgcd_num` DECIMAL(12,4)  NOT NULL  DEFAULT '0.00'  COMMENT '数量';
ALTER TABLE `vms_provider_goods_sample` CHANGE `pgs_weight` `pgs_weight` DECIMAL(12,4)  NOT NULL  DEFAULT '0.000'  COMMENT '重量/个（克）';
ALTER TABLE `vms_provider_goods_sample_record` CHANGE `pgsr_weight` `pgsr_weight` DECIMAL(12,4)  NOT NULL  DEFAULT '0.000'  COMMENT '重量(KG)';
