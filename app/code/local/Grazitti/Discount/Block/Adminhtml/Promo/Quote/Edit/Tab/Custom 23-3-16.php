<?php
class Fishpig_Customtabs_Block_Adminhtml_Promo_Quote_Edit_Tab_Custom 
extends Mage_Adminhtml_Block_Widget_Grid

{ /**
     * Mandatory to override as we are implementing Widget Tab interface
     * Return Tab Title
     *
     * @return string
     */
	  public function __construct()
    {
        parent::__construct();
       $this->setId('customerGrid');
		$this->setUseAjax(true); // Using ajax grid is important
		$this->setDefaultSort('entity_id');
        //$this->setTitle(Mage::helper('catalogrule')->__('Catalog Price Rule'));
		$this->setSaveParametersInSession(false);
	}
    

  protected function _prepareCollection()
    {
		 
        $collection = Mage::getResourceModel('customer/customer_collection')
				->addNameToSelect()
				->addAttributeToSelect('email')
				->addAttributeToSelect('created_at')
				->addAttributeToSelect('group_id')
				->joinAttribute('billing_postcode', 'customer_address/postcode', 'default_billing', null, 'left')
				->joinAttribute('billing_city', 'customer_address/city', 'default_billing', null, 'left')
				->joinAttribute('billing_telephone', 'customer_address/telephone', 'default_billing', null, 'left')
				->joinAttribute('billing_region', 'customer_address/region', 'default_billing', null, 'left')
				->joinAttribute('billing_country_id', 'customer_address/country_id', 'default_billing', null, 'left')
		;
		

		$tm_id = $this->getRequest()->getParam('id');
		if(!isset($tm_id)) {
			$tm_id = 0;
		}
		Mage::getResourceModel('customtabs/customtabs')->addGridPosition($collection,$tm_id);
		//echo'<pre>';print_r($test);
		$this->setCollection($collection);
		return parent::_prepareCollection();
    }
	protected function _addColumnFilterToCollection($column)
	{
		// Set custom filter for in product flag
		//echo'test';
		if ($column->getId() == 'selected_customers') {
			$ids = $this->_getSelectedCustomers();
			if (empty($ids)) {
				$ids = 0;
			}
			if ($column->getFilter()->getValue()) {
				$this->getCollection()->addFieldToFilter('entity_id', array('in'=>$ids));
			} else {
				if($productIds) {
					$this->getCollection()->addFieldToFilter('entity_id', array('nin'=>$ids));
				}
			}
		} else {
			parent::_addColumnFilterToCollection($column);
		}
		return $this;
	}
    protected function _prepareColumns()
    {
       
			$this->addColumn('selected_customers', array(
            'header'    => $this->__('Popular'),
            'type'      => 'checkbox',
            'index'     => 'entity_id',
            'align'     => 'center',
            'field_name'=> 'selected_customers[]',
            'values'    => $this->_getSelectedCustomers(),
        ));
 
       $this->addColumn('entity_id', array(
            'header'    => Mage::helper('customer')->__('ID'),
            'width'     => '50px',
            'index'     => 'entity_id',
            'type'  => 'number',
            ));
            $this->addColumn('customer_name', array(
            'header'    => Mage::helper('customer')->__('Name'),
            'index'     => 'name'
            ));
            $this->addColumn('customer_email', array(
            'header'    => Mage::helper('customer')->__('Email'),
            'width'     => '150',
            'index'     => 'email'
            ));

            $groups = Mage::getResourceModel('customer/group_collection')
            ->addFieldToFilter('customer_group_id', array('gt'=> 0))
            ->load()
            ->toOptionHash();

            $this->addColumn('group', array(
            'header'    =>  Mage::helper('customer')->__('Group'),
            'width'     =>  '100',
            'index'     =>  'group_id',
            'type'      =>  'options',
            'options'   =>  $groups,
            ));            
            $this->addColumn('customer_since', array(
            'header'    => Mage::helper('customer')->__('Customer Since'),
            'type'      => 'datetime',
            'align'     => 'center',
            'index'     => 'created_at',
            'gmtoffset' => true
            ));
			$this->addColumn('position', array(
            'header'            => Mage::helper('catalog')->__('Position'),
            'name'              => 'position',
            'width'             => 60,
            'type'              => 'number',
            'validate_class'    => 'validate-number',
            'index'             => 'position',
            'editable'          => true,
            'edit_only'         => true
            ));
 
        return parent::_prepareColumns();
    }
	protected function _getSelectedCustomers()   // Used in grid to return selected customers values.
	{
		$customers = array_keys($this->getSelectedCustomers());
		
		return $customers;
	}
	public function getGridUrl()
	{
		return $this->_getData('grid_url') ? $this->_getData('grid_url') : $this->getUrl('*/*/customergrid', array('_current'=>true));
	}
	protected function getSelectedCustomers()
    {
         /*  $tm_id = $this->getRequest()->getParam('id');
		//print_r($this->getRequest()->getParam('rule_id')); die;
		if(!isset($tm_id)) {
			$tm_id = 0;
		}
		$collection = Mage::getModel('customtabs/customtabs')->getCollection();
		$collection->addFieldToFilter('rule_id',$tm_id);

		//echo'<pre>';print_r($collection);die;
		$custIds = array();
		
		foreach($collection as $obj){
			$custIds[$obj->getCustomerId()] = array('position'=>$obj->getPosition());
			//print_r($custIds); die;
		}
		return $custIds;
    } */
		 $tm_id = $this->getRequest()->getParam('id');
		//print_r($this->getRequest()->getParam('rule_id')); die;
		if(!isset($tm_id)) {
			$tm_id = 0;
		}
		$collection = Mage::getModel('customtabs/customtabs')->getCollection();
		$collection = $collection->addFieldToFilter('rule_id',$tm_id)->getFirstItem();
		$customer_ids = explode(",",$collection['customer_id']);
		//echo'<pre>';print_r($customer_ids); die;
		$custIds = array();
		
		foreach($customer_ids as $obj){	
			$custIds[$obj] = array('position'=>'0');
			 
		}
		return $custIds;
    }
}