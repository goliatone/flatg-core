<?php namespace goliatone\flatg\logging\core {


    use goliatone\flatg\logging\core\LogMessage;

    /**
     * A formatter defines the text presentation
     * of a `LogMessage`.
     * `ILogPublisher` are associated
     * with one or more `ILogFormatter`.
     *
     * Interface ILogFormatter
     * @package goliatone\events\core
     */
    interface ILogMessageFormatter
    {

        /**
         * @param LogMessage $message
         * @return LogMessage
         */
        public function format(LogMessage $message);
    }
}