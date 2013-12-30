<?php
/**
 * Configuration fixture.
 */
return array(
    'basePath' => 'APPPATH',
    'articlesExtension' =>'yaml',
    'articlesPath' => "ROOT_DIR/articles",
    'router' => array(
        'basePath' => '/base/path'
     ),
    'backend_storage' =>array(
        'default'=>'dropbox',
        'dropbox'=>array(
            'folder'=>'/articles/'
        ),
        'github'=>array(
            'branch'=>'gh-pages'
        ),
    ),
);