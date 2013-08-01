<?php
namespace goliatone\flatg\backend;
/**
 * 
 */
class Storage
{
    
    public static function build($config)
    {
        $_DriverClass = $config['class'];
        $driver = new $_DriverClass($config);
        $driver->output_path = $config['output_path'];
        
        return $driver;
    }
    
    public function __construct()
    {
        
    }
}