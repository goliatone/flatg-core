<?php namespace goliatone\flatg\logging\filters {

    use goliatone\flatg\logging\core\ILogFilter;
    use goliatone\flatg\logging\core\ILogLevel;
    use goliatone\flatg\logging\core\LogLevel;
    use goliatone\flatg\logging\core\LogMessage;

    /**
     * TODO: Implement MaxBurst, Rate, Threshold, Time
     * RegExp, Marker, Map, DynamicThreshold. CompositeFilter and
     * FilterManager.
     * 
     * Class AbstractFilter
     * @package goliatone\flatg\logging\filters
     */
    abstract class AbstractFilter implements ILogFilter
    {
        /**
         * @var null
         */
        protected $_level = null;

        /**
         * @var null
         */
        protected $_namespace = null;

        /**
         * @var bool
         */
        protected $_isPreProcess = FALSE;


        /**
         * Returns TRUE if the filter can
         * be published. If the LogMessage
         * belongs to a `namespace` that is
         * disabled, then we can `filter` else
         * we collect it.
         *
         * @param  LogMessage $message
         * @return bool
         */
        public function sift(LogMessage $message)
        {
            // TODO: Implement collect() method.
        }

        /**
         * Returns `TRUE` if the filter
         * is to be applied as a pre process.
         *
         * Defaults to `FALSE`
         *
         * @return bool
         */
        public function isPreProcess()
        {
            // TODO: Implement isPreProcess() method.
        }

        /**
         * @return ILogFilter
         */
        public function getParent()
        {
            // TODO: Implement getParent() method.
        }

        /**
         * @param  LogLevel $level
         * @return mixed
         */
        public function setLevel(LogLevel $level)
        {
            $this->_level = $level;
        }

        /**
         * @return ILogLevel
         */
        public function getLevel()
        {
            return $this->_level;
        }

        /**
         * Defaults to NULL, thus not
         * filtering any namespace.
         *
         * @return string
         */
        public function getNamespace()
        {
            return $this->_namespace;
        }

        /**
         * @param $rate
         * @return $this
         */
        public function setRate($rate)
        {
            // TODO: Implement setRate() method.
        }

        /**
         * @param $burst
         * @return $this
         */
        public function setBurst($burst)
        {
            // TODO: Implement setBurst() method.
        }

        /**
         * @param string $match
         * @return $this
         */
        public function setMatch($match)
        {
            // TODO: Implement setMatch() method.
        }

        /**
         * @param string $mismatch
         * @return $this
         */
        public function setMismatch($mismatch)
        {
            // TODO: Implement setMismatch() method.
        }
    }
}