<?php

namespace IQnection\ORM\FieldType;

use SilverStripe\ORM\FieldType\DBText;
use SilverStripe\CMS\Model\SiteTree;
use SilverStripe\Control\Director;

class DBLink extends DBText
{
	public function URL()
	{
		if ($this->IsExternal())
		{
			return $this->fieldValue('External');
		}
		else
		{
			return ($page = $this->LinkedPage()) ? $page->Link() : null;
		}
	}
	
	public function AbsoluteURL()
	{
		if ($this->IsExternal())
		{
			$url = $this->URL();
			if ($url)
			{
				// were we give a complete URL?
				if ( (preg_match("/^https?+:\/\//",$url)) || (preg_match("/^\/\//",$url)) )
				{
					return $url;
				}
				// were we given a domain?
				if (preg_match('/^[^\/]+\.[^\/]+/',$url))
				{
					return '//'.$url;
				}
				// this must be a relative link
				return Director::absoluteURL($url);
			}
		}
		else
		{
			return ($page = $this->LinkedPage()) ? $page->AbsoluteLink() : null;
		}
	}
	
	public function LinkedPage()
	{
		return ($ID = $this->fieldValue('Internal')) ? SiteTree::get()->byId($ID) : null;
	}
	
	protected function fieldValue($name)
	{
		$value = unserialize($this->value);
		switch($name)
		{
			case 'NewTab':
				return (isset($value['NewTab'])) ? $value['NewTab'] : false;
				break;
				
			case 'Location':
				return (isset($value['Location']) && $value['Location']) ? $value['Location'] : 'External';
				break;
				
			case 'Internal':
				return (isset($value['Internal']) && $value['Internal']) ? $value['Internal'] : null;
				break;
				
			case 'External':
				return (isset($value['External']) && $value['External']) ? $value['External'] : null;
				break;
		}
	}
	
	public function formField($title = null, $name = null, $value = null, $form = null)
	{
		if ($title) $title = $this->name;
		if ($name) $name = $this->name;
		
		$field = LinkField::create($name,$title,$value,$form);
		return $field;
	}
	
	public function forTemplate()
	{
		return $this->URL();
	}
	
	public function IsExternal()
	{
		return ($this->Location() == 'External');
	}
	
	public function Location()
	{
		return $this->fieldValue('Location');
	}
	
	public function Target()
	{
		return ($this->fieldValue('NewTab')) ? '_blank' : '_self';
	}
	
	public function TargetATT($force=false)
	{
		if ($this->Target() == '_blank')
		{
			return 'target="_blank"';
		}
		if ($force)
		{
			return 'target="_self"';
		}
	}
}