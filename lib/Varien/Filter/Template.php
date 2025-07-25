<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Varien_Filter
 */

/**
 * Template constructions filter
 *
 * @package    Varien_Filter
 */

class Varien_Filter_Template implements Zend_Filter_Interface
{
    /**
     * Cunstruction regular expression
     */
    public const CONSTRUCTION_PATTERN = '/{{([a-z]{0,10})(.*?)}}/si';

    /**
     * Cunstruction logic regular expression
     */
    public const CONSTRUCTION_DEPEND_PATTERN = '/{{depend\s*(.*?)}}(.*?){{\\/depend\s*}}/si';
    public const CONSTRUCTION_IF_PATTERN = '/{{if\s*(.*?)}}(.*?)({{else}}(.*?))?{{\\/if\s*}}/si';

    /**
     * Assigned template variables
     *
     * @var array
     */
    protected $_templateVars = [];

    /**
     * Template processor
     *
     * @var array|string|null
     */
    protected $_templateProcessor = null;

    /**
     * Include processor
     *
     * @var array|string|null
     */
    protected $_includeProcessor = null;

    /**
     * Sets template variables that's can be called through {var ...} statement
     */
    public function setVariables(array $variables)
    {
        foreach ($variables as $name => $value) {
            $this->_templateVars[$name] = $value;
        }
        return $this;
    }

    /**
     * Sets the proccessor of templates. Templates are directives that include email templates based on system
     * configuration path.
     *
     * @param array $callback it must return string
     */
    public function setTemplateProcessor(array $callback)
    {
        $this->_templateProcessor = $callback;
        return $this;
    }

    /**
     * Sets the proccessor of templates.
     *
     * @return array|null
     */
    public function getTemplateProcessor()
    {
        return is_callable($this->_templateProcessor) ? $this->_templateProcessor : null;
    }

    /**
     * Sets the proccessor of includes.
     *
     * @param array $callback it must return string
     */
    public function setIncludeProcessor(array $callback)
    {
        $this->_includeProcessor = $callback;
        return $this;
    }

    /**
     * Sets the proccessor of includes.
     *
     * @return array|null
     */
    public function getIncludeProcessor()
    {
        return is_callable($this->_includeProcessor) ? $this->_includeProcessor : null;
    }

    /**
     * Filter the string as template.
     *
     * @param string $value
     * @return string
     */
    public function filter($value)
    {
        if ($value === null) {
            return '';
        }

        // "depend" and "if" operands should be first
        $directives = [
            self::CONSTRUCTION_DEPEND_PATTERN => 'dependDirective',
            self::CONSTRUCTION_IF_PATTERN     => 'ifDirective',
        ];
        foreach ($directives as $pattern => $directive) {
            if (preg_match_all($pattern, $value, $constructions, PREG_SET_ORDER)) {
                foreach ($constructions as $index => $construction) {
                    $replacedValue = '';
                    $callback = [$this, $directive];
                    try {
                        $replacedValue = call_user_func($callback, $construction);
                    } catch (Exception $e) {
                        throw $e;
                    }
                    $value = str_replace($construction[0], $replacedValue, $value);
                }
            }
        }

        if (preg_match_all(self::CONSTRUCTION_PATTERN, $value, $constructions, PREG_SET_ORDER)) {
            foreach ($constructions as $index => $construction) {
                $replacedValue = '';
                $callback = [$this, $construction[1] . 'Directive'];
                if (!is_callable($callback)) {
                    continue;
                }
                try {
                    $replacedValue = call_user_func($callback, $construction);
                } catch (Exception $e) {
                    throw $e;
                }
                $value = str_replace($construction[0], $replacedValue, $value);
            }
        }
        return $value;
    }

    public function varDirective($construction)
    {
        if (count($this->_templateVars) == 0) {
            // If template preprocessing
            return $construction[0];
        }
        return $this->_getVariable($construction[2], '');
    }

    public function includeDirective($construction)
    {
        // Processing of {include template=... [...]} statement
        $includeParameters = $this->_getIncludeParameters($construction[2]);
        if (!isset($includeParameters['template']) || !$this->getIncludeProcessor()) {
            // Not specified template or not seted include processor
            $replacedValue = '{Error in include processing}';
        } else {
            // Including of template
            $templateCode = $includeParameters['template'];
            unset($includeParameters['template']);
            $includeParameters = array_merge_recursive($includeParameters, $this->_templateVars);
            $replacedValue = call_user_func($this->getIncludeProcessor(), $templateCode, $includeParameters);
        }
        return $replacedValue;
    }

