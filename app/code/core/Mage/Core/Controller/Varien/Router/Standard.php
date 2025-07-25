<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Core
 */

/**
 * @package    Mage_Core
 */
class Mage_Core_Controller_Varien_Router_Standard extends Mage_Core_Controller_Varien_Router_Abstract
{
    protected $_modules = [];
    protected $_routes = [];
    protected $_dispatchData = [];

    /**
     * @param string $configArea
     * @param string $useRouterName
     */
    public function collectRoutes($configArea, $useRouterName)
    {
        $routers = [];
        $routersConfigNode = Mage::getConfig()->getNode($configArea . '/routers');
        if ($routersConfigNode) {
            $routers = $routersConfigNode->children();
        }
        foreach ($routers as $routerName => $routerConfig) {
            $use = (string) $routerConfig->use;
            if ($use == $useRouterName) {
                $modules = [(string) $routerConfig->args->module];
                if ($routerConfig->args->modules) {
                    /** @var Varien_Simplexml_Element $customModule */
                    foreach ($routerConfig->args->modules->children() as $customModule) {
                        if ((string) $customModule) {
                            if ($before = $customModule->getAttribute('before')) {
                                $position = array_search($before, $modules);
                                if ($position === false) {
                                    $position = 0;
                                }
                                array_splice($modules, $position, 0, (string) $customModule);
                            } elseif ($after = $customModule->getAttribute('after')) {
                                $position = array_search($after, $modules);
                                if ($position === false) {
                                    $position = count($modules);
                                }
                                array_splice($modules, $position + 1, 0, (string) $customModule);
                            } else {
                                $modules[] = (string) $customModule;
                            }
                        }
                    }
                }

                $frontName = (string) $routerConfig->args->frontName;
                $this->addModule($frontName, $modules, $routerName);
            }
        }
    }

    public function fetchDefault()
    {
        $this->getFront()->setDefault([
            'module' => 'core',
            'controller' => 'index',
            'action' => 'index',
        ]);
    }

    /**
     * checking if this admin if yes then we don't use this router
     *
     * @return bool
     */
    protected function _beforeModuleMatch()
    {
        if (Mage::app()->getStore()->isAdmin()) {
            return false;
        }
        return true;
    }

    /**
     * dummy call to pass through checking
     *
     * @return bool
     */
    protected function _afterModuleMatch()
    {
        return true;
    }

    /**
     * Match the request
     *
     * @param Mage_Core_Controller_Request_Http $request
     * @inheritDoc
     */
    public function match(Zend_Controller_Request_Http $request)
    {
        //checking before even try to find out that current module
        //should use this router
        if (!$this->_beforeModuleMatch()) {
            return false;
        }

        $this->fetchDefault();

        $front = $this->getFront();
        $path = trim($request->getPathInfo(), '/');

        if ($path) {
            $p = explode('/', $path);
        } else {
            $p = explode('/', $this->_getDefaultPath());
        }

        // get module name
        if ($request->getModuleName()) {
            $module = $request->getModuleName();
        } elseif (!empty($p[0])) {
            $module = $p[0];
        } else {
            $module = $this->getFront()->getDefault('module');
            $request->setAlias(Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS, '');
        }
        if (!$module) {
            if (Mage::app()->getStore()->isAdmin()) {
                $module = 'admin';
            } else {
                return false;
            }
        }

        /**
         * Searching router args by module name from route using it as key
         */
        $modules = $this->getModuleByFrontName($module);

        if ($modules === false) {
            return false;
        }

        // checks after we found out that this router should be used for current module
        if (!$this->_afterModuleMatch()) {
            return false;
        }

        /**
         * Going through modules to find appropriate controller
         */
        $found = false;
        foreach ($modules as $realModule) {
            $request->setRouteName($this->getRouteByFrontName($module));

            // get controller name
            if ($request->getControllerName()) {
                $controller = $request->getControllerName();
            } elseif (!empty($p[1])) {
                $controller = $p[1];
            } else {
                $controller = $front->getDefault('controller');
                $request->setAlias(
                    Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS,
                    ltrim($request->getOriginalPathInfo(), '/'),
                );
            }

            // get action name
            if (empty($action)) {
                if ($request->getActionName()) {
                    $action = $request->getActionName();
                } else {
                    $action = !empty($p[2]) ? $p[2] : $front->getDefault('action');
                }
            }

            //checking if this place should be secure
            $this->_checkShouldBeSecure($request, '/' . $module . '/' . $controller . '/' . $action);

            $controllerClassName = $this->_validateControllerClassName($realModule, $controller);
            if (!$controllerClassName) {
                continue;
            }

            // instantiate controller class
            $controllerInstance = Mage::getControllerInstance($controllerClassName, $request, $front->getResponse());

            if (!$this->_validateControllerInstance($controllerInstance)) {
                continue;
            }

            if (!$controllerInstance->hasAction($action)) {
                continue;
            }

            $found = true;
            break;
        }

        /**
         * if we did not found any suitable
         */
        if (!$found) {
            if (isset($realModule) && $this->_noRouteShouldBeApplied()) {
                $controller = 'index';
                $action = 'noroute';

                $controllerClassName = $this->_validateControllerClassName($realModule, $controller);
                if (!$controllerClassName) {
                    return false;
                }

                // instantiate controller class
                $controllerInstance = Mage::getControllerInstance(
                    $controllerClassName,
                    $request,
                    $front->getResponse(),
                );

                if (!$controllerInstance->hasAction($action)) {
                    return false;
                }
            } else {
                return false;
            }
        }

        // set values only after all the checks are done
        $request->setModuleName($module);

        if (isset($controller)) {
            $request->setControllerName($controller);
        }
        if (isset($action)) {
            $request->setActionName($action);
        }
        if (isset($realModule)) {
            $request->setControllerModule($realModule);
        }

        // set parameters from path info
        for ($i = 3, $l = count($p); $i < $l; $i += 2) {
            $request->setParam($p[$i], isset($p[$i + 1]) ? urldecode($p[$i + 1]) : '');
        }

        // dispatch action
        $request->setDispatched(true);
        if (isset($controllerInstance, $action)) {
            $controllerInstance->dispatch($action);
        }

        return true;
    }

