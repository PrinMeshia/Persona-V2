<?php
namespace Framework\Lib\Factories;

use Psr\Container\ContainerInterface;
use Framework\Lib\Database\PdoDB;

class PdoFactory {
    public function __invoke(ContainerInterface $container):PdoDB
    {
        return new PdoDB($container->get('config.database'));
    }
}