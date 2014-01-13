<?php namespace goliatone\events\core {


    use goliatone\flatg\logging\core\LogMessage;

    interface ILogMessageFormatter
    {

        public function format(LogMessage $message);
    }
}