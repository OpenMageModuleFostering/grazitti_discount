<?php

class Grazitti_Discount_Block_Adminhtml_Discount_Edit_Tabs extends Mage_Adminhtml_Block_Widget_Tabs
{

  public function __construct()
  {
      parent::__construct();
      $this->setId('discount_tabs');
      $this->setDestElementId('edit_form');
      $this->setTitle(Mage::helper('discount')->__('Item Information'));
  }

  protected function _beforeToHtml()
  {
    /*   $this->addTab('form_section', array(
          'label'     => Mage::helper('discount')->__('Item Information'),
          'title'     => Mage::helper('discount')->__('Item Information'),
          'content'   => $this->getLayout()->createBlock('discount/adminhtml_discount_edit_tab_form')->toHtml(),
      )); */
	    $this->addTab('grid_section', array(
          'label'     => Mage::helper('salesrule')->__('Customer List'),
          'title'     => Mage::helper('salesrule')->__('Customer List'),
          'content'   => $this->getLayout()->createBlock('discount/adminhtml_promo_quote_edit_tab_custom')->toHtml(),
      ));
     
     
      return parent::_beforeToHtml();
  }
}