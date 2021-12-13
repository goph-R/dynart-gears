<?php

namespace Dynart\Gears\Modules\User\src;

use Dynart\Minicore\Config;
use Dynart\Minicore\Database\Record;
use Dynart\Minicore\Framework;

use Dynart\Gears\GearsApp;

class AvatarService {

    /** @var Config */
    protected $config;

    /** @var GearsApp */
    protected $app;

    public function __construct() {
        $framework = Framework::instance();
        $this->config = $framework->get('config');
        $this->app = $framework->get('app');
    }

    public function getSize() {
        return $this->config->get(UserModule::CONFIG_AVATAR_SIZE, UserModule::DEFAULT_AVATAR_SIZE);
    }

    public function getMaxFileSize() {
        return $this->config->get(UserModule::CONFIG_AVATAR_MAX_FILE_SIZE, UserModule::DEFAULT_AVATAR_MAX_FILE_SIZE);
    }

    public function getQuality() {
        return $this->config->get(UserModule::CONFIG_AVATAR_QUALITY, UserModule::DEFAULT_AVATAR_QUALITY);
    }

    public function change(Record $user, $srcPath) {
        $this->remove($user);
        do {
            try {
                $user->set('avatar', bin2hex(random_bytes(16)));
            } catch (\Exception $ignore) {}
        } while ($this->hasAvatar($user));
        $path = $this->getPath($user);
        $dir = dirname($path);
        if (!file_exists($dir)) {
            mkdir($dir, 0755, true);
        }
        $srcSize = getimagesize($srcPath);
        $src = imagecreatefromjpeg($srcPath);
        $srcX = 0;
        $srcY = 0;
        $srcW = $srcSize[0];
        $srcH = $srcSize[1];
        $destSize = $this->getSize();
        $dest = imagecreatetruecolor($destSize, $destSize);
        if ($srcW > $srcH) {
            $srcX = ($srcW - $srcH) / 2;
            $srcW = $srcH;
        } else {
            $srcY = ($srcH - $srcW) / 2;
            $srcH = $srcW;
        }
        imagecopyresampled($dest, $src, 0, 0, $srcX, $srcY, $destSize, $destSize, $srcW, $srcH);
        imagejpeg($dest, $path, $this->getQuality());
        imagedestroy($dest);
        imagedestroy($src);
    }

    public function remove(Record $user) {
        if (!$this->hasAvatar($user)) {
            return;
        }
        unlink($this->getPath($user));
    }

    public function getPath(Record $user) {
        $avatar = $user->get('avatar');
        $prefix = $this->getPrefix($avatar);
        return $this->app->getMediaPath('/avatar/'.$prefix.$avatar.'.jpg');
    }

    private function getPrefix($avatar) {
        $prefix = '';
        if ($avatar) {
            $prefix = $avatar[0].$avatar[1].'/'.$avatar[2].$avatar[3].'/';
        }
        return $prefix;
    }

    public function hasAvatar(Record $user) {
        return file_exists($this->getPath($user));
    }

    public function getUrl(Record $user) {
        if (!$this->hasAvatar($user)) {
            return $this->app->getModuleUrl('User', '/static/default-avatar.png');
        }

        $avatar = $user->get('avatar');
        $prefix = $this->getPrefix($avatar);
        return $this->app->getMediaUrl('/avatar/'.$prefix.$avatar.'.jpg');
    }
}