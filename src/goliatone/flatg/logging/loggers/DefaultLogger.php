<?php namespace goliatone\flatg\logging\loggers {

    use goliatone\events\core\ILogMessageFormatter;
    use goliatone\events\core\ILogPublisher;
    use goliatone\flatg\logging\core\ILoggerAware;
    use goliatone\flatg\logging\core\LogMessage;
    use goliatone\flatg\logging\Debugger;
    use goliatone\flatg\logging\core\AbstractLogger;
    use goliatone\flatg\logging\publishers\CompoundPublisher;

    /**
     * Class DefaultLogger
     * @package goliatone\flatg\logging\loggers
     */
    class DefaultLogger extends AbstractLogger
    {


        protected $_publisher;

        /**
         * @var bool
         * @access protected
         */
        protected $_enabled = TRUE;


        /**
         * @var string
         */
        protected $_name    = '';

        protected $_owner   = NULL;

        public function __construct(ILoggerAware $owner)
        {
            $this->setOwner($owner);
            $this->_publisher = new CompoundPublisher();
        }

        /**
         * Logs with an arbitrary level.
         *
         * @param mixed $level
         * @param string $message
         * @param array $context
         * @return null
         */
        public function log($level, $message, array $context = array())
        {
            if($this->_isFiltered($level)) return;

            $msg = new LogMessage($level, $message, $context);
            $msg->setTimestamp(time());
            $msg->setLogger($this->getName());
            $msg->setStackTrace(Debugger::backtrace(2));

            $this->_publisher->publish($msg);

        }


        /**
         * @param $level
         * @return bool
         */
        protected function _isFiltered($level)
        {
            if($this->_enabled === FALSE) return TRUE;
            //TODO: We should have filters, and based on which level
            // we have up or what package is disabled, etc.
            return FALSE;
        }


        public function addPublisher($id, ILogPublisher $publisher)
        {
            $this->_publisher->add($id, $publisher);
            return $this;
        }

        public function addFormatter(ILogMessageFormatter $formatter)
        {
            $this->_publisher->addFormatter($formatter);
        }

        /**
         * @param bool $enabled
         */
        public function setEnabled($enabled)
        {
            $this->_enabled = $enabled;
        }

        /**
         * @return bool
         */
        public function getEnabled()
        {
            return $this->_enabled;
        }

        /**
         * @param string $name
         */
        public function setName($name)
        {
            $this->_name = $name;
        }

        /**
         * @return string
         */
        public function getName()
        {
            return $this->_name;
        }

        /**
         * @return null
         */
        public function getOwner()
        {
            return $this->_owner;
        }

        /**
         * @param null $owner
         */
        public function setOwner($owner)
        {
            $this->_owner = $owner;
        }

    }
}