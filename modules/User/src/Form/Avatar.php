<?php

namespace Dynart\Gears\Modules\User\src\Form;

use Dynart\Gears\Modules\User\src\Form\Input\CurrentAvatar;
use Dynart\Minicore\Database\Record;
use Dynart\Minicore\Form\Form;

use Dynart\Gears\Modules\User\src\UserService;

class Avatar extends Form {

    public function __construct(string $name='avatar') {
        parent::__construct($name);
        $this->addInput('file', text('user', 'avatar_upload'),
            ['File']
        );
        $currentAvatarInput = $this->addInput('current', '',
            ['CurrentAvatar', ]
        );
        $currentAvatarInput->setRequired(false);
        $this->addInput('user', '',
            ['Submit', text('user', 'save_avatar')]
        );

    }

    public function init(UserService $userService, Record $user) {
        $params = [
            'size' => $userService->getAvatarSize(),
            'max' => round($userService->getAvatarMaxFileSize() / 1024 / 1024)
        ];
        $fileInput = $this->getInput('file');
        $fileInput->setDescription(text('user', 'avatar_upload_description', $params));
        /** @var CurrentAvatar $currentAvatarInput */
        $currentAvatarInput = $this->getInput('current');
        $currentAvatarInput->setValue($user->get('id'));
    }

}