<?php namespace goliatone\flatg\logging\publishers\clients {


    use goliatone\flatg\logging\core\LogMessage;
    use goliatone\flatg\logging\core\ILogPublisher;
    use goliatone\flatg\logging\core\ILogMessageFormatter;
    use goliatone\flatg\logging\formatters\CompoundFormatter;

    abstract class AbstractPublisher implements ILogPublisher
    {

        /**
         * @var CompoundFormatter
         */
        protected $_formatter;

        protected $_defaultFormatterClass;

        protected $_name = __CLASS__;

        function __construct()
        {
            $this->_formatter = new CompoundFormatter();
            $this->_defaultFormatterClass = 'goliatone\flatg\logging\formatters\SimpleFormatter';
        }


        /**
         * @inheritdoc
         */
        abstract public function publish(LogMessage $message);

        /**
         * @inheritdoc
         */
        abstract public function flush(LogMessage $message);

        /**
         * @return void
         */
        public function terminate()
        {
            // TODO: Clean up before we exit. You might also want to defer flushing.
        }

        /**
         * @param LogMessage $message
         */
        public function applyFormat(LogMessage $message)
        {
            $this->_formatter->format($message);
        }
        /**
         * @param  string $id
         * @param  ILogMessageFormatter $formatter
         * @return $this|ILogPublisher
         */
        public function addFormatter($id, ILogMessageFormatter $formatter)
        {
            $this->_formatter->add($id, $formatter);

            return $this;
        }

        /**
         * @return string
         */
        public function getName()
        {
            return $this->_name;
        }

        /**
         * @return ILogMessageFormatter
         */
        public function getDefaultFormatter()
        {

            $_DefaultFormatter = $this->_defaultFormatterClass;

            return new $_DefaultFormatter();
        }

        /**
         *
         */
        public function __destruct()
        {
            try {
                $this->terminate();
            } catch(\Exception $e) {
                #Swallow it, just like that?!
            }
        }
    }
}