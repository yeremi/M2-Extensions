<?php

namespace Klloom\ProductComments\Model\Account;


class Customer
{
    public function __construct(
        \Magento\Customer\Model\Session $customerSession
    )
    {
        $this->session = $customerSession;
    }

    protected function isCustomerLoggedIn()
    {
        return $this->session->isLoggedIn();
    }

}
