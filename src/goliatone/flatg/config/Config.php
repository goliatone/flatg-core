<?php
namespace goliatone\flatg\config;

class Config extends \ArrayObject
{
    protected $_cache  = array();
    protected $_config = array();
    
    public function __construct($config = array())
    {
        $this->set($config);
        // parent::__construct($this->_config, ArrayObject::STD_PROP_LIST | ArrayObject::ARRAY_AS_PROPS);
    }
    
    public function get($key, $default = NULL)
    {
        if(!$key) return $this->_config;
        
        if(array_key_exists($key, $this->_cache)) return $this->_cache[$key];
        
        $target = $this->_config;
        foreach(explode('.', $key) as $key)
        {
          if(!array_key_exists($key, $target)) return $default;
          $target = $target[$key];
        }
        $this->_cache[$key] = $target;
        
        return $target;
    }
    
    public function set($key, $value = NULL)
    {
        if(is_array($key)) return $this->_config = array_merge($this->_config, $key);
        
        $this->_cache[$key] = $value;
        
        $target = &$this->_config;

        $keys = explode('.', $key);   
        $i = count($keys);
        foreach($keys as $key)
        {
          if(!array_key_exists($key, $target)) $target[$key] = array();
          if(--$i) $target = &$target[$key];
        }
        $target[$key] = $value;
        
        return $this;
    }
    
    public function del($key)
    {
        unset($this->_config[$key], $this->_cache[$key]);
        return $this;
    }
    
    public function init($target, $key, $defaults = array())
    {
        $values = $this->get($key);
        $properties = get_object_vars($target);
        foreach($properties as $prop => $value)
        {
            if(property_exists($target, $prop)) $target->{$prop} = $values[$prop];
        }
       
       
       return $this;
    }
    
    public function load($filename)
    {
        $config = $this->import($filename);
        return $this->set($config);
    }
    
    public function import($filename)
    {
        if(!file_exists($filename))
        {
            throw new Exception("File not found.", 1);
        }
        //Here we are handling just one type of config
        //PHP arrays. We could abstract the import per file
        //extension.
        $config = include($filename);
        
        return $config ? $config : array(); 
    }
    
    //////////////////
    public function offsetGet($key) 
    { 
        return $this->get($key);  
    } 
    public function offsetSet($key, $value) 
    {
        return $this->set($key, $value); 
    }
     
    public function offsetUnset($key)
    { 
        return $this->del($key); 
    } 
    
}