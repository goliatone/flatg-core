<?php namespace goliatone\flatg\logging\formatters\transformers {

    use goliatone\flatg\logging\helpers\Utils;

    class BaseTransformer
    {
        protected $_extraArguments = array();

        public function getName()
        {
            return Utils::qualifiedClassName($this, false);
        }

        public function transform($resource, $provider)
        {

        }

        public function __invoke($resource, $provider)
        {
            $args = func_get_args();

            if(sizeof($args))
            {
                //Start at index 1 to the end, and preserve numeric array indices.
                $this->_extraArguments = array_slice($args, 2, NULL, TRUE);
            }


            call_user_func_array(array($this, 'transform'), $args);

        }
    }
}