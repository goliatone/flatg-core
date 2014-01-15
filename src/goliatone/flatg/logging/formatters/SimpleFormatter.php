<?php namespace goliatone\flatg\logging\formatters {

    use goliatone\flatg\logging\core\ILogMessageFormatter;
    use goliatone\flatg\logging\core\LogMessage;
    use goliatone\flatg\logging\formatters\transformers\TransformManager;
    use goliatone\flatg\logging\helpers\Utils;

    class SimpleFormatter implements ILogMessageFormatter
    {
        public $pattern = "[{timestamp}] {level}: {message}\n";

        public $consumeUnusedTokens = true;

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
            $transformer = new TransformManager();
            $context = $transformer->parseContext($message->getContext());

            $message->consumeUnusedTokens = $this->consumeUnusedTokens;
            $message->updateMessage();

            $text = Utils::stringTemplate($this->pattern,
                                          $context,
                                          $this->consumeUnusedTokens
                                         );
            $message->setMessage($text);
            return $message;
        }


    }
}