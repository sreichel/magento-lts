<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Tax
 */

/**
 * Configuration paths storage
 *
 * @package    Mage_Tax
 */
class Mage_Tax_Model_Config
{
    /**
     * Paths to tax notification configs
     */
    public const XML_PATH_TAX_NOTIFICATION_DISCOUNT = 'tax/ignore_notification/discount';
    public const XML_PATH_TAX_NOTIFICATION_PRICE_DISPLAY = 'tax/ignore_notification/price_display';
    public const XML_PATH_TAX_NOTIFICATION_FPT_CONFIGURATION = 'tax/ignore_notification/fpt_configuration';
    public const XML_PATH_TAX_NOTIFICATION_URL = 'tax/notification/url';

    /**
     * Tax classes
     */
    public const CONFIG_XML_PATH_SHIPPING_TAX_CLASS = 'tax/classes/shipping_tax_class';

    /**
     * Paths to tax calculation configs
     */
    public const CONFIG_XML_PATH_PRICE_INCLUDES_TAX = 'tax/calculation/price_includes_tax';
    public const CONFIG_XML_PATH_SHIPPING_INCLUDES_TAX = 'tax/calculation/shipping_includes_tax';
    public const CONFIG_XML_PATH_BASED_ON = 'tax/calculation/based_on';
    public const CONFIG_XML_PATH_APPLY_ON = 'tax/calculation/apply_tax_on';
    public const CONFIG_XML_PATH_APPLY_AFTER_DISCOUNT = 'tax/calculation/apply_after_discount';
    public const CONFIG_XML_PATH_DISCOUNT_TAX = 'tax/calculation/discount_tax';
    public const XML_PATH_ALGORITHM = 'tax/calculation/algorithm';
    public const CONFIG_XML_PATH_CROSS_BORDER_TRADE_ENABLED = 'tax/calculation/cross_border_trade_enabled';

    /**
     * Paths to tax defaults configs
     */
    public const CONFIG_XML_PATH_DEFAULT_COUNTRY = 'tax/defaults/country';
    public const CONFIG_XML_PATH_DEFAULT_REGION = 'tax/defaults/region';
    public const CONFIG_XML_PATH_DEFAULT_POSTCODE = 'tax/defaults/postcode';

    /**
     * Prices display settings
     */
    public const CONFIG_XML_PATH_PRICE_DISPLAY_TYPE = 'tax/display/type';
    public const CONFIG_XML_PATH_DISPLAY_SHIPPING = 'tax/display/shipping';

    /**
     * Shopping cart display settings
     */
    public const XML_PATH_DISPLAY_CART_PRICE = 'tax/cart_display/price';
    public const XML_PATH_DISPLAY_CART_SUBTOTAL = 'tax/cart_display/subtotal';
    public const XML_PATH_DISPLAY_CART_SHIPPING = 'tax/cart_display/shipping';
    public const XML_PATH_DISPLAY_CART_DISCOUNT = 'tax/cart_display/discount';
    public const XML_PATH_DISPLAY_CART_GRANDTOTAL = 'tax/cart_display/grandtotal';
    public const XML_PATH_DISPLAY_CART_FULL_SUMMARY = 'tax/cart_display/full_summary';
    public const XML_PATH_DISPLAY_CART_ZERO_TAX = 'tax/cart_display/zero_tax';

    /**
     * Shopping cart display settings
     */
    public const XML_PATH_DISPLAY_SALES_PRICE = 'tax/sales_display/price';
    public const XML_PATH_DISPLAY_SALES_SUBTOTAL = 'tax/sales_display/subtotal';
    public const XML_PATH_DISPLAY_SALES_SHIPPING = 'tax/sales_display/shipping';
    public const XML_PATH_DISPLAY_SALES_DISCOUNT = 'tax/sales_display/discount';
    public const XML_PATH_DISPLAY_SALES_GRANDTOTAL = 'tax/sales_display/grandtotal';
    public const XML_PATH_DISPLAY_SALES_FULL_SUMMARY = 'tax/sales_display/full_summary';
    public const XML_PATH_DISPLAY_SALES_ZERO_TAX = 'tax/sales_display/zero_tax';

