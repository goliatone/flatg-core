<?php namespace goliatone\flatg\logging\formatters\transformers {

    use goliatone\flatg\logging\helpers\Utils;

    class ObjectTransformer extends BaseTransformer
    {

        public function transform($object, $provider)
        {
            $fullyQualifiedClassName = Utils::fullyQualifiedClassName($object);
            $handler = $provider->getHandler($fullyQualifiedClassName);
            echo $fullyQualifiedClassName;

            return $handler->transform($fullyQualifiedClassName, $provider);
        }
    }
}