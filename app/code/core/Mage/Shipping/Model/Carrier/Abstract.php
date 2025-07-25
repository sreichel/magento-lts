<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Shipping
 */

/**
 * Class Mage_Shipping_Model_Carrier_Abstract
 *
 * @package    Mage_Shipping
 *
 * @method $this setActiveFlag(bool $value)
 * @method array getAllowedMethods()
 * @method array getContainerTypesAll()
 * @method array getContainerTypesFilter()
 * @method Mage_Core_Model_Store getStore()
 * @method $this setStore(Mage_Core_Model_Store $value)
 */
abstract class Mage_Shipping_Model_Carrier_Abstract extends Varien_Object
{
    /**
     * Carrier's code
     *
     * @var string
     */
    protected $_code;

    /**
     * Rates result
     *
     * @var array|null
     */
    protected $_rates;

    /**
     * Number of boxes in package
     *
     * @var int
     */
    protected $_numBoxes = 1;

    /**
     * Free Method config path
     *
     * @var string
     */
    protected $_freeMethod = 'free_method';

    /**
     * Whether this carrier has fixed rates calculation
     *
     * @var bool
     */
    protected $_isFixed = false;

    /**
     * Container types that could be customized
     *
     * @var array
     */
    protected $_customizableContainerTypes = [];

    public const USA_COUNTRY_ID = 'US';
    public const CANADA_COUNTRY_ID = 'CA';
    public const MEXICO_COUNTRY_ID = 'MX';

    public const HANDLING_TYPE_PERCENT = 'P';
    public const HANDLING_TYPE_FIXED = 'F';

    public const HANDLING_ACTION_PERPACKAGE = 'P';
    public const HANDLING_ACTION_PERORDER = 'O';

    /**
     * Fields that should be replaced in debug with '***'
     *
     * @var array
     */
    protected $_debugReplacePrivateDataKeys = [];

    /**
     * Raw rate request data
     *
     * @var Varien_Object|null
     */
    protected $_rawRequest;

    /**
     * Rate result data
     *
     * @var Mage_Shipping_Model_Rate_Result|Mage_Shipping_Model_Tracking_Result|null
     */
    protected $_result;

    /**
     * Retrieve information from carrier configuration
     *
     * @param   string $field
     * @return  string|false
     */
    public function getConfigData($field)
    {
        if (empty($this->_code)) {
            return false;
        }
        $path = 'carriers/' . $this->_code . '/' . $field;
        return Mage::getStoreConfig($path, $this->getStore());
    }

    /**
     * Retrieve config flag for store by field
     *
     * @param string $field
     * @return bool
     */
    public function getConfigFlag($field)
    {
        if (empty($this->_code)) {
            return false;
        }
        $path = 'carriers/' . $this->_code . '/' . $field;
        return Mage::getStoreConfigFlag($path, $this->getStore());
    }

    /**
     * Collect and get rates
     *
     * @abstract
     * @return Mage_Shipping_Model_Rate_Result|bool|null
     */
    abstract public function collectRates(Mage_Shipping_Model_Rate_Request $request);

    /**
     * Do request to shipment
     * Implementation must be in overridden method
     *
     * @return Varien_Object
     */
    public function requestToShipment(Mage_Shipping_Model_Shipment_Request $request)
    {
        return new Varien_Object();
    }

    /**
     * Do return of shipment
     * Implementation must be in overridden method
     *
     * @param mixed $request
     * @return Varien_Object
     */
    public function returnOfShipment($request)
    {
        return new Varien_Object();
    }

    /**
     * Return container types of carrier
     *
     * @return array
     */
    public function getContainerTypes(?Varien_Object $params = null)
    {
        return [];
    }

    /**
     * Get allowed containers of carrier
     *
     * @return array|bool
     */
    protected function _getAllowedContainers(?Varien_Object $params = null)
    {
        $containersAll = $this->getContainerTypesAll();
        if (empty($containersAll)) {
            return [];
        }
        if (!$params instanceof Varien_Object) {
            return $containersAll;
        }
        $containersFilter   = $this->getContainerTypesFilter();
        $containersFiltered = [];
        $method             = $params->getMethod();
        $countryShipper     = $params->getCountryShipper();
        $countryRecipient   = $params->getCountryRecipient();

        if (empty($containersFilter)) {
            return $containersAll;
        }
        if (!$params || !$method || !$countryShipper || !$countryRecipient) {
            return $containersAll;
        }

        if ($countryShipper == self::USA_COUNTRY_ID && $countryRecipient == self::USA_COUNTRY_ID) {
            $direction = 'within_us';
        } elseif ($countryShipper == self::USA_COUNTRY_ID && $countryRecipient != self::USA_COUNTRY_ID) {
            $direction = 'from_us';
        } else {
            return $containersAll;
        }

        foreach ($containersFilter as $dataItem) {
            $containers = $dataItem['containers'];
            $filters = $dataItem['filters'];
            if (!empty($filters[$direction]['method'])
                && in_array($method, $filters[$direction]['method'])
            ) {
                foreach ($containers as $container) {
                    if (!empty($containersAll[$container])) {
                        $containersFiltered[$container] = $containersAll[$container];
                    }
                }
            }
        }

        return !empty($containersFiltered) ? $containersFiltered : $containersAll;
    }

