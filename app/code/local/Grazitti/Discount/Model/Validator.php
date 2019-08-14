<?php
/**
 * Magento
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Magento to newer
 * versions in the future. If you wish to customize Magento for your
 * needs please refer to http://www.magento.com for more information.
 *
 * @category    Mage
 * @package     Mage_SalesRule
 * @copyright  Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */


/**
 * SalesRule Validator Model
 *
 * Allows dispatching before and after events for each controller action
 *
 * @category   Mage
 * @package    Mage_SalesRule
 * @author     Magento Core Team <core@magentocommerce.com>
 */
class Grazitti_Discount_Model_Validator extends Mage_SalesRule_Model_Validator
{
    
    public function init($websiteId, $customerGroupId, $couponCode)
    {
		        $customerID = Mage::getSingleton('customer/session')->getCustomer()->getID();
				$currentcustomercroupId = Mage::getSingleton('customer/session')->getCustomerGroupId();
				$oCoupon = Mage::getModel('salesrule/coupon')->load($couponCode, 'code');
				$oRule   = Mage::getModel('salesrule/rule')->load($oCoupon->getRuleId());
				$ruleId  = $oRule->getRuleId();	
				$rule_customergroup_ids = Mage::getModel('salesrule/rule')->load($ruleId)
				->getData('customer_group_ids');	
				
				$collection = Mage::getModel('discount/discount')
				->getCollection()
                ->addFieldToFilter('customer_id', array('finset' => $customerID))				
				->addFieldToFilter('rule_id' , $ruleId)
				->getData();
				if(in_array($currentcustomercroupId, $rule_customergroup_ids)){
					$customerGroupId=$customerGroupId;
				} else if($collection){
					$customerGroupId=$rule_customergroup_ids[0];
				}else{
					$customerGroupId=$customerGroupId;
				}
				
        $this->setWebsiteId($websiteId)
            ->setCustomerGroupId($customerGroupId)
            ->setCouponCode($couponCode);

        $key = $websiteId . '_' . $customerGroupId . '_' . $couponCode;
        if (!isset($this->_rules[$key])) {
            $this->_rules[$key] = Mage::getResourceModel('salesrule/rule_collection')
                ->setValidationFilter($websiteId, $customerGroupId, $couponCode)
                ->load();
        }
        return $this;
    }
}
