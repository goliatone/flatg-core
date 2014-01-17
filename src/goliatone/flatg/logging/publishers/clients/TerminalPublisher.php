<?php namespace goliatone\flatg\logging\publishers\clients {

    use goliatone\flatg\logging\core\LogMessage;


    class TerminalPublisher extends AbstractPublisher
    {
        /**
         * @inheritdoc
         */
        public function publish(LogMessage $message)
        {
            $this->applyFormat($message);
            $this->flush($message);
        }

        /**
         * @inheritdoc
         */
        public function flush(LogMessage $message)
        {
            // Terminal does not need delayed flushing :P
            echo $message->getMessage();
        }

    }
}