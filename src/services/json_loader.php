<?php
namespace app;

use Monolog\Logger;
use Pimple\Container;

class json_loader
{
    protected $container;
    protected $logger;

    /**
     * json_loader constructor.
     * @param $container Container
     * @param $logger Logger
     */
    public function __construct(Container $container, Logger $logger)
    {
        $this->container = $container;
        $this->logger = $logger;
    }

    public function load($url)
    {
        $data_file = file_get_contents($url);
        if ($data_file === false) {
            $this->logger->err('Could not find JSON file at: ' . $url);
        }

        $data = json_decode($data_file);
        if (empty($data)) {
            $this->logger->err('Could not decode JSON file at: ' . $url);
        }

        return $data;
    }

    public function save($url, $data)
    {
        $data_file = json_encode($data);
        file_put_contents($url, $data_file);
    }
}