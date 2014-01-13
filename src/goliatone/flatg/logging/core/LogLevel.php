<?php namespace goliatone\flatg\logging\core;


/**
 * Class LogLevel. Holds log level constants.
 * Error severity from low to high.
 * @package goliatone\flatg\logging\core
 */
final class LogLevel
{
    /**
     *
     */
    const OFF       = 0xFFFFFF;

    /**
     * Detailed debug information
     */
    const DEBUG     = 1;

    /**
     * Interesting events
     *
     * Examples: User logs in, SQL logs.
     */
    const INFO      = 2;

    /**
     * Uncommon events
     */
    const NOTICE    = 4;

    /**
     * Exceptional occurrences that are not errors
     *
     * Examples: Use of deprecated APIs,
     * poor use of an API, undesirable things
     * that are not necessarily wrong.
     */
    const WARNING   = 8;

    /**
     * Runtime errors
     */
    const ERROR     = 16;

    /**
     * Critical conditions
     *
     * Example: Application component
     * unavailable, unexpected exception.
     */
    const CRITICAL  = 32;

    /**
     * Action must be taken immediately
     *
     * Example: Entire website down, database unavailable, etc.
     * This should trigger the SMS alerts and wake you up.
     */
    const ALERT     = 64;

    /**
     * Urgent alert.
     */
    const EMERGENCY = 128;


    /**
     *
     */
    const ALL       = 0;


    /**
     * @var array
     */
    public static $levels = array(
        self::OFF       => 'OFF',
        self::DEBUG     => 'DEBUG',
        self::INFO      => 'INFO',
        self::NOTICE    => 'NOTICE',
        self::WARNING   => 'WARNING',
        self::ERROR     => 'ERROR',
        self::CRITICAL  => 'CRITICAL',
        self::ALERT     => 'ALERT',
        self::EMERGENCY => 'EMERGENCY',
        self::ALL       => 'ALL',
    );

    static public $OFF;
    static public $DEBUG;
    static public $INFO;
    static public $NOTICE;
    static public $WARNING;
    static public $ERROR;
    static public $CRITICAL;
    static public $ALERT;
    static public $EMERGENCY;
    static public $ALL;

    /**
     * Gets the name of the logging level.
     *
     * @param  int $level
     * @throws \InvalidArgumentException
     * @return string
     */
    public static function getLevelName($level)
    {
        if (!isset(static::$levels[$level])) {
            throw new \InvalidArgumentException('Level "'.$level.'" is not defined, use one of: '.implode(', ', array_keys(static::$levels)));
        }

        return static::$levels[$level];
    }

    /**
     * Gets all supported logging levels.
     *
     * @return array Assoc array with human-readable
     *               level names => level codes.
     */
    public static function getLevels()
    {
        return array_flip(static::$levels);
    }


    /**
     * @var string
     */
    protected $_label = '';


    /**
     * @var int
     */
    protected $_code = -1;

    /**
     * @param $label
     * @param $code
     */
    public function __construct($label, $code)
    {
        $this->_label = $label;
        $this->_code = $code;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return $this->_code;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->_label;
    }

    public function __toString()
    {
        return $this->getLabel();
    }
}


if(!LogLevel::$ALL)
{
    foreach(LogLevel::$levels as $code => $label)
    {
        LogLevel::$$label = new LogLevel($label, $code);
    }
}

//LogLevel::$OFF       = new LogLevel("OFF", LogLevel::OFF);
//LogLevel::$DEBUG     = new LogLevel("DEBUG", LogLevel::DEBUG);
//LogLevel::$INFO      = new LogLevel("INFO", LogLevel::INFO);
//LogLevel::$NOTICE    = new LogLevel("NOTICE", LogLevel::NOTICE);
//LogLevel::$WARNING   = new LogLevel("WARNING", LogLevel::WARNING);
//LogLevel::$ERROR     = new LogLevel("ERROR", LogLevel::ERROR);
//LogLevel::$CRITICAL  = new LogLevel("CRITICAL", LogLevel::CRITICAL);
//LogLevel::$ALERT     = new LogLevel("ALERT", LogLevel::ALERT);
//LogLevel::$EMERGENCY = new LogLevel("EMERGENCY", LogLevel::EMERGENCY);
//LogLevel::$ALL       = new LogLevel("ALL", LogLevel::ALL);
