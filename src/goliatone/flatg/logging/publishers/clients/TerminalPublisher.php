<?php namespace goliatone\flatg\logging\publishers\clients {

    use goliatone\flatg\logging\core\LogMessage;

    class TerminalPublisher extends AbstractPublisher
    {
        /**
         * @inheritdoc
         */
        public function publish(LogMessage $message)
        {
            echo $message->getMessage();
        }

        /**
         * @inheritdoc
         */
        public function flush(LogMessage $message)
        {
            // TODO: Implement flush() method.
        }

    }
}