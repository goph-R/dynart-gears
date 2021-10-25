<?php

namespace Dynart\Gears;

use Dynart\Minicore\App;

class GearsApp extends App {

    const CONFIG_MODULES_FOLDER = 'app.modules_folder';

    public function __construct(array $configPaths) {
        parent::__construct($configPaths);
        $this->framework->add([

            'moduleTable'          => 'ModuleTable',
            'moduleQuery'          => 'ModuleQuery',
            'moduleService'        => 'ModuleService',

            'dashboard'            => 'Controllers\Dashboard',
            'test'                 => 'Controllers\Test',
            'oauth'                => 'Controllers\OAuth',
            'testTable'            => 'Controllers\TestTable',
            'testTranslationTable' => 'Controllers\TestTranslationTable',
            'testQuery'            => 'Controllers\TestQuery'

        ],
            '\Dynart\Gears'
        );
    }

    public function init() {

        $modules = $this->framework->get('moduleService');
        $modules->create();

        parent::init();

        $this->router->add([
            ['/', 'dashboard', 'index'],
            ['/dashboard', 'dashboard', 'ajaxIndex'],
            ['/test', 'test', 'index'],
            ['/test/process-form', 'test', 'processForm', ['POST']],
            ['/test/list', 'test', 'list'],
            ['/login', 'oauth', 'login'],
            ['/oauth/code-exchange', 'oauth', 'codeExchange'],
        ]);
        $this->view->addFolder(':app', 'templates');

        $modules->init();
    }

}