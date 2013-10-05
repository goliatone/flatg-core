<?php
namespace goliatone\flatg;

use Exception;
use goliatone\flatg\Route;

/**
 * Routing class to match request URL's against given routes 
 * and map them to a controller action.
 * 
 * TODO: Standardize trailing slashes!!! Clean user routes to coform to it.
 * 
 * @copyright Copyright (c) 2013, goliatone
 * @author Goliatone <hello@goliatone.com>
 *
 * @license Please reference the MIT.md file at the root of this distribution
 *
 * @package flatg
 */
class Router {

    /**
    * Array that holds all Route objects
    * @var array
    */ 
    private $_routes;
    
    /**
    * Array that holds all preprocessors Routes
    * @var array
    */ 
    private $_preprocessors;

    /**
     * Array to store named routes in, used for reverse routing.
     * @var array 
     */
    public $namedRoutes;

    /**
     * The base REQUEST_URI. Gets prepended to all route url's.
     * TODO:RENAME TO BASE_URL
     * @var string
     */
    private $basePath = '';
    
    
    public $requestUrl;
    
    public function __construct()
    {
        $this->reset();    
    }
    
    /**
     * 
     */
    public function reset()
    {
        $this->_routes = array();
        $this->namedRoutes = array();
        
        $this->_preprocessors = array();
    }
    
    /**
     * Set the base url - gets prepended to all route url's.
     * TODO:RENAME TO setBaseUrl
     * @param string $base_url 
     */
    public function setBasePath($basePath) 
    {
        $basePath = rtrim($basePath, "/");
        $this->basePath = (string) $basePath;
    }

    /**
    * Route factory method
    *
    * Maps the given URL to the given target.
    * @param string $routeUrl string
    * @param mixed $target  The target of this route. Can be anything. 
    *                       You'll have to provide your own method to turn
    *                       this into a filename, controller / action pair, etc..
    * @param array $args Array of optional arguments.
    */
    public function map($routeUrl, $target = '', array $args = array())
    {
        
        if(is_array($routeUrl))
        {
            foreach($routeUrl as $index => $route)
            {
                $this->map($route, $target, $args);
            }
            return;
        }
        
        $route = new Route();
        $routeUrl = GHelper::removeTrailingSlash($routeUrl);
        $route->setUrl($this->basePath.$routeUrl);
        $route->setTarget($target);

        if(isset($args['methods']))
            $route->setMethods($args['methods']);
        

        if(isset($args['filters']))
            $route->setFilters($args['filters']);
            
        if(isset($args['params']))
            $route->setParameters($args['params']);
        

        if(isset($args['name'])) 
        {
            $route_name = $args['name'];
            $route->setName($route_name);
            //TODO: We should have policy about this, what to do
            //overwrite, notify, WFT?
            if(!$this->hasRoute($route_name)) 
            {
                $this->namedRoutes[$route_name] = $route;
            } 
            else
            {
                //we already have a route with this name.
                //WHAT TO DO?!      
            } 
        } 
        else 
        {
            //we have a route without a name.
            //WHAT TO DO?!
        }
        
        $this->_routes[] = $route;
        
        return $this;
    }
    
    /**
     * 
     */
    public function addPreprocess($routeUrl, $target = '', array $args = array())
    {
        $route = new Route();
        $routeUrl = GHelper::removeTrailingSlash($routeUrl);
        $route->setUrl($this->basePath.$routeUrl);
        $route->setTarget($target);

        if(isset($args['methods']))
            $route->setMethods($args['methods']);
        

        if(isset($args['filters']))
            $route->setFilters($args['filters']);
            
        if(isset($args['params']))
            $route->setParameters($args['params']);
        
        $this->_preprocessors[] = $route;
        
        return $this;
    }

    /**
    * Matches the current request against mapped routes
    */
    public function matchCurrentRequest() {
        //Dirty hack to support PUT/DELETE
        $requestMethod = (isset($_POST['_method']) &&
                         ($_method = strtoupper($_POST['_method'])) &&
                         in_array($_method, array('PUT','DELETE'))) ? $_method : $_SERVER['REQUEST_METHOD'];
        
        // $requestUrl    = $_SERVER['REQUEST_URI']; $_SERVER['PATH_INFO'];
        $requestUrl    = array_key_exists('REQUEST_URI', $_SERVER) ? $_SERVER['REQUEST_URI'] : '/';
        
        // strip GET variables from URL
        if(($pos = strpos($requestUrl, '?')) !== FALSE) 
        {
            $requestUrl =  substr($requestUrl, 0, $pos);
        }
        /*
        if(($pos = strpos($requestUrl, 'http')) === FALSE)
        {
            $requestUrl = "http://".$_SERVER['SERVER_NAME'].$requestUrl;
        }
        */
        
        //Get rid of the trailing slash.
        if($requestUrl !== '/')
            $requestUrl = GHelper::removeTrailingSlash($requestUrl);
        
        $this->requestUrl = $requestUrl;
        
        return $this->match($requestUrl, $requestMethod);
    }

