<?php
namespace SRAG\ILIAS\Plugins\MetaData\FormProperty;

/**
 * Class ilAsmSelectInputGUI
 *
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 * @package SRAG\ILIAS\Plugins\MetaData\FormProperty
 */
class ilAsmSelectInputGUI extends \ilSelectInputGUI
{
    /**
     * @var array
     */
    protected $asmOptions = array(
        'sortable' => true,
        'highlight' => false,
    );

    /**
     * @var array
     */
    protected $value = array();

    public function __construct($a_title = "", $a_postvar = "")
    {
        parent::__construct($a_title, $a_postvar);
        global $tpl;
        $tpl->addJavaScript('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/MetaData/templates/libs/asmselect/jquery.asmselect.min.js');
        $tpl->addCss('./Customizing/global/plugins/Services/UIComponent/UserInterfaceHook/MetaData/templates/libs/asmselect/jquery.asmselect.css');
        $this->setPostVar($a_postvar);
        $this->addCustomAttribute('multiple="multiple"');
    }

    function checkInput()
    {
        global $lng;
        $valid = true;
        if ($this->getRequired()) {
            $post_var = str_replace('[]', '', $this->getPostVar());
            $valid = isset($_POST[$post_var]) && count($_POST[$post_var]);
        }
        if (!$valid) {
            $this->setAlert($lng->txt("msg_input_is_required"));
        }

        return $valid;
    }


    public function setPostVar($a_postvar)
    {
        if (substr($a_postvar, -2) != '[]') {
            $a_postvar .= '[]';
        }
        parent::setPostVar($a_postvar);
    }

    function setValue($a_value)
    {
        $this->value = $a_value;
    }


    function setValueByArray($a_values)
    {
        $post_var = str_replace('[]', '', $this->getPostVar());
        $this->setValue(array_values((array)$a_values[$post_var]));
    }


    protected function renderJavascript()
    {
        $id = $this->getFieldId();
        $options = array_merge(array(
            'jQueryUI' => false,
        ), $this->asmOptions);
        $json = json_encode($options);
        $out = <<<EOL
<script>
$(function() {
    $('#$id').asmSelect({$json});
});
</script>
EOL;
        return $out;
    }


    public function render($a_mode = "")
    {
        $tpl = new \ilTemplate("tpl.prop_select.html", true, true, "Services/Form");
        $tpl->setCurrentBlock('cust_attr');
        $tpl->setVariable('CUSTOM_ATTR', 'multiple="multiple"');
        $tpl->parseCurrentBlock();
        $tpl->setVariable("ID", $this->getFieldId());
        $tpl->setVariable("POST_VAR", $this->getPostVar());

        // We must remove the selected values from options and append them to the end, so we don't loose the sorting!
        $selected_options = $this->getValue();
	    if(count($selected_options) > 0 && is_array($selected_options)) {
		    foreach ($selected_options as $id) {
			    if (!isset($this->options[$id])) {
				    continue;
			    }
			    $label = $this->options[$id];
			    unset($this->options[$id]);
			    $this->options[$id] = $label;
		    }
	    }

        foreach($this->getOptions() as $option_value => $option_text) {
            $tpl->setCurrentBlock("prop_select_option");
            $tpl->setVariable("VAL_SELECT_OPTION", \ilUtil::prepareFormOutput($option_value));
            if (is_array($this->value) && in_array($option_value, $this->value)) {
                $tpl->setVariable("CHK_SEL_OPTION", 'selected="selected"');
            }
            $tpl->setVariable("TXT_SELECT_OPTION", $option_text);
            $tpl->parseCurrentBlock();
        }

        return $this->renderJavascript() . $tpl->get();
    }


    /**
     * @param string $key
     * @param $value
     */
    public function setAsmOption($key, $value)
    {
        $this->asmOptions[$key] = $value;
    }



}