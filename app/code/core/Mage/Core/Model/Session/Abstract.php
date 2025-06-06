<?php

/**
 * OpenMage
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available at https://opensource.org/license/osl-3-0-php
 *
 * @category   Mage
 * @package    Mage_Core
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) 2019-2025 The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Core Session Abstract model
 *
 * @category   Mage
 * @package    Mage_Core
 *
 * @method string getErrorMessage()
 * @method $this setErrorMessage(string $value)
 * @method $this unsErrorMessage()
 * @method string getSuccessMessage()
 * @method $this setSuccessMessage(string $value)
 * @method $this unsSuccessMessage()
 * @method $this setMessages(Mage_Core_Model_Abstract|Mage_Core_Model_Message_Collection $value)
 */
class Mage_Core_Model_Session_Abstract extends Mage_Core_Model_Session_Abstract_Varien
{
    public const XML_PATH_COOKIE_DOMAIN        = 'web/cookie/cookie_domain';
    public const XML_PATH_COOKIE_PATH          = 'web/cookie/cookie_path';
    public const XML_PATH_COOKIE_LIFETIME      = 'web/cookie/cookie_lifetime';
    public const XML_NODE_SESSION_SAVE         = 'global/session_save';
    public const XML_NODE_SESSION_SAVE_PATH    = 'global/session_save_path';

    public const XML_PATH_USE_REMOTE_ADDR      = 'web/session/use_remote_addr';
    public const XML_PATH_USE_HTTP_VIA         = 'web/session/use_http_via';
    public const XML_PATH_USE_X_FORWARDED      = 'web/session/use_http_x_forwarded_for';
    public const XML_PATH_USE_USER_AGENT       = 'web/session/use_http_user_agent';
    public const XML_PATH_USE_FRONTEND_SID     = 'web/session/use_frontend_sid';

    public const XML_NODE_USET_AGENT_SKIP      = 'global/session/validation/http_user_agent_skip';
    public const XML_PATH_LOG_EXCEPTION_FILE   = 'dev/log/exception_file';

    public const SESSION_ID_QUERY_PARAM        = 'SID';

    /**
     * URL host cache
     *
     * @var array
     */
    protected static $_urlHostCache = [];

    /**
     * Encrypted session id cache
     *
     * @var string
     */
    protected static $_encryptedSessionId;

    /**
     * Skip session id flag
     *
     * @var bool
     */
    protected $_skipSessionIdFlag   = false;

    /**
     * Init session
     *
     * @param string $namespace
     * @param string $sessionName
     * @return $this
     */
    public function init($namespace, $sessionName = null)
    {
        parent::init($namespace, $sessionName);
        $this->addHost(true);
        return $this;
    }

    /**
     * Retrieve Cookie domain
     *
     * @return string
     */
    public function getCookieDomain()
    {
        return $this->getCookie()->getDomain();
    }

    /**
     * Retrieve cookie path
     *
     * @return string
     */
    public function getCookiePath()
    {
        return $this->getCookie()->getPath();
    }

    /**
     * Retrieve cookie lifetime
     *
     * @return int
     */
    public function getCookieLifetime()
    {
        return $this->getCookie()->getLifetime();
    }

    /**
     * Use REMOTE_ADDR in validator key
     *
     * @return bool
     */
    public function useValidateRemoteAddr()
    {
        $use = Mage::getStoreConfig(self::XML_PATH_USE_REMOTE_ADDR);
        if (is_null($use)) {
            return parent::useValidateRemoteAddr();
        }
        return (bool) $use;
    }

    /**
     * Use HTTP_VIA in validator key
     *
     * @return bool
     */
    public function useValidateHttpVia()
    {
        $use = Mage::getStoreConfig(self::XML_PATH_USE_HTTP_VIA);
        if (is_null($use)) {
            return parent::useValidateHttpVia();
        }
        return (bool) $use;
    }

    /**
     * Use HTTP_X_FORWARDED_FOR in validator key
     *
     * @return bool
     */
    public function useValidateHttpXForwardedFor()
    {
        $use = Mage::getStoreConfig(self::XML_PATH_USE_X_FORWARDED);
        if (is_null($use)) {
            return parent::useValidateHttpXForwardedFor();
        }
        return (bool) $use;
    }

    /**
     * Use HTTP_USER_AGENT in validator key
     *
     * @return bool
     */
    public function useValidateHttpUserAgent()
    {
        $use = Mage::getStoreConfig(self::XML_PATH_USE_USER_AGENT);
        if (is_null($use)) {
            return parent::useValidateHttpUserAgent();
        }
        return (bool) $use;
    }

    /**
     * Check whether SID can be used for session initialization
     * Admin area will always have this feature enabled
     *
     * @return bool
     */
    public function useSid()
    {
        return Mage::app()->getStore()->isAdmin() || Mage::getStoreConfig(self::XML_PATH_USE_FRONTEND_SID);
    }

