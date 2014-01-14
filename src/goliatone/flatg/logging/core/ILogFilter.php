<?php namespace goliatone\flatg\logging\core {

    use goliatone\flatg\logging\core\LogMessage;

    /**
     * Filters provide secondary control
     * over what `LogMessage` get logged,
     * beyond the control that is provided
     * by the ILogLevel.
     * They can be applied at two different
     * moments of the publishing process;
     * before the message is compiled, and
     * after the message has been compiled.
     *
     *
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

        /**
         * Returns `TRUE` if the filter
         * is to be applied as a pre process.
         *
         * Defaults to `FALSE`
         *
         * @return bool
         */
        public function isPreProcess();


        /**
         * @return ILogFilter
         */
        public function getParent();

        /**
         * @param  LogLevel $level
         * @return mixed
         */
        public function setLevel(LogLevel $level);

        /**
         * @return ILogLevel
         */
        public function getLevel();


        /**
         * Defaults to NULL, thus not
         * filtering any namespace.
         *
         * @return string
         */
        public function getNamespace();

    }
}