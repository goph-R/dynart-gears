<?php

namespace Dynart\Gears\Controllers;

use Dynart\Gears\Views\ListView;

use Dynart\Minicore\Controller;
use Dynart\Minicore\Form;

// -- Don't store these here (declarations added in GearsAdminApp constructor)
use Dynart\Minicore\Database\Table;
use Dynart\Minicore\Database\TranslationTable;
use Dynart\Minicore\Database\Query;
use Dynart\Minicore\Framework;

class TestTable extends Table {
    protected $name = 'test'; // use the name as in SQL
    protected $translationTable = 'testTextTable'; // use the name as the instance name in Framework
    protected $fields = [
        'id' => null,
        'number' => null,
        'created_on' => null,
        'updated_on' => ['default_value' => null]
    ];
}

class TestTextTable extends TranslationTable {
    protected $name = 'test_text';
    protected $fields = [
        'id' => null,
        'locale' => null,
        'name' => null
    ];
}

class TestQuery extends Query {
    protected $table = 'testTable';
    protected $textSearchFields = ['name'];
}
// --

class Test extends Controller {

    public function __construct() {
        parent::__construct();
        Framework::instance()->get('userService')->requireLogin();
    }

    public function index() {
        $this->renderContent(':app/test-index', [
            'form' => $this->createForm(),
            'listView' => $this->createListView(),
        ]);
    }

    public function list() {
        $this->json($this->createListView());
    }

    public function processForm() {
        $form = $this->createForm();
        $status = 'error';
        if ($form->process()) {
            // $form->getValues();
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

    private function createListView() {


        $listView = new ListView('testQuery');

        // $filterForm = $this->createFilterForm();
        // $filterForm->getOptions()
        // list view with options
        $listView->setOptions([
            'text'      => $this->request->get('text', ''),
            'order_by'  => $this->request->get('orderBy', 'name'),
            'order_dir' => $this->request->get('orderDir', 'asc'),
            'page'      => $this->request->get('page', 0),
            'page_size' => $this->request->get('pageSize', 7)
        ]);

        $listView->setFields([
            'id' => 'test.id', // means: SELECT test.id AS id
            'number',
            'name',
            'created_on'
        ]);

        $listView->setHeaders([[
            'field' => 'id',
            'label' => 'ID',
            'width' => '8%'
        ], [
            'field' => 'number',
            'label' => 'Number',
            'width' => '8%'
        ], [
            'field' => 'name',
            'label' => 'Name',
            'width' => '32%'
        ], [
            'field' => 'created_on',
            'label' => 'Created on',
            'width' => '20%'
        ]]);

        return $listView;
    }

    private function createForm() {

        $form = new Form('test');

        // $form->beginRow(); would be nice

        $form->addInput('name1', 'Email label', ['Text', '']);
        $form->addValidator('name1', 'Email');
        
        $form->addInput('nev', 'Uj mezo', ['Text', 'Erteke']);
        $form->addValidator('nev', 'Email');

        // $form->endRow(); would be nice
        
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