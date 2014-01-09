<?php
/**
 * 
 */
namespace goliatone\flatg;

use stdClass;
use ErrorException;
use goliatone\flatg\View;
use goliatone\flatg\backend\Storage;
use goliatone\flatg\controllers\DefaultController;

use goliatone\events\Event;
/////////////////////////////////////////////////////////
//BOOTSTRAP
//TODO: Move to boostrap file.
/////////////////////////////////////////////////////////
define('G_FLATG', 'FlatG');
define('G_VERSION',"v0.0.1");
define('G_LINK', 'http://goliatone.com');

date_default_timezone_set('America/New_York');

//TODO: Hacky way to make FlatG and ArticleModel available
//      from the views.        
class_alias('goliatone\flatg\FlatG', 'FlatG');
class_alias('goliatone\flatg\GHtml', 'GHtml');
class_alias('goliatone\flatg\GHelper', 'GHelper');
class_alias('goliatone\flatg\ArticleModel', 'ArticleModel');
/////////////////////////////////////////////////////////

/*
define('REMOTE_VERSION_URL', 'http://flat-g.com/version.txt');

// this is the version of the deployed script
define('VERSION', '1.0.1');

function isUpToDate()
{
    $remoteVersion=trim(file_get_contents(REMOTE_VERSION_URL));
    return version_compare(VERSION, $remoteVersion, 'ge');
}*/

/**
 * Main interface. WIP.
 * 
 *
 * TODO: Figure out what mysql wrapper to use.
 * TODO: Implemente defaults for config. Have a configure method
 *       and instead of doing self::$config['prop']
 * 
 * @copyright Copyright (c) 2013, goliatone
 * @author Goliatone <hello@goliatone.com>
 *
 * @license Please reference the MIT.md 
 *          file at the root of this distribution
 *
 * @package flatg
 */
class FlatG {
    
    /**
     * Static config holder
     *
     * @access static
     * @var Config|array
     */
    static public $config = array();
    
    /**
     * Static container holder
     *
     * @access static
     * @var array
     */
    static public $_container = array();
    
    /**
     * Router facade.
     *
     * @access static
     * @var Router
     */
    static public $router;
    
    /**
     * Articles facade.
     *
     * @access static
     * @var array
     */
    static public $articles;
    
    /**
     * Markdown instance
     *
     * @access static
     * @var Markdown
     */
    static public $markdown;
    
    /**
     * Creates a new FlatG instance
     *
     * @access public
     * @return void
     */
    public function __constructor()
    {
    }

    static public $initialized = FALSE;
    static public function initialize($config)
    {
        self::$config = $config;

        if(self::$initialized) return;
        self::$initialized = TRUE;

        self::metadata('generator', 'FlatG');
        
        //TODO: Decouple, handle with plugin/events
        //TODO: Do we want to use simpleIOC?   
        ArticleModel::$parser = new \Spyc();
        ArticleModel::$path = $config['articles_path'];
        ArticleModel::$file_extension = $config['articles_extension']; 
        self::$articles = ArticleModel::fetch( );
        
        //Add markdown support.
        self::$markdown = new \Markdown_Parser();
        
        
        //TODO: make this for realz.
        self::$router = new Router();
        self::$router->setBasePath($config['router']['basePath']);
    }
    
    /**
     * TODO: Make it for real!
     */
    static private $_messages = array();
    /**
     * @var GLogger
     */
    static public $logger;
    static public function logger($id='default')
    {
        return self::$logger;
    }
    
    /**
     * Simple registry. 
     */
    static public function container($id, $containee = ':::GETTER:::')
    {
        if($containee === ':::GETTER:::')
            return isset(self::$_container[$id])? self::$_container[$id] : NULL;
        
        
        self::$_container[$id] = $containee;
        
        return $containee;
    }

    /**
     * Simple cache interface.
     *
     * @param string $id Cache ID key.
     * @param string $content Content to be saved
     *
     * @return null|string
     */
    static public function cache($id, $content = NULL)
    {
        if(!$content && ($cache = @file_get_contents(ROOT_DIR.'cache/'.$id))) return $cache;

        if(is_callable($content)) $content = call_user_func($content);

        $handle = fopen(ROOT_DIR.'cache/'.$id, 'w') or die('Cannot open file:  '.$id);
        fwrite($handle, $content);
        fclose($handle);
        return $content;
    }

