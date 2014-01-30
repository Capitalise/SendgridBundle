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
     * @param SendGrid $sendGrid
     * @param \Symfony\Bundle\TwigBundle\TwigEngine $templating
     */
    public function __construct(SendGrid $sendGrid, TwigEngine $templating)
    {
        $this->sendGrid = $sendGrid;
        $this->templating = $templating;
    }
    
    /**
     * 
     * @return \Symfony\Bundle\TwigBundle\TwigEngine
     */
    public function getTemplating()
    {
        return $this->templating;
    }
    
    public function sendHtmlEmail($from, $to, $subject, $bodyHtml, array $additionalHeaders = array())
    {
        // 
        $email = static::buildBaseEmail($from, $to, $subject, $additionalHeaders);
        
        // If the given body is a TemplatedEmailBody object, populate and reassign the string value to itself
        if ($bodyHtml instanceof TemplatedEmailBody) {
            $bodyHtml = $this->templating->renderResponse(
                $bodyHtml->getTemplateName(),
                $bodyHtml->getVariables()
            )->getContent();
        }
        
        $email->setText($bodyHtml);
        
        return $this->processResponse($this->sendGrid->web->send($email));
    }
    
    protected function processResponse($response) {
        $json = json_decode($response);
        
        if (is_array($json) && isset($json["message"])) {
            return ($json["message"] == "success");
        }
        
        return false;
    }
    
    protected static function buildBaseEmail($from, $to, $subject, array $additionalHeaders = array())
    {
        $email = new Email();
        
        $email->setTo($to)
              ->setFrom($from)
              ->setSubject($subject);
        
        // Set CC header if a value is given
        if (isset($additionalHeaders["cc"])) {
            $email->setCc($additionalHeaders["cc"]);
        }
        
        // Set BCC header if a valud is given
        if (isset($additionalHeaders["bcc"])) {
            $email->setBcc($additionalHeaders["bcc"]);
        }
        
        return $email;
    }
    
}
