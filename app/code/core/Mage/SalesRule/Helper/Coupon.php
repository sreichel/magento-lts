<?php

/**
 * @category   Mage
 * @package    Mage_SalesRule
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Helper for coupon codes creating and managing
 *
 * @category   Mage
 * @package    Mage_SalesRule
 */
class Mage_SalesRule_Helper_Coupon extends Mage_Core_Helper_Abstract
{
    /**
     * Constants which defines all possible coupon codes formats
     */
    public const COUPON_FORMAT_ALPHANUMERIC    = 'alphanum';
    public const COUPON_FORMAT_ALPHABETICAL    = 'alpha';
    public const COUPON_FORMAT_NUMERIC         = 'num';

    /**
     * Defines type of Coupon
     */
    public const COUPON_TYPE_SPECIFIC_AUTOGENERATED = 1;

    /**
     * XML paths to coupon codes generation options
     */
    public const XML_PATH_SALES_RULE_COUPON_LENGTH        = 'promo/auto_generated_coupon_codes/length';
    public const XML_PATH_SALES_RULE_COUPON_FORMAT        = 'promo/auto_generated_coupon_codes/format';
    public const XML_PATH_SALES_RULE_COUPON_PREFIX        = 'promo/auto_generated_coupon_codes/prefix';
    public const XML_PATH_SALES_RULE_COUPON_SUFFIX        = 'promo/auto_generated_coupon_codes/suffix';
    public const XML_PATH_SALES_RULE_COUPON_DASH_INTERVAL = 'promo/auto_generated_coupon_codes/dash';

    /**
     * Config path for character set and separator
     */
    public const XML_CHARSET_NODE      = 'global/salesrule/coupon/charset/%s';
    public const XML_CHARSET_SEPARATOR = 'global/salesrule/coupon/separator';

    protected $_moduleName = 'Mage_SalesRule';

    /**
     * Get all possible coupon codes formats
     *
     * @return array
     */
    public function getFormatsList()
    {
        return [
            self::COUPON_FORMAT_ALPHANUMERIC => $this->__('Alphanumeric'),
            self::COUPON_FORMAT_ALPHABETICAL => $this->__('Alphabetical'),
            self::COUPON_FORMAT_NUMERIC      => $this->__('Numeric'),
        ];
    }

    /**
     * Get default coupon code length
     *
     * @return int
     */
    public function getDefaultLength()
    {
        return Mage::getStoreConfigAsInt(self::XML_PATH_SALES_RULE_COUPON_LENGTH);
    }

    /**
     * Get default coupon code format
     *
     * @return int
     */
    public function getDefaultFormat()
    {
        return Mage::getStoreConfig(self::XML_PATH_SALES_RULE_COUPON_FORMAT);
    }

    /**
     * Get default coupon code prefix
     *
     * @return string
     */
    public function getDefaultPrefix()
    {
        return Mage::getStoreConfig(self::XML_PATH_SALES_RULE_COUPON_PREFIX);
    }

    /**
     * Get default coupon code suffix
     *
     * @return string
     */
    public function getDefaultSuffix()
    {
        return Mage::getStoreConfig(self::XML_PATH_SALES_RULE_COUPON_SUFFIX);
    }

    /**
     * Get dashes occurrences frequency in coupon code
     *
     * @return int
     */
    public function getDefaultDashInterval()
    {
        return Mage::getStoreConfigAsInt(self::XML_PATH_SALES_RULE_COUPON_DASH_INTERVAL);
    }

    /**
     * Get Coupon's alphabet as array of chars
     *
     * @param string $format
     * @return array
     */
    public function getCharset($format)
    {
        return str_split((string) Mage::app()->getConfig()->getNode(sprintf(self::XML_CHARSET_NODE, $format)));
    }

    /**
     * Retrieve Separator from config
     *
     * @return string
     */
    public function getCodeSeparator()
    {
        return (string) Mage::app()->getConfig()->getNode(self::XML_CHARSET_SEPARATOR);
    }
}
