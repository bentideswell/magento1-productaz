<?php
/**
 * @category    Fishpig
 * @package     Fishpig_ProductAZ
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 */

class Fishpig_ProductAZ_Helper_Data extends Mage_Core_Helper_Abstract
{
	/**
	 * Determine whether the extension is enabled
	 *
	 * @return bool
	 */	
	public function isEnabled()
	{
		return Mage::getStoreConfigFlag('productaz/settings/enabled');
	}

	/**
	 * Retrieve a URL relative to the route
	 * @param string|array
	 * @return string
	 */
	public function getUrl($params)
	{
		if (!is_array($params)) {
			$params = array('_direct' => $params);
		}
		
		$params['_direct'] = isset($params['_direct']) 
			? $this->getRouteName() . '/' . ltrim($params['_direct'], '/')
			: $this->getRouteName() . '/';
		
		return Mage::getUrl('', $params);
	}
	
	/**
	 * Retrieve the route name
	 *
	 * @return string
	 */
	public function getRouteName()
	{
		return ($routeName =  trim(Mage::getStoreConfig('productaz/seo/url'), '/ ')) !== ''
			? $routeName
			: trim(Mage::getStoreConfig('productaz/settings/url'), '/ ');
	}
	
	/**
	 * Initialise the map route
	 *
	 * @param Varien_Event_Observer $observer
	 */
	public function initRoute(Varien_Event_Observer $observer)
	{
		Mage::app()->getConfig()->setNode('frontend/routers/productaz/args/frontName', $this->getRouteName(), true);
	}
}
