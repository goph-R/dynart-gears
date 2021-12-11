<?php

namespace Dynart\Gears\Modules\User\src\Controller;

use Dynart\Gears\Modules\User\src\UserService;

use Dynart\Minicore\Form\Form;
use Dynart\Minicore\Framework;
use Dynart\Minicore\Controller;
use Dynart\Minicore\UploadedFile;

class Avatar extends Controller {
    
    /** @var UserService */
    protected $userService;

    /** @var \Dynart\Gears\Modules\User\src\Form\Avatar */
    protected $avatarForm;
    
    public function __construct() {
        parent::__construct();
        $framework = Framework::instance();
        $this->userService = $framework->get('userService');
        $this->avatarForm = $framework->get('userAvatarForm');
    }    

    public function index() {
        $this->userService->requireLogin();
        $this->avatarForm->init($this->userService, $this->userService->getCurrentUser());
        $this->saveAvatar($this->avatarForm);
        $this->view->set(['userService' => $this->userService]);
        $this->render(':user/settings', [
            'form' => $this->avatarForm,
            'active' => 'avatar',
            'action' => route_url('/settings/avatar')
        ]);        
    }
    
    public function remove() {
        $this->userService->requireLogin();
        $this->userService->removeAvatar($this->userService->getCurrentUser());
        $this->redirect('/settings/avatar');
    }
    
    public function saveAvatar(Form $form) {
        if (!$form->process()) {
            return;
        }
                
        // TODO: put this part in a Validator
        /** @var UploadedFile $file */
        $file = $form->getValue('file');
        $error = null;
        if (!$file->isUploaded()) {
            $error = $this->getMessage('error', 'upload_was_unsuccessful');
        } else if ($file->getSize() > $this->userService->getAvatarMaxFileSize() || in_array($file->getError(), [UPLOAD_ERR_INI_SIZE, UPLOAD_ERR_FORM_SIZE])) {
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
        $user = $this->userService->getCurrentUser();
        $this->userService->changeAvatar($user, $file->getTempPath());
    }
    
    private function getMessage($type, $text) {
        return [
            'type' => $type,
            'text' => text('user', $text)
        ];
    }    
    
}