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
        $composer = $event->getComposer();
        $installer = new Installer($pwd, $composer);
        
        $templates = $installer->getTemplatesPath();
        
        $config = $composer->getConfig();
        $vendorDir = rtrim($config->get('vendor-dir'), '/');
        echo "----------------\n";
        echo "WORKING DIR home is: ".getcwd()."\n";
        echo "Templates DIR home is: ".$pwd.DIRECTORY_SEPARATOR.$vendorDir.DIRECTORY_SEPARATOR.$templates."\n";
        echo "Templates DIR home is: ".realpath($pwd.DIRECTORY_SEPARATOR.$vendorDir.DIRECTORY_SEPARATOR.$templates)."\n";
        echo "----------------\n";
    }
    
    
    public function __construct($workingDir, $composer)
    {
        $this->pwd = $workingDir;
        $this->composer = $composer;
    }
    
    public function getTemplatesPath()
    {
        return $this->getResourcesPath().'/templates';
    }
    
    public function getResourcesPath()
    {
        return 'goliatone/flatg/installer';
    }
}
