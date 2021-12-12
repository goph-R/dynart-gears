<?php

namespace Dynart\Gears\Modules\User\src\Form\Input;

use Dynart\Minicore\Form\Input;
use Dynart\Minicore\Framework;
use Dynart\Minicore\View;

use Dynart\Gears\Module;
use Dynart\Gears\Modules\User\src\AvatarService;
use Dynart\Gears\Modules\User\src\UserService;

class CurrentAvatar extends Input {

    /** @var AvatarService */
    protected $avatarService;

    /** @var UserService */
    protected $userService;
    
    /** @var View */
    protected $view;

    /** @var Module */
    protected $module;

    protected $bind = false;

    public function __construct($name, $defaultValue='') {
        parent::__construct($name, $defaultValue);
        $framework = Framework::instance();
        $this->avatarService = $framework->get('avatarService');
        $this->userService = $framework->get('userService');
        $this->view = $framework->get('view');
        //$app = $framework->get('app');
        //$this->module = $app->getModule('minicore-users');
    }
    
    public function fetch() {
        $user = $this->userService->findById($this->getValue());
        if (!$user) {
            return '';
        }
        $url = $this->avatarService->getUrl($user);
        $img = '<img src="'.$url.'" alt="Avatar">';
        $link = '';
        if ($this->avatarService->hasAvatar($user)) {
            $removeUrl = route_url('/settings/remove-avatar');
            $icon = '<i class="fas fa-trash" style="margin-right: 0.4rem"></i>';
            $text = text('user', 'remove_avatar');
            $link = '<p style="margin-bottom: 1rem"><a id="avatar_remove_link" href="'.$removeUrl.'">'.$icon.$text.'</a></p>';
        }

        //$this->view->addScript($this->module->getUrl().'static/current-avatar-input.js');
        $this->view->startBlock('scripts');
        $this->view->write('<script>createCurrentAvatarInput("'.text('user', 'confirm_remove').'")</script>');
        $this->view->endBlock();
        return $img.$link;
    }
    
}
