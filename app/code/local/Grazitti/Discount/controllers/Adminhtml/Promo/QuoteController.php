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
 * @package     Mage_Adminhtml
 * @copyright  Copyright (c) 2006-2015 X.commerce, Inc. (http://www.magento.com)
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

require_once 'Mage/Adminhtml/controllers/Promo/QuoteController.php';
class Grazitti_Discount_Adminhtml_Promo_QuoteController extends Mage_Adminhtml_Promo_QuoteController
{
 
public function customerAction(){
		$this->loadLayout();
		$this->getLayout()->getBlock('customer.grid')
		->setCustomers($this->getRequest()->getPost('customers', null));
		$this->renderLayout();
	}
	public function customergridAction(){
		$this->loadLayout();
		$this->getLayout()->getBlock('customer.grid')
		->setCustomers($this->getRequest()->getPost('customers', null));
		$this->renderLayout();
	}
    public function editAction()
    {
        $id = $this->getRequest()->getParam('id');
        $model = Mage::getModel('salesrule/rule');

        if ($id) {
            $model->load($id);
            if (! $model->getRuleId()) {
                Mage::getSingleton('adminhtml/session')->addError(
                    Mage::helper('salesrule')->__('This rule no longer exists.'));
                $this->_redirect('*/*');
                return;
            }
        }

        $this->_title($model->getRuleId() ? $model->getName() : $this->__('New Rule'));

        // set entered data if was error when we do save
        $data = Mage::getSingleton('adminhtml/session')->getPageData(true);
        if (!empty($data)) {
            $model->addData($data);
        }

        $model->getConditions()->setJsFormObject('rule_conditions_fieldset');
        $model->getActions()->setJsFormObject('rule_actions_fieldset');

        Mage::register('current_promo_quote_rule', $model);

        $this->_initAction()->getLayout()->getBlock('promo_quote_edit')
             ->setData('action', $this->getUrl('*/*/save'));

        $this
            ->_addBreadcrumb(
                $id ? Mage::helper('salesrule')->__('Edit Rule')
                    : Mage::helper('salesrule')->__('New Rule'),
                $id ? Mage::helper('salesrule')->__('Edit Rule')
                    : Mage::helper('salesrule')->__('New Rule'))
            ->renderLayout();

    }

    /**
     * Promo quote save action
     *
     */
    public function saveAction()
    {  
		
		if ($this->getRequest()->getPost()) {
            try {
                /** @var $model Mage_SalesRule_Model_Rule */
                
			
				$model = Mage::getModel('salesrule/rule');
                Mage::dispatchEvent(
                    'adminhtml_controller_salesrule_prepare_save',
                    array('request' => $this->getRequest()));
                $data = $this->getRequest()->getPost();
				//echo'<pre>'; print_r($data); die;
                $data = $this->_filterDates($data, array('from_date', 'to_date'));
                $id = $this->getRequest()->getParam('rule_id');
                if ($id) {
                    $model->load($id);
                    if ($id != $model->getId()) {
                        Mage::throwException(Mage::helper('salesrule')->__('Wrong rule specified.'));
                    }
                }

                $session = Mage::getSingleton('adminhtml/session');

                $validateResult = $model->validateData(new Varien_Object($data));
                if ($validateResult !== true) {
                    foreach($validateResult as $errorMessage) {
                        $session->addError($errorMessage);
                    }
                    $session->setPageData($data);
                    $this->_redirect('*/*/edit', array('id'=>$model->getId()));
                    return;
                }

                if (isset($data['simple_action']) && $data['simple_action'] == 'by_percent'
                && isset($data['discount_amount'])) {
                    $data['discount_amount'] = min(100,$data['discount_amount']);
                }
                if (isset($data['rule']['conditions'])) {
                    $data['conditions'] = $data['rule']['conditions'];
                }
                if (isset($data['rule']['actions'])) {
                    $data['actions'] = $data['rule']['actions'];
                }
                unset($data['rule']);
                $model->loadPost($data);

                $useAutoGeneration = (int)!empty($data['use_auto_generation']);
                $model->setUseAutoGeneration($useAutoGeneration);

                $session->setPageData($model->getData());

                $model->save();
				/* Save Customer list data in Custom tab table database */
				$rule_id=Mage::app()->getRequest()->getParam('rule_id');
				$selected_customers = Mage::app()->getRequest()->getParam('selected_customers');
				$selected_customers = implode(",",$selected_customers);
				//echo "<pre>";
				/* echo "<pre>";
				print_r($selected_customers);die; */
				$customModel = Mage::getModel('discount/discount');
				$customTabModule = Mage::getModel('discount/discount')
				->getCollection()
				->addFieldToFilter('rule_id',$rule_id)
				->addFieldToSelect('discount_id')
				->getFirstItem()->getData();
				//print_r($customTabModule); die;
				if($customTabModule)
				{
					$customModel=$customModel->setDiscountId($customTabModule['discount_id']);
					$customModel=$customModel->setRuleId($rule_id);		
					if($selected_customers)
					{
					$customModel=$customModel->setCustomerId($selected_customers);
					}
					else
					{
					$customModel=$customModel->setCustomerId('');
					}
					$customModel=$customModel->setPosition($value['position']);
				}
				else
				{
					$customModel=$customModel->setRuleId($rule_id);
					if($selected_customers){
						$customModel=$customModel->setCustomerId($selected_customers);
					}else{
						$customModel=$customModel->setCustomerId('');
					}
					$customModel=$customModel->setPosition($value['position']);					
				}
				$customModel  = $customModel->save();
				/* Save Customer list data in Custom tab table database */

                $session->addSuccess(Mage::helper('salesrule')->__('The rule has been saved.'));
                $session->setPageData(false);
                if ($this->getRequest()->getParam('back')) {
                    $this->_redirect('*/*/edit', array('id' => $model->getId()));
                    return;
                }
                $this->_redirect('*/*/');
                return;
            } catch (Mage_Core_Exception $e) {
                $this->_getSession()->addError($e->getMessage());
                $id = (int)$this->getRequest()->getParam('rule_id');
                if (!empty($id)) {
                    $this->_redirect('*/*/edit', array('id' => $id));
                } else {
                    $this->_redirect('*/*/new');
                }
                return;

            } catch (Exception $e) {
                $this->_getSession()->addError(
                    Mage::helper('catalogrule')->__('An error occurred while saving the rule data. Please review the log and try again.'));
                Mage::logException($e);
                Mage::getSingleton('adminhtml/session')->setPageData($data);
                $this->_redirect('*/*/edit', array('id' => $this->getRequest()->getParam('rule_id')));
                return;
            }
        }
        $this->_redirect('*/*/');
    }
	public function gridAction()
	{
		
		$this->loadLayout();
		$this->getResponse()->setBody(
			$this->getLayout()->createBlock('discount/adminhtml_promo_quote_edit_tab_custom')->toHtml()
		);
		 

	}

}