    /**
    * Match given request url and request method and see if a route has been defined for it
    * If so, return route's target
    * If called multiple times
    */
    public function match($requestUrl, $requestMethod = 'GET') {
        
        foreach($this->_routes as $route) 
        {
            if(!($matches = $this->checkRoute($route, $requestUrl, $requestMethod))) continue;
            
            return $this->setRouteParams($route, $matches);
        }

        return FALSE;
    }
    
    /**
     * Check if a route's URL can handle current request.
     */
    public function checkRoute($route, $requestUrl, $requestMethod = 'GET')
    {
         // compare server request method with route's allowed http methods
        if(!in_array($requestMethod, $route->getMethods())) return FALSE;
        
        // check if request url matches route regex. if not, return false.
        if (!preg_match("~^".$route->getRegex()."*$~i", $requestUrl, $matches)) return FALSE;
        
        return $matches;
    }
    
    /**
     * Extract params from current request and set route's 
     */
    public function setRouteParams($route, $matches)
    {
        //We initialize our route with the defalt params, if any.
        $params = $route->getParameters();

        if (preg_match_all("/:([\w-]+)/", $route->getUrl(), $argument_keys)) 
        {
            // grab array with matches
            $argument_keys = $argument_keys[1];

            // loop trough parameter names, store matching value in $params array
            foreach ($argument_keys as $key => $name) 
            {
                //if(!isset($matches[$key + 1])) continue;
                if(isset($matches[$key + 1]))
                    $params[$name] = rtrim($matches[$key + 1], "/");
            }

        }

        $route->setParameters($params);

        return $route;
    }
    
    /**
     * Reverse route a named route
     * 
     * @param string $route_name The name of the route to reverse route.
     * @param array $params Optional array of parameters to use in URL
     * @return string The url to the route
     */
    public function generate($routeName, array $params = array()) {
        
        // Check if route exists
        //TODO: Do we really want to kill the app here?!
        if(!$this->hasRoute($routeName))
            throw new Exception("No route '{$routeName}' has been found.");
        
        $route = $this->namedRoutes[$routeName];
        $url = $route->getUrl();
        
        $url = str_replace(array('(', ')'), array('', ''), $url);
        
        //WE NEED TO MERGE DEFAULT PARAMS WITH GIVEN PARAMS.
        $params = array_merge($route->getParameters(), $params);
        $params = array_map('trim', $params);
        
        // replace route url with given parameters
        if ($params && preg_match_all("/:(\w+)/", $url, $param_keys)) {
            // grab array with matches
            $param_keys = $param_keys[1];

            // loop trough parameter names, store matching value in $params array
            foreach ($param_keys as $i => $key) 
            {
                if(!isset($params[$key])) continue;
                
                $url = preg_replace("/:(\w+)/", $params[$key], $url, 1);
            }
        }
        
        //we should make sure we dont have any :w left, if we do
        //everything after that is kk.
        //TODO: Make this for realz!
        if (strpos($url,':') !== FALSE)
            $url = strstr($url, ':', TRUE);
        
        //If we want to go to root, allow.
        if($url !== '/')
            GHelper::removeTrailingSlash($url);
        
        return $url; 
    }
    
    /**
     * Check if router has route by name.
     */
    public function hasRoute($routeName)
    {
        return isset($this->namedRoutes[$routeName]);
    }
    
    /**
     * 
     */
    public function getRoute($routeName)
    {
        return $this->namedRoutes[$routeName];
    }
    
    /**
     * HTTP Status Code:
     * 301 Moved Permanently
     * 302 Moved Temporarily
     * 303 See Other
     */
    public function redirect($url, $statusCode = 303)
    {
        if (strpos($url, '://') === FALSE)
        {
            // Make the URI into a URL
            $url = $this->basePath."/".$url;//.ltrim($url,"/");
            //$url = URL::site($url, TRUE);
        }
           
       
       header('Location: ' . $url, TRUE, $statusCode);
       exit( );
    }
}