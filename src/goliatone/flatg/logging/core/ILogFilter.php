<?php namespace goliatone\flatg\logging\core {


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
     * Filters should apply at the logger
     * level, and filter by package.
     *
     * Filters should apply at the publisher
     * level, and filter by different
     * LogLevel properties criteria.
     *
     * 1 - Context wide Filters are set up in
     * the configuration file.
     * Events filtered at this point will
     * be discarded.
     *
     * 2 - Logger Filters are configured on
     * a specific logger instance. This
     * are evaluated after the Context wide
     * filters, and the LogLevel filters.
     * Events filtered at this point will
     * be discarded.
     *
     * 3 - Publisher filters determine if
     * a specific publisher should handle
     * an event.
     *
     * 4 - Publisher reference filters
     * are used at the logger level to
     * determinate if a publisher should
     * handle a given event.
     *
     * ThrottleFilter: Keeps count of events
     * and after a number, it will discard and
     * then restart.
     *
     * ThresholdFilter: Keeps count of events
     * and it wont log them until a certain
     * number of event have occurred.
     *
     * PackageFilter: Based on the package
     * aka namespace of the logger.
     *
     * LevelFilter: Based on the LogLevel.
     *
     * RegexFilter: Filters based on a regular
     * expression against the message.
     *
     *
     * TODO: There should be an IFilterable
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
         * TODO: Rename to, filter, duh!
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