    /**
     * @return string
     */
    static public function scriptURL()
    {
        $server = rtrim($_SERVER['SERVER_NAME'], '/') . '/';
        return "http://".$server.$_SERVER['SCRIPT_NAME'];
    }
    
    /**
     * Map url resource to handler implementation.
     * 
     * @param string $routeUrl Resource string that represents the URL to be mapped.
     * @param mixed $target Handler for the provided route.
     * @param array $args Options.
     * @return goliatone\flatg\Router Router instance, chainable method.
     */
    static public function map($routeUrl, $target = '', array $args = array())
    {
        self::$router->map($routeUrl, $target, $args);
        return self::$router;
    }

    /**
     * @param $routeUrl
     * @param string $target
     * @param array $args
     * @return Router
     */
    static public function preprocess($routeUrl, $target = '', array $args = array())
    {
        self::$router->addPreprocess($routeUrl, $target, $args);
        
        return self::$router;
    }
    
    static public $_metadata = array();

    /**
     * @param null $name
     * @param null $content
     * @return null|string
     */
    static public function metadata($name=NULL, $content=NULL)
    {
        //we are setting a meta key
        if(isset($name) && isset($content))
            return self::$_metadata[$name] = $content;
        //we will just render all
        $out = array();
        foreach(self::$_metadata as $name => $content)
        {
            $meta = GHtml::meta(array('name'=> $name, 'content'=>$content));
            array_push($out, $meta);
        }
        
        return implode(CHR(13).CHR(10).CHR(9), $out);
    }
    
    
    
    /**
     * TODO: dry, CLEAN.
     * TODO: Use EventDispatcher,
     *       forget callback madness!
     *
     *
     * @throws \ErrorException
     */
    static public function run()
    {
        //Have we been initialized?
        if(empty(self::$config))
            throw new ErrorException("FlatG needs to be initialized");
        
        $route = self::$router->handleRequest();

        if($route)
        {
            ////// TODO: Implement real event flow
            $event_name = $route->getName();
            $event_args = $route->getAugmentedParams();
            $e = new Event($event_name, $event_args);
            $e->dispatch();
            //////////////////////////////////////
            
            $callback = $route->getTarget();
            
            if(is_callable($callback))
            {
                if(is_array($callback)) call_user_func($callback, $route->getAugmentedParams());
                else $callback($route->getAugmentedParams());
                
            } 
            else if(is_array($callback))
            {
                $_Controller = $callback[0];
                call_user_func($_Controller::$callback[1], $route->getAugmentedParams());
            } else throw new ErrorException('Internal Router Error 500 '.print_r($callback), 500);
        } 
        else 
        {
            //If we have a route to handle 404s, fire it!
            if(self::$router->hasRoute('404'))
            {
                //TODO: Send params as well!!!
                $route = self::$router->getRoute('404');
                $callback = $route->getTarget();
                if($callback && is_callable($callback)) call_user_func($callback);
                else self::render404();
            }
            //else, we assume our theme has a 404 view, and try that.
            else 
            {
                self::render404();
            }
            
            //else, we just show an error message.
        }
    }

    /**
     * @param string $name      View ID.
     * @param array  $data      Context data, expanded in view.
     * @param bool   $layout    Parent layout.
     * @param bool   $return    Do we return string or print.
     *
     * @return string
     */
    static public function render($name, $data = array(), $layout = FALSE, $return = FALSE)
    {
        
        //get main content.
        $output = self::renderView($name, $data);
        
        if(!$layout) $layout = self::$config['layout'];
        
        $output = self::renderView($layout, array_merge(array('content'=>$output), $data) );
        
        if($return) return $output;
        //TODO: Output headers!! header (Content-Type:text/html; charset=UTF-8)
        else echo $output;
    }
    
