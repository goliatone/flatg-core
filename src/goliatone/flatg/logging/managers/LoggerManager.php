<?php namespace goliatone\flatg\logging\managers {

    use goliatone\flatg\logging\helpers\CachedHash;
    use goliatone\flatg\logging\helpers\Utils;
    use goliatone\flatg\logging\loggers\DefaultLogger;

    /**
     * Class LoggerManager
     * @package goliatone\flatg\logging\managers
     */
    class LoggerManager
    {
        /**
         * @var CachedHash
         */
        protected $_cache;

        protected $_defaults = array(
            'loggerClass'=>'goliatone\\flatg\\logging\\loggers\\DefaultLogger',
            'formatter'  =>'goliatone\\flatg\\logging\\formatters\\SimpleFormatter',
            'publisher'  =>'goliatone\\flatg\\logging\\publishers\\clients\\TerminalPublisher',
        );
        /**
         *
         */
        public function __construct()
        {
            $this->_cache = new CachedHash();
        }

        /**
         * @param $forItem
         * @param array $options
         */
        public function buildLogger($forItem, $options = array())
        {
            //should we serialize options and package...
            $key = $this->buildKey($forItem, $options);
            return $this->_cache->get($key, array($this, 'build'), $forItem, $options);
        }

        public function build($forItem, $options=array())
        {
            $config = array_merge_recursive($this->_defaults, $options);

            $_LoggerClass = $config['loggerClass'];

            //This should be the default
            $_SimpleFormatter   = $config['formatter'];
            //A publisher should have a related formatter, we should config that
            $_TerminalPublisher = $config['publisher'];

            $formatter = new $_SimpleFormatter();
            $publisher = new $_TerminalPublisher();
            $publisher->addFormatter($formatter->getName(), $formatter);


            //How do we get logger class? Default or from config. We should merge.
            $logger = new $_LoggerClass($forItem);

            //we should do logger < publisher < formatter
            //TODO: Logger should have no clue about FORMATTERS.
            $logger->addPublisher($publisher->getName(), $publisher);
            $logger->addFormatter($publisher->getName(), $formatter);

            $logger->configure($options);

            return $logger;
        }


        /**
         * @param $forItem
         * @param $options
         * @return string
         */
        public function buildKey($forItem, $options)
        {
            return Utils::qualifiedClassName($forItem).serialize($options);
        }
    }
}