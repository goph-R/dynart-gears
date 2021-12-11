<?php

namespace Dynart\Gears\Controllers;

use Dynart\Minicore\Controller;
use Dynart\Minicore\Framework;

class Dashboard extends Controller {

    public function __construct() {
        parent::__construct();
        Framework::instance()->get('userService')->requireLogin();
    }

    public function index() {
        $this->render(':app/dashboard-index');
    }

    public function ajaxIndex() {
        $this->renderContent(':app/dashboard-index');
    }

}