    /**
     * Get Container Types, that could be customized
     *
     * @return array
     */
    public function getCustomizableContainerTypes()
    {
        return $this->_customizableContainerTypes;
    }

    /**
     * Return delivery confirmation types of carrier
     *
     * @return array
     */
    public function getDeliveryConfirmationTypes(?Varien_Object $params = null)
    {
        return [];
    }

    /**
     * @return $this|false|Mage_Shipping_Model_Rate_Result_Error
     */
    public function checkAvailableShipCountries(Mage_Shipping_Model_Rate_Request $request)
    {
        $speCountriesAllow = $this->getConfigData('sallowspecific');
        /*
        * for specific countries, the flag will be 1
        */
        if ($speCountriesAllow && $speCountriesAllow == 1) {
            $showMethod = $this->getConfigData('showmethod');
            $availableCountries = [];
            if ($this->getConfigData('specificcountry')) {
                $availableCountries = explode(',', $this->getConfigData('specificcountry'));
            }
            if ($availableCountries && in_array($request->getDestCountryId(), $availableCountries)) {
                return $this;
            } elseif ($showMethod && (!$availableCountries || ($availableCountries
                 && !in_array($request->getDestCountryId(), $availableCountries)))
            ) {
                $error = Mage::getModel('shipping/rate_result_error');
                $error->setCarrier($this->_code);
                $error->setCarrierTitle($this->getConfigData('title'));
                $errorMsg = $this->getConfigData('specificerrmsg');
                $error->setErrorMessage($errorMsg ? $errorMsg : Mage::helper('shipping')->__('The shipping module is not available for selected delivery country.'));
                return $error;
            } else {
                /*
                * The admin set not to show the shipping module if the devliery country is not within specific countries
                */
                return false;
            }
        }
        return $this;
    }

    /**
     * Processing additional validation to check is carrier applicable.
     *
     * @return $this
     */
    public function proccessAdditionalValidation(Mage_Shipping_Model_Rate_Request $request)
    {
        return $this;
    }

    /**
     * Determine whether current carrier enabled for activity
     *
     * @return bool
     */
    public function isActive()
    {
        $active = $this->getConfigData('active');
        return $active == 1 || $active == 'true';
    }

    /**
     * Whether this carrier has fixed rates calculation
     *
     * @return bool
     */
    public function isFixed()
    {
        return $this->_isFixed;
    }

    /**
     * Check if carrier has shipping tracking option available
     *
     * @return bool
     */
    public function isTrackingAvailable()
    {
        return false;
    }

    /**
     * Check if carrier has shipping label option available
     *
     * @return bool
     */
    public function isShippingLabelsAvailable()
    {
        return false;
    }

    /**
     *  Retrieve sort order of current carrier
     *
     * @return mixed
     */
    public function getSortOrder()
    {
        return $this->getConfigData('sort_order');
    }

    /**
     * @param Mage_Shipping_Model_Rate_Request $request
     */
    protected function _updateFreeMethodQuote($request)
    {
        if ($request->getFreeMethodWeight() == $request->getPackageWeight() || !$request->hasFreeMethodWeight()) {
            return;
        }

        $freeMethod = $this->getConfigData($this->_freeMethod);
        if (!$freeMethod) {
            return;
        }
        $freeRateId = false;

        if (is_object($this->_result)) {
            foreach ($this->_result->getAllRates() as $i => $item) {
                if ($item->getMethod() == $freeMethod) {
                    $freeRateId = $i;
                    break;
                }
            }
        }

        if ($freeRateId === false) {
            return;
        }
        $price = null;
        if ($request->getFreeMethodWeight() > 0) {
            $this->_setFreeMethodRequest($freeMethod);

            $result = $this->_getQuotes();
            if ($result && ($rates = $result->getAllRates()) && count($rates) > 0) {
                if ((count($rates) == 1) && ($rates[0] instanceof Mage_Shipping_Model_Rate_Result_Method)) {
                    $price = $rates[0]->getPrice();
                }
                if (count($rates) > 1) {
                    foreach ($rates as $rate) {
                        if ($rate instanceof Mage_Shipping_Model_Rate_Result_Method
                            && $rate->getMethod() == $freeMethod
                        ) {
                            $price = $rate->getPrice();
                        }
                    }
                }
            }
        } else {
            /**
             * if we can apply free shipping for all order we should force price
             * to $0.00 for shipping with out sending second request to carrier
             */
            $price = 0;
        }

        /**
         * if we did not get our free shipping method in response we must use its old price
         */
        if (!is_null($price)) {
            $this->_result->getRateById($freeRateId)->setPrice($price);
        }
    }

