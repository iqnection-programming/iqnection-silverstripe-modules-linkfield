<?php

/**
 * A simple set of fields to allow users to either enter an external URL or select an internal page
 * Expects value to be an array or serialized array:
 * array(
 *		[Location] => Internal | External,
 *		[NewTab] => bool,
 *		[Internal] => int - SiteTree ID
 *		[External] => string
 * );
 */
class LinkField extends TextField
{
	protected $fieldHolderTemplate = 'LinkField';
	
	private static $url_handlers = array(
		'$Action!/$ID' => '$Action'
	);
 
	private static $allowed_actions = array(
		'tree'
	);
	
	public function __construct($name, $title = null, $value = null, $form =  null)
	{
		parent::__construct($name, $title, $value, null, $form);
	}
	
	protected $_internalField;
	protected function InternalField()
	{
		if (!$this->_internalField)
		{
			$this->_internalField = LinkFieldTreeDropdownField::create($this->getName().'[Internal]','Internal Page','SiteTree',$this->getName());
		}
		return $this->_internalField;
	}
	
	public function getInternalField()
	{
		return $this->InternalField();
	}
	
	public function setInternalField($field)
	{
		$this->_internalField = $field;
	}
	
	protected $_externalField;
	protected function ExternalField()
	{
		if (!$this->_externalField)
		{
			$this->_externalField = TextField::create($this->getName().'[External]','External URL');
		}
		return $this->_externalField;
	}
	
	public function getExternalField()
	{
		return $this->ExternalField();
	}
	
	public function setExternalField($field)
	{
		$this->_externalField = $field;
	}
	
	protected $_locationField;
	protected function LocationField()
	{
		if (!$this->_locationField)
		{
			$this->_locationField = OptionsetField::create($this->getName().'[Location]','Location',array('Internal' => 'Internal','External' => 'External'));
		}
		return $this->_locationField;
	}
	
	public function getLocationField()
	{
		return $this->LocationField();
	}
	
	public function setLocationField($field)
	{
		$this->_locationField = $field;
	}
	
	protected $_newTabField;
	protected function NewTabField()
	{
		if (!$this->_newTabField)
		{
			$this->_newTabField = CheckboxField::create($this->getName().'[NewTab]','Open in new tab');
		}
		return $this->_newTabField;
	}
	
	public function getNewTabField()
	{
		return $this->NewTabField();
	}
	
	public function setNewTabField($field)
	{
		$this->_newTabField = $field;
	}
	
	public function Field()
	{
		Requirements::javascript(LINKFIELD_DIR."/javascript/LinkField.js");
		$SelectField = $this->getLocationField();
		$ExternalField = $this->getExternalField()->addExtraClass('linkfieldexternal')->setRightTitle('http://www.example.com')->setAttribute('placeholder','http://www.example.com');;
		$InternalField = $this->getInternalField()->setForm($this->getForm())->addExtraClass('linkfieldinternal');
		$NewTabField = $this->getNewTabField();
		$defaults = unserialize($this->value);
		// set defaults
		switch($defaults['Location'])
		{
			case 'Internal':
			{
				$InternalField->setValue($defaults['Internal']);
				$SelectField->setValue('Internal');
				break;
			}
			default:
			case 'External':
			{
				$ExternalField->setValue($defaults['External']);
				$SelectField->setValue('External');
				break;
			}
		}
		$NewTabField->setValue($defaults['NewTab']);
		return $SelectField->FieldHolder().$InternalField->FieldHolder().$ExternalField->FieldHolder().$NewTabField->FieldHolder();
	}
	
	public function tree( SS_HTTPRequest $request)
	{
		return $this->getInternalField()->tree($request);
	}
	
	public function setValue($values)
	{
		// set defaults
		$defaults = array(
			'NewTab' => 0,
			'Internal' => '',
			'External' => '',
			'Location' => 'External'
		);
		if (!is_array($values))
		{
			$values = unserialize($values);
		}
		if (!is_array($values))
		{
			$values = array($values);
		}
		$this->value = array_merge($defaults,$values);
		if ($this->value['Location'] == 'External')
		{
			$this->value['Internal'] == '';
		}
		else
		{
			$this->value['External'] == '';
		}
		$this->value = serialize($this->value);
		return $this;
	}
}

class LinkFieldTreeDropdownField extends TreeDropdownField
{
	protected $link_name;
	
	public function __construct($name,$title=null,$sourceObject,$parentName)
	{
		$this->link_name = $parentName;
		parent::__construct($name,$title,$sourceObject);
	}
	
	public function Link($action = null)
	{
		return Controller::join_links($this->form->FormAction(), 'field/' . $this->link_name, $action);
	}
}