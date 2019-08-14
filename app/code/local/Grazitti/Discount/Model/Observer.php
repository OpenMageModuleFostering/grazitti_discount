<?php
 
class Grazitti_Discount_Model_Observer
{
	/**
	 * Flag to stop observer executing more than once
	 *
	 * @var static bool
	 */
	static protected $_singletonFlag = false;

	/**
	 * This method will run when the product is saved from the Magento Admin
	 * Use this function to update the product model, process the 
	 * data or anything you like
	 *
	 * @param Varien_Event_Observer $observer
	 */
	 public function saveProductTabData1(Varien_Event_Observer $observer)
	{
		// Mage::getModel('customtabs/customtabs')->load(5)
		// $rule_id=Mage::app()->getRequest()->getParam('rule_id');
		// $selected_customers=Mage::app()->getRequest()->getParam('selected_customers');
		// $selected_customers=implode(",",$selected_customers);
		
		// $customTabModule=Mage::getModel('customtabs/customtabs');
		// $customTabModule=$customTabModule->setRuleId($rule_id);
		// $customTabModule=$customTabModule->setCustomerId($selected_customers);
		// $customTabModule=$customTabModule->save();
		// echo "<pre>";
		// print_r($customTabModule->getData());
		// echo "<pre>asdsa";
		// print_r(Mage::app()->getRequest()->getParam('selected_customers'));
		// print_r(Mage::app()->getRequest()->getParams());die;
		// print_r($observer->getEvent()->getRule()->getData());
		$customFieldValue =  $this->_getRequest()->getPost('custom_field');
			// echo "<pre>";
			// print_r($customFieldValue);
			// die;
	}
	public function saveProductTabData(Varien_Event_Observer $observer)
	{
		// echo "<pre>";
		// print_r($observer->getEvent());die;
		if (!self::$_singletonFlag) {
			self::$_singletonFlag = true;
			
			$product = $observer->getEvent()->getProduct();
		
			try {
				/**
				 * Perform any actions you want here
				 *
				 */
			$customFieldValue =  $this->_getRequest()->getPost('custom_field');
			//echo "<pre>";
			//print_r($customFieldValue);die;

				/**
				 * Uncomment the line below to save the product
				 *
				 */
				//$product->save();
			}
			catch (Exception $e) {
				Mage::getSingleton('adminhtml/session')->addError($e->getMessage());
			}
		}
	}
     
	/**
	 * Retrieve the product model
	 *
	 * @return Mage_Catalog_Model_Product $product
	 */
	public function getProduct()
	{
		return Mage::registry('product');
	}
	
    /**
     * Shortcut to getRequest
     *
     */
    protected function _getRequest()
    {
        return Mage::app()->getRequest();
    }
}