<?php

namespace Dynart\Gears;

use Dynart\Minicore\Framework;
use Dynart\Minicore\Logger;

class ModuleService {

    /** @var Logger */
    private $logger;

    /** @var Framework */
    private $framework;

    /** @var ModuleTable */
    private $table;

    /** @var ModuleQuery */
    private $query;

    /**
     * @var Module[]
     */
    private $modules = [];

    public function __construct() {
        $this->framework = Framework::instance();
        $this->logger = $this->framework->get('logger');
        $this->table = $this->framework->get('moduleTable');
        $this->query = $this->framework->get('moduleQuery');
    }

    public function create() {
        $this->logger->info('ModuleService: Create modules.');
        $records = $this->query->find(null, [
            'active' => true,
            'no_limit' => true
        ]);
        foreach ($records as $record) {
            $name = $record->get('name');
            $id = $record->get('id');
            $namespace = '\Dynart\Gears\Modules\\'.$name.'\src';
            $className = $namespace.'\\'.$name.'Module';
            $this->modules[$id] = $this->framework->create($className);
            $this->logger->info("ModuleService: '$name' module has been created.");
        }
    }

    public function init() {
        $this->logger->info('ModuleService: Init modules.');
        foreach ($this->modules as $module) {
            $module->init();
            $this->logger->info("ModuleService: '".$module->getName()."' module has been initialized.");
        }
    }
}