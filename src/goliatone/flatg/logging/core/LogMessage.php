<?php namespace goliatone\flatg\logging\core {

    use goliatone\flatg\logging\core\LogLevel;

    /**
     * Class LogMessage
     * @package goliatone\events\core
     */
    class LogMessage
    {
        /**
         * @var LogLevel
         *
         */
        protected $_level;

        /**
         * @var string
         */
        protected $_rawMessage = '';


        /**
         * @var string
         */
        protected $_message = '';

        /**
         *
         */
        protected $_stackTrace;

        /**
         * @var string
         */
        protected $_logger = '';

        /**
         * @var int
         */
        protected $_timestamp;


        /**
         * @var array
         */
        protected $_context = array();


        protected $_address = null;



        /**
         * @param $level
         * @param $message
         * @param array $context
         */
        function __construct($level, $message, array $context = array())
        {
            $this->_level      = $level;
            $this->_context    = $context;
            $this->_rawMessage = $message;
        }


        /**
         * @param LogLevel $level
         */
        public function setLevel(LogLevel $level)
        {
            $this->_level = $level;
        }

        /**
         * @return LogLevel
         */
        public function getLevel()
        {
            return $this->_level;
        }

        /**
         * @param string $logger
         */
        public function setLogger($logger)
        {
            $this->_logger = $logger;
        }

        /**
         * @return string
         */
        public function getLogger()
        {
            return $this->_logger;
        }

        /**
         * @param string $message
         */
        public function setMessage($message)
        {
            $this->_rawMessage = $message;
            $this->_updateMessage();
        }

        /**
         * @return string
         */
        public function getMessage()
        {
            return $this->_message;
        }

        /**
         * @param mixed $stackTrace
         */
        public function setStackTrace($stackTrace)
        {
            $this->_stackTrace = $stackTrace;
        }

        /**
         * @return mixed
         */
        public function getStackTrace()
        {
            return $this->_stackTrace;
        }

        /**
         * @param int $timestamp
         */
        public function setTimestamp($timestamp)
        {
            $this->_timestamp = $timestamp;
        }

        /**
         * @return int
         */
        public function getTimestamp()
        {
            return $this->_timestamp;
        }

        /**
         * @param array $context
         */
        public function setContext($context)
        {
            $this->_context = $context;
            $this->_updateMessage();
        }

        /**
         * @return array
         */
        public function getContext()
        {
            return $this->_context;
        }

        /**
         * @param string $machine
         */
        public function setAddress($machine)
        {
            $this->_address = $machine;
        }

        /**
         * TODO: This should be static, one per Log!!
         * @return string
         */
        public function getAddress()
        {
            if(!$this->_address)
            {
               $this->_address = (isset ($_SERVER['SERVER_ADDR'])) ? $_SERVER['SERVER_ADDR'] : 'localhost';
            }

            return $this->_address;
        }

        protected function _updateMessage()
        {
            $this->_message = $this->_interpolate($this->_rawMessage, $this->_context);
        }

        /**
         * Interpolate log message
         * @param  mixed     $message               The log message
         * @param  array     $context               An array of placeholder values
         * @return string    The processed string
         */
        protected function _interpolate($message, $context = array())
        {
            $replace = array();
            foreach ($context as $key => $value) {
                $replace['{' . $key . '}'] = $value;
            }
            return strtr($message, $replace);
        }

    }
}