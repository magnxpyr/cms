<?php
/**
 * Created by IntelliJ IDEA.
 * User: gatz
 * Date: 05.10.2016
 * Time: 22:06
 */

namespace Core\Forms;


use Engine\Forms\Form;
use Phalcon\Forms\Element\Hidden;
use Phalcon\Forms\Element\Select;
use Phalcon\Forms\Element\TextArea;
use Phalcon\Validation\Validator\PresenceOf;

class AdminModuleEditForm extends Form
{
    /**
     * Initialize the form
     */
    public function initialize()
    {
        parent::initialize();

        // Id
        $id = new Hidden('id');
        $id->setFilters('int');
        $this->add($id);

        // Status
        $status = new Select('status',
            $this->helper->getArticleStatuses(),
            ['using' => ['id', 'name'], 'class' => 'form-control']
        );
        $status->setLabel($this->t->_('Status'));
        $status->setFilters('int');
        $status->addValidator(
            new PresenceOf([
                'status' => $this->t->_('%field% is required', ['field' => $this->t->_('Status')])
            ])
        );
        $this->add($status);

        // Description
        $description = new TextArea('description', [
            'rows' => 5,
            'cols' => 30,
            'class' => 'form-control'
        ]);
        $description->setLabel($this->t->_('Description'));
        $description->setFilters('string');
        $this->add($description);
    }
}