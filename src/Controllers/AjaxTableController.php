<?php

namespace Dynart\Gears\Controllers;

use Dynart\Minicore\Controller;

class AjaxTableController extends Controller {

    public function index() {        
        $this->renderContent(':app/ajax-table');
    }

}