    /**
     * String separator
     */
    public const CALCULATION_STRING_SEPARATOR = '|';

    /**
     * Indexes for tax display types
     */
    public const DISPLAY_TYPE_EXCLUDING_TAX = 1;
    public const DISPLAY_TYPE_INCLUDING_TAX = 2;
    public const DISPLAY_TYPE_BOTH = 3;

    /**
     * Indexes for FPT Configuration Types
     */
    public const FPT_NOT_TAXED = 0;
    public const FPT_TAXED = 1;
    public const FPT_LOADED_DISPLAY_WITH_TAX = 2;

    /**
     * @deprecated
     */
    public const CONFIG_XML_PATH_SHOW_IN_CATALOG = 'tax/display/show_in_catalog';

    /**
     * @deprecated
     */
    public const CONFIG_XML_PATH_DEFAULT_PRODUCT_TAX_GROUP = 'catalog/product/default_tax_group';

    /**
     * @deprecated
     */
    public const CONFIG_XML_PATH_DISPLAY_TAX_COLUMN = 'tax/display/column_in_summary';

    /**
     * @deprecated
     */
    public const CONFIG_XML_PATH_DISPLAY_FULL_SUMMARY = 'tax/display/full_summary';

    /**
     * @deprecated
     */
    public const CONFIG_XML_PATH_DISPLAY_ZERO_TAX = 'tax/display/zero_tax';

    /**
     * Flag which notify what we need use prices exclude tax for calculations
     *
     * @var bool
     */
    protected $_needUsePriceExcludeTax = false;

    /**
     * Flag which notify what we need use shipping prices exclude tax for calculations
     *
     * @var bool
     */
    protected $_needUseShippingExcludeTax = false;

    /**
     * @var bool $_shippingPriceIncludeTax
     */
    protected $_shippingPriceIncludeTax = null;

    /**
     * Retrieve config value for store by path
     *
     * @param string $path
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return mixed
     */
    protected function _getStoreConfig($path, $store)
    {
        return Mage::getStoreConfig($path, $store);
    }

    /**
     * Check if product prices inputted include tax
     *
     * @param  null|string|bool|int|Mage_Core_Model_Store $store
     * @return  bool
     */
    public function priceIncludesTax($store = null)
    {
        if ($this->_needUsePriceExcludeTax) {
            return false;
        }
        return (bool) $this->_getStoreConfig(self::CONFIG_XML_PATH_PRICE_INCLUDES_TAX, $store);
    }

    /**
     * Check what taxes should be applied after discount
     *
     * @param   null|string|bool|int|Mage_Core_Model_Store $store
     * @return  bool
     */
    public function applyTaxAfterDiscount($store = null)
    {
        return (bool) $this->_getStoreConfig(self::CONFIG_XML_PATH_APPLY_AFTER_DISCOUNT, $store);
    }

    /**
     * Get product price display type
     *  1 - Excluding tax
     *  2 - Including tax
     *  3 - Both
     *
     * @param   null|string|bool|int|Mage_Core_Model_Store $store
     * @return  int
     */
    public function getPriceDisplayType($store = null)
    {
        return (int) $this->_getStoreConfig(self::CONFIG_XML_PATH_PRICE_DISPLAY_TYPE, $store);
    }

    /**
     * Get configuration setting "Apply Discount On Prices Including Tax" value
     *
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return bool 0|1
     */
    public function discountTax($store = null)
    {
        return ((int) $this->_getStoreConfig(self::CONFIG_XML_PATH_DISCOUNT_TAX, $store) == 1);
    }

