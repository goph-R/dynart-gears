<?php

namespace Dynart\Gears\Controllers;

use Dynart\Minicore\Controller;

class Dashboard extends Controller {

    public function index() {
        if ($this->request->has('ajax')) {
            $this->renderContent(':app/dashboard-index');
        } else {
            $this->render(':app/dashboard-index');
        }
    }

}