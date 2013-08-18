<?php
namespace goliatone\flatg\composer;

use Composer\Script\Event;

class Installer
{
    public static function build(Event $event)
    {
        print "Installing flatG";
        exec("touch IMHERE.md");
        
        $pwd = getcwd();
        $installer = new Installer($pwd);
        
        $templates = $installer->getTemplatesPath();
        $composer = $event->getComposer();
        $config = $composer->getConfig();
        $vendorDir = rtrim($config->get('vendor-dir'), '/');
        echo "----------------\n";
        echo "WORKING DIR home is: ".getcwd()."\n";
        echo "Templates DIR home is: ".realpath($templates)."\n";
        echo "----------------\n";
    }
    
    
    public function __construct($workingDir)
    {
        $this->pwd = $workingDir;
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
