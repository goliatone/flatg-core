<?php namespace goliatone\flatg\logging\formatters\transformers {


    use goliatone\flatg\logging\helpers\Utils;

    class DateTimeTransformer extends BaseTransformer
    {
        public function transform($resource, $provider)
        {
            $format = Utils::ISO8601;
            return $resource->format($format);
        }
    }
}