    /**
     * Get taxes/discounts calculation sequence.
     * This sequence depends on "Apply Customer Tax" and "Apply Discount On Prices" configuration options.
     *
     * @param   null|int|string|Mage_Core_Model_Store $store
     * @return  string
     */
    public function getCalculationSequence($store = null)
    {
        if ($this->applyTaxAfterDiscount($store)) {
            if ($this->discountTax($store)) {
                $seq = Mage_Tax_Model_Calculation::CALC_TAX_AFTER_DISCOUNT_ON_INCL;
            } else {
                $seq = Mage_Tax_Model_Calculation::CALC_TAX_AFTER_DISCOUNT_ON_EXCL;
            }
        } elseif ($this->discountTax($store)) {
            $seq = Mage_Tax_Model_Calculation::CALC_TAX_BEFORE_DISCOUNT_ON_INCL;
        } else {
            $seq = Mage_Tax_Model_Calculation::CALC_TAX_BEFORE_DISCOUNT_ON_EXCL;
        }
        return $seq;
    }

    /**
     * Specify flag what we need use price exclude tax
     *
     * @param   bool $flag
     * @return  Mage_Tax_Model_Config
     */
    public function setNeedUsePriceExcludeTax($flag)
    {
        $this->_needUsePriceExcludeTax = $flag;
        return $this;
    }

    /**
     * Get flag what we need use price exclude tax
     *
     * @return bool $flag
     */
    public function getNeedUsePriceExcludeTax()
    {
        return $this->_needUsePriceExcludeTax;
    }

    /**
     * Specify flag what we need use shipping price exclude tax
     *
     * @param   bool $flag
     * @return  Mage_Tax_Model_Config
     */
    public function setNeedUseShippingExcludeTax($flag)
    {
        $this->_needUseShippingExcludeTax = $flag;
        return $this;
    }

    /**
     * Get flag what we need use shipping price exclude tax
     *
     * @return bool $flag
     */
    public function getNeedUseShippingExcludeTax()
    {
        return $this->_needUseShippingExcludeTax;
    }

    /**
     * Get defined tax calculation algorithm
     *
     * @param   null|string|bool|int|Mage_Core_Model_Store $store
     * @return  string
     */
    public function getAlgorithm($store = null)
    {
        return $this->_getStoreConfig(self::XML_PATH_ALGORITHM, $store);
    }

    /**
     * Get tax class id specified for shipping tax estimation
     *
     * @param   null|string|bool|int|Mage_Core_Model_Store $store
     * @return  int
     */
    public function getShippingTaxClass($store = null)
    {
        return (int) $this->_getStoreConfig(self::CONFIG_XML_PATH_SHIPPING_TAX_CLASS, $store);
    }

    /**
     * Get shipping methods prices display type
     *
     * @param   null|string|bool|int|Mage_Core_Model_Store $store
     * @return  int
     */
    public function getShippingPriceDisplayType($store = null)
    {
        return (int) $this->_getStoreConfig(self::CONFIG_XML_PATH_DISPLAY_SHIPPING, $store);
    }

    /**
     * Check if shipping prices include tax
     *
     * @param   null|string|bool|int|Mage_Core_Model_Store $store
     * @return  bool
     */
    public function shippingPriceIncludesTax($store = null)
    {
        if ($this->_shippingPriceIncludeTax === null) {
            $this->_shippingPriceIncludeTax = (bool) $this->_getStoreConfig(
                self::CONFIG_XML_PATH_SHIPPING_INCLUDES_TAX,
                $store,
            );
        }
        return $this->_shippingPriceIncludeTax;
    }

    /**
     * Declare shipping prices type
     * @param bool $flag
     * @return $this
     */
    public function setShippingPriceIncludeTax($flag)
    {
        $this->_shippingPriceIncludeTax = $flag;
        return $this;
    }

    /**
     * Check if we need display full tax summary information in totals block
     *
     * @deprecated please use displayCartFullSummary or displaySalesFullSummary
     * @param   null|string|bool|int|Mage_Core_Model_Store $store
     * @return  bool
     */
    public function displayFullSummary($store = null)
    {
        return $this->displayCartFullSummary($store);
    }

