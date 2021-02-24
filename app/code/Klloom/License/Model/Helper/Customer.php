<?php

namespace Klloom\License\Model\Helper;


class Customer extends \Magento\Framework\App\Helper\AbstractHelper
{
    protected $_customerSession;
    protected $_customerGroupCollection;

    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Customer\Model\Session $customerSession,
        \Magento\Customer\Model\Group $customerGroupCollection
    )
    {
        parent::__construct($context);
        $this->_customerSession         = $customerSession;
        $this->_customerGroupCollection = $customerGroupCollection;
    }

    public function getCustomerGroup()
    {
        $currentGroupId = $this->_customerSession->getCustomer()->getGroupId(); //Get customer group Id , you have already this so directly get name
        $collection     = $this->_customerGroupCollection->load($currentGroupId);
        return $collection->getCustomerGroupCode();//Get group name
    }

}
