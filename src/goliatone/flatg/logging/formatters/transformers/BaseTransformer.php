<?php namespace goliatone\flatg\logging\formatters\transformers {

    use goliatone\flatg\logging\helpers\Utils;
    use goliatone\flatg\logging\core\ILogMessageFormatTransformer;

    /**
     * Class BaseTransformer
     * @package goliatone\flatg\logging\formatters\transformers
     */
    class BaseTransformer implements ILogMessageFormatTransformer
    {
        /**
         * @var array
         */
        protected $_extraArguments = array();

        /**
         * @var null
         */
        protected $_type = null;

        /**
         * @var
         */
        protected $_delegated;

        /**
         * @param $type
         */
        public function __construct($type)
        {
            $this->_type = $type;
            $this->_delegated = 'transform';
        }

        /**
         * TODO: Move to IConfigurable.
         * TODO: Move to BaseObject.
         *
         * @param array $config
         * @throws \InvalidArgumentException
         */
        public function configure($config=array())
        {
            if(is_string($config))
            {
                if(!($args = func_get_args()))
                    throw new \InvalidArgumentException("Configure: If first param is string, second must be array!");
                $config = array($config=>func_get_args());
            }
            //TODO: Move to Utils?
            $getValue = function($value){
                if(!$value && $value !== FALSE) return null;
                return is_callable($value) ? call_user_func_array($value, func_get_args()) : $value;
            };

            //TODO: Move to Utils?
            $setValue = function($scope, $name, $value){
                if(is_callable(array($scope, $name))) return call_user_func(array($scope, $name), $value);
                if(property_exists($scope, $name))
                {
                    if(is_array($scope))       return $scope[$name] = $value;
                    else if(is_object($scope)) return $scope->$name = $value;
                }
            };

            foreach($config as $prop => $value)
            {
                $value = $getValue($value);
                $setValue($this, $prop, $value);
            }
        }

        /**
         * @return null
         */
        public function getType()
        {
            return $this->_type;
        }

        /**
         * @return mixed
         */
        public function getName()
        {
            return Utils::qualifiedClassName($this, false);
        }

        /**
         * @param $resource
         * @param $provider
         * @internal param array|null $options
         */
        public function transform($resource, $provider = NULL)
        {

        }

        /**
         * @param $resource
         * @param $provider
         * @return mixed
         */
        public function __invoke($resource, $provider)
        {
            $args = func_get_args();

            if(sizeof($args))
            {
                //Start at index 1 to the end, and preserve numeric array indices.
                $this->_extraArguments = array_slice($args, 2, NULL, TRUE);
            }

            return call_user_func_array(array($this, $this->_delegated), $args);
        }
    }
}