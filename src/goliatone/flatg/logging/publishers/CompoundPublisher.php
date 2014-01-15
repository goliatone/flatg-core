<?php namespace goliatone\flatg\logging\publishers {

    use goliatone\flatg\logging\core\LogMessage;
    use goliatone\flatg\logging\core\ILogPublisher;
    use goliatone\flatg\logging\core\ILogMessageFormatter;

    /**
     * TODO: Rename to CompositePublisher.
     * Class CompoundPublisher
     * @package goliatone\flatg\logging\publishers
     */
    class CompoundPublisher implements ILogPublisher
    {

        /**
         * @var array
         */
        protected $_publishers = array();

        /**
         * We name it add insteda of addPublisher to
         * remain consistent with CompoundFormatter.
         * Should both extend a base class?
         *
         * @param $id
         * @param ILogPublisher $publisher
         * @return $this
         */
        public function add($id, ILogPublisher $publisher)
        {
            $this->_publishers[$id] = $publisher;
            return $this;
        }

        /**
         * @param $id
         * @param null $default
         * @return ILogPublisher
         * @throws \Exception
         */
        public function get($id, $default=NULL)
        {
            if(array_key_exists($id, $this->_publishers)) return $this->_publishers[$id];
            throw new \Exception("TODO: We need to handle default Publisher. Return here");
        }


        /**
         * @param LogMessage $message
         */
        public function publish(LogMessage $message)
        {
            foreach($this->_publishers as $publisher)
            {
                echo "PUBLISHING!!\n";
                $publisher->publish($message);
            }
        }

        /**
         * @param  string $id
         * @param  ILogMessageFormatter $formatter
         * @return ILogPublisher
         */
        public function addFormatter($id, ILogMessageFormatter $formatter)
        {

            $this->get($id)->addFormatter($id, $formatter);

            return $this;
        }

        /**
         *
         */
        public function terminate()
        {
            foreach($this->_publishers as $publisher)
            {
                $publisher->terminate();
            }
        }


        /**
         * @return string
         */
        public function getName()
        {
            return "CompoundPublisher";
        }

        /**
         * @param  LogMessage $message
         * @return mixed
         */
        public function flush(LogMessage $message)
        {
            foreach($this->_publishers as $publisher)
            {
                $publisher->flush($message);
            }
        }

        //TODO: Implement somethign like this.
        public function each($callback)
        {
            $iterator = $this->getIterator();

            while($iterator->valid()) {
                $callback($iterator->current());
                $iterator->next();
            }

        }
    }
}