<?php

namespace Dynart\Gears\Modules\User\src\Form\Validator;

use Dynart\Minicore\Form\Form;
use Dynart\Minicore\Form\Validator;

class Same extends Validator {

    /** @var Form */
    private $form;
    private $otherInputName;

    public function __construct() {
        parent::__construct();
        $this->message = text('user', 'didnt_match');
    }

    public function setOtherInput(Form $form, string $otherInputName) {
        $this->otherInputName = $otherInputName;
        $this->form = $form;
    }

    public function doValidate($value) {
        if ($this->form->getValue($this->otherInputName) != $value) {
            return false;
        }
        return true;
    }

}