<?php
/**
 * SendGridTemplatingMailerService.php
 * Definition of class SendGridTemplatingMailerService
 *
 * Created 29-Jan-2014 17:43:59
 *
 * @author M.D.Ward <matthew.ward@byng-systems.com>
 * @copyright (c) 2014, Byng Systems/SkillsWeb Ltd
 */

namespace Savch\SendgridBundle\Service;

use SendGrid;
use SendGrid\Attachment;
use SendGrid\Content;
use SendGrid\Email;
use SendGrid\Mail;
use SendGrid\Personalization;
use SendGrid\Response;
use Savch\SendgridBundle\Exception\MailNotSentException;
use Savch\SendgridBundle\Model\TemplatedEmailBody;
use Symfony\Bundle\TwigBundle\TwigEngine;



/**
 * SendGridTemplatingMailerService - Registered as a Symfony2 service to provide quick,
 * convenient methods of sending a multipart plain & HTML email via the
 * SendGrid mail API
 *
 * @author M.D.Ward <matthew.ward@byng-systems.com>
 */
class SendGridTemplatingMailerService
{
    /**
     *
     * @var \SendGrid
     */
    private $sendGrid;

    /**
     *
     * @var \Symfony\Bundle\TwigBundle\TwigEngine
     */
    private $templating;

    /**
     *
     * @var boolean
     */
    protected $throwExceptionsOnFail;

    /**
     *
     * @param SendGrid   $sendGrid
     * @param TwigEngine $templating
     * @param boolean    $throwExceptionsOnFail
     */
    public function __construct(SendGrid $sendGrid, TwigEngine $templating, $throwExceptionsOnFail = true)
    {
        $this->sendGrid = $sendGrid;
        $this->templating = $templating;

        $this->setThrowExceptionsOnFail($throwExceptionsOnFail);
    }

    /**
     *
     * @return \Symfony\Bundle\TwigBundle\TwigEngine
     */
    public function getTemplating()
    {
        return $this->templating;
    }

    /**
     * @return SendGrid
     */
    protected function getSendGrid()
    {
        return $this->sendGrid;
    }

    /**
     *
     * @return boolean
     */
    public function getThrowExceptionsOnFail()
    {
        return $this->throwExceptionsOnFail;
    }

    /**
     *
     * @param boolean $throwExceptionsOnFail
     */
    public function setThrowExceptionsOnFail($throwExceptionsOnFail)
    {
        $this->throwExceptionsOnFail = ($throwExceptionsOnFail === true);
    }

    public function sendHtmlEmail(array $from, array $to, $subject, $bodyHtml, array $additionalHeaders = array(), array $attachments = null)
    {
        // Build base email
        $email = static::buildBaseEmail($from, $to, $subject, $additionalHeaders, $attachments);

        // If the given body is a TemplatedEmailBody object, populate and reassign the string value to itself
        if ($bodyHtml instanceof TemplatedEmailBody) {
            $bodyHtml = $this->templating->renderResponse(
                $bodyHtml->getTemplateName(),
                $bodyHtml->getVariables()
            )->getContent();
        }

        $content = new Content("text/html", $bodyHtml);
        $email->addContent($content);

        return $this->processResponse($this->sendGrid->client->mail()->send()->post($email));
    }

    /**
     * @param Response $response
     *
     * @return bool
     * @throws MailNotSentException
     */
    protected function processResponse(Response $response) {
        $result = (empty($response->body()) && ($response->statusCode() === 200 || $response->statusCode() === 202));

        if ($result === false && $this->throwExceptionsOnFail === true) {

            $body = json_decode($response->body(), true);

            $hasErrors = !is_null($response->headers())
                && $response->body()
                && array_key_exists('errors', $body)
                && is_array($body['errors']);

            throw new MailNotSentException(
                (
                $hasErrors
                    ? implode("", $response->headers()) . implode(" ", $body['errors'][0])
                    : "No error information given"
                )
            );
        }

        return $result;
    }

    /**
     * @param array      $from
     * @param array      $to
     * @param            $subject
     * @param array      $additionalHeaders
     * @param array|null $attachments
     *
     * @return Mail
     */
    protected static function buildBaseEmail(array $from, array $to, $subject, array $additionalHeaders = array(), array $attachments = null)
    {
        $fromAddress = current(array_keys($from));
        $fromName = current($from);

        $fromObj = new Email($fromName, $fromAddress);

        $email = new Mail();
        $email->setFrom($fromObj);
        $email->setSubject($subject);

        $personalization = new Personalization();

        // Set to headers
        foreach ($to as $toAddress => $toName) {
            $toObj = new Email($toName, $toAddress);
            $personalization->addTo($toObj);
        }

        // Set CC header if a value is given
        if (isset($additionalHeaders["cc"]) && is_array(($ccs = $additionalHeaders["cc"]))) {
            foreach ($ccs as $ccAddress => $ccName) {
                $ccObj = new Email($ccName, $ccAddress);
                $personalization->addCc($ccObj);
            }
        }

        // Set BCC header if a valud is given
        if (isset($additionalHeaders["bcc"]) && is_array(($bccs = $additionalHeaders["bcc"]))) {
            foreach ($bccs as $bccAddress => $bccName) {
                $bccObj = new Email($bccName, $bccAddress);
                $personalization->addBcc($bccObj);
            }
        }

        if (isset($additionalHeaders["reply-to"])) {
            $email->setReplyTo($additionalHeaders["reply-to"]);
        }

        if (isset($attachments)) {

            foreach ($attachments as $attachmentPath) {
                $attachment = new Attachment();
                $attachment->setContent(base64_encode(file_get_contents($attachmentPath)));
                $attachment->setFilename(basename($attachmentPath));
                $attachment->setDisposition("attachment");
                $email->addAttachment($attachment);
            }
        }

        $email->addPersonalization($personalization);

        return $email;
    }

    /**
     * Unsubscribe an email from the global mailing list
     *
     * @param string $email
     *
     * @return SendGrid\Response
     */
    public function unsubscribeEmail($email)
    {
        $request = [
            "recipient_emails" => [
                $email
            ]
        ];

        return $this->sendGrid->client->asm()->suppressions()->global()->post($request);
    }

}
