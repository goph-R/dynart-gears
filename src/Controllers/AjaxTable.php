<?php

namespace Dynart\Gears\Controllers;

use Dynart\Minicore\Controller;
use Dynart\Minicore\Form\Form;

class AjaxTable extends Controller {

    public function index() {
        $this->renderContent(':app/ajax', [
            'form' => $this->createForm(),            
        ]);
    }

    public function processForm() {
        $form = $this->createForm();
        $values = [];
        $status = 'error';
        if ($form->process()) {
            $values = $form->getValues();
            
            // ..
            $status = 'ok';
        }
        $errors = $form->getInputErrors();
        $this->json([
            'status'     => $status,
            'formErrors' => $form->getErrors(),
            'errors'     => $errors
        ]);

    }

    private function createTableView() {
        // Db
        // convert
        return '';
    }

    private function createForm() {

        $form = new Form('test');

        $form->addInput('name1', 'Email label', ['Text', '']);
        $form->addValidator('name1', 'Email');
        
        $form->addInput('nev', 'Uj mezo', ['Text', 'Erteke']);
        $form->addValidator('nev', 'Email');
        
        $form->addInput('name2', '', [
            'Checkbox', 'Value', 'Label for checkbox', true // is checked?
        ]);
        
        $form->addInput('name3', 'Checkbox group label', [
            'CheckboxGroup', ['b', 'c'], [
                'a' => 'Option A.',
                'b' => 'Option B.',
                'c' => 'Option C.',
            ]
        ]);        
        
        $file = $form->addInput('name4', 'File label', ['File']);
        $file->setRequired(false);

        $form->addInput('name5', null, ['Hidden', 'Some hidden value.']);

        $form->addInput('name6', 'Password label', ['Password', 'Your password']);
        $form->addValidator('name6', 'Password');

        $form->addInput('name6b', 'Select label', [
            'Select', 0, [
                '-- Choose! --',
                'Selectable 1',
                'Selectable 2'
            ]
        ]);
        
        $form->addInput('name9', 'Textarea', ['Textarea', "Some multiline\nText"]);
        $form->addInput('name7', '', ['Separator', 'Some HTML']);        
        $form->addInput('name8', '', ['Submit', 'Save']);        
        
        return $form;
    }

}