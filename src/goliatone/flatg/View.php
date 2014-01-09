<?php
namespace goliatone\flatg;
use Exception;

/**
 * View class.
 * 
 * @copyright Copyright (c) 2013, goliatone
 * @author Goliatone <hello@goliatone.com>
 *
 * @license Please reference the LICENSE-MIT
 *          file at the root of this distribution
 *
 * @package flatg
 */
class View
{
    /**
     *
     */
    const FILE_EXTENSION = '.php';

    /**
     * Global container to be extracted
     * whenever a View instance is
     * included
     *
     * @var array
     * @access private
     */
    public static $_global_data = array();
    
    /**
     * 
     * @access private
     */
    protected $_partials;
    
    /**
     * 
     * @access private
     */
    protected $_viewDirectory;
    
    /**
     * View's filename
     * @access private
     */
    protected $_filename;

    /**
     * Data payload for the view.
     * @var array
     */
    public $data;

    /**
     * @param string $filename
     */
    public function __construct($filename = '')
    {
       $this->data = array();
       
       $this->_partials = array(); 
       
       if($filename)
       {
           $this->setFilename($filename);
       }
    }

    /**
     *
     * @param string $filename  View's filename.
     * @param array  $data      Data to be extracted in the view's
     *                          context
     *
     * @return string
     *
     * @throws \Exception
     */
    public function render($filename, $data = array())
    {
        if(is_array($filename) && empty($data))
        {
            //we assigned $filename on construct.
            $data = $filename;
        } 
        else if( is_string($filename))
        {
            $this->setFilename($filename);
        }
        
        $this->data = $data;

        extract($this->data, EXTR_SKIP);

        foreach ($this->_partials as $viewName => $view) {
            // $view->set("layout", $this);
            $this->data[$viewName] = $view->render();
        }
        
        // extract($this->data, EXTR_SKIP);

        if (View::$_global_data)
        {
            // Import the global view variables to local namespace
            extract(View::$_global_data, EXTR_SKIP | EXTR_REFS);
        }

        // Capture the view output
        ob_start();

        try
        {
            // Load the view within the current scope
            include $this->getPath();
        }
        catch (Exception $e)
        {
            // Delete the output buffer
            ob_end_clean();

            // Re-throw the exception
            throw $e;
        }

        // Get the captured output and close the buffer
        return ob_get_clean();
        
    }

    /**
     * @return string Directory containing the view
     */
    public function getViewDirectory()
    {
         return $this->_viewDirectory;
    }

    /**
     * @param string $dir
     * @return $this
     * @throws \Exception
     */
    public function setViewDirectory($dir)
    {
        if(!is_dir($dir)) throw new Exception("Invalid view directory: {$dir}");
        
        $this->_viewDirectory = rtrim($dir, DIRECTORY_SEPARATOR);
        
        return $this;
    }

    /**
     * @param $filename
     * @return $this
     */
    public function setFilename($filename)
    {
        $this->_filename = $filename.self::FILE_EXTENSION;
        return $this;
    }

    /**
     * @return string Filename
     */
    public function getFilename()
    {
        return $this->_filename;
    }
    
    /**
     * Add a view using a given name.
     *
     * @param string $name Name of the view used in the layout
     * @param View   $view The instance to be associated with the name
     * @return $this
     */
    public function addPartial($name, View $view) {
        $this->_partials[$name] = $view;
        
        return $this;
    }


    /**
     * @param string $filename
     * @return string
     */
    public function getPath($filename = '')
    {
        $filename = $filename ? $filename : $this->getFilename();
        return realpath($this->_viewDirectory.DIRECTORY_SEPARATOR).$filename;
    }


    /**
     * @param $key
     * @param mixed $value
     */
    static public function setGlobal($key, $value)
    {
        if (is_array($key))
        {
            foreach ($key as $key2 => $value)
            {
                View::$_global_data[$key2] = $value;
            }
        }
        else
        {
            View::$_global_data[$key] = $value;
        }
        
    }

    /**
     * @param $key
     * @param $value
     * @return $this
     */
    static public function bindGlobal($key, & $value)
    {
        View::$_global_data[$key] =& $value;
    }
}