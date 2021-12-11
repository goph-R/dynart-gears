<?php

namespace Dynart\Gears;

use Dynart\Minicore\App;

abstract class GearsApp extends App {

    const CONFIG_MODULES_FOLDER = 'app.modules_folder';
    const CONFIG_MODULES_URL = 'app.modules_url';

    public function __construct(array $configPaths) {
        parent::__construct($configPaths);
        $this->framework->add([
            'moduleTable'   => 'ModuleTable',
            'moduleQuery'   => 'ModuleQuery',
            'moduleService' => 'ModuleService',
        ],
            '\Dynart\Gears'
        );
    }

    public function init() {
        /** @var ModuleService $modules */
        $modules = $this->framework->get('moduleService');
        $modules->create();
        parent::init();
        $modules->init();
    }

    public function getModuleFolder(string $name, string $path) {
        return $this->getFullPath($this->config->get(self::CONFIG_MODULES_FOLDER)).'/'.$name.$path;
    }

    // TODO: nearly duplicated from App::getStaticUrl .. do it from one place
    public function getModuleUrl(string $name, string $path, bool $useTimestamp=true) {
        if ($this->isStartWithHttp($path)) {
            return $path;
        }
        if ($useTimestamp) {
            $folder = $this->config->get(self::CONFIG_MODULES_FOLDER).'/'.$name;
            $filePath = $this->getFullPath($folder.$path);
            $path .= '?'.filemtime($filePath);
        }
        $url = $this->config->get(self::CONFIG_MODULES_URL).'/'.$name;
        return $this->getFullUrl($url).$path;
    }

}