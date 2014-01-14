<?php namespace goliatone\flatg\logging\core {


    /**
     * Interface ILoggerFactory
     * @package goliatone\flatg\logging\core
     */
    interface ILoggerFactory
    {
        /**
         * Retrieves a logger for the specified class.
         * If `forItem` is a string, that string is used
         * as the class name, regardless of what it is.
         * For any other
         * object, the class type of that object is used.
         * Usually we pass a Class definition, but can
         * use an instance as well.
         *
         * If this method is called multiple times for
         * the same `forItem`, the same logger instance
         * will be returned rather than creating a new one.
         *
         * @param  mixed $forItem
         * @return ILogger
         */
        public function setLogger( $forItem);
    }
}