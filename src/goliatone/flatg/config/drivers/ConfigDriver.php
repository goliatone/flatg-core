<?php namespace goliatone\flatg\config\drivers {


    /**
     * Class ConfigDriver
     * @package goliatone\flatg\config\drivers
     */
    class ConfigDriver implements IConfigDriver
    {

        /**
         * @var array
         */
        protected $drivers = array();

        /**
         * @var AbstractDriver
         */
        public $driver;

        /**
         * @var string
         */
        public $extension;

        /**
         *
         */
        public function __construct()
        {

            $this->registerDriver('xml',  'XmlConfigDriver');
            $this->registerDriver('php',  'PhpConfigDriver');
            $this->registerDriver('json', 'JsonConfigDriver');
            $this->registerDriver('ini',  'IniConfigDriver');
            $this->registerDriver('yml',  'YamlConfigDriver');
            $this->registerDriver('yaml', 'YamlConfigDriver');
        }

        /**
         * @param $extension
         * @param $driver
         */
        public function registerDriver($extension, $driver)
        {
            $this->drivers[$extension] = $driver;
        }


        public function import($filename)
        {
            $driver = $this->driverFromPath($filename);
            return $driver->import($filename);
        }


        public function save($path, $data)
        {
            $driver = $this->driverFromPath($path);
            return $driver->save($path, $data);
        }


        //TODO: This is ugly, how do we mange?
        public function load($content){}

        //TODO: This is ugly, how do we mange?
        public function format($content){}

        /**
         * @param $filename
         * @return mixed
         * @throws \InvalidArgumentException
         */
        protected function driverFromPath($filename)
        {
            $ext = pathinfo($filename, PATHINFO_EXTENSION);
            if($this->extension === $ext) return $this->driver;

            $this->extension = $ext;

            if(!array_key_exists($ext, $this->drivers))
                throw new \InvalidArgumentException("Extension {$ext} has no driver assigned.");

            $DriverClass = $this->drivers[$ext];
            $this->driver = new $DriverClass();

            return $this->driver;
        }
    }
}