    /**
     * Retrieve skip User Agent validation strings (Flash etc)
     *
     * @return array
     */
    public function getValidateHttpUserAgentSkip()
    {
        $userAgents = [];
        $skip = Mage::getConfig()->getNode(self::XML_NODE_USET_AGENT_SKIP);
        foreach ($skip->children() as $userAgent) {
            $userAgents[] = (string) $userAgent;
        }
        return $userAgents;
    }

    /**
     * Retrieve messages from session
     *
     * @param   bool $clear
     * @return  Mage_Core_Model_Message_Collection
     */
    public function getMessages($clear = false)
    {
        if (!$this->getData('messages')) {
            $this->setMessages(Mage::getModel('core/message_collection'));
        }

        if ($clear) {
            $messages = clone $this->getData('messages');
            $this->getData('messages')->clear();
            Mage::dispatchEvent('core_session_abstract_clear_messages');
            return $messages;
        }
        return $this->getData('messages');
    }

    /**
     * Not Mage exception handling
     *
     * @param   string $alternativeText
     * @return  $this
     */
    public function addException(Exception $exception, $alternativeText)
    {
        Mage::logException($exception);
        $this->addError($alternativeText);
        return $this;
    }

    /**
     * Adding new message to message collection
     *
     * @return  $this
     */
    public function addMessage(Mage_Core_Model_Message_Abstract $message)
    {
        $this->getMessages()->add($message);
        Mage::dispatchEvent('core_session_abstract_add_message');
        return $this;
    }

    /**
     * Adding new error message
     *
     * @param   string $message
     * @return  $this
     */
    public function addError($message)
    {
        $this->addMessage(Mage::getSingleton('core/message')->error($message));
        return $this;
    }

    /**
     * Adding new warning message
     *
     * @param   string $message
     * @return  $this
     */
    public function addWarning($message)
    {
        $this->addMessage(Mage::getSingleton('core/message')->warning($message));
        return $this;
    }

    /**
     * Adding new notice message
     *
     * @param   string $message
     * @return  $this
     */
    public function addNotice($message)
    {
        $this->addMessage(Mage::getSingleton('core/message')->notice($message));
        return $this;
    }

    /**
     * Adding new success message
     *
     * @param   string $message
     * @return  $this
     */
    public function addSuccess($message)
    {
        $this->addMessage(Mage::getSingleton('core/message')->success($message));
        return $this;
    }

    /**
     * Adding messages array to message collection
     *
     * @param   array $messages
     * @return  $this
     */
    public function addMessages($messages)
    {
        if (is_array($messages)) {
            foreach ($messages as $message) {
                $this->addMessage($message);
            }
        }
        return $this;
    }

    /**
     * Adds messages array to message collection, but doesn't add duplicates to it
     *
     * @param   array|string|Mage_Core_Model_Message_Abstract $messages
     * @return  $this
     */
    public function addUniqueMessages($messages)
    {
        if (!is_array($messages)) {
            $messages = [$messages];
        }
        if (!$messages) {
            return $this;
        }

        $messagesAlready = [];
        $items = $this->getMessages()->getItems();
        foreach ($items as $item) {
            if ($item instanceof Mage_Core_Model_Message_Abstract) {
                $text = $item->getText();
            } elseif (is_string($item)) {
                $text = $item;
            } else {
                continue; // Some unknown object, do not put it in already existing messages
            }
            $messagesAlready[$text] = true;
        }

        foreach ($messages as $message) {
            if ($message instanceof Mage_Core_Model_Message_Abstract) {
                $text = $message->getText();
            } elseif (is_string($message)) {
                $text = $message;
            } else {
                $text = null; // Some unknown object, add it anyway
            }

            // Check for duplication
            if ($text !== null) {
                if (isset($messagesAlready[$text])) {
                    continue;
                }
                $messagesAlready[$text] = true;
            }
            $this->addMessage($message);
        }

        return $this;
    }

    /**
     * Specify session identifier
     *
     * @inheritDoc
     * @SuppressWarnings("PHPMD.Superglobals")
     */
    public function setSessionId($id = null)
    {
        if (is_null($id) && $this->useSid()) {
            $queryParam = $this->getSessionIdQueryParam();
            if (isset($_GET[$queryParam]) && Mage::getSingleton('core/url')->isOwnOriginUrl()) {
                $id = $_GET[$queryParam];
            }
        }

        $this->addHost(true);
        return parent::setSessionId($id);
    }

    /**
     * Get encrypted session identifier.
     * No reason use crypt key for session id encryption, we can use session identifier as is.
     *
     * @return string
     */
    public function getEncryptedSessionId()
    {
        if (!self::$_encryptedSessionId) {
            self::$_encryptedSessionId = $this->getSessionId();
        }
        return self::$_encryptedSessionId;
    }

