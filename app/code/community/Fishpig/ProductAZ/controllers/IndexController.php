<?php
/**
 * @category    Fishpig
 * @package     Fishpig_ProductAZ
 * @license     http://fishpig.co.uk/license.txt
 * @author      Ben Tideswell <help@fishpig.co.uk>
 * @SkipObfuscation
 */

class Fishpig_ProductAZ_IndexController extends Mage_Core_Controller_Front_Action
{
	/**
	 * Display the product AZ
	 *
	 * @return void
	 */
	public function indexAction()
	{
		$this->loadLayout();

		if (($pageTitle = trim(Mage::getStoreConfig('productaz/seo/page_title'))) !== '') {
			$this->_title($pageTitle);
		}
				
		if ($headBlock = $this->getLayout()->getBlock('head')) {
			if (($metaDescription = trim(Mage::getStoreConfig('productaz/seo/meta_description'))) !== '') {
				$headBlock->setDescription($metaDescription);
			}
		}
		
		$this->renderLayout();
	}
}