    /**
     * Check if we need display zero tax in subtotal
     *
     * @deprecated please use displayCartZeroTax or displaySalesZeroTax
     * @param   null|string|bool|int|Mage_Core_Model_Store $store
     * @return  bool
     */
    public function displayZeroTax($store = null)
    {
        return $this->displayCartZeroTax($store);
    }

    /**
     * Get shopping cart prices display type
     *
     * @deprecated please use displayCartPrice or displaySalesZeroTax
     * @param   null|string|bool|int|Mage_Core_Model_Store $store
     * @return  bool
     */
    public function displayTaxColumn($store = null)
    {
        return (bool) $this->_getStoreConfig(self::CONFIG_XML_PATH_DISPLAY_TAX_COLUMN, $store);
    }

    /**
     * Check if display cart prices included tax
     *
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return bool
     */
    public function displayCartPricesInclTax($store = null)
    {
        return $this->_getStoreConfig(self::XML_PATH_DISPLAY_CART_PRICE, $store) == self::DISPLAY_TYPE_INCLUDING_TAX;
    }

    /**
     * Check if display cart prices excluded tax
     *
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return bool
     */
    public function displayCartPricesExclTax($store = null)
    {
        return $this->_getStoreConfig(self::XML_PATH_DISPLAY_CART_PRICE, $store) == self::DISPLAY_TYPE_EXCLUDING_TAX;
    }

    /**
     * Check if display cart prices included and excluded tax
     *
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return bool
     */
    public function displayCartPricesBoth($store = null)
    {
        return $this->_getStoreConfig(self::XML_PATH_DISPLAY_CART_PRICE, $store) == self::DISPLAY_TYPE_BOTH;
    }

    /**
     * Check if display cart subtotal included tax
     *
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return bool
     */
    public function displayCartSubtotalInclTax($store = null)
    {
        return $this->_getStoreConfig(self::XML_PATH_DISPLAY_CART_SUBTOTAL, $store) == self::DISPLAY_TYPE_INCLUDING_TAX;
    }

    /**
     * Check if display cart subtotal excluded tax
     *
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return bool
     */
    public function displayCartSubtotalExclTax($store = null)
    {
        return $this->_getStoreConfig(self::XML_PATH_DISPLAY_CART_SUBTOTAL, $store) == self::DISPLAY_TYPE_EXCLUDING_TAX;
    }

    /**
     * Check if display cart subtotal included and excluded tax
     *
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return bool
     */
    public function displayCartSubtotalBoth($store = null)
    {
        return $this->_getStoreConfig(self::XML_PATH_DISPLAY_CART_SUBTOTAL, $store) == self::DISPLAY_TYPE_BOTH;
    }

    /**
     * Check if display cart shipping included tax
     *
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return bool
     */
    public function displayCartShippingInclTax($store = null)
    {
        return $this->_getStoreConfig(self::XML_PATH_DISPLAY_CART_SHIPPING, $store) == self::DISPLAY_TYPE_INCLUDING_TAX;
    }

    /**
     * Check if display cart shipping excluded tax
     *
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return bool
     */
    public function displayCartShippingExclTax($store = null)
    {
        return $this->_getStoreConfig(self::XML_PATH_DISPLAY_CART_SHIPPING, $store) == self::DISPLAY_TYPE_EXCLUDING_TAX;
    }

    /**
     * Check if display cart shipping included and excluded tax
     *
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return bool
     */
    public function displayCartShippingBoth($store = null)
    {
        return $this->_getStoreConfig(self::XML_PATH_DISPLAY_CART_SHIPPING, $store) == self::DISPLAY_TYPE_BOTH;
    }

    /**
     * Check if display cart discount included tax
     *
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return bool
     */
    public function displayCartDiscountInclTax($store = null)
    {
        return $this->_getStoreConfig(self::XML_PATH_DISPLAY_CART_DISCOUNT, $store) == self::DISPLAY_TYPE_INCLUDING_TAX;
    }

