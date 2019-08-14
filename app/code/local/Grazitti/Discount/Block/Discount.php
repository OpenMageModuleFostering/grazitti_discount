<?php
class Grazitti_Discount_Block_Discount extends Mage_Core_Block_Template
{
	public function _prepareLayout()
    {
		return parent::_prepareLayout();
    }
    
     public function getDiscount()     
     { 
        if (!$this->hasData('discount')) {
            $this->setData('discount', Mage::registry('discount'));
        }
        return $this->getData('discount');
        
    }
}