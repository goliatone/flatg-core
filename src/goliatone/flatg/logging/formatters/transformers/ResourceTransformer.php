<?php namespace goliatone\flatg\logging\formatters\transformers {


    class ResourceTransformer extends BaseTransformer
    {

        public function transform($resource, $provider)
        {
            return $provider->transform(stream_get_meta_data($resource), $provider);
        }
    }
}