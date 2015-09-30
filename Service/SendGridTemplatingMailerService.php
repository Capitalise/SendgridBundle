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
use SendGrid\Email;
use StdClass;
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
     * @var array
     */
    private $credentials;

    /**
     *
     * @param SendGrid   $sendGrid
     * @param TwigEngine $templating
     * @param boolean    $throwExceptionsOnFail
     * @param array      $credentials
     */
    public function __construct(SendGrid $sendGrid, TwigEngine $templating, $throwExceptionsOnFail = true, $credentials = array())
    {
        $this->sendGrid = $sendGrid;
        $this->templating = $templating;
        $this->credentials = $credentials;

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
     *
     * @return type
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
        //
        $email = static::buildBaseEmail($from, $to, $subject, $additionalHeaders, $attachments);

        // If the given body is a TemplatedEmailBody object, populate and reassign the string value to itself
        if ($bodyHtml instanceof TemplatedEmailBody) {
            $bodyHtml = $this->templating->renderResponse(
                $bodyHtml->getTemplateName(),
                $bodyHtml->getVariables()
            )->getContent();
        }

        $email->setHtml($bodyHtml);

        return $this->processResponse($this->sendGrid->web->send($email));
    }

    /**
     *
     * @param type $response
     *
     * @return boolean
     */
    protected function processResponse(StdClass $response, $throwExceptionOnFail = true) {
        $result = (isset($response->message) && $response->message == "success");

        if ($result === false && $this->throwExceptionsOnFail === true) {
            throw new MailNotSentException(
                (
                    isset($response->errors) && is_array($response->errors)
                    ? implode(";", $response->errors)
                    : "No error information given"
                )
            );
        }

        return $result;
    }

    protected static function buildBaseEmail(array $from, array $to, $subject, array $additionalHeaders = array(), array $attachments = null)
    {
        $email = new Email();

        $fromAddress = current(array_keys($from));
        $fromName = current($from);

        $email->setFrom($fromAddress)
              ->setFromName($fromName)
              ->setSubject($subject);

        // Set to headers
        foreach ($to as $toAddress => $toName) {
            $email->addTo($toAddress, $toName);
        }

        // Set CC header if a value is given
        if (isset($additionalHeaders["cc"]) && is_array(($cc = $additionalHeaders["cc"]))) {
            $email->setCcs($cc);
        }

        // Set BCC header if a valud is given
        if (isset($additionalHeaders["bcc"]) && is_array(($bcc = $additionalHeaders["bcc"]))) {
            $email->setBccs($bcc);
        }

        if (isset($additionalHeaders["reply-to"])) {
            $email->setReplyTo($additionalHeaders["reply-to"]);
        }

        if (isset($attachments)) {
            $email->setAttachments($attachments);
        }

        return $email;
    }

    /**
     * Unsubscribe an email from the global mailing list
     *
     * @param string $email
     *
     * @return \Unirest\HttpResponse
     */
    public function unsubscribeEmail($email)
    {
        $url = "https://api.sendgrid.com/api/unsubscribes.add.json";

        $form             = array();
        $form['api_user'] = $this->credentials['username'];
        $form['api_key']  = $this->credentials['password'];
        $form['email']    = $email;

        $response = \Unirest::post($url, array(), $form);

        return $response;
    }

}
