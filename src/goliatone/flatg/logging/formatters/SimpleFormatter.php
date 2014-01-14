<?php namespace goliatone\flatg\logging\formatters {

    use goliatone\flatg\logging\core\ILogMessageFormatter;
    use goliatone\flatg\logging\core\LogMessage;

    class SimpleFormatter implements ILogMessageFormatter
    {
        public $pattern = "[{timestamp}] {level} - {message}\n";

        /**
         * TODO: Maybe take in a string + array? Make it generic enough
         * to reuse on other contexts without having to implement interface.
         * TODO: We need a `dump` method, that handles object => string conversion.
         *       We can have a basic formatter that handles that and then we override
         *       or just use an utility.
         *
         * @param LogMessage $message
         * @return LogMessage
         */
        public function format(LogMessage $message)
        {
            //TODO: There should be a better way to handle this?
            //seems a bit wasteful. But, we do have to set the message after interpolation
            //so maybe we should keep the first round on LogMessage, do simple interpolation
            //then here is another interpolation about the actual display:
            //LogMessage: message = The class {class} could not be found. Use {alt} instead.
            //Log entry: [{timestamp}] {logLevel}: {message}. {extras}.\n
            $text = $this->_interpolate($message->getMessage(), $message->getContext());
            $message->setMessage($text);

            $text = $this->_interpolate($this->pattern, $message->getContext());

            $message->setMessage($text);

            return $message;
        }

        /**
         * Interpolate log message
         * @param  mixed     $message               The log message
         * @param  array     $context               An array of placeholder values
         * @return string    The processed string
         */
        protected function _interpolate($message, $context = array())
        {
            $replace = array();
            foreach ($context as $key => $value) {
                $replace['{' . $key . '}'] = $value;
            }
            return strtr($message, $replace);
        }
    }
}