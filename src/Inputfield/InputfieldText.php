<?php
namespace SRAG\ILIAS\Plugins\MetaData\Inputfield;

use SRAG\ILIAS\Plugins\MetaData\Field\TextField;
use SRAG\ILIAS\Plugins\MetaData\Language\ilLanguage;
use SRAG\ILIAS\Plugins\MetaData\Language\Language;
use SRAG\ILIAS\Plugins\MetaData\Record\Record;

/**
 * Class InputfieldText
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Inputfield
 */
class InputfieldText extends BaseInputfield
{

    /**
     * @var Language
     */
    protected $language;

    public function __construct(TextField $field, $lang = '')
    {
        parent::__construct($field, $lang);
        $this->language = new ilLanguage();
    }

    public function getILIASFormInputs(Record $record)
    {
        $inputs = array();
        $value = $record->getValue();
        $options = $this->field->options();
        foreach ($this->getLanguages() as $lang) {
            $input = new \ilTextInputGUI($this->getLabel($lang), $this->getPostVar($record) . "_$lang");
            if ($this->field->getDescription($this->lang)) {
                $input->setInfo($this->field->getDescription($this->lang));
            }
            if (isset($value[$lang])) {
                $input->setValue($value[$lang]);
            }
            // Field is required only in the default language, even if rendered for multiple languages
            $input->setRequired($options->isRequired() && $lang == $this->language->getDefaultLanguage());
            if ($options->getMaxLength()) {
                $input->setMaxLength($options->getMaxLength());
            }
            if ($options->getRegex()) {
                $input->setValidationRegexp($options->getRegex());
            }
            $inputs[] = $input;
        }
        $this->initLanguageTabs($this->getPostVar($record));
        return $inputs;
    }

    public function getRecordValue(Record $record, \ilPropertyFormGUI $form)
    {
        $values = array();
        foreach ($this->getLanguages() as $lang) {
            $value = $form->getInput($this->getPostVar($record) . "_$lang");
            $values[$lang] = $value;
        }
        return $values;
    }


    /**
     * Return the languages depending if the field is marked as multilang
     *
     * @return array
     */
    protected function getLanguages()
    {
        $options = $this->field->options();
        return ($options->isMultiLang()) ? $this->language->getAvailableLanguages() : array($this->language->getDefaultLanguage());
    }


    protected function initLanguageTabs($post_var)
    {
        global $tpl;
        $options = $this->field->options();
        if (!$options->isMultiLang() || ($options->isMultiLang() && !$options->getUseLanguageTabs())) {
            return;
        }
        static $init = false;
        if (!$init) {
            $tpl->addJavaScript('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/MetaData/templates/js/langtabs.js');
            $tpl->addCss('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/MetaData/templates/css/langtabs.css');
            $init = true;
        }
        $languages = json_encode($this->language->getAvailableLanguages());
        $default = $this->language->getDefaultLanguage();
        $tpl->addOnLoadCode("srmd.languageTabs.useTabs('{$post_var}', '{$default}', {$languages});");
    }

    /**
     * Return the label for the field in the given language
     *
     * @param string $lang
     * @return string
     */
    protected function getLabel($lang)
    {
        $options = $this->field->options();
        $suffix = ($options->isMultiLang() && !$options->getUseLanguageTabs()) ? strtoupper(" $lang") : '';
        return $this->field->getLabel($this->lang) . $suffix;
    }

}