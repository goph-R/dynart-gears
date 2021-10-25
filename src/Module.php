<?php

namespace Dynart\Gears;

use Dynart\Minicore\Framework;
use Dynart\Minicore\Config;

abstract class Module {

    protected $name = '';

    /** @var Framework */
    protected $framework;

    /** @var Config */
    protected $config;

    /** @var GearsApp */
    protected $app;

    public function __construct() {
        $this->framework = Framework::instance();
        $this->config = $this->framework->get('config');
    }

    public function getName() {
        return $this->name;
    }

    public function init() {}

    public function getFolder() {
        return $this->config->get(GearsApp::CONFIG_MODULES_FOLDER).'/'.$this->getName();
    }

}