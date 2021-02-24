<?php

namespace Klloom\BugReport\Model\Mail;

class TransportBuilder extends \Magento\Framework\Mail\Template\TransportBuilder
{
    /**
     * @param $pdfString
     * @return $this
     */
    public function addAttachment($pdfString, $name)
    {
        $this->message->createAttachment(
            $pdfString,
            \Zend_Mime::TYPE_OCTETSTREAM, //'application/pdf',
            \Zend_Mime::DISPOSITION_ATTACHMENT,
            \Zend_Mime::ENCODING_BASE64,
            null //'attached.pdf'
        );
        return $this;
    }
}