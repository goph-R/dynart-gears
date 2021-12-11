<?php

namespace Dynart\Gears;

class GearsFrontApp extends GearsApp {


    public function __construct(array $configPaths) {
        parent::__construct($configPaths);
        $this->framework->add([
            'home'          => 'Controllers\Home',
        ],
            '\Dynart\Gears'
        );
    }

    public function init() {
        parent::init();
        $this->router->add([
            ['/', 'home', 'index']
        ]);
        $this->view->addFolder(':app', 'themes/default/templates'); // TODO: load path from settings
    }

}