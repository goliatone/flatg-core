<?php namespace goliatone\flatg\logging\formatters {

    use goliatone\flatg\logging\core\ILogMessageFormatter;
    use goliatone\flatg\logging\core\LogMessage;
    use goliatone\flatg\logging\formatters\transformers\TransformManager;
    use goliatone\flatg\logging\helpers\Utils;

    class SimpleFormatter implements ILogMessageFormatter
    {
        public $pattern = "[{timestamp}] {padded_level}: {message}\n";

        public $consumeUnusedTokens = true;

        public $transformer;

        public function __construct()
        {
            $this->transformer = new TransformManager();
            $this->transformer->configHandler("DateTime", array('format'=>'H:i:s'));
        }

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
            $context = $message->getContext();
            //We pad level so that msg is nicely aligned. 9 = strlen('EMERGENCY');
            $context['padded_level'] = str_pad($message->getLevel(), 9, " ", STR_PAD_LEFT);



            $context = $this->transformer->parseContext($context);

            $message->consumeUnusedTokens = $this->consumeUnusedTokens;
            $text = Utils::stringTemplate($this->pattern,
                                          $context,
                                          $this->consumeUnusedTokens
                                         );
            $message->setMessage($text);
            $message->updateMessage();

            return $message;
        }


    }
}