<?php namespace goliatone\flatg\logging\publishers {

    use goliatone\events\core\ILogMessageFormatter;
    use goliatone\events\core\ILogPublisher;
    use goliatone\flatg\logging\core\LogMessage;

    class CompoundPublisher implements ILogPublisher
    {

        protected $_publishers = array();

        /**
         * @param $id
         * @param ILogPublisher $publisher
         * @return $this
         */
        public function add($id, ILogPublisher $publisher)
        {
            $this->_publishers[$id] = $publisher;
            return $this;
        }


        public function publish(LogMessage $message)
        {
            foreach($this->_publishers as $id => $publisher)
            {
                $publisher->publish($message);
            }
        }

        public function addFormatter(ILogMessageFormatter $formatter)
        {
            foreach($this->_publishers as $id => $publisher)
            {
                $publisher->addFormatter($formatter);
            }
        }

        public function terminate()
        {
            foreach($this->_publishers as $id => $publisher)
            {
                $publisher->terminate();
            }
        }


        public function getName()
        {
            return "CompoundPublisher";
        }
    }
}