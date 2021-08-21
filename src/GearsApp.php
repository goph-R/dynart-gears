<?php

namespace Dynart\Gears;

use Dynart\Minicore\App;

class GearsApp extends App {

    public function __construct(array $configPaths) {
        parent::__construct($configPaths);
        $this->framework->add([
            'dashboard' => 'Controllers\Dashboard',
            'test' => 'Controllers\Test',
        ],
            '\Dynart\Gears'
        );
    }

    public function init() {
        parent::init();
        $this->router->add([
            ['/', 'dashboard', 'index'],
            ['/dashboard', 'dashboard', 'ajaxIndex'],
            ['/test', 'test', 'index'],
            ['/test/process-form', 'test', 'processForm', ['POST']],
        ]);
        $this->view->addFolder(':app', 'templates');
    }

}