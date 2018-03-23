<?php
/**
 * @category    Fishpig
 * @package     Fishpig_ProductAZ
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 * @SkipObfuscation
 */

class Fishpig_ProductAZ_Block_Wrapper extends Mage_Catalog_Block_Product_Abstract
{
	/**
	 * Retrieve the character array
	 *
	 * @return array
	 */
	public function getCharacters()
	{
		return array_merge(array('#'), range('A', 'Z'));
	}
	
	/**
	 * Retrieve the URL for $char
	 *
	 * @param string $char
	 * @return string
	 */	
	public function getCharacterUrl($char)
	{
		return Mage::helper('productaz')->getUrl(array('_fragment' => $char));
	}
	
	/**
	 * Retrieve an array of products by the char
	 *
	 * @return array
	 */
	public function getProductsByChar()
	{
		if (!$this->hasProductsByChar()) {
			$products = $this->_getProductCollection()->load();
			$data = array();
			
			foreach($products as $product) {
				$cleanName = preg_replace('/([^a-z0-9]{1,})/i', '', strtoupper(trim($product->getName())));
				
				if (preg_match('/^[A-Z]{1}/', $cleanName, $match)) {
					$char = $match[0];
				}
				else if (preg_match('/^([0-9]{1})/', $cleanName, $match)) {
					$char = '#';
				}
				
				if (!isset($data[$char])) {
					$data[$char] = array();
				}
				
				$data[$char][trim(strtolower($product->getName() . $product->getId()))] = $product;
			}
			
			foreach($data as $char => $products) {
				ksort($products, SORT_STRING);
				
				$data[$char] = $products;
			}

			ksort($data);

			$this->setProductsByChar($data);
		}
		
		return $this->_getData('products_by_char');
	}
	
	/**
	 * Retrieve an array of attribute names for the product collection
	 *
	 * @return array
	 */
	public function getProductSelectAttributes()
	{
		if ($attributes = $this->_getData('product_select_attributes')) {
			if (!is_array($attributes)) {
				$attributes = explode(',', $attributes);
			}
		}
		
		if (!isset($attributes) || count($attributes) === 0) {
			$attributes = array('name', 'short_description');
		}
		
		return $attributes;
	}

	/**
	 * Retrieve a product collection
	 *
	 * @return Mage_Catalog_Model_Resource_Product_Collection
	 */
	protected function _getProductCollection()
	{
		$collection = Mage::getResourceModel('catalog/product_collection')
			->setStoreId(Mage::app()->getStore()->getId())
			->addAttributeToSelect(array_merge($this->getProductSelectAttributes(), array($this->getProductTextAttribute())))
			->addUrlRewrite()
			->addAttributeToSort('name', 'asc');

		Mage::getSingleton('catalog/product_status')->addVisibleFilterToCollection($collection);
		Mage::getSingleton('catalog/product_visibility')->addVisibleInCatalogFilterToCollection($collection);
		
		if (!Mage::helper('cataloginventory')->isShowOutOfStock()) {
			Mage::getSingleton('cataloginventory/stock')->addInStockFilterToCollection($collection);
		}
		
		Mage::getModel('review/review')->appendSummary($collection);

		return $collection;
	}
	
	/**
	 * Retrieve the attribute for the product text
	 *
	 * @return null|string
	 */
	public function getProductTextAttribute()
	{
		if (!$this->hasProductTextAttribute()) {
			$this->setProductTextAttribute(
				Mage::getStoreConfig('productaz/settings/product_text_attribute')
			);
		}
		
		return $this->_getData('product_text_attribute');
	}
	
	/**
	  Retrieve the value of the attribute that has been set for the product text
	  *
	  * @param Mage_Catalog_Model_Product
	  * @return string
	  */
	public function getProductText(Mage_Catalog_Model_Product $product)
	{
		if ($code = $this->getProductTextAttribute()) {
			return trim(
				$product->getData($code)
			);
		}
		
		return '';
	}
}
