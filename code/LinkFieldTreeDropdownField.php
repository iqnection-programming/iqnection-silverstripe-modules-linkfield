<?php

namespace IQnection\Forms;

use SilverStripe\Forms\TreeDropdownField;
use SilverStripe\Control\Controller;

class LinkFieldTreeDropdownField extends TreeDropdownField
{
	protected $link_name;
	
	public function __construct($name,$title=null,$parentName)
	{
		$this->link_name = $parentName;
		parent::__construct($name,$title,'SilverStripe\CMS\Model\SiteTree');
	}
	
	public function Link($action = null)
	{
		return Controller::join_links($this->form->FormAction(), 'field/' . $this->link_name, $action);
	}
}