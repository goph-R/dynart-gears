<?php

namespace Dynart\Gears\Controllers;

use Dynart\Minicore\Framework;
use Dynart\Minicore\Controller;

class OAuth extends Controller {

    public function login() {
        $config = Framework::instance()->get('config');
        $params = [
            'client_id' => $config->get('google.client_id'),
            'redirect_uri' => 'http://localhost/gears/admin/index.dev.php?route=/oauth/code-exchange',
            'response_type' => 'code',
            'scope' => 'profile email openid'
        ];
        $this->redirect('https://accounts.google.com/o/oauth2/v2/auth', $params);
        //$this->view->render(':app/login');
    }

    public function codeExchange() {
        $this->request->get('code');

    }

}