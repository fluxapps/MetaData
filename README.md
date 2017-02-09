# MetaData for ILIAS
This UserInterface plugin offers to add metadata for ILIAS objects (and custom objects). 
In contrast to the advanced metadata in the core, multi language support is built in among other features.

## Configuration

### Fields
* Text
* Textarea
* Dropdown
* Dropdown with multiple selections
* Integer
* Float
* DateTime
* Boolean
* Location

#### Field Type Options
Each field type offers different options like "required", "multi language support" etc.

#### Inputfield
The inputfield controls how this field is represented in a form. For example, for the "Dropdown with multiple selections", one
can choose between "Checkboxes" or "AsmSelect".

#### Field Data
A field type may require additional data. For example, a dropdown field needs to define its selectable options. 

#### Output Formatters
Multiple formatters can be stacked to influcence the value returned by `\SRAG\ILIAS\Plugins\MetaData\Record::getFormattedValue()`. See the "API" section for more information.

### Field Groups

A field group groups multiple fields. Thus, it is possible to reuse a field in different groups. When rendering the form to display the fields, the field group acts as "Form Section".

### ILIAS Object Mapping

The plugin offers a quick way to show and store metadata for an ILIAS object type. A mapping consists of:

* ILIAS Object Type, e.g. `crs`
* Field Groups
* Tab title

If the context of the request matches an ILIAS object type, e.g. when the context of `ilCtrl` is course, the plugin adds a new tab containing a form with all the fields of the field groups.

Note that this feature is experimental and has the following constraints:
* It relies on the values of `ilCtrl::getContextObjType()` and `ilCtrl::getContextObjId()` to determine the object.
* Currently, permission check is based on write access to the Ref-ID present in GET.

## API
This plugin uses psr-4 namespaces with the root namespace `SRAG\ILIAS\Plugins\MetaData` pointing to the folder `src`. 
Also it uses composer for autoloading all classes, so you don't require any includes except the following (already included if the plugin is active):
```php
require_once('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/MetaData/vendor/autoload.php');
```

### Record

The `SRAG\ILIAS\Plugins\Metadata\Record\Record` object represents a metadata record of a given object, identified by:

* Object type
* Object ID
* Field Group ID
* Field ID

Note that you can use the MetaData service for your own objects, you just need to make sure to represent them with a unique object type.

#### Querying Records

Use `SRAG\ILIAS\Plugins\MetaData\RecordQuery` to query records for a given object:
```php
$myConsumer = new ilConsumerObject(new ilObjCourse(123));
$query = new RecordQuery($myConsumer);
$fieldGroup = FieldGroup::findByIdentifier('course_metadata');
$records = $query->getRecords($fieldGroup); // Returns an array of Record objects for the given field group
// or to get a Record of a given field
$field = Field::findByIdentifier('introduction');
$record = $query->getRecord($fieldGroup, $field);
```

#### Setting/Getting Values
The field of a record uses a `StorageLocation` which controls how the values are stored in the databases. For example, the
`StringStorageLocation` saves a value for each language. Therefore, setting and getting the value of a Record depends on the `StorageLocation` of the field.
Here are some examples:
```php
// For text or textarea fields (String|TextStorage):
$record->setValue(['de' => 'Text DE', 'fr' => 'Text FR']);
// Dropdown fields (IntegerStorage)
$record->setValue(12); // 12 = Internal ID of an option in the field data
// Dropdown with mutliple selection fields (IntegerMultiStorage)
$record->setValue([3,20,15]); // Store multiple internal IDs of options from the field data
// Persist data
$record->save();
```
`Record::getValue()` returns the value from the StorageLocation, which is not always desired. In case of a dropdown field, we would want to display
the text of the selected option, not its internal ID. For this purpose, one can use `Record::getFormattedValue()` which passes the raw value
from the `StorageLocation` through the output formatters of the field.

**Example**

The record represents a text field which uses the following output formatter:
 * Text(area)Field: Display value in users language, fallback to default language [array -> string]
 
 This means that the formatter will try to output the value in the current users language. If the value is not available, it fallbacks
 to the default language (system language of ILIAS):
 
```php
$record->getValue(); // --> ['de' => 'Guten Tag', 'fr' => 'Bonjour']
// DE User
$record->getFormattedValue() // --> 'Guten Tag'
// FR User
$record->getFormattedValue() // --> 'Bonjour'
```

### FormAdapter

Use `SRAG\ILIAS\Plugins\Form\FormAdapter` to add metadata fields to a given form. The adapter is also used to save Record data after the form has been validated:
```php
$form = new ilPropertyFormGUI();
$item = new ilTextInputGUI('My Item', 'myItem');
$form->addItem($item);

// Now we want to add some metadata fields to the existing form
$myConsumer = new ilConsumerObject(new ilObjCourse(123));
$adapter = new FormAdapter($form, $myConsumer);
$fieldGroup = FieldGroup::findByIdentifier('course_metadata');
$adapter->addFields($fieldGroup); // Adds all fields of the group 'course_metadata' to the form

// When the form is submitted, we use the adapter to save the records
if ($form->checkInput()) {
    $result = $adapter->saveRecords();
    if (!$result) {
        $errors = $adapter->getErrors(); // Returns Error objects holding the record and exception occured
    }
}
```

###Hinweis Plugin-Patenschaft
Grundsätzlich veröffentlichen wir unsere Plugins (Extensions, Add-Ons), weil wir sie für alle Community-Mitglieder zugänglich machen möchten. Auch diese Extension wird der ILIAS Community durch die studer + raimann ag als open source zur Verfügung gestellt. Diese Plugin hat noch keinen Plugin-Paten. Das bedeutet, dass die studer + raimann ag etwaige Fehlerbehebungen, Supportanfragen oder die Release-Pflege lediglich für Kunden mit entsprechendem Hosting-/Wartungsvertrag leistet. Falls Sie nicht zu unseren Hosting-Kunden gehören, bitten wir Sie um Verständnis, dass wir leider weder kostenlosen Support noch Release-Pflege für Sie garantieren können.

Sind Sie interessiert an einer Plugin-Patenschaft (https://studer-raimann.ch/produkte/ilias-plugins/plugin-patenschaften/ ) Rufen Sie uns an oder senden Sie uns eine E-Mail.
