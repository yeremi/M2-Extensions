<?php
/**
 * Created by PhpStorm.
 * User: yeremiloli
 * Date: 7/06/18
 * Time: 9:13 AM
 */

namespace Klloom\AbuseReport\Model\Account;

class Customer
{
    protected $session;

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
