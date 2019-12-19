<?php

namespace SRAG\ILIAS\Plugins\MetaData\FormProperty;

class ilDateTimeInput2GUI extends \ilSubEnabledFormPropertyGUI
{

    /**
     * @var \DateTime
     */
    protected $value;
    /**
     * @var array
     */
    protected $options
        = array(
            'enableTime' => false,
            'altInput'   => true,
            'altFormat'  => 'd.m.Y',
            'allowInput' => true,
            'locale'     => 'en',
        );
    /**
     * @var string
     */
    protected $locale = 'en';


    public function __construct($a_title = "", $a_postvar = "")
    {
        parent::__construct($a_title, $a_postvar);
        global $tpl;
        $tpl->addJavaScript('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/MetaData/templates/libs/flatpickr/bower_components/flatpickr/dist/flatpickr.min.js');
        $tpl->addCss('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/MetaData/templates/libs/flatpickr/bower_components/flatpickr/dist/flatpickr.min.css');
    }


    public function setLocale($locale)
    {
        global $tpl;
        $tpl->addJavaScript("./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/MetaData/templates/libs/flatpickr/bower_components/flatpickr/dist/l10n/{$locale}.js");
        $this->setOption('locale', $locale);
    }


    /**
     * Set an option to flatpickr
     *
     * @see https://eonasdan.github.io/bootstrap-datetimepicker/ for all options
     *
     * @param string $key
     * @param string $value
     */
    public function setOption($key, $value)
    {
        $this->options[$key] = $value;
    }


    function checkInput()
    {
        if ($this->getRequired()) {
            if (!isset($_POST[$this->getPostVar()]) && !$_POST[$this->getPostVar()]) {
                return false;
            }
        }

        return true;
    }


    /**
     * Insert property html
     *
     * @return    int    Size
     */
    function insert(&$a_tpl)
    {
        $html = $this->render();

        $a_tpl->setCurrentBlock("prop_generic");
        $a_tpl->setVariable("PROP_GENERIC", $html);
        $a_tpl->parseCurrentBlock();
    }


    public function render()
    {
        $value = ($this->getValue()) ? $this->getValue()->format('Y-m-d H:i:s') : '';
        $id = $this->getFieldId();
        $post_var = $this->getPostVar();
        $options = json_encode($this->options);
        $out = <<<EOL
            <div class='input-group date'>
                <input type='text' id='{$id}' name='{$post_var}' value='{$value}'>
                <span class="input-group-addon"><span class="glyphicon glyphicon-calendar"></span></span>
            </div>
            <script>
                $(function() {
                    $('#{$id}').flatpickr($options);
                });
            </script>
EOL;

        return $out;
    }


    /**
     * @return \DateTime
     */
    public function getValue()
    {
        return $this->value;
    }


    /**
     * @param \DateTime $date
     */
    public function setValue(\DateTime $date)
    {
        $this->value = $date;
    }


    /**
     * @param $value
     */
    public function setValueByArray($value)
    {
        $datetime = $value[$this->getPostVar()];
        if ($datetime) {
            $this->setValue(new \DateTime($datetime));
        }
    }
}