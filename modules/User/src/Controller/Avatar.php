<?php

namespace Dynart\Gears\Modules\User\src\Controller;

use Dynart\Minicore\Form\Form;
use Dynart\Minicore\Framework;
use Dynart\Minicore\Controller;
use Dynart\Minicore\UploadedFile;

use Dynart\Gears\Modules\User\src\UserService;
use Dynart\Gears\Modules\User\src\AvatarService;
use Dynart\Gears\Modules\User\src\Form\Avatar as AvatarForm;


class Avatar extends Controller {
    
    /** @var AvatarService */
    protected $avatarService;

    /** @var UserService */
    protected $userService;

    /** @var AvatarForm */
    protected $avatarForm;
    
    public function __construct() {
        parent::__construct();
        $framework = Framework::instance();
        $this->userService = $framework->get('userService');
        $this->userService->requireLogin('/user-settings/avatar');
        $this->avatarService = $framework->get('avatarService');
        $this->avatarForm = $framework->get('avatarForm');
    }    

    public function index() {
        $this->avatarForm->init($this->avatarService, $this->userService->getCurrentId());
        $this->saveAvatar($this->avatarForm);
        $this->render(':user/settings', [
            'form' => $this->avatarForm,
            'active' => 'avatar',
            'action' => route_url('/user-settings/avatar')
        ]);        
    }
    
    public function remove() {
        $this->avatarService->remove($this->userService->getCurrentUser());
        $this->redirect('/user-settings/avatar');
    }
    
    protected function saveAvatar(Form $form) {
        if (!$form->process()) {
            return;
        }
                
        // TODO: put this part in a Validator
        /** @var UploadedFile $file */
        $file = $form->getValue('file');
        $error = null;
        if (!$file->isUploaded()) {
            $error = $this->getMessage('error', 'upload_was_unsuccessful');
        } else if ($file->getSize() > $this->avatarService->getMaxFileSize() || in_array($file->getError(), [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE])) {
            $error = $this->getMessage('error', 'file_too_big');
        } else if ($file->getError() != UPLOAD_ERR_OK) {
            $error = $this->getMessage('error', 'upload_was_unsuccessful');
        } else if ($file->getType() != 'image/jpeg' || !getimagesize($file->getTempPath())) {
            $error = $this->getMessage('error', 'uploaded_file_not_image');
        }
        if ($error) {
            //$this->session->setFlash('settings_messages', [$error]);
            return;
        }
        $this->avatarService->change($this->userService->getCurrentUser(), $file->getTempPath());
        $this->userService->saveCurrentUser();
    }
    
    protected function getMessage($type, $text) {
        return [
            'type' => $type,
            'text' => text('user', $text)
        ];
    }    
    
}