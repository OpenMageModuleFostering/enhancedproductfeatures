<?php

class Bricks_Catalog_Model_Resource_Features extends Mage_Core_Model_Resource_Db_Abstract {

	protected function _construct() {
		$this->_init( 'bricks_catalog/features', 'feature_id' );
	}

}