    /**
     * Check if display cart discount excluded tax
     *
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return bool
     */
    public function displayCartDiscountExclTax($store = null)
    {
        return $this->_getStoreConfig(self::XML_PATH_DISPLAY_CART_DISCOUNT, $store) == self::DISPLAY_TYPE_EXCLUDING_TAX;
    }

    /**
     * Check if display cart discount included and excluded tax
     *
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return bool
     */
    public function displayCartDiscountBoth($store = null)
    {
        return $this->_getStoreConfig(self::XML_PATH_DISPLAY_CART_DISCOUNT, $store) == self::DISPLAY_TYPE_BOTH;
    }

    /**
     * Get display cart tax with grand total
     *
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return bool
     */
    public function displayCartTaxWithGrandTotal($store = null)
    {
        return (bool) $this->_getStoreConfig(self::XML_PATH_DISPLAY_CART_GRANDTOTAL, $store);
    }

    /**
     * Get display cart full summary
     *
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return bool
     */
    public function displayCartFullSummary($store = null)
    {
        return (bool) $this->_getStoreConfig(self::XML_PATH_DISPLAY_CART_FULL_SUMMARY, $store);
    }

    /**
     * Get display cart zero tax
     *
     * @param mixed $store
     * @return bool
     */
    public function displayCartZeroTax($store = null)
    {
        return (bool) $this->_getStoreConfig(self::XML_PATH_DISPLAY_CART_ZERO_TAX, $store);
    }

    /**
     * Check if display sales prices include tax
     *
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return bool
     */
    public function displaySalesPricesInclTax($store = null)
    {
        return $this->_getStoreConfig(self::XML_PATH_DISPLAY_SALES_PRICE, $store) == self::DISPLAY_TYPE_INCLUDING_TAX;
    }

    /**
     * Check if display sales prices exclude tax
     *
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return bool
     */
    public function displaySalesPricesExclTax($store = null)
    {
        return $this->_getStoreConfig(self::XML_PATH_DISPLAY_SALES_PRICE, $store) == self::DISPLAY_TYPE_EXCLUDING_TAX;
    }

    /**
     * Check if display sales prices include and exclude tax
     *
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return bool
     */
    public function displaySalesPricesBoth($store = null)
    {
        return $this->_getStoreConfig(self::XML_PATH_DISPLAY_SALES_PRICE, $store) == self::DISPLAY_TYPE_BOTH;
    }

    /**
     * Check if display sales subtotal include tax
     *
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return bool
     */
    public function displaySalesSubtotalInclTax($store = null)
    {
        return $this->_getStoreConfig(self::XML_PATH_DISPLAY_SALES_SUBTOTAL, $store)
            == self::DISPLAY_TYPE_INCLUDING_TAX;
    }

    /**
     * Check if display sales subtotal exclude tax
     *
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return bool
     */
    public function displaySalesSubtotalExclTax($store = null)
    {
        return $this->_getStoreConfig(self::XML_PATH_DISPLAY_SALES_SUBTOTAL, $store)
            == self::DISPLAY_TYPE_EXCLUDING_TAX;
    }

    /**
     * Check if display sales subtotal include and exclude tax
     *
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return bool
     */
    public function displaySalesSubtotalBoth($store = null)
    {
        return $this->_getStoreConfig(self::XML_PATH_DISPLAY_SALES_SUBTOTAL, $store) == self::DISPLAY_TYPE_BOTH;
    }

    /**
     * Check if display sales shipping include tax
     *
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return bool
     */
    public function displaySalesShippingInclTax($store = null)
    {
        return $this->_getStoreConfig(self::XML_PATH_DISPLAY_SALES_SHIPPING, $store)
            == self::DISPLAY_TYPE_INCLUDING_TAX;
    }

    /**
     * Check if display sales shipping exclude tax
     *
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return bool
     */
    public function displaySalesShippingExclTax($store = null)
    {
        return $this->_getStoreConfig(self::XML_PATH_DISPLAY_SALES_SHIPPING, $store)
            == self::DISPLAY_TYPE_EXCLUDING_TAX;
    }

