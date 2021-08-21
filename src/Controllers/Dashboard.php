<?php

namespace Dynart\Gears\Controllers;

use Dynart\Minicore\Controller;

class Dashboard extends Controller {

    public function index() {
        $this->render(':app/dashboard-index');
    }

    public function ajaxIndex() {
        $this->renderContent(':app/dashboard-index');
    }

}