    /**
     * TODO: Normalize signature, use same for all
     *       renderX methods.
     *
     * @param $data
     */
    static public function renderJSON($data)
    {
        //TODO: Should we include CORS support?
                
        if(array_key_exists('callback', $_REQUEST))
        {
            header('Content-Type: application/javascript');
            echo $_REQUEST['callback'].'('.json_encode($data).')'; 
        }
        else 
        {
            header('Content-Type: application/json');
            echo json_encode($data); 
        } 
    }
    
    /**
     * TODO: Normalize signature, use same for all
     *       renderX methods.
     *
     * @param $data
     */
    static public function renderXML($data)
    {
        // header('Content-Type: application/atom+xml');
        header('Content-Type: text/xml; charset=UTF-8');
        echo $data;
        exit();
    }
    
    /**
     * TODO: Nested render views?
     */
    static public function renderView($name, $data)
    {
        $dir  = self::getThemeDir();
        $view = new View($name);
        $view->setViewDirectory($dir);
        
        return $view->render($data);
    }
    
    /**
     * 
     */
    static public function render404()
    {
        //Just render the 404
        header('HTTP/1.0 404 Not Found');
        echo "<h1>404 Not Found</h1>";
        echo "The page that you have requested could not be found.";
        exit;
    }
    
    /**
     * Echoes the asset uri with the prepended base path.
     * TODO: Make clean interface. We should be able to not
     *       append anything and not specify asset dir.
     * 
     * @param  string  $asset    Asset url.
     * @param  mixed   $base_url Base url to prepend.
     * @return string            absolute url to the asset.
     */
    static public function assetUri($asset, $asset_dir = FALSE)
    {
        $asset_dir = $asset_dir === FALSE ? self::$config['asset_dir'] : $asset_dir;
        $asset_dir = GHelper::ensureTrailingSlash($asset_dir);
        echo self::$config['base_url'].$asset_dir.$asset;
    }
    
    /**
     * 
     */
    static public function isAJAX()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest';
    }
    
    /**
     * 
     */
    static public function getThemeDir()
    {
        return self::$config['view_dir'].DIRECTORY_SEPARATOR.self::$config['theme'];
    }
    
    /**
     * TODO: Rename, FlatG::credits
     */
    static public function version($link = TRUE, $version=TRUE)
    {
        if($link) 
            return "<a href='".G_LINK."'>".G_FLATG.($version ? G_VERSION : '')."</a>";
        
        return G_VERSION;
    }
    
    static public function dump($var)
    {
        echo "<pre>";
        print_r($var);
        echo "</pre>";
    }
/////////////////////////////////////////////////
//  MOVE THIS INTO ITS OWN CLASS/PLUGIN?
/////////////////////////////////////////////////
    /**
     * TODO: Move to module.
     */
    static public function synchronize()
    {
        if(!self::$config) throw new ErrorException("Initialize", 500);
        
        //TODO: make relative...
        if(array_key_exists('default', self::$config['backend_storage']))
        {
            $backend_id = self::$config['backend_storage']['default']; 
        }
        
        $config = self::$config['backend_storage'][ $backend_id ];
        // $config['vendor'] = $config['vendor'];
        $config['output_path'] = self::$config['articles_path'];
        
        $storage = Storage::build($config);
        
        echo "Sync start:<br/>";
        
        // $store
        $files = $storage->listFiles();
        echo "Total files: ".$storage->totalFiles()."<br/>";
        echo $storage->sync();
        
        // date_default_timezone_set('UTC');
        // date_default_timezone_set($reset);
        echo "<pre>Files listed<br/>";
        print_r($files);
    }
    
    //TODO: Clean install, we should actuall
    static public function initialCommit()
    {
                
    }
    
/////////////////////////////////////////////////
//  MOVE THIS INTO ITS OWN CLASS/PLUGIN?
/////////////////////////////////////////////////
    /**
     * Convinience method to access featured article.
     * TODO: How do we set this, manage? From metadata?!
     * TODO: Move to ArticleModel, which should be Notes?
     * 
     * @return string Slug of featured ArticleModel
     */
    static public function featuredArticle()
    {
        //TODO: Defensive coding. What if we dont have
        //articles? or we did not set this prop in config?
        return self::$config['featured_article'];
    }
    
}

