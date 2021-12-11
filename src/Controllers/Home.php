<?php

namespace Dynart\Gears\Controllers;

use Dynart\Minicore\Controller;

class Home extends Controller {

    public function index() {
        $this->json("hello");
    }

}