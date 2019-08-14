<?php

class Bricks_Catalog_Block_Adminhtml_Tab extends Mage_Adminhtml_Block_Template implements Mage_Adminhtml_Block_Widget_Tab_Interface {

	public function __construct() {
		parent::__construct();

		$this->setTemplate('bricks/catalog/tab.phtml');
	}

	protected function _prepareLayout() {
		$this->setChild( 'add_button', $this->getLayout()->createBlock('adminhtml/widget_button')->setData( array(
			'label'	 => Mage::helper( 'bricks_catalog' )->__( 'Add New Field' ),
			'class'	 => 'add',
			'id'	 => 'add_new_product_feature'
		) ) );

		$this->setChild( 'delete_button', $this->getLayout()->createBlock('adminhtml/widget_button')->setData( array(
			'label'	 => Mage::helper( 'bricks_catalog' )->__( 'Delete' ),
			'class'	 => 'delete delete-bricks-product-feature'
		) ) );

		parent::_prepareLayout();

		if ( Mage::getSingleton( 'cms/wysiwyg_config' )->isEnabled() ) {
			$this->getLayout()->getBlock( 'head' )->setCanLoadTinyMce( true );
		}

		return $this;
	}

	public function getTabLabel() {
		return $this->__( 'Product Features' );
	}

	public function getTabTitle() {
		return $this->__( 'Product Features' );
	}

	public function canShowTab() {
		return true;
	}

	public function isHidden() {
		return false;
	}

	public function getFeatures() {
		$product = Mage::registry('product');
		$results = array();

		/* @var $features Bricks_Catalog_Model_Resource_Features_Collection */
		$features = Mage::getSingleton('bricks_catalog/features')
			->getCollection()
			->addFieldToFilter( 'product_id', $product->getId() )
			->setOrder( 'sort_order', Mage_Core_Model_Resource_Db_Collection_Abstract::SORT_ORDER_ASC );
		$count = $features->count();
		foreach ( $features as $feature ) {
			/* @var $feature Bricks_Catalog_Model_Features */
			$ret = $feature->getData();
			if ( isset ( $ret['image'] ) && $ret['image'] ) {
				$ret['image'] = '<img src="' . $ret['image'] . '" width="100" height="100" />';
			}
			$ret['item_count'] = $feature->getId();
			$ret['id'] = $feature->getId();
			$ret['title'] = htmlspecialchars( $feature->getTitle(), ENT_QUOTES );

			array_push($results, $ret);
		}

		return array_reverse($results);
	}

}