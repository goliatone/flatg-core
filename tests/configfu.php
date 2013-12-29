<?php

require './vendor/autoload.php';
require './vendor/funkatron/funit/FUnit.php';

$config = include_once('./tests/fixtures/config.php');

use \FUnit\fu;

fu::setup(function() use($config) {
    // set a fixture to use in tests
    fu::fixture('config', $config);
});

fu::teardown(function() {
    // this resets the fu::$fixtures array. May not provide clean shutdown
    fu::reset_fixtures();
});

/////////////////////////////////////////////
// FLATG HELPER METHODS
/////////////////////////////////////////////
fu::test("scriptURL returns the current url", function() {
    
    
});

fu::test("view theme path",function(){
    $config = fu::fixture('config');
    $expected = $config['view_dir'].DIRECTORY_SEPARATOR.$config['theme'];
    fu::equal($dir, $expected, $expected);
});

/////////////////////////////////////////////
// App



$exit = fu::run();
exit($exit);