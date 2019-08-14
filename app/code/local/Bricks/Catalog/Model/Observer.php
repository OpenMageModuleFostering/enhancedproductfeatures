<?php

class Bricks_Catalog_Model_Observer {

	public function saveProductFeatures( $observer ) {
		try {
			/* @var $product Mage_Catalog_Model_Product */
			$product = $observer->getEvent()->getProduct();
			$features = $this->_getRequest()->getParam('bricks_pf');
			if ( ! empty( $features ) && is_array(  $features ) ) {
				/* @var $model Bricks_Catalog_Model_Features */
				$model = Mage::getModel('bricks_catalog/features');
				foreach ( $features as $key => $feature ) {
					$model->setData( $feature );

					if ( $model->getData( 'feature_id' ) ) {
						$model->setId( $feature['feature_id'] );
					} else {
						$model->unsetData('feature_id');
					}

					$isEdit = ( bool ) $model->getId();

					if ( $model->getData('is_delete') == '1' && $isEdit ) {
						$model->delete();
					} else if ( $model->getData( 'is_delete' ) != '1' ) {
						$fileKey = 'bricks_pf_' . $key;
						if ( isset ( $_FILES[ $fileKey ] ) ) {
							try {
								$uploader = new Varien_File_Uploader( $fileKey );
								$uploader->setAllowedExtensions(array('jpg', 'jpeg', 'png', 'gif'));
								$uploader->setAllowRenameFiles(true);
								$uploader->setFilesDispersion(false);
								$uploader->setAllowCreateFolders(true);
								$path = implode( DS, array( Mage::getBaseDir('media'), 'catalog', 'product', 'features' ) ) . DS;
								$url = implode( DS, array( Mage::getBaseUrl('media'), 'catalog', 'product', 'features' ) ) . DS;
								$uploader->save( $path, $_FILES[$fileKey]['name'] );
								$model->setData( 'image', $url . basename( $uploader->getUploadedFileName() ) );
							} catch ( Exception $ex ) {
								Mage::logException($ex);
							}
						}

						$model->setData( 'product_id', $product->getId() );
						$model->save();
					}
				}
			}
		} catch ( Exception $e ) {
			Mage::logException($e);
		}
	}

	/**
	 * Shortcut to getRequest
	 *
	 * @return Mage_Core_Controller_Request_Http
	 */
	protected function _getRequest() {
		return Mage::app()->getRequest();
	}

	/**
	 *
	 * @param Varien_Event_Observer $observer
	 */
	public function beforeRenderProductView( $observer ) {
		$layout = $observer->getEvent()->getData( 'layout' );
		/* @var $layout Mage_Core_Model_Layout */
		$xml = $layout->getXmlString();
		$search = '<block type="catalog/product_view_tabs" name="product.info.tabs" as="info_tabs" template="catalog/product/view/tabs.phtml">';
		
		// echo "<pre>";
	 	// print_r($observer->getEvent()->getLayout()->getUpdate()->asString());
		// echo $xml;
		// exit;
		
		$pos = strpos( $xml, $search );
		if ( $pos !== false ) {
			$action = <<<LAYOUT
<action method="addTab" translate="title" module="bricks_catalog">
	<alias>product_features</alias>
	<title>Product Features</title>
	<block>bricks_catalog/features</block>
	<template>bricks/catalog/features.phtml</template>
</action>
LAYOUT;
			$xml = substr_replace( $xml, $search . $action , $pos, strlen( $search ) );
			$layout->setXml( simplexml_load_string( $xml, $layout->getUpdate()->getElementClass() ) );
		}
	}

}