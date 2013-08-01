<?php
namespace goliatone\flatg\composer;

use Composer\Script\Event;

class Installer
{
    public static function build(Event $event)
    {
        echo "Installing flatG";
        exec("touch IMHERE.md");
        
    }
}
