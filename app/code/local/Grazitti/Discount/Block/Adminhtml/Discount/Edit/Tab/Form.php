<?php

class Grazitti_Discount_Block_Adminhtml_Discount_Edit_Tab_Form extends Mage_Adminhtml_Block_Widget_Form
{
  protected function _prepareForm()
  {
      $form = new Varien_Data_Form();
      $this->setForm($form);
      $fieldset = $form->addFieldset('discount_form', array('legend'=>Mage::helper('discount')->__('Item information')));
     
      $fieldset->addField('title', 'text', array(
          'label'     => Mage::helper('discount')->__('Title'),
          'class'     => 'required-entry',
          'required'  => true,
          'name'      => 'title',
      ));

      $fieldset->addField('filename', 'file', array(
          'label'     => Mage::helper('discount')->__('File'),
          'required'  => false,
          'name'      => 'filename',
	  ));
		
      $fieldset->addField('status', 'select', array(
          'label'     => Mage::helper('discount')->__('Status'),
          'name'      => 'status',
          'values'    => array(
              array(
                  'value'     => 1,
                  'label'     => Mage::helper('discount')->__('Enabled'),
              ),

              array(
                  'value'     => 2,
                  'label'     => Mage::helper('discount')->__('Disabled'),
              ),
          ),
      ));
     
      $fieldset->addField('content', 'editor', array(
          'name'      => 'content',
          'label'     => Mage::helper('discount')->__('Content'),
          'title'     => Mage::helper('discount')->__('Content'),
          'style'     => 'width:700px; height:500px;',
          'wysiwyg'   => false,
          'required'  => true,
      ));
     
      if ( Mage::getSingleton('adminhtml/session')->getDiscountData() )
      {
          $form->setValues(Mage::getSingleton('adminhtml/session')->getDiscountData());
          Mage::getSingleton('adminhtml/session')->setDiscountData(null);
      } elseif ( Mage::registry('discount_data') ) {
          $form->setValues(Mage::registry('discount_data')->getData());
      }
      return parent::_prepareForm();
  }
}