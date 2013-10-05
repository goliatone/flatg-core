<?php

require './vendor/autoload.php';
require './vendor/funkatron/funit/FUnit.php';

$config = include_once('./tests/config.php');

use \FUnit\fu;
use goliatone\flatg\FlatG;
use goliatone\flatg\Route;
use goliatone\events\Event;

fu::setup(function() use($config) {
    // set a fixture to use in tests
    FlatG::initialize($config);
    fu::fixture('config', $config);
});

fu::teardown(function() {
    // this resets the fu::$fixtures array. May not provide clean shutdown
    FlatG::$router->reset();
    fu::reset_fixtures();
});

/////////////////////////////////////////////
// FLATG HELPER METHODS
/////////////////////////////////////////////
fu::test("scriptURL returns the current url", function() {
    $_SERVER['SERVER_NAME'] = 'localhost';
    $_SERVER['SCRIPT_NAME'] = 'index.php';
    $script = FlatG::scriptURL();
    $expected = 'http://localhost/index.php';
    fu::equal($script, $expected, "");
    
});

fu::test("view theme path",function(){
    $dir = FlatG::getThemeDir();
    $config = fu::fixture('config');
    $expected = $config['view_dir'].DIRECTORY_SEPARATOR.$config['theme'];
    fu::equal($dir, $expected, $expected);
});
/////////////////////////////////////////////
// HTML HELPER METHODS
/////////////////////////////////////////////
fu::test("GHtml helper serializes arrays into attr strings",function(){
    $attrs = GHtml::attr('dddd');    
    $expected = "";
    fu::equal($attrs, $expected, "Empty arrays return nothing");
    
    $attrs = GHtml::attr(array());    
    $expected = "";
    fu::equal($attrs, $expected, "Empty arrays return nothing");
    
    $attrs = GHtml::attr(array('href'=>'localhost', 'target'=>'_blank'));    
    $expected = " href='localhost' target='_blank'";
    fu::equal($attrs, $expected, "Generated tag matches expected");
    
    $link = new stdClass();
    $link->href = 'localhost';
    $attrs = GHtml::attr($link);    
    $expected = " href='localhost'";
    fu::equal($attrs, $expected, "It can handle objects: $attrs::$expected");
});

fu::test("GHtml helper generates html tags",function(){
    $link = GHtml::a('Link', array('href'=>'localhost', 'target'=>'_blank'));
    $link = trim($link);
    $expected = "<a href='localhost' target='_blank'>Link</a>";
    fu::equal($link, $expected, "Generated tag matches expected");
});

fu::test("GHtml truncates html respecting tags and words",function(){
    $lipsum = "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam id tortor in nunc egestas volutpat quis ut urna. Donec facilisis volutpat ullamcorper. Sed ac sodales nibh. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Interdum et malesuada fames ac ante ipsum primis in faucibus. Vestibulum condimentum aliquet placerat. Etiam et odio et nisl hendrerit malesuada. Pellentesque id consequat arcu. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. In hac habitasse platea dictumst. Pellentesque lectus magna, fermentum in varius vitae, viverra in lectus. Donec tincidunt molestie nunc, vel pharetra nisi molestie ac. Nullam sodales imperdiet egestas.";
    $html = "<p>$lipsum</p>";
    $truncated = GHtml::truncate($html, 15);
    //truncate 15, minus word => Lorem ipsum dol  
    $expected = "<p>Lorem ipsum</p>";
    fu::equal($truncated, $expected, "Truncated text respects word boundary and tags");
    
    $lipsum = "Lorem <b>ipsum</b> dolor sit amet, consectetur adipiscing elit. Nullam id tortor in nunc egestas volutpat quis ut urna. Donec facilisis volutpat ullamcorper. Sed ac sodales nibh. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Interdum et malesuada fames ac ante ipsum primis in faucibus. Vestibulum condimentum aliquet placerat. Etiam et odio et nisl hendrerit malesuada. Pellentesque id consequat arcu. Cum sociis natoque penatibus et magnis dis parturient montes, nascetur ridiculus mus. In hac habitasse platea dictumst. Pellentesque lectus magna, fermentum in varius vitae, viverra in lectus. Donec tincidunt molestie nunc, vel pharetra nisi molestie ac. Nullam sodales imperdiet egestas.";
    $html = "<p>$lipsum</p>";
    $truncated = GHtml::truncate($html, 15);
    //truncate 15, minus word => Lorem ipsum dol  
    $expected = "<p>Lorem <b>ipsum</b></p>";
    fu::equal($truncated, $expected, "Truncated text respects word boundary and nested tags");
});
/////////////////////////////////////////////
// HELPER METHODS
/////////////////////////////////////////////
fu::test("GHelper::removeTrailingSlash",function(){
    $html = "http://localhost/notes/";
    $truncated = GHelper::removeTrailingSlash($html);
    $expected = "http://localhost/notes";
    fu::equal($truncated, $expected, "");
});
fu::test("GHelper::arrayToObject",function(){
    $array  = array('a'=>1, 'b'=>2);
    $object = new stdClass();
    $object->a = 1;
    $object->b = 2;
    $result = GHelper::arrayToObject($array);
    fu::equal($result, $object, "Plain arrays");
    
    $array  = array('a'=>1, 'b'=>2, 'c'=>array('d'=>3));
    $object = new stdClass();
    $object->a = 1;
    $object->b = 2;
    $object->c = new stdClass();
    $object->c->d = 3;
    $result = GHelper::arrayToObject($array);
    fu::equal($result, $object, "Nested arrays");
});

/////////////////////////////////////////////
// ROUTE
/////////////////////////////////////////////
fu::test("Route::arrayToObject",function(){
    $routeUrl = "/unit";
    $target = function(){return "TARGET";};
    
    $route = new Route();
    $route->setUrl($routeUrl);
    $route->setTarget($target);
    
    $expected = "/unit/";
    $result = $route->getUrl();
    fu::equal($result, $expected, "Returns a valid regexp string");
    
    $routeUrl = "unit";
    fu::equal($result, $expected, "Returns a valid regexp string");
    
    $result = $route->getTarget();
    fu::equal($result, $target, "Returns a valid regexp string");
});

/////////////////////////////////////////////
// ROUTER
/////////////////////////////////////////////
fu::test("Router: we can add routes", function(){
   FlatG::map('/404', function(){}, array('name'=>'404'));
   $result = FlatG::$router->hasRoute('404');
   fu::ok($result, 'We have a 404 route'); 
});
/////////////////////////////////////////////
// App
/////////////////////////////////////////////
fu::test("FlatG::map",function(){
    
    $_SERVER['REQUEST_URI'] = '/notes';
    $_SERVER['REDIRECT_URL'] = '/notes';
    $_SERVER['REQUEST_METHOD'] ='GET';
    $_SERVER['QUERY_STRING'] = '';
    $_SERVER['SCRIPT_NAME'] = '/index.php';
    $_SERVER['PATH_INFO'] = '/notes';
    $_SERVER['PATH_TRANSLATED'] = 'redirect:/index.php/notes';
    $_SERVER['PHP_SELF'] = '/index.php/notes';
    
    $result = "empty";
    $expected = "__handled__";
    $handler = function() use (&$result){
        $result = "__handled__";
    };
    FlatG::map('/404', function(){}, array('name'=>'404'));
    FlatG::map('/notes', 
               $handler,
               array('methods' => 'GET', 'name'=>'notes')
               );
    $config = fu::fixture('config');
    FlatG::initialize($config);
    FlatG::run();
    
    fu::equal($result, $expected);
});


$exit = fu::run();
exit($exit);