class GHtml
{
    /**
     * Compiles an array of HTML attributes into an attribute string and
     * HTML escape it to prevent malformed (but not malicious) data.
     *
     * @access static public
     * @param array $attrs the tag's attribute list
     * @return string The formatted html string.
     */
    static public function attr($attrs = array())
    {
        if(!is_array($attrs) && !is_object($attrs)) return '';
        
        $h = '';
        foreach((array)$attrs as $k => $v) $h .= " $k='$v'";
        return $h;
    }
    
    /**
     * The magic call static method is triggered when invoking inaccessible
     * methods in a static context. This allows us to create tags from method
     * calls.
     *
     *     Html::div('This is div content.', array('id' => 'myDiv'));
     *
     * @param string $tag The method name being called.
     * @param array $args Parameters passed to the called method.
     * @return string Formatted tag.
     */
    static public function __callStatic($tag, $args)
    {
        $auto_close = preg_match('/img|input|hr|br|meta|link/', $tag);
        $index = $auto_close ? 0 : 1;
        $out  = "<{$tag}";
        $out .= isset($args[$index]) ? self::attr($args[$index]) : ''; 
        $out .= $auto_close ? "/>" : ">{$args[0]}</{$tag}>";
        
        return $out;
    }
    
    /**
     * http://stackoverflow.com/questions/8504270/how-to-truncate-string-with-html-tags-in-desired-way
     */
    static public function truncate($html, $maxLength=100)
    {
        mb_internal_encoding("UTF-8");
        $printedLength = 0;
        $position = 0;
        $tags = array();
        $newContent = '';
    
        $html = $content = preg_replace("/<img[^>]+\>/i", "", $html);
    
        while ($printedLength < $maxLength && preg_match('{</?([a-z]+)[^>]*>|&#?[a-zA-Z0-9]+;}', $html, $match, PREG_OFFSET_CAPTURE, $position))
        {
            list($tag, $tagPosition) = $match[0];
            // Print text leading up to the tag.
            $str = mb_strcut($html, $position, $tagPosition - $position);
            if ($printedLength + mb_strlen($str) > $maxLength)
            {
                $newstr = mb_strcut($str, 0, $maxLength - $printedLength);
                $newstr = preg_replace('~\s+\S+$~', '', $newstr);  
                $newContent .= $newstr;
                $printedLength = $maxLength;
                break;
            }
            
            $newContent .= $str;
            $printedLength += mb_strlen($str);
            if ($tag[0] == '&') 
            {
                // Handle the entity.
                $newContent .= $tag;
                $printedLength++;
            } else {
                // Handle the tag.
                $tagName = $match[1][0];
                if ($tag[1] == '/') {
                  // This is a closing tag.
                  $openingTag = array_pop($tags);
                  assert($openingTag == $tagName); // check that tags are properly nested.
                  $newContent .= $tag;
                } else if ($tag[mb_strlen($tag) - 2] == '/'){
              // Self-closing tag.
                $newContent .= $tag;
            } else {
              // Opening tag.
              $newContent .= $tag;
              $tags[] = $tagName;
            }
          }
    
          // Continue after the tag.
          $position = $tagPosition + mb_strlen($tag);
        }
    
        // Print any remaining text.
        if ($printedLength < $maxLength && $position < mb_strlen($html))
          {
            $newstr = mb_strcut($html, $position, $maxLength - $printedLength);
            $newstr = preg_replace('~\s+\S+$~', '', $newstr);
            $newContent .= $newstr;
          }
    
        // Close any open tags.
        while (!empty($tags))
          {
            $newContent .= sprintf('</%s>', array_pop($tags));
          }
    
        return $newContent;
    }    
}

