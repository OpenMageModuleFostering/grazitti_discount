<?php

class Grazitti_Discount_Block_Adminhtml_Discount_Edit extends Mage_Adminhtml_Block_Widget_Form_Container
{
    public function __construct()
    {
        parent::__construct();
                 
        $this->_objectId = 'id';
        $this->_blockGroup = 'discount';
        $this->_controller = 'adminhtml_discount';
        
        $this->_updateButton('save', 'label', Mage::helper('discount')->__('Save Item'));
        $this->_updateButton('delete', 'label', Mage::helper('discount')->__('Delete Item'));
		
        $this->_addButton('saveandcontinue', array(
            'label'     => Mage::helper('adminhtml')->__('Save And Continue Edit'),
            'onclick'   => 'saveAndContinueEdit()',
            'class'     => 'save',
        ), -100);

        $this->_formScripts[] = "
            function toggleEditor() {
                if (tinyMCE.getInstanceById('discount_content') == null) {
                    tinyMCE.execCommand('mceAddControl', false, 'discount_content');
                } else {
                    tinyMCE.execCommand('mceRemoveControl', false, 'discount_content');
                }
            }

            function saveAndContinueEdit(){
                editForm.submit($('edit_form').action+'back/edit/');
            }
        ";
    }

    public function getHeaderText()
    {
        if( Mage::registry('discount_data') && Mage::registry('discount_data')->getId() ) {
            return Mage::helper('discount')->__("Edit Item '%s'", $this->htmlEscape(Mage::registry('discount_data')->getTitle()));
        } else {
            return Mage::helper('discount')->__('Add Item');
        }
    }
}