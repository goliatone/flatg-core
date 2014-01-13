<?php namespace goliatone\events\core {


    use goliatone\flatg\logging\core\LogMessage;

    interface ILogPublisher
    {

        public function getName();

        public function publish(LogMessage $message);

        public function addFormatter(ILogMessageFormatter $formatter);


        public function terminate();

    }
}