<?php
$passwords = array(
    'dropbox'=>array(
        'key'=>'xxxxxxxxxxxxx',
        'secret'=>'xxxxxxxxxxxxx',
        
    ),
    'github'=>array(
        'key'=>'xxxxxxxxxxxxx',
        'secret'=>'xxxxxxxxxxxxx',
    ),
);

//TODO: Normalize basePath & base_url. Make robust.

//TODO: We need to move index from router, and then 
//handle/simplify all path management!! look into an 
//autoloader. Also, could we manage this in a helper?!
return array(
    'base_path' => APPPATH,
    'view_dir'  => "themes",
    'theme' => 'gone',
    'articles_extension' =>'yaml',
    'articles_path' => ROOT_DIR."articles",
    //TODO: Rename this to asset_url.
    //TODO: This will break once we are online and 
    //we dont have the localhost/dreamcach.es this should be base url plu
    'asset_dir' => "assets/",
    'layout' => 'layout',
    'featured_article' => 'hello-world',
    'router' => array(
        'basePath' => ''
     ),
     //TODO: We should we able to get this by default.
    'base_url' => 'http://localhost:8000/',
    
    'analytics_code'=>'UA-41380790-1',
    
    'backend_storage' =>array(
        'default'=>'dropbox',
        'dropbox'=>array(
            'class' => 'goliatone\flatg\backend\drivers\DropboxDriver',
            'key'=>$passwords['dropbox']['key'],
            'secret'=>$passwords['dropbox']['secret'],
            'folder'=>'/articles/'
            
        ),
        'github'=>array(
            'class' => 'goliatone\flatg\backend\drivers\GithubDriver',
            'key'=>$passwords['github']['key'],
            'secret'=>$passwords['github']['secret'],
            'repo'=>'https://github.com/goliatone/jii',
            'branch'=>'gh-pages'
            
        ),
    ),
);