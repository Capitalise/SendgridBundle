<?php
/**
 * MailNotSentException.php
 * Definition of class MailNotSentException
 * 
 * Created 31-Jan-2014 08:42:55
 *
 * @author M.D.Ward <matthew.ward@byng-systems.com>
 * @copyright (c) 2014, Byng Systems/SkillsWeb Ltd
 */
namespace Savch\SendgridBundle\Exception;

use Exception;



/**
 * MailNotSentException
 * 
 * @author M.D.Ward <matthew.ward@byng-systems.com>
 */
class MailNotSentException extends Exception
{
    
    public function __construct($message, $code = null, Exception $previous = null)
    {
        parent::__construct(
            "Failed to send email" . (!empty($message) ? "; " . $message : ""),
            $code,
            $previous
        );
    }
    
}
