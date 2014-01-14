<?php namespace goliatone\flatg\logging\augmenters {

    use goliatone\flatg\logging\core\LogMessage;
    use goliatone\flatg\logging\core\ILogAugmenter;

    class CallbackAugmenter implements ILogAugmenter
    {
        protected $_callable;

        public function __construct($callable)
        {
            $this->wrap($callable);
        }

        public function wrap(callable $callable)
        {
            $this->_callable = $callable;
        }

        /**
         * @param LogMessage $message
         * @return LogMessage
         */
        public function process(LogMessage $message)
        {
            return call_user_func($this->_callable, $message);
        }


    }
}