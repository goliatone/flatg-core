<?php namespace goliatone\flatg\config;
/**
 * TODO: Add support for env.
 *       Default env = prod. Do nothing.
 *       If we provide env, then when we 
 *       import, we also check for config.{env}.php
 *       and we merge. ENV should override
 * 
 * https://github.com/caseyamcl/Configula/tree/master/src/Configula
 * https://github.com/hinchley/Config/blob/master/config.php
 * 
 * @author goliatone <hello@goliatone.com>
 * @license MIT
 * @package goliatone\flatg\config
 */
use ArrayObject;

class Config extends ArrayObject
{
    /** @var array  Cached keys.*/
    protected $_cache  = array();
    
    /** @var array  Config values.*/
    public $_config = array();
    
    /** @var string  Env mode.*/
    protected $_environment = '';
    
    /**
     * 
     */
    public function __construct($config = array())
    {
        $this->set($config);
        // parent::__construct($this->_config, ArrayObject::STD_PROP_LIST | ArrayObject::ARRAY_AS_PROPS);
    }
    
    /**
     * Retrieve a configuration setting identified by they 
     * `$key` parameter.
     * 
     * They key can be a path in dot syntax, where the 
     * last segment is the property retrieved.
     * ```php
     * $key=backend_storage.dropbox.api_key;
     * $config['backend_storage']['dropbox']['api_key'];
     * ```
     * If the `$key` parameter is `NULL` the method returns
     * the entire config.
     * 
     * The `$default` parameter lets you specify the returned
     * value in case the requested setting is undefined.
     * If `$default` is not provided it will return `NULL`
     * 
     * ```php
     *   $apiKey = $config->get('backend_storage.dropbox.api_key');
     *  
     *   $theme = $config->get('theme', 'default');
     * ```
     *
     * @param  string $key Setting's unique identifier.
     * @param  mixed  $default The default value.
     * @return mixed
     */
    public function get($key = NULL, $default = NULL)
    {
        //NULL key means dump it all.
        if(!$key) return $this->_config;
        
        //We want a set of items:
        if(is_array($key))
        {
            $out = array();
            foreach($key as $item) $out[$item] = $this->get($key);
            return $out;
        }
        
        //We want an specific item, retrieve from cache or cache it.
        $self = $this;
        $config = $this->_config;
        return $this->_cacheGet($key, function() use($self, $key, $default, &$config){
            return $self->getNestedValue( $self->_config, $key, $default);  
        });
    }
    
    public function getSource()
    {
        return $this->_config;
    }
    
    public function getCache()
    {
        return $this->_cache;
    }
    
    /**
     * Register one or more configuration settings values.
     *
     * Configuration settings are typically defined by 
     * returning an array from a file stored under a 
     * registered config folder. However, it is
     * also possible to explicitly set configuration values using
     * the Config class. 
     *
     * @param  string $key Setting's unique identifier.
     * @param  mixed  $value The default value.
     * @return goliatone\flatg\config\Config
     */
    public function set($key, $value = NULL)
    {
        //TODO: This should be array_merge_recursive, prob custom!
        if(is_array($key)) return $this->_configMerge($key);
        
        $this->_cache[$key] = $value;
        
        $target = &$this->_config;

        $keys = explode('.', $key);   
        $i = sizeof($keys);
        foreach($keys as $key)
        {
          if(!array_key_exists($key, $target)) $target[$key] = array();
          if(--$i) $target = &$target[$key];
        }
        $target[$key] = $value;
        
        return $this;
    }
    
    /**
     * 
     */
    public function del($key)
    {
        //TODO: We need to access the final target and 
        //then unset that!!!
        unset($this->_config[$key], $this->_cache[$key]);
        return $this;
    }
    
    /**
     * 
     */
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
    
    /**
     * 
     */
    public function load($filename)
    {
        $config = $this->import($filename);
        return $this->_configMerge($config);
    }
    
    /**
     * TODO: We could implement a strategy pattern and have the 
     *       import/save methods be implemented that way. We could
     *       handle different config formats: PHP, JSON, YAML, PHP.ini, 
     *       XML, etc. It would make more sense if this becomes a lib
     *       outside of FlatG.
     */
    public function import($filename, $defaults = array(), $required = FALSE)
    {
        if($required && !file_exists($filename))
        {
            throw new InvalidArgumentException("Configuration file {$filename} not loaded");
        }
        //Here we are handling just one type of config
        //PHP arrays. We could abstract the import per file
        //extension.
        $config = include_once($filename);
        
        return $config ? $config : $defaults; 
    }
    
    public function save($path, $config=NULL)
    {
        $config = $config ? $config : $this->_config;
        $output = $this->format($config);
        $file = new SplFileObject($path, "w");
        if(!$file->fwrite($output))
        {
            //We were unable to save the file 
            throw new RuntimeException("Configuration file could not be saved to {$path}");
        }
        return $this;
    }
    
    public function format($config)
    {
        $config = var_export($config, TRUE);

        $formatted = str_replace(
                array('  ', 'array ('),
                array("\t", 'array('),
                $config
        );

        $output = <<<CONF
<?php
/* FILE AUTOGENERATED BY
 * goliatone\flatg\config\Config
 */
return {$formatted};
CONF;
        return $output;
    }
    
/////////////////////////////////////////////////////////////////////////////////
// Protected methods
/////////////////////////////////////////////////////////////////////////////////
    /**
     * @param string $key Setting's unique identifier
     * @param mixed  $getter Function to retrieve value or the 
     *                       default value.
     * @return mixed
     */
    protected function _cacheGet($key, $getter)
    {
        if(array_key_exists($key, $this->_cache)) return $this->_cache[$key];
        
        return $this->_cache[$key] = is_callable($getter) ? $getter() : $getter;
    }
    
    /**
     * 
     */
    public function _configMerge($config, $source = FALSE, &$ids = array())
    {
        $this->_config = array_merge($config, $source);
        return $this;
        
        if($source === FALSE) $source = $this->_config;
        
        //TODO: Remove foreach loop. 
        foreach($config as $key => $value)
        {
            echo "HANDLE KEY {$key}\n";
            if(array_key_exists($key, $this->_config) && is_array($value))
            {
                $ids[] = $key;
                echo "==HANDLE KEY {$key}\n";
                $source[$key] = $this->_configMerge($config[$key],
                                                    $source[$key],
                                                    $ids);
            }
            else 
            {
                $this->_config[$key] = $value;
                $key = empty($ids) ? $key : implode('.', $ids);
                echo "SET CACHE: {$key} TO {$value}\n";
                $this->_cache[$key] = $value;
            }

        }
        return $this;
    }
    /**
     * Merge the items in the given file into the items.
     *
     * @param  array   $items
     * @param  string  $file
     * @return array
     */
    protected function _mergeEnvironment(array $items, $file)
    {
            return array_replace_recursive($items, $this->load($file));
    }
    
    /**
     * Note that the visibility of this method is set to public
     * to be accessible inside the cache closure.
     */
    public function getNestedValue(&$target, $key, $default = NULL)
    {
        //Optimizations: explode in foreach loop bad.
        //foreach loop bad, use for, sizeof and array_keys :)        
        foreach(explode('.', $key) as $key) {
            if ( !is_array($target) || ! array_key_exists($key, $target)) return $default;
            $target = &$target[$key];
        }

        return $target;
    }
    
/////////////////////////////////////////////////////////////////////////////////
// ArrayObject implementation
/////////////////////////////////////////////////////////////////////////////////
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