    /**
     * Get router default request path
     * @return string
     */
    protected function _getDefaultPath()
    {
        return Mage::getStoreConfig('web/default/front');
    }

    /**
     * Allow to control if we need to enable no route functionality in current router
     *
     * @return bool
     */
    protected function _noRouteShouldBeApplied()
    {
        return false;
    }

    /**
     * Check if current controller instance is allowed in current router.
     *
     * @param Mage_Core_Controller_Varien_Action $controllerInstance
     * @return bool
     */
    protected function _validateControllerInstance($controllerInstance)
    {
        return $controllerInstance instanceof Mage_Core_Controller_Front_Action;
    }

    /**
     * Generating and validating class file name,
     * class and if everything ok do include if needed and return of class name
     *
     * @param string $realModule
     * @param string $controller
     * @return false|string
     * @throws Mage_Core_Exception
     */
    protected function _validateControllerClassName($realModule, $controller)
    {
        $controllerFileName = $this->getControllerFileName($realModule, $controller);
        if (!$this->validateControllerFileName($controllerFileName)) {
            return false;
        }

        $controllerClassName = $this->getControllerClassName($realModule, $controller);
        if (!$controllerClassName) {
            return false;
        }

        // include controller file if needed
        if (!$this->_includeControllerClass($controllerFileName, $controllerClassName)) {
            return false;
        }

        return $controllerClassName;
    }

    /**
     * @param string $controllerFileName
     * @param string $controllerClassName
     * @return bool
     * @throws Mage_Core_Exception
     * @deprecated
     * @see _includeControllerClass()
     */
    protected function _inludeControllerClass($controllerFileName, $controllerClassName)
    {
        return $this->_includeControllerClass($controllerFileName, $controllerClassName);
    }

    /**
     * Include the file containing controller class if this class is not defined yet
     *
     * @param string $controllerFileName
     * @param string $controllerClassName
     * @return bool
     */
    protected function _includeControllerClass($controllerFileName, $controllerClassName)
    {
        if (!class_exists($controllerClassName, false)) {
            if (!file_exists($controllerFileName)) {
                return false;
            }
            include $controllerFileName;

            if (!class_exists($controllerClassName, false)) {
                throw Mage::exception('Mage_Core', Mage::helper('core')->__('Controller file was loaded but class does not exist'));
            }
        }
        return true;
    }

    /**
     * @param string $frontName
     * @param array $moduleNames
     * @param string $routeName
     * @return $this
     */
    public function addModule($frontName, $moduleNames, $routeName)
    {
        $this->_modules[$frontName] = $moduleNames;
        $this->_routes[$routeName] = $frontName;
        return $this;
    }

