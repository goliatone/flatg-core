<?php namespace goliatone\flatg\logging\helpers {
    use goliatone\flatg\logging\core\ILogger;
    use goliatone\flatg\logging\core\LogLevel;

    /**
     * Class ErrorLogger, helper class to
     * hook into the error life cycle and
     * report errors at will.
     *
     * @package goliatone\flatg\logging\helpers
     */
    class ErrorLogger
    {
        private static $fatalErrors = array(E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR, E_USER_ERROR);

        const EXCEPTION_MESSAGE        = 'Uncaught exception: {exception}';

        protected $_logger             = null;

        protected $_alertLevel         = null;

        protected $_errorHandler       = null;

        protected $_errorLevelMap      = null;

        protected $_exceptionMessage   = null;

        protected $_exceptionHandler   = null;

        protected $_exceptionThreshold = null;

        /**
         * @param ILogger $logger
         * @param null $errors
         * @param null $threshold
         * @param null $shutdown
         */
        static public function register(ILogger $logger, $errors=null, $threshold=null, $shutdown=null)
        {
            $errorLogger = new ErrorLogger($logger);

            if($errors)    $errorLogger->registerErrorHandler($errors);
            if($threshold) $errorLogger->registerExceptionHandler($threshold);
            if($shutdown)  $errorLogger->registerShutdownHandler($shutdown);

            return $errorLogger;
        }


        /**
         * @param $logger
         */
        public function __construct($logger)
        {
            $this->_logger = $logger;
        }

        /**
         * @param array $levelMap
         * @param $errorTypes
         */
        public function registerErrorHandler($levelMap = array(), $errorTypes = -1)
        {
            $this->_errorHandler  = set_error_handler(array($this, 'handleError'), $errorTypes);
            $this->_errorLevelMap = array_replace(self::$defaultErrorLevelMap, $levelMap);
        }

        /**
         * @param $code
         * @param $message
         * @param string $file
         * @param int $line
         * @param array $context
         * @return bool|mixed
         */
        public function handleError($code, $message, $file='', $line = 0, $context = array())
        {
            //Are we interested in this level?
            if (!($code & error_reporting())) return;

            //Let's get the LogLevel based on the provided map and defaults.
            $level = array_key_exists($code, $this->_errorLevelMap) ? $this->_errorLevelMap[$code] : LogLevel::$CRITICAL;

            //TODO: We should add or make sure we have an error augmenter.
            $this->_logger->log(
                $level,
                $this->formatErrorMessage($code, $message),
                array('file'=>$file,
                      'line'=>$line
                )
            );

            if(!$this->_errorHandler) return false;

            return call_user_func_array($this->_errorHandler, func_get_args());
        }

        /**
         * @param null $threshold
         * @param $message
         */
        public function registerExceptionHandler($threshold = null, $message = EXCEPTION_MESSAGE )
        {
            $threshold || ($threshold  = LogLevel::$ERROR);

            $this->_exceptionMessage   = $message;
            $this->_exceptionThreshold = $threshold;
            $this->_exceptionHandler   = set_exception_handler(array($this, "handleException"));
        }

        /**
         * @param \Exception $e
         */
        public function handleException(\Exception $e)
        {
            //Log this puppy!
            //TODO: We should add or make sure we have an exception augmenter.
            $this->_logger->log(
                $this->_exceptionThreshold,
                $this->_exceptionMessage,
                array('exception' => $e)
            );

            if(!$this->_exceptionHandler) return;

            //Bubble up to the original exception handler.
            call_user_func($this->_exceptionHandler, $e);

        }

        /**
         * @param null $level
         */
        public function registerShutdownHandler($level = null)
        {
            register_shutdown_function(array($this, 'handleShutdown'));

            $level && ($this->_alertLevel = $level);
        }

        /**
         *
         */
        public function handleShutdown()
        {
            $lastError = error_get_last();

            if(!$lastError || !in_array($lastError['type'], self::$fataErrors)) return;

            $this->_logger->log(
                $this->_alertLevel,
                $this->formatErrorMessage($lastError['type'], $lastError['message'], 'shutdown'),
                array('file' => $lastError['file'],
                      'line' => $lastError['line']
                )
            );
        }

        /**
         * @param $code
         * @param $message
         * @param string $type
         * @return string
         */
        public function formatErrorMessage($code, $message, $type='exception')
        {
            //shutdown
            //'Fatal Error ('.self::$CODE_STRINGS[$lastError['type']].'): '.$lastError['message']

            //exception
            //self::codeToString($code).': '.$message
            return 'TODO'
        }

        /**
         * @var array
         */
        static public $CODE_STRINGS = array(
            E_ERROR             => 'E_ERROR',
            E_PARSE             => 'E_PARSE',
            E_NOTICE            => 'E_NOTICE',
            E_STRICT            => 'E_STRICT',
            E_WARNING           => 'E_WARNING',
            E_USER_ERROR        => 'E_USER_ERROR',
            E_CORE_ERROR        => 'E_CORE_ERROR',
            E_DEPRECATED        => 'E_DEPRECATED',
            E_USER_NOTICE       => 'E_USER_NOTICE',
            E_USER_WARNING      => 'E_USER_WARNING',
            E_CORE_WARNING      => 'E_CORE_WARNING',
            E_COMPILE_ERROR     => 'E_COMPILE_ERROR',
            E_COMPILE_WARNING   => 'E_COMPILE_WARNING',
            E_USER_DEPRECATED   => 'E_USER_DEPRECATED',
            E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR'
        );

        /**
         * @var array
         */
        static public $defaultErrorLevelMap = array(
            E_ERROR             => LogLevel::CRITICAL,
            E_PARSE             => LogLevel::ALERT,
            E_NOTICE            => LogLevel::NOTICE,
            E_STRICT            => LogLevel::NOTICE,
            E_WARNING           => LogLevel::WARNING,
            E_USER_ERROR        => LogLevel::ERROR,
            E_CORE_ERROR        => LogLevel::CRITICAL,
            E_DEPRECATED        => LogLevel::NOTICE,
            E_USER_NOTICE       => LogLevel::NOTICE,
            E_USER_WARNING      => LogLevel::WARNING,
            E_CORE_WARNING      => LogLevel::WARNING,
            E_COMPILE_ERROR     => LogLevel::ALERT,
            E_COMPILE_WARNING   => LogLevel::WARNING,
            E_USER_DEPRECATED   => LogLevel::NOTICE,
            E_RECOVERABLE_ERROR => LogLevel::ERROR
        );
    }
}