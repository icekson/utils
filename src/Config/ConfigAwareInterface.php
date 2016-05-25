<?php
/**
 * Created by PhpStorm.
 * User: Alexey
 * Date: 21.11.2015
 * Time: 13:31
 */

namespace Icekson\Config;


use Icekson\Config\ConfigureInterface;

interface ConfigAwareInterface
{
    /**
     * @return ConfigureInterface
     */
    public function getConfiguration();

    public function setConfiguration(ConfigureInterface $config);

}