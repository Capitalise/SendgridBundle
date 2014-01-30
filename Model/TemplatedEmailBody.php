<?php
/**
 * TemplatedEmailBody.php
 * Definition of class TemplatedEmailBody
 * 
 * Created 29-Jan-2014 18:18:50
 *
 * @author M.D.Ward <matthew.ward@byng-systems.com>
 * @copyright (c) 2014, Byng Systems/SkillsWeb Ltd
 */
namespace Savch\SendgridBundle\Model;



/**
 * EmailTemplateBody
 * 
 * @author M.D.Ward <matthew.ward@byng-systems.com>
 */
class TemplatedEmailBody
{
    
    /**
     *
     * @var string
     */
    protected $templateName;
    
    /**
     *
     * @var string
     */
    protected $templateBody;
    
    /**
     *
     * @var array
     */
    protected $variables;
    
    /**
     * 
     * @param string $templateName
     *      
     * @param string $templateBody
     *      
     * @param array $variables
     *      
     */
    public function __construct($templateName, $templateBody, array $variables = array())
    {
        $this->templateName = $templateName;
        $this->templateBody = $templateBody;
        $this->variables = $variables;
    }
    
    public function getTemplateName()
    {
        return $this->templateName;
    }

    public function setTemplateName($templateName)
    {
        $this->templateName = $templateName;
    }
    
    public function getTemplateBody()
    {
        return $this->templateBody;
    }

    public function setTemplateBody($templateBody)
    {
        $this->templateBody = $templateBody;
    }
    
    public function getVariables()
    {
        return array_merge(
            array("body"    =>  $this->templateBody),
            $this->variables
        );
    }

    public function setVariables(array $variables)
    {
        $this->variables = $variables;
    }

}
