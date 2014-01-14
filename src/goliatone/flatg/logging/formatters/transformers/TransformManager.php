<?php namespace goliatone\flatg\logging\formatters\transformers {

    //We do register defaults, but we should be able to do it from outside.

    use goliatone\flatg\logging\formatters\transformers\ArrayTransformer;
    use goliatone\flatg\logging\formatters\transformers\ObjectTransformer;
    use goliatone\flatg\logging\formatters\transformers\ResourceTransformer;

    /**
     * Handles basic string conversion of different types:
     *  "boolean"
     *  "integer"
     *  "double"
     *  "string"
     *  "array"
     *  "object"
     *  "resource"
     *  "NULL"
     *  "unknown type"
     *
     * Class TransformManager
     * @package goliatone\flatg\logging\formatters\transformers
     */
    class TransformManager extends BaseTransformer
    {
        protected $_handlers = array();

        public function __construct($config=array())
        {
            $this->initialize($config);
        }

        public function initialize($config = array())
        {
            $this->registerDefaultHandlers();

        }

        public function registerDefaultHandlers()
        {
            $this->register('double',  function($value){return sprintf("%f", $value);});
            $this->register('integer', function($value){return sprintf("%d", $value);});

            $this->register('string',  function($value){ return $value;});
            $this->register('boolean', function($value){ return $value === TRUE ? "TRUE" : "FALSE";});
            $this->register('NULL',    function(){ return "NULL";});
            $this->register('unknown type', function(){ return "unknown type";});

//            $this->register('array',    $this->wrapClass('goliatone\\flatg\\logging\\formatters\\transformers\\ArrayTransformer'));
//            $this->register('object',   $this->wrapClass('goliatone\\flatg\\logging\\formatters\\transformers\\ObjectTransformer'));
//            $this->register('resource', $this->wrapClass('goliatone\\flatg\\logging\\formatters\\transformers\\ResourceTransformer'));
//            $this->register('DateTime', $this->wrapClass('goliatone\\flatg\\logging\\formatters\\transformers\\DateTimeTransformer'));

            $this->register('default', function($value){return print_r($value, TRUE);});
        }

        //TODO: Use __invoke on BaseTransformer...
        protected function wrapClass($Class)
        {
            return function($value, $provider) use($Class){
                $handler = new $Class();
                return $handler->transform($value, $provider);
            };
        }

        /**
         * @param $type
         * @param callable|Class $handler
         * @throws \InvalidArgumentException
         * @return $this
         */
        public function register($type, $handler)
        {
            if(!is_callable($handler)) throw new \InvalidArgumentException("Provided handler for {$type} has to be callable");
            //We overwrite other handlers.
            $this->_handlers[$type] = $handler;
            return $this;
        }

        /**
         * @param $value
         * @param null $provider
         * @return mixed|string|void
         */
        public function transform($value, $provider = NULL)
        {
            //If we did not get handled a provider, we are root.
            $provider || ($provider = $this);

            $type = gettype($value);

            $handler = $this->getHandler($type);

            try {
                $value = @call_user_func($handler, $value, $provider);
            } catch(\Exception $e) {
                $value = $e->getMessage();
                print $value;
            }

            return $value;
        }


        public function hasHandler($type)
        {
            return array_key_exists($type, $this->_handlers);
        }


        public function getHandler($type, $default = 'default')
        {
            if(! $this->hasHandler($type)) $type = $default;

            return $this->_handlers[$type];
        }


    }
}