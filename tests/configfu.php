<?php

require './vendor/autoload.php';
require './vendor/funkatron/funit/FUnit.php';
use \FUnit\fu;
use goliatone\flatg\config\Config;

$config = include('./tests/fixtures/config/config.php');

/*
 * Setup runs before each test.
 * We reset our fixtures.
 * $config Source configuration fixture.
 */
fu::setup(function() use($config) {
    // set a fixture to use in tests
    fu::fixture('config', $config);
});

/*
 * Teardown runs after each test.
 * Reset fixtures.
 */
fu::teardown(function() {
    fu::reset_fixtures();
});

/////////////////////////////////////////////
// Configuration FU
/////////////////////////////////////////////
fu::test("Constructor can take an array with default values", function() {
        $defualts = array('key1'=>'value1', 'key2'=>'value2');
        $config = new Config($defualts);
        fu::equal($config->get('key1'), $defualts['key1'], "Default values ok");
});

fu::test("Set can handle simple IDs", function() {
        $config = new Config();
        $config->set('key', 'value');
        fu::equal($config->get('key'), 'value', "Get value ok");
});

fu::test("Set can handle dot syntax IDs", function() {
        $config = new Config();
        $config->set('key.nested', 'value');
        fu::equal($config->get('key.nested'), 'value', "Get nested value ok");
});

fu::test("Set can handle arrays", function() {
        $defaults = array('key1'=>'value1', 'key2'=>'value2');
        $config = new Config();
        $config->set($defaults);
        fu::equal($config->get('key1'), 'value1', "Get value 1 ok");
        fu::equal($config->get('key2'), 'value2', "Get value 2 ok");
});

fu::test("Set can handle multidimensional arrays", function() {
        $defaults = array('key1'=>'value1', 'key2'=>'value2', 'key3'=>array('key4'=>'value4', 'key5'=>array('key6'=>'value6')));
        $config = new Config();
        $config->set($defaults);
        fu::equal($config->get('key1'), 'value1', "Get key1 1 ok");
        fu::equal($config->get('key2'), 'value2', "Get key2 2 ok");
        fu::equal($config->get('key3.key4'), 'value4', "Get key3.key4 4 ok");
        fu::equal($config->get('key3.key5.key6'), 'value6', "Get key3.key5.key6 6 ok");
});
fu::test("Set will cache any value after is retrieved.", function() {
        $defaults = array('key1'=>'value1', 'key2'=>'value2', 'key3'=>array('key4'=>'value4', 'key5'=>array('key6'=>'value6')));
        $config = new Config($defaults);        
        $cache = $config->getCache();
        fu::equal(empty($cache), TRUE, "Cache should be empty");
        
        $config->get('key1');
        $cache = $config->getCache();
        fu::equal($cache['key1'], 'value1', "Get key1 = value1 ok");
});

fu::test("Config configuration override", function() {
        $defaults = array('key1'=>'value1', 'key2'=>'value2', 'key3'=>array('key4'=>'value4', 'key5'=>array('key6'=>'value6')));
        $override = array('key1'=>'value1_override', 'key3'=>array('key31'=>31, 'key4'=>'value4_override'));
        
        $config = new Config($defaults);        
        $config->set($override);
        
        fu::equal($config->get('key1'), 'value1_override', "Get key1 = value1_override");
        fu::equal($config->get('key3.key4'), 'value4_override', "Get key3.key31 = value4_override ok");
        fu::equal($config->get('key3.key31'), 31, "Get key3.key31 = 31 ok");
        fu::equal($config->get('key3.key5.key6'), 'value6', "Get key3.key5.key6 = value6 ok");
});

fu::test("Config:import will load a filename", function() {
        $path = './tests/fixtures/config/config.php';
        $config = new Config();
        $defaults = $config->import($path);
        fu::ok($defaults, "It should return the contents of the file.");
        
        $expected = array('key1'=>'value1');
        $defaults = $config->import('fake/path.php', $expected);
        fu::equal($defaults, $expected, "If no file found, return defaults parameter provided");       
        
        //TODO: Break into own test?
        $merge = array('key1'=>'marege1', 'newKey'=>'newValue');
        $defaults = $config->import($path, $merge);
        fu::equal($defaults['key1'], 'value1');
        fu::equal($defaults['newKey'], $merge['newKey']);
        
        //TODO: Break into own test?
        $callback = function() use($config)
        {
            $config->import('fake/path.php', NULL, TRUE);
        };
        $params = array('fake/path.php', NULL, TRUE);
        fu::throws($callback, 
                   'InvalidArgumentException', 
                   "It should throw an exception if
                    file is required.");
});

fu::test("Constructor can take a string that will load a config file", function() {
        $path = './tests/fixtures/config/config.php';
        $defaults = include($path);
        $config = new Config($path);
        foreach($defaults as $key=>$value)
        {
            fu::equal($config->get($key), $value, "Get {$key} = {$value} OK.");
        }
});


/////////////////////////////////////////////
// Lower level methods
/////////////////////////////////////////////
fu::test("Config::getNestedValue", function() {
        $defaults = array('key1'=>'value1', 'key2'=>'value2', 'key3'=>array('key4'=>'value4', 'key5'=>array('key6'=>'value6')));
        $config = new Config();
        
        $value = $config->getNestedValue($defaults, 'key3.key4');
        fu::equal($value, $defaults['key3']['key4'], "Nested value key3.key4");
        
        $value = $config->getNestedValue($defaults, 'key3.key5.key6');
        fu::equal($value, $defaults['key3']['key5']['key6'], "Nested value key3.key5.key6");
        
        $value = $config->getNestedValue($defaults, 'key.does.not.exist');
        fu::strict_equal($value, NULL, "If key does not exist and no default value is provided, return NULL");
        
        $value = $config->getNestedValue($defaults, 'key.does.not.exist', 'DEFAULT');
        fu::equal($value, 'DEFAULT', "If key does not exist, default value is returned");
});

$exit = fu::run();
exit($exit);