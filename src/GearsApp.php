<?php

namespace Dynart\Gears;

use Dynart\Minicore\App;

class GearsApp extends App {

    public function __construct($env, $configPath) {
        parent::__construct($env, $configPath);
        $ns = 'Dynart\Gears\\';
        $this->framework->add([
            'dashboardController' => $ns.'Controllers\DashboardController',
            'ajaxTableController' => $ns.'Controllers\AjaxTableController',
        ]);
    }

    public function init() {
        parent::init();
        $this->router->add([
            ['', 'dashboardController', 'index'],
            ['ajax-table', 'ajaxTableController', 'index']
        ]);
        $this->view->addFolder(':app', 'templates/');
    }

}