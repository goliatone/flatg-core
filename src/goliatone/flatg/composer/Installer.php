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
        $config = $composer->getConfig();
        $vendorDir = rtrim($config->get('vendor-dir'), '/');
        echo "----------------\n";
        echo "Config home is: ".$config->get('home')."\n";
        echo "Vendor home is: ".$vendorDir."\n";
        echo "WORKING DIR home is: ".getcwd()."\n";
        echo "----------------\n";
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
