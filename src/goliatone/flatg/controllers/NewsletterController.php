<?php
namespace goliatone\flatg\controllers;

//TODO: Use autoloader.
require_once('./services/email/emailer.php');
require_once('./services/email/emailtemplate.php');
require_once("./services/newsletter/newsletterdelegate.php");

/**
 * TODO: Normalize use of request. Make Request object.
 */
class NewsletterController 
{
    public $config = array();
    
    public $token = FALSE;
    
    public $action = 'subscribe';
    
    public $error = FALSE;
    
    /**
     * 
     */
    public function __construct()
    {
        //TODO: Manage config from FlatG.
        $this->config = require_once("./services/includes/config.php");
        
        $this->_validateRequest();
    }
    
    /**
     * 
     */
    protected function _validateRequest()
    {
        //We need a reference to target email
        if(!isset($_REQUEST['e'])) 
            return $this->error = array('success'=>FALSE, 'message'=> "Not valid email");
        
        $this->email = $_REQUEST['e'];
        
        //Email needs to be valid shit.
        if(!filter_var($this->email, FILTER_VALIDATE_EMAIL))
            return $this->error = array('success'=>FALSE, 'message'=> "Not valid email");
        
        if(key_exists('a', $_REQUEST) && $_REQUEST['a']==='unsubscribe')
            $this->action = 'request_unsubscribe';
        
        if(key_exists('t', $_REQUEST))
        {
            $this->token = $_REQUEST["t"];
            $this->action = 'unsubscribe';
        }
    }
    
    /**
     * 
     */
    public function run()
    {
        if($this->error) return $this->error;
        
        //Handle DDBB stuff.
        $delegate = new NewsLetterDelegate($this->config['newsletter']);
        $result = $delegate->email($this->email)->action($this->action)->execute();
        
        //Check if we have error:
        if(key_exists('success', $result) && $result['success'] === FALSE)
        {
             $this->error = $result;       
        }
        
        //Notify user.
        $template = new EmailTemplate($this->config['template']['file_path']);
        
        //TODO: Config from object, load config object with options + template path 
        //for each action.
        $template->subject = "Testing stuff!";
        $template->archive = FlatG::scriptURL().'/newsletter.php';
        //TODO: Make URL helper/manager.
        $template->unsubscribe = FlatG::scriptURL().'?a=unsubscribe&e='.$this->email;
        
        $filename = $this->config['template'][$this->action];
        
        //TODO: Check for result errors!!!!
        if($this->action === 'request_unsubscribe' && key_exists('token', $result))
        {
            $template->subject = 'Unsubscribe request';
            $link = $template->unsubscribe;
            $link .= "&t=".$result['token'];
            $template->unsubscribe_confirm = $link;
            $template->content = file_get_contents($filename);
        } 
        else if($this->action === 'subscribe')
        {
            $template->subject = 'Subscribed';
            $template->content = file_get_contents($filename);
        } 
        else if($this->action === 'unsubscribe')
        {
            $template->subject = 'Unsubscribed done';
            $template->content = file_get_contents($filename);
        }
        
        $template->subject .= " ".time();
        
        //TODO: Get from config file.
        $emailer = new Emailer($this->email);
        $emailer->set_from("support@dreamcach.es", "Oneiric");
        $emailer->set_source("support@dreamcach.es");
        $emailer->set_subject($template->subject);
        
        //Email runs the replace
        $emailer->set_template($template); 
        $emailer->send( );
        
        //We want to treat errors.
        //TODO: Throw FlatException();
        if($this->error) return $this->error;
        
        //TODO: We want to catch AJAX header here, and output 
        //this, else render template!
        if(FlatG::isAJAX()) return array('success'=>TRUE);       
        else return $template->replace();
    }
}
