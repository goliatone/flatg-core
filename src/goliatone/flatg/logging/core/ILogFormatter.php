<?php namespace goliatone\events\core {

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
    interface ILogFormatter
    {

        /**
         * @param LogMessage $message
         * @return mixed
         */
        public function format(LogMessage $message);

    }
}