    /**
     * @return string
     */
    public function getSessionIdQueryParam()
    {
        $sessionName = $this->getSessionName();
        if ($sessionName && $queryParam = (string) Mage::getConfig()->getNode($sessionName . '/session/query_param')) {
            return $queryParam;
        }
        return self::SESSION_ID_QUERY_PARAM;
    }

    /**
     * Set skip flag if need skip generating of _GET session_id_key param
     *
     * @param bool $flag
     * @return $this
     */
    public function setSkipSessionIdFlag($flag)
    {
        $this->_skipSessionIdFlag = $flag;
        return $this;
    }

    /**
     * Retrieve session id skip flag
     *
     * @return bool
     */
    public function getSkipSessionIdFlag()
    {
        return $this->_skipSessionIdFlag;
    }

    /**
     * If session cookie is not applicable due to host or path mismatch - add session id to query
     *
     * @param string $urlHost can be host or url
     * @return string {session_id_key}={session_id_encrypted}
     * @SuppressWarnings("PHPMD.CamelCaseVariableName")
     */
    public function getSessionIdForHost($urlHost)
    {
        if ($this->getSkipSessionIdFlag() === true) {
            return '';
        }

        $httpHost = Mage::app()->getFrontController()->getRequest()->getHttpHost();
        if (!$httpHost) {
            return '';
        }

        $urlHostArr = explode('/', $urlHost, 4);
        if (!empty($urlHostArr[2])) {
            $urlHost = $urlHostArr[2];
        }
        $urlPath = empty($urlHostArr[3]) ? '' : $urlHostArr[3];

        if (!isset(self::$_urlHostCache[$urlHost])) {
            $urlHostArr = explode(':', $urlHost);
            $urlHost = $urlHostArr[0];
            $sessionId = $httpHost !== $urlHost && !$this->isValidForHost($urlHost)
                ? $this->getEncryptedSessionId() : '';
            self::$_urlHostCache[$urlHost] = $sessionId;
        }

        return Mage::app()->getStore()->isAdmin() || $this->isValidForPath($urlPath) ? self::$_urlHostCache[$urlHost]
            : $this->getEncryptedSessionId();
    }

    /**
     * Check if session is valid for given hostname
     *
     * @param string $host
     * @return bool
     */
    public function isValidForHost($host)
    {
        $hostArr = explode(':', $host);
        $hosts = $this->getSessionHosts();
        return !empty($hosts[$hostArr[0]]);
    }

    /**
     * Check if session is valid for given path
     *
     * @param string $path
     * @return bool
     */
    public function isValidForPath($path)
    {
        $cookiePath = trim($this->getCookiePath(), '/') . '/';
        if ($cookiePath == '/') {
            return true;
        }

        $urlPath = trim($path, '/') . '/';

        return str_starts_with($urlPath, $cookiePath);
    }

    /**
     * Add hostname to session
     *
     * @param string $host
     * @return $this
     */
    public function addHost($host)
    {
        if ($host === true) {
            if (!$host = Mage::app()->getFrontController()->getRequest()->getHttpHost()) {
                return $this;
            }
        }

        if (!$host) {
            return $this;
        }

        $hosts = $this->getSessionHosts();
        $hosts[$host] = true;
        $this->setSessionHosts($hosts);
        return $this;
    }

    /**
     * Retrieve session hostnames
     *
     * @return array
     */
    public function getSessionHosts()
    {
        return parent::getSessionHosts();
    }

    /**
     * Retrieve session save method
     *
     * @return Mage_Core_Model_Config_Element|Varien_Simplexml_Element|false|string
     */
    public function getSessionSaveMethod()
    {
        if (Mage::isInstalled() && $sessionSave = Mage::getConfig()->getNode(self::XML_NODE_SESSION_SAVE)) {
            return $sessionSave;
        }
        return parent::getSessionSaveMethod();
    }

    /**
     * Get session save path
     *
     * @return Mage_Core_Model_Config_Element|Varien_Simplexml_Element|false|string
     */
    public function getSessionSavePath()
    {
        if (Mage::isInstalled() && $sessionSavePath = Mage::getConfig()->getNode(self::XML_NODE_SESSION_SAVE_PATH)) {
            return $sessionSavePath;
        }
        return parent::getSessionSavePath();
    }

    /**
     * Renew session id and update session cookie
     *
     * @return $this
     */
    public function renewSession()
    {
        $this->getCookie()->delete($this->getSessionName());
        $this->regenerateSessionId();

        $sessionHosts = $this->getSessionHosts();
        $currentCookieDomain = $this->getCookie()->getDomain();
        if (is_array($sessionHosts)) {
            foreach (array_keys($sessionHosts) as $host) {
                // Delete cookies with the same name for parent domains
                if (strpos($currentCookieDomain, (string) $host) > 0) {
                    // phpcs:ignore Ecg.Performance.Loop.ModelLSD
                    $this->getCookie()->delete($this->getSessionName(), null, $host);
                }
            }
        }

        return $this;
    }
}
