<?php
namespace Klloom\AbuseReport\Model;

/**
 * Contact module configuration
 *
 * @api
 * @since 100.2.0
 */
interface ConfigInterface
{
    /**
     * Recipient email config path
     */
    const XML_PATH_EMAIL_RECIPIENT = 'abuse/email/recipient_email';

    /**
     * Sender email config path
     */
    const XML_PATH_EMAIL_SENDER = 'abuse/email/sender_email_identity';

    /**
     * Email template config path
     */
    const XML_PATH_EMAIL_TEMPLATE = 'abuse/email/abuse_email_template';

    /**
     * Sender notifyemail config path
     */
    const XML_PATH_NOTIFYEMAIL_SENDER = 'abuse/email/notify_email_sender';

    /**
     * Email template config path
     */
    const XML_PATH_NOTIFYEMAIL_TEMPLATE = 'abuse/email/notify_email_template';

    /**
     * Enabled config path
     */
    const XML_PATH_ENABLED = 'abuse/abuse/enabled';

    /**
     * Check if contacts module is enabled
     *
     * @return bool
     * @since 100.2.0
     */
    public function isEnabled();

    /**
     * Return email template identifier
     *
     * @return string
     * @since 100.2.0
     */
    public function emailTemplate();

    /**
     * Return email recipient address
     *
     * @return string
     * @since 100.2.0
     */
    public function emailRecipient();

    /**
     * Return notify email template identifier
     * @return string
     * @since 100.2.0
     */

    public function emailSender();

    /**
     * Return notify email sender address
     * @return string
     * @since 100.2.0
     */
    public function notifyEmailTemplate();

    /**
     * Return email sender address
     *
     * @return string
     * @since 100.2.0
     */

    public function notifyEmailSender();


}
