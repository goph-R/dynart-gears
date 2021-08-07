<?php

namespace Dynart\Gears\Controllers;

use Dynart\Minicore\Controller;
use Dynart\Minicore\Form\Form;

class AjaxTable extends Controller {

    public function index() {
        $this->renderContent(':app/ajax', [
            'form' => $this->createForm()
        ]);
    }

    public function processForm() {

        $form = $this->createForm();
        $values = [];
        $errors = [];
        $status = 'error';

        if ($form->processInput()) {
            $values = $form->getValues();
            
            // ..
            $status = 'ok';
        } else {
            $errors = $form->getInputErrors();
        }
        $this->json([
            'status'     => $status,
            'formErrors' => $form->getErrors(),
            'errors'     => $errors
        ]);

    }

    private function createForm() {
        
        $form = new Form('test');

        $form->addInput('Email', ['name1', 'Text', 'Value']);
        $form->addValidator('name1', 'Email');
        
        $form->addInput('', [
            'name2', 'Checkbox', 'Value', 'Label for checkbox', true // is checked?
        ]);
        
        $form->addInput('Checkbox group', [
            'name3', 'CheckboxGroup', [
                'a' => 'Option A.',
                'b' => 'Option B.'
            ],            
            ['b']
        ]);
        
        $file = $form->addInput('File', [
            'name4', 'File'
        ]);
        $file->setRequired(false);

        $form->addInput(null, ['name5', 'Hidden', 'Some hidden value.']);

        $form->addInput('Password', ['name6', 'Password', 'Your password']);
        $form->addValidator('name6', 'Password');

        $form->addInput('Select', [
            'name6b', 'Select', 0, [
                '-- Choose! --',
                'Selectable 1',
                'Selectable 2'
            ]
        ]);
        
        $form->addInput('', ['name7', 'Separator', 'Some HTML']);        
        $form->addInput('', ['name8', 'Submit', 'Save']);        
        $form->addInput('', ['name9', 'Textarea', "Some multiline\nText"]);
        
        return $form;
    }

}