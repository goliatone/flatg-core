<?php
namespace goliatone\flatg\backend\drivers;

interface IStorageManager 
{
    /**
     * 
     */
    public function bootstrap($config);
    
    /**
     * 
     */
    public function listFiles($refresh = FALSE, $options = FALSE);
    
    /**
     * 
     */
    public function totalFiles();
    
    /**
     * md5_file
     * TODO: Use dropbox delta API!!!
     */
    public function sync($local = TRUE);   
}
