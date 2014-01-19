<?php namespace goliatone\flatg\logging\publishers\clients {


    use goliatone\flatg\logging\core\LogMessage;
    use goliatone\flatg\logging\core\ILogPublisher;
    use goliatone\flatg\logging\core\ILogMessageFormatter;
    use goliatone\flatg\logging\formatters\CompoundFormatter;
    use goliatone\flatg\logging\helpers\Utils;

    /**
     * TODO: Add RunTimeAugmenter.
     *
     * Class AbstractPublisher
     * @package goliatone\flatg\logging\publishers\clients
     */
    abstract class AbstractPublisher implements ILogPublisher
    {

        /**
         * @var null
         */
        public $header = null;

        /**
         * @var null
         */
        public $footer = null;

        /**
         * @var array
         */
        public $messages = array();

        /**
         * @var bool
         */
        public $asynchronous = FALSE;

///////////////////////////////////////
//      TODO: Move to Debugger::stopWatch?
///////////////////////////////////////
        /**
         * @var null
         */
        public $startTime = null;

        /**
         * @var null
         */
        public $endTime   = null;

        /**
         * @var null
         */
        public $runTime   = null;
///////////////////////////////////////

        /**
         * @var CompoundFormatter
         */
        protected $_formatter;

        /**
         * @var string
         */
        protected $_defaultFormatterClass;

        /**
         * @var string
         */
        protected $_name = __CLASS__;

        /**
         * @var bool
         */
        protected $_runOnConstruct = TRUE;

        /**
         *
         */
        function __construct()
        {
            $this->_formatter = new CompoundFormatter();
            $this->_defaultFormatterClass = 'goliatone\flatg\logging\formatters\SimpleFormatter';

            if($this->_runOnConstruct)
                $this->doBegin();
        }


        /**
         * @inheritdoc
         */
        public function publish(LogMessage $message)
        {
            $this->messages[] = $message;

            if($this->asynchronous) return $this;

            $this->onPublish($message);

            return $this;
        }

        protected function onPublish(LogMessage $message)
        {
            //This has to be overwritten
        }

        /**
         * @inheritdoc
         */
        public function flush(array $messages = null)
        {
            if(!$messages) $messages = array();
            $load = array_merge($this->messages, $messages);
            foreach($load as $message)
            {
                $this->onPublish($message);
            }
        }

        /**
         * @param $header
         * @return mixed
         */
        public function setHeader($header)
        {
            $this->header = $header;
        }

        /**
         * @param $footer
         * @return mixed
         */
        public function setFooter($footer)
        {
            $this->footer = $footer;
        }

        /**
         *
         */
        protected function doBegin()
        {
            $this->startTime = microtime(true);

            $this->begin();
        }

        /**
         *
         */
        public function begin()
        {

        }

        /**
         *
         */
        protected function doTerminate()
        {
            if($this->asynchronous) $this->flush();

            $this->endTime = microtime(true);
            $this->runTime = ($this->endTime - $this->startTime)/60;

            $this->terminate();

        }
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
         * @param $message
         * @param $context
         * @param bool $consume
         * @return string
         */
        protected function stringTemplate($message, $context, $consume = TRUE)
        {
            return Utils::stringTemplate($message, $context, $consume);
        }

        /**
         *
         */
        public function __destruct()
        {
            try {
                $this->doTerminate();
            } catch(\Exception $e) {
                #Swallow it, just like that?!
            }
        }
    }
}