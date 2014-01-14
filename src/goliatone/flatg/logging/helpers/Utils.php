<?php namespace goliatone\flatg\logging\helpers {

    class Utils
    {

        /**
         * DateTime format ISO8601 compatible
         * with JS construct.
         */
        const ISO8601 = 'Y-m-d\TH:i:sP';

        static public function qualifiedClassName($object, $fullyQualified=TRUE, $glue='.')
        {
            $name      = get_class($object);
            $className = str_replace("\\", $glue, $name);

            if($fullyQualified) return $className;

            if (preg_match('@\\\\([\w]+)$@', $name, $matches))
                $className = $matches[1];

            return $className;
        }
    }
}