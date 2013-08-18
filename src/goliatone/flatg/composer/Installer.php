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
        echo "Templates DIR home is: ".$templates."\n";
        echo "Templates DIR home is: ".realpath($templates)."\n";
        echo "----------------\n";
        
        copy($templates.'index.php', $pwd.'index.php');
    }
    
    
    public function __construct($workingDir, $composer)
    {
        $this->pwd = $workingDir;
        $this->composer = $composer;
        $config = $composer->getConfig();
        $this->vendor = rtrim($config->get('vendor-dir'), DIRECTORY_SEPARATOR);
        $this->basePath = $this->pwd.DIRECTORY_SEPARATOR.$this->vendor.DIRECTORY_SEPARATOR;
    }
    
    public function getTemplatesPath()
    {
        // return implode(DIRECTORY_SEPARATOR, array($this->getResourcesPath(),'templates'));
        return $this->getResourcesPath().'templates'.DIRECTORY_SEPARATOR;
    }
    
    public function getResourcesPath()
    {
        return $this->basePath.'goliatone/flatg/installer'.DIRECTORY_SEPARATOR;
        // return implode(DIRECTORY_SEPARATOR, array($this->basePath,'goliatone', 'flatg','installer'));
    }
}
