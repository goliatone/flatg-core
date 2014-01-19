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
         * @param string $id
         * @param \goliatone\flatg\logging\core\ILogPublisher|null $default
         * @throws \Exception
         * @return ILogPublisher
         */
        public function get($id, ILogPublisher $default=NULL)
        {
            if($this->has($id)) return $this->_publishers[$id];

            if($default) return $default;

            throw new \Exception("TODO: We need to handle default Publisher. Return here");
        }

        /**
         * @param string $id
         * @return bool
         */
        public function has($id){
            return array_key_exists($id, $this->_publishers);
        }


        /**
         * @param LogMessage $message
         */
        public function publish(LogMessage $message)
        {
            foreach($this->_publishers as $publisher)
            {
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
         * @return $this|mixed
         */
        public function begin()
        {
            foreach($this->_publishers as $publisher)
            {
                $publisher->begin();
            }
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

            return $this;
        }


        /**
         * @return string
         */
        public function getName()
        {
            return "CompoundPublisher";
        }

        /**
         * @param array $messages
         * @return mixed|void
         */
        public function flush(array $messages = null)
        {
            foreach($this->_publishers as $publisher)
            {
                $publisher->flush($messages);
            }

            return $this;
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

        /**
         * @param $headers
         * @return $this
         */
        public function setHeader($headers)
        {
            foreach($headers as $id => $header)
            {
                if(!$this->has($id)) continue;
                $this->get($id)->setHeader($header);
            }

            return $this;
        }

        /**
         * @param $footers
         * @return $this
         */
        public function setFooter($footers)
        {
            foreach($footers as $id => $footer)
            {
                if(!$this->has($id)) continue;
                $this->get($id)->setFooter($footer);
            }

            return $this;
        }
    }
}