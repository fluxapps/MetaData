<?php
namespace SRAG\ILIAS\Plugins\MetaData\Field;

/**
 * Class TextareaFieldOptions
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\Field
 */
class TextareaFieldOptions extends FieldOptions
{


    /**
     * @label Number of rows
     * @description Silly setting, this only influences the height of the rendered textarea ;)
     * @formProperty ilNumberInputGUI
     * @var int
     */
    protected $nRows = 8;

    /**
     * @label Language tabs
     * @description The textareas of different languages are separated via tabs, only available if Multi Language Support is enabled
     * @formProperty ilCheckboxInputGUI
     * @var bool
     */
    protected $languageTabs = true;

    /**
     * @label Multi Language support
     * @description Offers to add text for all available languages. If not checked, there is only an input for the default language
     * @formProperty ilCheckboxInputGUI
     * @var bool
     */
    protected $multiLang = true;

    public function __construct($data = array())
    {
        parent::__construct($data);
        $this->data = array_merge(
            $this->data, array(
            'nRows' => $this->nRows,
            'multiLang' => $this->multiLang,
            'languageTabs' => $this->languageTabs,
        ), (array) $data);
    }

    /**
     * @return mixed
     */
    public function getNRows()
    {
        return $this->data['nRows'];
    }

    /**
     * @param mixed $nRows
     */
    public function setNRows($nRows)
    {
        $this->data['nRows'] = $nRows;
    }

    /**
     * @return bool
     */
    public function isMultiLang()
    {
        return (bool) $this->data['multiLang'];
    }

    /**
     * @param bool $multilang
     */
    public function setMultiLang($multilang)
    {
        $this->data['multiLang'] = (bool) $multilang;
    }

    /**
     * @return mixed
     */
    public function getUseLanguageTabs()
    {
        return (bool) $this->data['languageTabs'];
    }


    /**
     * @param mixed $languageTabs
     */
    public function setLanguageTabs($languageTabs)
    {
        $this->data['languageTabs'] = (bool) $languageTabs;
    }

}