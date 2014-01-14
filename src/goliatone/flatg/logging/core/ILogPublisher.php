<?php namespace goliatone\flatg\logging\core {


    use goliatone\flatg\logging\core\LogMessage;

    /**
     * Interface ILogPublisher
     * @package goliatone\flatg\logging\core
     */
    interface ILogPublisher
    {

        /**
         * @param  LogMessage $message
         * @return void
         */
        public function publish(LogMessage $message);

        /**
         * @param  LogMessage $message
         * @return mixed
         */
        public function flush(LogMessage $message);

        /**
         * @param  string               $id
         * @param  ILogMessageFormatter $formatter
         * @return ILogPublisher
         */
        public function addFormatter($id, ILogMessageFormatter $formatter);


        /**
         * @return void
         */
        public function terminate();

        /**
         * @return string
         */
        public function getName();

    }
}