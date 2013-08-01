<?php
namespace goliatone\flatg\controllers;

use goliatone\flatg\FlatG;

/**
 * TODO: Add basic page rendering. 
 * TODO: Handle basic route <controller>(/<action>(/<params>))
 * TODO: Undo static methods!!!!
 *
 */
class DefaultController
{
    /**
     * 
     */
    public function home($params=array())
    {
        FlatG::$router->redirect('beta'); 
    }   
    
    /**
     * 
     */
    public function error404($params=array())
    {
        //TODO: MAKE A PROPER METHOD. 
        //TODO: Handle header, send out a proper 404;
        if(FlatG::isAJAX()) 
            return FlatG::renderJSON(array('error'=>'Page not found', 
                                           'status'=>'404',
                                           'url'=>FlatG::$router->requestUrl
                                    ));
        
        FlatG::render('404', $params, 'layout');  
    } 
    
    
}