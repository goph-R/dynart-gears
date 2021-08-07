<?php

namespace Dynart\Gears;

use Dynart\Minicore\App;

class GearsApp extends App {

    public function __construct($env, $configPath) {
        parent::__construct($env, $configPath);
        $this->framework->add([
            'dashboard' => 'Controllers\Dashboard',
            'ajaxTable' => 'Controllers\AjaxTable',
        ],
            '\Dynart\Gears'
        );
    }

    public function init() {
        parent::init();
        $this->router->add([
            ['', 'dashboard', 'index'],
            ['ajax-table', 'ajaxTable', 'index'],
            ['ajax-table/process-form', 'ajaxTable', 'processForm', ['POST']],
        ]);
        $this->view->addFolder(':app', 'templates/');
    }

}