class GHelper 
{
    /**
     * TODO: Remove?
     */
    static public function getPathFromClassName($class, $base='.', $ext='.php')
    {
        $segs = GHelper::from_camel_case($class, FALSE);
        $dir = array_pop($segs);
        
        $name = lcfirst(preg_replace( '/\s+/', ' ',ucwords(implode(' ', $segs))));
        
        return $base.DIRECTORY_SEPARATOR.$dir.DIRECTORY_SEPARATOR.$name.$ext;
    }
    
    
    /**
     * TODO: Remove?
     */
    static public function fromCamelCase($input, $as_string= TRUE) {
        preg_match_all('!([A-Z][A-Z0-9]*(?=$|[A-Z][a-z0-9])|[A-Za-z][a-z0-9]+)!', $input, $matches);
        $ret = $matches[0];
        
        foreach ($ret as &$match) 
        {
            $match = $match == strtoupper($match) ? strtolower($match) : lcfirst($match);
        }
        
        if($as_string) return implode('_', $ret);
        return $ret;
    }
    
    /**
     * TODO: Remove?
     */
    static public function mergeAsObjects($source, $expand)
    {
        //We could also do a simple one liner...
        // return (object) array_merge((array) $source, (array) $expand);
        
        if(is_array($expand)) $expand = self::arrayToObject($expand);
        if(is_array($source)) $source = self::arrayToObject($source);
        
        foreach($expand as $k => $v) $source->$k = $v;
        
        return $source;
    }
    
    /**
     * 
     */
    static public function arrayToObject($array, &$obj = FALSE)
    {
        
        if(!$obj)
            $obj = new stdClass();
            
        foreach ($array as $key => $value)
        {
            //TODO: Ensure $key has a valid format!
            
            if (is_array($value))
            {
                $obj->$key = new stdClass();
                self::arrayToObject($value, $obj->$key);
            }
            else
            {
                $obj->$key = $value;
            }
        }
        return $obj;
     }
    
    /**
     * 
     */
    static public function appendFilenameToPath($source, $target)
    {
        $path_info = pathinfo($source);
        $file_name = $path_info['filename'].'.'.$path_info['extension'];
        return self::removeTrailingSlash($target, DS).DS.$file_name;   
    }
    
    /**
     * 
     */
    static public function removeTrailingSlash($path, $slash = '/')
    {
        return rtrim($path, $slash);
    }

    /**
     * Make sure our path has a trailing slash.
     * @param  string $path Source path.
     * @return string       Source path with trailing slash.
     */
    static public function ensureTrailingSlash($path)
    {
        return GHelper::removeTrailingSlash($path).'/';
    }

    /**
     * 
     */
    static public function removeFilesFromDir($path, $ext = 'png')
    {
        $path = self::removeTrailingSlash($path, DS).DS;
        
        $files = glob("{$path}*.{$ext}");
        
        foreach($files as $file)
            @unlink($file);            
     }
    
    
}

class GLogger
{
    const EMERGENCY = 1;
    const ALERT     = 2;
    const CRITICAL  = 3;
    const ERROR     = 4;
    const WARN      = 5;
    const WARNING   = 5;
    const NOTICE    = 6;
    const INFO      = 7;
    const DEBUG     = 8;

    /**
     * @var array
     */
    protected static $levels = array(
        self::EMERGENCY => 'EMERGENCY',
        self::ALERT     => 'ALERT',
        self::CRITICAL  => 'CRITICAL',
        self::ERROR     => 'ERROR',
        self::WARNING   => 'WARNING',
        self::NOTICE    => 'NOTICE',
        self::INFO      => 'INFO',
        self::DEBUG     => 'DEBUG'
    );
    
    static public $messages = array();
    
    /**
     * Constructor
     */
    public function __construct()
    {
    }
   
    public function __call($method, $args)
    {
        
        if(in_array(strtoupper($method), self::$levels))
        {
            array_unshift($args, $method);
            call_user_func_array(array($this, 'log'), $args);
        }
    }
    
    public function log($level, $object, $context = array())
    {
        array_push(self::$messages, array('l'=>$level, 'o'=>$object, 'c'=>$context));
        $time = date("[g:i:sA] ", time());
        echo "$time $level".print_r($object, true)."\n";
    }

    public function publish()
    {

    }
    
    public function __toString()
    {
        return "[object Glogger]";
    }
}
FlatG::$logger = new GLogger;