    /**
     * This directive allows email templates to be included inside other email templates using the following syntax:
     * {{template config_path="<PATH>"}}, where <PATH> equals the XPATH to the system configuration value that contains
     * the value of the email template. For example "sales_email/order/template", which is stored in the
     * Mage_Sales_Model_Order::sales_email/order/template. This directive is useful to include things like a global
     * header/footer.
     *
     * @param $construction
     * @return mixed|string
     */
    public function templateDirective($construction)
    {
        // Processing of {template config_path=... [...]} statement
        $templateParameters = $this->_getIncludeParameters($construction[2]);
        if (!isset($templateParameters['config_path']) || !$this->getTemplateProcessor()) {
            $replacedValue = '{Error in template processing}';
        } else {
            // Including of template
            $configPath = $templateParameters['config_path'];
            unset($templateParameters['config_path']);
            $templateParameters = array_merge_recursive($templateParameters, $this->_templateVars);
            $replacedValue = call_user_func($this->getTemplateProcessor(), $configPath, $templateParameters);
        }
        return $replacedValue;
    }

    public function dependDirective($construction)
    {
        if (count($this->_templateVars) == 0) {
            // If template preprocessing
            return $construction[0];
        }

        if ($this->_getVariable($construction[1], '') == '') {
            return '';
        } else {
            return $construction[2];
        }
    }

    public function ifDirective($construction)
    {
        if (count($this->_templateVars) == 0) {
            return $construction[0];
        }

        if ($this->_getVariable($construction[1], '') == '') {
            if (isset($construction[3]) && isset($construction[4])) {
                return $construction[4];
            }
            return '';
        } else {
            return $construction[2];
        }
    }

    /**
     * Return associative array of include construction.
     *
     * @param string $value raw parameters
     * @return array
     */
    protected function _getIncludeParameters($value)
    {
        $tokenizer = new Varien_Filter_Template_Tokenizer_Parameter();
        $tokenizer->setString($value);
        $params = $tokenizer->tokenize();
        foreach ($params as $key => $value) {
            if (str_starts_with($value, '$')) {
                $params[$key] = $this->_getVariable(substr($value, 1), null);
            }
        }
        return $params;
    }

    /**
    * Return variable value for var construction
    *
    * @param string $value raw parameters
    * @param string|null $default default value
    * @return string
    */
    protected function _getVariable($value, $default = '{no_value_defined}')
    {
        Varien_Profiler::start('email_template_proccessing_variables');
        $tokenizer = new Varien_Filter_Template_Tokenizer_Variable();
        $tokenizer->setString($value);
        $stackVars = $tokenizer->tokenize();
        $result = $default;
        $last = 0;
        /** @var Mage_Adminhtml_Model_Email_PathValidator $emailPathValidator */
        $emailPathValidator = $this->getEmailPathValidator();
        $counter = count($stackVars);
        for ($i = 0; $i < $counter; $i++) {
            if ($i == 0 && isset($this->_templateVars[$stackVars[$i]['name']])) {
                // Getting of template value
                $stackVars[$i]['variable'] = & $this->_templateVars[$stackVars[$i]['name']];
            } elseif (isset($stackVars[$i - 1]['variable']) && $stackVars[$i - 1]['variable'] instanceof Varien_Object) {
                // If object calling methods or getting properties
                if ($stackVars[$i]['type'] == 'property') {
                    $caller = 'get' . uc_words($stackVars[$i]['name'], '');
                    $stackVars[$i]['variable'] = method_exists($stackVars[$i - 1]['variable'], $caller)
                        ? $stackVars[$i - 1]['variable']->$caller()
                        : $stackVars[$i - 1]['variable']->getData($stackVars[$i]['name']);
                } elseif ($stackVars[$i]['type'] == 'method') {
                    // Calling of object method
                    if (method_exists($stackVars[$i - 1]['variable'], $stackVars[$i]['name'])
                        || str_starts_with($stackVars[$i]['name'], 'get')
                    ) {
                        $isEncrypted = false;
                        if ($stackVars[$i]['name'] == 'getConfig') {
                            $isEncrypted = $emailPathValidator->isValid($stackVars[$i]['args']);
                        }
                        $stackVars[$i]['variable'] = call_user_func_array(
                            [$stackVars[$i - 1]['variable'], $stackVars[$i]['name']],
                            !$isEncrypted ? $stackVars[$i]['args'] : [null],
                        );
                    }
                }
                $last = $i;
            }
        }

        if (isset($stackVars[$last]['variable'])) {
            // If value for construction exists set it
            $result = $stackVars[$last]['variable'];
        }
        Varien_Profiler::stop('email_template_proccessing_variables');
        return $result;
    }

    /**
     * Retrieve model object
     *
     * @return Mage_Adminhtml_Model_Email_PathValidator
     */
    protected function getEmailPathValidator()
    {
        return Mage::getModel('adminhtml/email_pathValidator');
    }
}
