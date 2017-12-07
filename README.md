# Link Field

Works just like the RedirectorPage URL/Page field
Provides and interface for creating an internal or external link. 
Internal link provides a TreeDropdownField for selecting a page within the site tree
External provides a text field for entering the full URL
Also provides availability to store opening in a new tab

## Requirements ##
Silverstripe 4+

## Install ##
composer require iqnection-modules/linkfield

## Usage ##
### In your DataObject class, use the DB field "Link"
```
private static $db = array(
	'LinkedPage' => 'Link'
);
```

### In your forms (CMS only)
```
$fields->push( IQnection\LinkField\LinkField::create($name,$title) );
```

setValue() expects either an array or serialized array of the following:
```
array(
	'Location' => string ['Internal' | 'External'] (required)
	'Internal' => int [SiteTree Page ID]
	'External' => string [external URL | null],
	'NewTab' => [bool]
);
```

### In your templates
```
<a href="$LinkedPage">Link Text</a>
```

## DB class methods ##

### URL
the raw entered URL for External, or $Page->Link() for internal

### AbsoluteURL
an absolute URL for external (adds http://), or $Page->AbsoluteLink() for internal

### LinkedPage
for internal only, returns the linked page SiteTree object

### forTemplate
returns URL()

### IsExternal
returns bool, if the link is external

### Location
returns string, the location set as either Internal or External

### Target
returns _blank or self for use in a target HTML attribute

### TargetATT(force=false)
returns a full target attribute, for new tab only [target="_blank"]
pass $force=true to return attribute for both new tab and same tab