    /**
     * @param string $frontName
     * @return bool|array
     */
    public function getModuleByFrontName($frontName)
    {
        return $this->_modules[$frontName] ?? false;
    }

    /**
     * @param string $moduleName
     * @param array $modules
     * @return bool
     */
    public function getModuleByName($moduleName, $modules)
    {
        foreach ($modules as $module) {
            if ($moduleName === $module || (is_array($module)
                    && $this->getModuleByName($moduleName, $module))
            ) {
                return true;
            }
        }
        return false;
    }

    /**
     * @param string $routeName
     * @return false|string
     */
    public function getFrontNameByRoute($routeName)
    {
        return $this->_routes[$routeName] ?? false;
    }

    /**
     * @param string $frontName
     * @return false|int|string
     */
    public function getRouteByFrontName($frontName)
    {
        return array_search($frontName, $this->_routes);
    }

    /**
     * @param string $realModule
     * @param string $controller
     * @return string
     */
    public function getControllerFileName($realModule, $controller)
    {
        $parts = explode('_', $realModule);
        $realModule = implode('_', array_splice($parts, 0, 2));
        $file = Mage::getModuleDir('controllers', $realModule);
        if (count($parts)) {
            $file .= DS . implode(DS, $parts);
        }
        return $file . (DS . uc_words($controller, DS) . 'Controller.php');
    }

    /**
     * @param string $fileName
     * @return bool
     */
    public function validateControllerFileName($fileName)
    {
        if ($fileName && is_readable($fileName) && !str_contains($fileName, '//')) {
            return true;
        }
        return false;
    }

    /**
     * @param string $realModule
     * @param string $controller
     * @return string
     */
    public function getControllerClassName($realModule, $controller)
    {
        return $realModule . '_' . uc_words($controller) . 'Controller';
    }

    /**
     * @param string[] $p
     * @return string[]
     */
    public function rewrite(array $p)
    {
        $rewrite = Mage::getConfig()->getNode('global/rewrite');
        if ($module = $rewrite->{$p[0]}) {
            if (!$module->children()) {
                $p[0] = trim((string) $module);
            }
        }
        if (isset($p[1]) && ($controller = $rewrite->{$p[0]}->{$p[1]})) {
            if (!$controller->children()) {
                $p[1] = trim((string) $controller);
            }
        }
        if (isset($p[2]) && ($action = $rewrite->{$p[0]}->{$p[1]}->{$p[2]})) {
            if (!$action->children()) {
                $p[2] = trim((string) $action);
            }
        }

        return $p;
    }

    /**
     * Check that request uses https protocol if it should.
     * Function redirects user to correct URL if needed.
     *
     * @param Mage_Core_Controller_Request_Http $request
     * @param string $path
     * @SuppressWarnings("PHPMD.ExitExpression")
     */
    protected function _checkShouldBeSecure($request, $path = '')
    {
        if (!Mage::isInstalled() || $request->getPost()) {
            return;
        }

        if ($this->_shouldBeSecure($path) && !$request->isSecure()) {
            $url = $this->_getCurrentSecureUrl($request);
            if ($request->getRouteName() != 'adminhtml' && Mage::app()->getUseSessionInUrl()) {
                $url = Mage::getSingleton('core/url')->getRedirectUrl($url);
            }

            Mage::app()->getFrontController()->getResponse()
                ->setRedirect($url)
                ->sendResponse();
            exit;
        }
    }

    /**
     * @param Mage_Core_Controller_Request_Http $request
     * @return string
     */
    protected function _getCurrentSecureUrl($request)
    {
        if ($alias = $request->getAlias(Mage_Core_Model_Url_Rewrite::REWRITE_REQUEST_PATH_ALIAS)) {
            return Mage::getBaseUrl('link', true) . ltrim($alias, '/');
        }

        return Mage::getBaseUrl('link', true) . ltrim($request->getPathInfo(), '/');
    }

    /**
     * Check whether URL for corresponding path should use https protocol
     *
     * @param string $path
     * @return bool
     */
    protected function _shouldBeSecure($path)
    {
        return str_starts_with(Mage::getStoreConfig(Mage_Core_Model_Store::XML_PATH_UNSECURE_BASE_URL), 'https')
            || Mage::getStoreConfigFlag(Mage_Core_Model_Store::XML_PATH_SECURE_IN_FRONTEND)
                && str_starts_with(Mage::getStoreConfig(Mage_Core_Model_Store::XML_PATH_SECURE_BASE_URL), 'https')
                && Mage::getConfig()->shouldUrlBeSecure($path);
    }
}
