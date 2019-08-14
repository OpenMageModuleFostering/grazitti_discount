<?php
class Fishpig_Customtabs_Block_Adminhtml_Promo_Quote_Edit_Tab_Custom 
extends Mage_Adminhtml_Block_Widget_Grid
implements Mage_Adminhtml_Block_Widget_Tab_Interface
{ /* /**
     * Mandatory to override as we are implementing Widget Tab interface
     * Return Tab Title
     *
     * @return string
     */
    public function getTabTitle(){
        return Mage::helper('salesrule')->__('Custom Tab');
    }

    /**
     * Mandatory to override as we are implementing Widget Tab interface
     * Return Tab Label
     *
     * @return string
     */
    public function getTabLabel(){
        return Mage::helper('salesrule')->__('Custom Tab');
    }

    /**
     * Mandatory to override as we are implementing Widget Tab interface
     * Can show tab in tabs
     * Here you can write condition when the tab should we shown or not. Like you see when we create shopping cart rule
     * Manage coupon tab doesn't come. If you want that then just make a function and check whether you have information
     * in registry or not
     *
     * @return boolean
     */
    public function canShowTab(){
        return true;
    }

    
    public function isHidden(){
        return false;
    }

   
    public function getAfter(){
        return 'coupons_section';
    }

  protected function _prepareCollection()
    {
        $collection = Mage::getResourceModel('customer/customer_collection')
            ->addNameToSelect();
 
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }
    protected function _prepareColumns()
    {
        $this->addColumn('selected_customers', array(
            'header'    => $this->__('Popular'),
            'type'      => 'checkbox',
            'index'     => 'entity_id',
            'align'     => 'center',
            'field_name'=> 'selected_customers',
            'values'    => $this->getSelectedCustomers(),
        ));
 
        return parent::_prepareColumns();
    }
    public function getGridUrl()
    {
        return $this->getUrl('*/*/customersGrid', array('_current' => true));
    } */
}