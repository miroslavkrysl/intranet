<?php


namespace Intranet\Services\Mail;


use Core\Contracts\Validation\ValidatorInterface;
use Intranet\Services\Mail\Exception\EmailException;

class Mail
{
    /**
     * @var ValidatorInterface
     */
    private $validator;
    /**
     * @var string
     */
    private $from;

    /**
     * Mail constructor.
     * @param ValidatorInterface $validator
     * @param string $from
     */
    public function __construct(ValidatorInterface $validator, string $from)
    {
        $this->validator = $validator;
        $this->from = $from;
    }

    /**
     * Send an email.
     * @param string $to
     * @param string $subject
     * @param string $message
     * @throws EmailException
     */
    public function send(string $to, string $subject, string $message)
    {
        if (!$to or !$this->validator->email($to)) {
            throw new EmailException('No valid recipient given.' . ($to ? (" Given: " . $to) : ""));
        }
        if (!$subject) {
            throw new EmailException('No subject given.');
        }
        if (!$message) {
            throw new EmailException('No message given.');
        }

        $message = wordwrap($message, 70, "\r\n");

        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=UTF-8';
        $headers[] = 'From: ' . $this->from;
        $headers[] = 'To: ' . $to;

        \register_shutdown_function('mail', $to, $subject, $message, implode("\r\n", $headers));
    }
}