    /**
     * Check if display sales shipping include and exclude tax
     *
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return bool
     */
    public function displaySalesShippingBoth($store = null)
    {
        return $this->_getStoreConfig(self::XML_PATH_DISPLAY_SALES_SHIPPING, $store) == self::DISPLAY_TYPE_BOTH;
    }

    /**
     * Check if display sales discount include tax
     *
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return bool
     */
    public function displaySalesDiscountInclTax($store = null)
    {
        return $this->_getStoreConfig(self::XML_PATH_DISPLAY_SALES_DISCOUNT, $store)
            == self::DISPLAY_TYPE_INCLUDING_TAX;
    }

    /**
     * Check if display sales discount exclude tax
     *
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return bool
     */
    public function displaySalestDiscountExclTax($store = null)
    {
        return $this->_getStoreConfig(self::XML_PATH_DISPLAY_SALES_DISCOUNT, $store)
            == self::DISPLAY_TYPE_EXCLUDING_TAX;
    }

    /**
     * Check if display sales discount include and exclude tax
     *
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return bool
     */
    public function displaySalesDiscountBoth($store = null)
    {
        return $this->_getStoreConfig(self::XML_PATH_DISPLAY_SALES_DISCOUNT, $store) == self::DISPLAY_TYPE_BOTH;
    }

    /**
     * Get display sales tax with grand total
     *
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return bool
     */
    public function displaySalesTaxWithGrandTotal($store = null)
    {
        return (bool) $this->_getStoreConfig(self::XML_PATH_DISPLAY_SALES_GRANDTOTAL, $store);
    }

    /**
     * Get display sales full summary
     *
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return bool
     */
    public function displaySalesFullSummary($store = null)
    {
        return (bool) $this->_getStoreConfig(self::XML_PATH_DISPLAY_SALES_FULL_SUMMARY, $store);
    }

    /**
     * Get display sales zero tax
     *
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return bool
     */
    public function displaySalesZeroTax($store = null)
    {
        return (bool) $this->_getStoreConfig(self::XML_PATH_DISPLAY_SALES_ZERO_TAX, $store);
    }

    /**
     * Check if tax calculation type and price display settings are compatible
     *
     * invalid settings if
     *      Tax Calculation Method Based On 'Total' or 'Row'
     *      and at least one Price Display Settings has 'Including and Excluding Tax' value
     *
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return bool
     */
    public function checkDisplaySettings($store = null)
    {
        if ($this->getAlgorithm($store) == Mage_Tax_Model_Calculation::CALC_UNIT_BASE) {
            return true;
        }
        return $this->getPriceDisplayType($store) != self::DISPLAY_TYPE_BOTH
            && $this->getShippingPriceDisplayType($store) != self::DISPLAY_TYPE_BOTH
            && !$this->displayCartPricesBoth($store)
            && !$this->displayCartSubtotalBoth($store)
            && !$this->displayCartShippingBoth($store)
            && !$this->displaySalesPricesBoth($store)
            && !$this->displaySalesSubtotalBoth($store)
            && !$this->displaySalesShippingBoth($store);
    }

    /**
     * Check if tax discount settings are compatible
     *
     * Matrix for invalid discount settings is as follows:
     *      Before Discount / Excluding Tax
     *      Before Discount / Including Tax
     *
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return bool
     */
    public function checkDiscountSettings($store = null)
    {
        return $this->applyTaxAfterDiscount($store);
    }

    /**
     * Return the config value for self::CONFIG_XML_PATH_CROSS_BORDER_TRADE_ENABLED
     *
     * @param null|string|bool|int|Mage_Core_Model_Store $store
     * @return int
     */
    public function crossBorderTradeEnabled($store = null)
    {
        return $this->_getStoreConfig(self::CONFIG_XML_PATH_CROSS_BORDER_TRADE_ENABLED, $store);
    }
}
