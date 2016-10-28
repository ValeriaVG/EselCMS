<?php

class EselMailer extends EselModule
{
    public function __construct()
    {
        require_once SL_CORE.'vendor/swiftmailer/swiftmailer/lib/swift_required.php';
        $this->config = self::getConfig('EselMailer');
    }
    public $config;
    public function sendMail($to, $subject, $body)
    {

        print_r($this->config);
        $transport = Swift_MailTransport::newInstance();

        if ($this->config->smtp) {

            $transport = Swift_SmtpTransport::newInstance($this->config->smtp_host, $this->config->smtp_port)
            ->setUsername($this->config->smtp_username)
            ->setPassword($this->config->smtp_password);
            if (!empty($this->config->smtp_encrypt)) {
                $transport->setEncryption($this->config->smtp_encrypt);
            }
        }
        $mailer = Swift_Mailer::newInstance($transport);
        $message = Swift_Message::newInstance($subject)
          ->setFrom(array($this->config->from_email => $this->config->from_name))
          ->setTo(explode(',', $to))
          ->setBody($body, 'text/html');

  // Send the message
  return $mailer->send($message);
    }
}