    /**
     * Calculate price considering free shipping and handling fee
     *
     * @param float $cost
     * @param string $method
     * @return float|string
     */
    public function getMethodPrice($cost, $method = '')
    {
        return $method == $this->getConfigData($this->_freeMethod)
            && $this->getConfigFlag('free_shipping_enable')
            && $this->getConfigData('free_shipping_subtotal') <= $this->_rawRequest->getBaseSubtotalInclTax()
            ? '0.00'
            : $this->getFinalPriceWithHandlingFee($cost);
    }

    /**
     * Get the handling fee for the shipping + cost
     *
     * @param float $cost
     * @return float final price for shipping method
     */
    public function getFinalPriceWithHandlingFee($cost)
    {
        $handlingFee = (float) $this->getConfigData('handling_fee');
        $handlingType = $this->getConfigData('handling_type');
        if (!$handlingType) {
            $handlingType = self::HANDLING_TYPE_FIXED;
        }
        $handlingAction = $this->getConfigData('handling_action');
        if (!$handlingAction) {
            $handlingAction = self::HANDLING_ACTION_PERORDER;
        }

        return $handlingAction == self::HANDLING_ACTION_PERPACKAGE
            ? $this->_getPerpackagePrice($cost, $handlingType, $handlingFee)
            : $this->_getPerorderPrice($cost, $handlingType, $handlingFee);
    }

    /**
     * Get final price for shipping method with handling fee per package
     *
     * @param float $cost
     * @param string $handlingType
     * @param float $handlingFee
     * @return float
     */
    protected function _getPerpackagePrice($cost, $handlingType, $handlingFee)
    {
        if ($handlingType == self::HANDLING_TYPE_PERCENT) {
            return ($cost + ($cost * $handlingFee / 100)) * $this->_numBoxes;
        }

        return ($cost + $handlingFee) * $this->_numBoxes;
    }

    /**
     * Get final price for shipping method with handling fee per order
     *
     * @param float $cost
     * @param string $handlingType
     * @param float $handlingFee
     * @return float
     */
    protected function _getPerorderPrice($cost, $handlingType, $handlingFee)
    {
        if ($handlingType == self::HANDLING_TYPE_PERCENT) {
            return ($cost * $this->_numBoxes) + ($cost * $this->_numBoxes * $handlingFee / 100);
        }

        return ($cost * $this->_numBoxes) + $handlingFee;
    }

    /**
     *  Return weight in pounds
     *
     *  @param integer $weight Weight in someone measure
     *  @return float Weight in pounds
     */
    public function convertWeightToLbs($weight)
    {
        return $weight;
    }

    /**
     * set the number of boxes for shipping
     *
     * @param int $weight
     * @return float
     */
    public function getTotalNumOfBoxes($weight)
    {
        /*
        reset num box first before retrieve again
        */
        $this->_numBoxes = 1;
        $weight = $this->convertWeightToLbs($weight);
        $maxPackageWeight = (float) $this->getConfigData('max_package_weight');
        if ($weight > $maxPackageWeight && $maxPackageWeight != 0) {
            $this->_numBoxes = ceil($weight / $maxPackageWeight);
            $weight = $weight / $this->_numBoxes;
        }
        return $weight;
    }

    /**
     * Is state province required
     *
     * @return bool
     */
    public function isStateProvinceRequired()
    {
        return false;
    }

    /**
     * Check if city option required
     *
     * @return bool
     */
    public function isCityRequired()
    {
        return false;
    }

    /**
     * Determine whether zip-code is required for the country of destination
     *
     * @param string|null $countryId
     * @return false
     */
    public function isZipCodeRequired($countryId = null)
    {
        return false;
    }

    /**
     * Log debug data to file
     *
     * @param mixed $debugData
     */
    protected function _debug($debugData)
    {
        if ($this->getDebugFlag()) {
            Mage::getModel('core/log_adapter', 'shipping_' . $this->getCarrierCode() . '.log')
               ->setFilterDataKeys($this->_debugReplacePrivateDataKeys)
               ->log($debugData);
        }
    }

    /**
     * Define if debugging is enabled
     *
     * @return bool
     */
    public function getDebugFlag()
    {
        return $this->getConfigData('debug');
    }

    /**
     * Used to call debug method from not Payment Method context
     *
     * @param mixed $debugData
     */
    public function debugData($debugData)
    {
        $this->_debug($debugData);
    }

    /**
     * Getter for carrier code
     *
     * @return string
     */
    public function getCarrierCode()
    {
        return $this->_code;
    }

    /**
     * Return content types of package
     *
     * @return array
     */
    public function getContentTypes(Varien_Object $params)
    {
        return [];
    }
}
