
ALTER TABLE `vms_provider_goods_sample_record` ADD INDEX `idx_goods_id` (`pgsr_provider_goods_id`);

ALTER TABLE `vms_goods_sale_online` ADD INDEX `idx_shop_id` (`gso_shop_id`);
