<?php namespace goliatone\flatg\logging\loggers {

    use \DateTime;

    use goliatone\flatg\logging\augmenters\CallbackAugmenter;
    use goliatone\flatg\logging\core\ILogAugmenter;
    use goliatone\flatg\logging\Debugger;
    use goliatone\flatg\logging\core\LogLevel;
    use goliatone\flatg\logging\core\LogMessage;
    use goliatone\flatg\logging\core\ILoggerAware;
    use goliatone\flatg\logging\core\ILogPublisher;
    use goliatone\flatg\logging\core\AbstractLogger;
    use goliatone\flatg\logging\core\ILogMessageFormatter;
    use goliatone\flatg\logging\helpers\Utils;
    use goliatone\flatg\logging\publishers\CompoundPublisher;

    /**
     * Class DefaultLogger
     * @package goliatone\flatg\logging\loggers
     */
    class DefaultLogger extends AbstractLogger
    {

        /**
         * @var CompoundPublisher
         */
        protected $_publisher;

        protected $_augmenters = array();

        /**
         * @var bool
         * @access protected
         */
        protected $_enabled = TRUE;

        /**
         * @var LogLevel
         * @access protected
         */
        protected $_threshold;


        protected $_fullyQualifiedClassName;


        /**
         * @var string
         */
        protected $_name    = '';

        protected $_owner   = NULL;

        /**
         * @param ILoggerAware $owner
         */
        public function __construct(ILoggerAware $owner = NULL)
        {
            $this->setOwner($owner);

            $this->_publisher = new CompoundPublisher();

            $this->_threshold = LogLevel::$ALL;
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
            //Ensure we have a LogLevel instance
            $level = LogLevel::getLevel($level);

            //Can we log this level?
            //First filter pass. We should have filter.isPreProcess()
            if($this->_isFiltered($level)) return;


            //Build LogMessage
            $msg = $this->buildMessage($level, $message, $context, 3);


            //apply augmenters, this should extend the context with
            //custom data, ie: memory usage, request info.
            $msg = $this->applyAugmenters($msg);


            //Send to all the publishers that have been registered.
            //Publishers still have a change to decide if they
            //want to handle this event or not.
            $this->_publish($msg);

        }

        /**
         * @param $level
         * @param $message
         * @param array $context
         * @param int $stackTraceSkip
         * @return \goliatone\flatg\logging\core\LogMessage
         */
        public function buildMessage($level, $message, array $context = array(), $stackTraceSkip = 3)
        {
            $msg = new LogMessage($level, $message, $context);
            $msg->setLogger($this->getName());
            $msg->setTimestamp(new DateTime('NOW'));
//            $tmp = $msg['timestamp'];
//            $msg['timestamp']=function($context, $match)use($tmp){return date(Utils::ISO8601).$tmp->format(Utils::ISO8601);};
//            $msg->setStackTrace(Debugger::backtrace($stackTraceSkip));

            return $msg;
        }


        public function addAugmenter($callable)
        {
            $augmenter = $callable;

            if(!($callable instanceof ILogAugmenter))
            {
                if(is_callable($callable)) $augmenter = new CallbackAugmenter($callable);
            }

            $this->_augmenters[] = $augmenter;
        }

        /**
         * Procedures add metadata to the `$message`.
         * You should register procedures directly on
         * the logger.
         *
         * @param  LogMessage $message
         * @return LogMessage
         */
        public function applyAugmenters(LogMessage $message)
        {
            foreach($this->_augmenters as $process)
            {
                $message = $process->process($message);
            }

            return $message;
        }



        /**
         * @param $level
         * @return bool
         */
        protected function _isFiltered($level)
        {
            if($this->_enabled === FALSE) return TRUE;

//            $this->
            //TODO: We should have filters, and based on which level
            // we have up or what package is disabled, etc.
            return FALSE;
        }


        protected  function _publish(LogMessage $message)
        {
            $this->_publisher->publish($message);
        }

        public function addPublisher($id, ILogPublisher $publisher)
        {
            $this->_publisher->add($id, $publisher);
            return $this;
        }

        //TODO: Should we have a setFormatterToPublisher($publisherId, $formatter)
        //or should publisher request formatter? Or should we move this method to
        //the ILogPublisher?
        public function addFormatter($publisherId, ILogMessageFormatter $formatter)
        {
            $this->_publisher->addFormatter($publisherId, $formatter);
            return $this;
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
        public function setOwner($owner = NULL)
        {
            $this->_owner = $owner;

            if(! $owner) return;

            $this->_name = Utils::qualifiedClassName($owner, FALSE);
            $this->_fullyQualifiedClassName = Utils::qualifiedClassName($owner);
        }

        public function getFullyQualifiedClassName()
        {
            return $this->_fullyQualifiedClassName;
        }

        /**
         * @param \goliatone\flatg\logging\core\LogLevel $threshold
         */
        public function setThreshold($threshold)
        {
            $this->_threshold = $threshold;
        }

        /**
         * @return \goliatone\flatg\logging\core\LogLevel
         */
        public function getThreshold()
        {
            return $this->_threshold;
        }

    }
}