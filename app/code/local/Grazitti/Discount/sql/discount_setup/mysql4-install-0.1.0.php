<?php

$installer = $this;

$installer->startSetup();

$installer->run("

-- DROP TABLE IF EXISTS {$this->getTable('discount')};
CREATE TABLE {$this->getTable('discount')} (
  `discount_id` int(11) unsigned NOT NULL auto_increment,
  `rule_id` varchar(255) NOT NULL default '',
  `customer_id` varchar(255) NOT NULL default '',
  `update_time` datetime NULL,
  PRIMARY KEY (`discount_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

    ");

$installer->endSetup(); 