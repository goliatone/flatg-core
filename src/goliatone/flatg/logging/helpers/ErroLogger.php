<?php namespace goliatone\flatg\logging\helpers {
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

        protected $_errorHandler       = null;

        protected $_exceptionMessage   = null;

        protected $_exceptionHandler   = null;

        protected $_exceptionThreshold = null;


        static public function register()
        {

        }


        public function __construct($logger)
        {
            $this->_logger = $logger;
        }


        public function registerErrorHandler($levelMap = array(), $errorTypes = -1)
        {
            $this->_errorHandler = set_error_handler(array($this, 'handleError'), $errorTypes);
        }

        public function handleError($code, $message, $file='', $line = 0, $context = array())
        {
            //Are we interested in this level?
            if (!($code & error_reporting())) return;

            $level = array_key_exists($code, $this->_errorLevels) ? $this->_errorLevels[$code] : LogLevel::$CRITICAL;

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

        //TODO: do we need this?
        public function restoreErrorHandler()
        {
            $handler = function(){};

            if($this->_errorHandler) $handler = $this->_errorHandler;

            set_error_handler($handler, $errorTypes);
        }

        public function registerExceptionHandler($threshold = null, $message = EXCEPTION_MESSAGE )
        {
            $threshold || ($threshold  = LogLevel::$ERROR);

            $this->_exceptionMessage   = $message;
            $this->_exceptionThreshold = $threshold;
            $this->_exceptionHandler   = set_exception_handler(array($this, "handleException"));
        }

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

        public function restoreExceptionHandler()
        {
            $handler = function(\Exception $e){};

            if($this->_exceptionHandler) $handler = $this->_exceptionHandler;

            set_exception_handler($handler);
        }

        public function registerShutdownHandler($level = null)
        {
            register_shutdown_function(array($this, 'handleShutdown'));

            $level && $this->_alertLevel;
        }

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


        public function formatErrorMessage($code, $message, $type='exception')
        {
            //shutdown
            //'Fatal Error ('.self::$CODE_STRINGS[$lastError['type']].'): '.$lastError['message']

            //exception
            //self::codeToString($code).': '.$message
            return 'TODO'
        }


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

    }
}