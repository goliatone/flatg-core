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
 * @license Please reference the MIT.md file at the root of this distribution
 *
 * @package flatg
 */
class FlatG {
    
    /**
     * Static config holder
     *
     * @access static
     * @var array
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
     * @var array
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
     * @var array
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
        echo "<h2>Hello world!</h2>";
    }
    
    static public function initialize($config)
    {
        self::$config = $config;
        
        
        
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
        
        return self::$markdown->transform("[FlatG](http://flatg.com/)");
    }
    
    static public function container($id, $containee = ':::GETTER:::')
    {
        if($containee === ':::GETTER:::')
            return isset(self::$_container[$id])? self::$_container[$id] : NULL;
        
        
        self::$_container[$id] = $containee;
    }
    
    /**
     * 
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
     * 
     */
    static public function preprocess($routeUrl, $target = '', array $args = array())
    {
        self::$router->addPreprocess($routeUrl, $target, $args);
        
        return self::$router;
    }
    
    
    /**
     * TODO: dry, CLEAN.
     * TODO: Use EventDispatcher, foget callback madness!
     */
    static public function run()
    {
        $route = self::$router->matchCurrentRequest();
        
        if($route)
        {
            ////// TODO: Implement real event flow
            $e = new Event('route');
            $e->dispatch();
            //////////////////////////////////////
            
            $callback = $route->getTarget();
            
            if(is_callable($callback))
            {
                if(is_array($callback)) call_user_func($callback, $route->getAugmentedParams());
                else $callback($route->getAugmentedParams());
                
            } else if(is_array($callback)){
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
                else echo 404;
            }
            //else, we assume our theme has a 404 view, and try that.
            else self::render('404', array());
            
            //else, we just show an error message.
        }
    }

    /**
     * 
     */
    static public function render($name, $data = array(), $layout = FALSE, $return = FALSE)
    {
        
        //get main content.
        $output = self::renderView($name, $data);
        
        if(!$layout) $layout = self::$config['layout'];
        
        $output = self::renderView($layout, array_merge(array('content'=>$output), $data) );
        
        if($return) return $output;
        else echo $output;
    }
    
    /**
     * 
     */
    static public function renderJSON($data)
    {
        
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
        $args[1] = isset($args[1]) ? self::attr($args[1]) : '';
        return "<$tag{$args[1]}>{$args[0]}</$tag>\n";
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