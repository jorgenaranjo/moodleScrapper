<?php
/**
 * Created by PhpStorm.
 * User: isaac
 * Date: 6/09/15
 * Time: 09:06 PM
 */
use Aura\Autoload\Loader;

$loader = new Loader();
$loader->register();
$loader->addPrefix('Crayon', dirname(__FILE__));