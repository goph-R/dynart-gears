<?php

namespace Dynart\Gears;

class GearsAdminApp extends GearsApp {

    public function __construct(array $configPaths) {
        parent::__construct($configPaths);
        $this->framework->add([
            'dashboard' => 'Controllers\Dashboard',

            /* just for testing, will be removed */
            'test'          => 'Controllers\Test',
            'testTable'     => 'Controllers\TestTable',
            'testTextTable' => 'Controllers\TestTextTable',
            'testQuery'     => 'Controllers\TestQuery'
        ],
            '\Dynart\Gears'
        );
    }

    public function init() {
        parent::init();
        $this->router->add([
            ['/', 'dashboard', 'index'],
            ['/dashboard', 'dashboard', 'ajaxIndex'],

            /* just for testing, will be removed */
            ['/test', 'test', 'index'],
            ['/test/process-form', 'test', 'processForm', ['POST']],
            ['/test/list', 'test', 'list']
        ]);
        $this->view->addFolder(':app', 'templates');
        $this->view->changePath(':user/login-layout', ':app/login-layout');
    }
}

