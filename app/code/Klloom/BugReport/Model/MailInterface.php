<?php

namespace Klloom\BugReport\Model;

/**
 * Email from bug form
 *
 * @api
 * @since 100.2.0
 */
interface MailInterface
{
    /**
     * Send email from bug form
     *
     * @param string $replyTo Reply-to email address
     * @param array $variables Email template variables
     * @return void
     * @since 100.2.0
     */
    public function send($replyTo, array $variables);
}
