<?php

namespace Klloom\BugReport\Block;

use Magento\Framework\View\Element\Template;

/**
 * Main bug form block
 *
 * @api
 * @since 100.0.2
 */
class ContactForm extends Template
{
    /**
     * @var \Magento\Framework\App\Response\RedirectInterface
     */
    protected $redirect;
    /**
     * ContactForm constructor.
     * @param Template\Context $context
     * @param array $data
     */
    public function __construct(
        Template\Context $context,
        \Magento\Framework\App\Response\RedirectInterface $redirect,
        array $data = []
    )
    {
        $this->redirect = $redirect;
        parent::__construct($context, $data);
        $this->_isScopePrivate = true;
    }

    /**
     * Returns action url for bug form
     *
     * @return string
     */
    public function getFormAction()
    {
        return $this->getUrl('bug-report/index/post', ['_secure' => true]);
    }
    public function  getLastUrl(){
        return  $redirectUrl = $this->redirect->getRedirectUrl();
    }
}
