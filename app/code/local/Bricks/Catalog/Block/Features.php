<?php

class Bricks_Catalog_Block_Features extends Mage_Core_Block_Template {

	protected function _construct() {
		parent::_construct();
	}

	public function getProduct() {
		return Mage::registry('product');
	}

	public function getFeatures() {
		$results = array();

		/* @var $features Bricks_Catalog_Model_Resource_Features_Collection */
		$features = Mage::getSingleton('bricks_catalog/features')
			->getCollection()
			->addFieldToFilter( 'product_id', $this->getProduct()->getId() )
			->setOrder( 'sort_order', Mage_Core_Model_Resource_Db_Collection_Abstract::SORT_ORDER_ASC );
		$count = $features->count();
		foreach ( $features as $feature ) {
			/* @var $feature Bricks_Catalog_Model_Features */
			$ret = $feature->getData();
			if ( isset ( $ret['image'] ) && $ret['image'] ) {
				$ret['image'] = '<img src="' . $ret['image'] . '" />';
			}
			$ret['item_count'] = $count;
			$ret['id'] = $feature->getId();

			array_push($results, $ret);
		}

		return $results;
	}

}