<?php namespace goliatone\events\core {

    use goliatone\flatg\logging\core\LogMessage;

    /**
     * Filters provide secondary control
     * over what `LogMessage` get logged,
     * beyond the control that is provided
     * by the ILogLevel.
     *
     * Interface ILogFilter
     * @package goliatone\events\core
     */
    interface ILogFilter
    {

        /**
         * Returns TRUE if the filter can
         * be published. If the LogMessage
         * belongs to a `namespace` that is
         * disabled, then we can `filter` else
         * we collect it.
         *
         * @param  LogMessage $message
         * @return bool
         */
        public function collect(LogMessage $message);

    }
}