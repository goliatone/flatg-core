<?php
namespace goliatone\flatg\composer;

use Composer\Script\Event;

class Installer
{
    public static function build(Event $event)
    {
        print "Installing flatG";
        exec("touch IMHERE.md");
        $installer = new Installer();
        
        $templates = $installer->getTemplatesPath();
        $composer = $event->getComposer();
        $this->vendorDir = rtrim($composer->getConfig()->get('vendor-dir'), '/');
    }
    
    
    public function __construct()
    {
        
    }
    
    public function getTemplatesPath()
    {
        return '../../../../installer/templates';
    }
    
    public function getResourcesPath()
    {
        return '../../../../installer';
    }
}
