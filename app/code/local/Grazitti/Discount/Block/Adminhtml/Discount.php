<?php
class Grazitti_Discount_Block_Adminhtml_Discount extends Mage_Adminhtml_Block_Widget_Grid_Container
{
  public function __construct()
  {
    $this->_controller = 'adminhtml_discount';
    $this->_blockGroup = 'discount';
    $this->_headerText = Mage::helper('discount')->__('Item Manager');
    $this->_addButtonLabel = Mage::helper('discount')->__('Add Item');
    parent::__construct();
  }
}