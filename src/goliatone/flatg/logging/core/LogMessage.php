<?php namespace goliatone\flatg\logging\core {

    use ArrayIterator;
    use \ArrayObject;
    use goliatone\flatg\logging\core\LogLevel;

    /**
     * TODO: Make it extend ArrayObject
     * Class LogMessage
     * @package goliatone\events\core
     */
    class LogMessage extends ArrayObject
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


        /**
         * @var null
         */
        protected $_address = null;


        /**
         * @var array
         */
//        protected $_extra = array();

        /**
         * @param $level
         * @param $message
         * @param array $context
         */
        function __construct($level, $message, array $context = array())
        {
            //TODO: We should integrate extras/content with __call and
            // use @method for set{$property} if property_exists($this, '_'.$property)
            // make sure we keep both setters and offsetSet in sync.
            $this->_context    = $context;
            $this->_message    = $message;

            parent::__construct($this->_context,
                \ArrayObject::STD_PROP_LIST |
                \ArrayObject::ARRAY_AS_PROPS);

            //TODO: Remove this!!! we should not have this cruft.
            $this->setLevel($level);
            $this->setMessage($message);
            $this->offsetSet('rawMessage', $message);
            $this->getAddress();
            //This should not be here!
            $this->setTimestamp(time());
        }


        /**
         * @param LogLevel $level
         */
        public function setLevel(LogLevel $level)
        {
            $this->_level = $level;
            $this->offsetSet('level', $level);
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
            $this->_message = $message;
            $this->offsetSet('message', $message);
        }

        /**
         * @return string
         */
        public function getMessage()
        {
            return $this->_message;
        }

        public function getRawMessage()
        {
            return $this->_rawMessage;
        }

        /**
         * @param mixed $stackTrace
         */
        public function setStackTrace($stackTrace)
        {
            $this->_stackTrace = $stackTrace;
            $this->offsetSet('stackTrace', $stackTrace);
        }

        /**
         * @return mixed
         */
        public function getStackTrace()
        {
            return $this->_stackTrace;
        }

        /**
         * @param mixed $timestamp
         */
        public function setTimestamp($timestamp)
        {
            $this->_timestamp = $timestamp;
            $this->offsetSet('timestamp', $timestamp);
        }

        /**
         * @return mixed
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
            return $this->getArrayCopy();
        }

        /**
         * @param string $machine
         */
        public function setAddress($machine)
        {
            $this->_address = $machine;
            $this->offsetSet('address', $machine);
        }

        /**
         * TODO: This should be static, one per Log!!
         * @return string
         */
        public function getAddress()
        {
            if(!$this->_address)
            {
                $address = (isset ($_SERVER['SERVER_ADDR'])) ? $_SERVER['SERVER_ADDR'] : 'localhost';
                $this->setAddress($address);
            }

            return $this->_address;
        }

    }
}