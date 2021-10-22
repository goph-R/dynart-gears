<?php

namespace Dynart\Gears;

use Dynart\Minicore\App;

class GearsApp extends App {

    public function __construct(array $configPaths) {
        parent::__construct($configPaths);
        $this->framework->add([
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
    }

}