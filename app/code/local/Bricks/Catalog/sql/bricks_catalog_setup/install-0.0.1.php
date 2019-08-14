<?php

/* @var $installer Mage_Catalog_Model_Resource_Setup */
$installer = $this;
$installer->startSetup();

/* @var $adapter Varien_Db_Adapter_Pdo_Mysql */
$adapter = $installer->getConnection();

$descriptions = $adapter->newTable( $installer->getTable( 'bricks_catalog/features' ) );
$descriptions->addColumn( 'feature_id', Varien_Db_Ddl_Table::TYPE_BIGINT, null, array(
	'identity'	 => true,
	'unsigned'	 => true,
	'nullable'	 => false,
	'primary'	 => true
) )->addColumn( 'product_id', Varien_Db_Ddl_Table::TYPE_BIGINT, null, array(
	'unsigned'	 => true,
	'nullable'	 => false
) )->addColumn( 'title', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
	'nullable' => false
) )->addColumn( 'content', Varien_Db_Ddl_Table::TYPE_TEXT, null, array(
	'nullable' => false
) )->addColumn( 'image', Varien_Db_Ddl_Table::TYPE_VARCHAR, 255, array(
	'nullable' => false
) )->addColumn( 'image_position', Varien_Db_Ddl_Table::TYPE_VARCHAR, 20, array(
	'nullable' => false
) )->addColumn( 'sort_order', Varien_Db_Ddl_Table::TYPE_BIGINT, null, array(
	'unsigned'	 => true,
	'nullable'	 => true
) )->addIndex( $installer->getIdxName( 'bricks_catalog/features', array( 'product_id' ) ), array( 'product_id' ) );

$adapter->createTable( $descriptions );

$installer->endSetup();