<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Mage_Index
 */

/**
 * @package    Mage_Index
 *
 * @method Mage_Index_Model_Resource_Event _getResource()
 * @method Mage_Index_Model_Resource_Event getResource()
 * @method $this setType(string $value)
 * @method $this setEntity(string $value)
 * @method bool hasEntityPk()
 * @method int getEntityPk()
 * @method $this setEntityPk(int $value)
 * @method string getCreatedAt()
 * @method $this setCreatedAt(string $value)
 * @method $this setOldData(string|array $value)
 * @method $this setNewData(string|array $value)
 * @method Varien_Object getDataObject()
 * @method $this setDataObject(Varien_Object $value)
 * @method bool hasCreatedAt()
 */
class Mage_Index_Model_Event extends Mage_Core_Model_Abstract
{
    /**
     * Predefined event types
     */
    public const TYPE_SAVE        = 'save';
    public const TYPE_DELETE      = 'delete';
    public const TYPE_MASS_ACTION = 'mass_action';
    public const TYPE_REINDEX     = 'reindex';

    /**
     * Array of related processes ids
     * @var array
     */
    protected $_processIds = null;

    /**
     * New and old data namespace. Used for separate processes data
     *
     * @var string
     */
    protected $_dataNamespace = null;

    /**
     * Process object which currently working with event
     */
    protected $_process = null;

    /**
     * Initialize resource
     */
    protected function _construct()
    {
        $this->_init('index/event');
    }

    /**
     * Specify process object
     *
     * @param Mage_Index_Model_Process|null $process
     * @return $this
     */
    public function setProcess($process)
    {
        $this->_process = $process;
        return $this;
    }

    /**
     * Get related process object
     *
     * @return Mage_Index_Model_Process|null
     */
    public function getProcess()
    {
        return $this->_process;
    }

    /**
     * Specify namespace for old and new data
     * @param string|null $namespace
     * @return $this
     */
    public function setDataNamespace($namespace)
    {
        $this->_dataNamespace = $namespace;
        return $this;
    }

    /**
     * Reset old and new data arrays
     *
     * @return $this
     */
    public function resetData()
    {
        if ($this->_dataNamespace) {
            $data = $this->getNewData(false);
            $data[$this->_dataNamespace] = null;
            $this->setNewData($data);
        } else {
            $this->setNewData([]);
        }
        return $this;
    }

    /**
     * Add process id to event object
     *
     * @param int $processId
     * @param Mage_Index_Model_Process::EVENT_STATUS_* $status
     * @return $this
     */
    public function addProcessId($processId, $status = Mage_Index_Model_Process::EVENT_STATUS_NEW)
    {
        $this->_processIds[$processId] = $status;
        return $this;
    }

    /**
     * Get event process ids
     *
     * @return array
     */
    public function getProcessIds()
    {
        return $this->_processIds;
    }

    /**
     * Merge new data
     *
     * @param array $previous
     * @param mixed $current
     * @return array
     */
    protected function _mergeNewDataRecursive($previous, $current)
    {
        if (!is_array($current)) {
            if (!is_null($current)) {
                $previous[] = $current;
            }
            return $previous;
        }

        foreach (array_keys($previous) as $key) {
            if (array_key_exists($key, $current) && !is_null($current[$key]) && is_array($previous[$key])) {
                if (!is_string($key) || is_array($current[$key])) {
                    $current[$key] = $this->_mergeNewDataRecursive($previous[$key], $current[$key]);
                }
            } elseif (!array_key_exists($key, $current) || is_null($current[$key])) {
                $current[$key] = $previous[$key];
            } elseif (!is_array($previous[$key]) && !is_string($key)) {
                $current[] = $previous[$key];
            }
        }

        return $current;
    }

    /**
     * Merge previous event data to object.
     * Used for events duplicated protection
     *
     * @param array $data
     * @return $this
     */
    public function mergePreviousData($data)
    {
        if (!empty($data['event_id'])) {
            $this->setId($data['event_id']);
            $this->setCreatedAt($data['created_at']);
        }

        if (!empty($data['new_data'])) {
            $previousNewData = unserialize($data['new_data'], ['allowed_classes' => false]);
            $currentNewData  = $this->getNewData(false);
            $currentNewData = $this->_mergeNewDataRecursive($previousNewData, $currentNewData);
            $this->setNewData(serialize($currentNewData));
        }
        return $this;
    }

    /**
     * Clean new data, unset data for done processes
     *
     * @return $this
     */
    public function cleanNewData()
    {
        $processIds = $this->getProcessIds();
        if (!is_array($processIds) || empty($processIds)) {
            return $this;
        }

        $newData = $this->getNewData(false);
        foreach ($processIds as $processId => $processStatus) {
            if ($processStatus == Mage_Index_Model_Process::EVENT_STATUS_DONE) {
                $process = Mage::getSingleton('index/indexer')->getProcessById($processId);
                if ($process) {
                    $namespace = $process->getIndexer()::class;
                    if (array_key_exists($namespace, $newData)) {
                        unset($newData[$namespace]);
                    }
                }
            }
        }
        $this->setNewData(serialize($newData));

        return $this;
    }

    /**
     * Get event old data array
     *
     * @deprecated since 1.6.2.0
     * @param bool $useNamespace
     * @return array
     */
    public function getOldData($useNamespace = true)
    {
        return [];
    }

    /**
     * Get event new data array
     *
     * @param bool $useNamespace
     * @return array
     */
    public function getNewData($useNamespace = true)
    {
        $data = $this->_getData('new_data');
        if (is_string($data)) {
            $data = unserialize($data, ['allowed_classes' => false]);
        } elseif (empty($data) || !is_array($data)) {
            $data = [];
        }
        if ($useNamespace && $this->_dataNamespace) {
            return $data[$this->_dataNamespace] ?? [];
        }
        return $data;
    }

    /**
     * Add new values to old data array (overwrite if value with same key exist)
     *
     * @param array|string $key
     * @param null|mixed $value
     * @return $this
     * @deprecated since 1.6.2.0
     */
    public function addOldData($key, $value = null)
    {
        return $this;
    }

    /**
     * Add new values to new data array (overwrite if value with same key exist)
     *
     * @param array|string $key
     * @param null|mixed $value
     * @return $this
     */
    public function addNewData($key, $value = null)
    {
        $newData = $this->getNewData(false);
        if (!is_array($key)) {
            $key = [$key => $value];
        }
        if ($this->_dataNamespace) {
            if (!isset($newData[$this->_dataNamespace])) {
                $newData[$this->_dataNamespace] = [];
            }
            $newData[$this->_dataNamespace] = array_merge($newData[$this->_dataNamespace], $key);
        } else {
            $newData = array_merge($newData, $key);
        }
        $this->setNewData($newData);
        return $this;
    }

    /**
     * Get event entity code.
     * Entity code declare what kind of data object related with event (product, category etc.)
     *
     * @return string
     */
    public function getEntity()
    {
        return $this->_getData('entity');
    }

    /**
     * Get event action type.
     * Data related on self::TYPE_* constants
     *
     * @return string
     */
    public function getType()
    {
        return $this->_getData('type');
    }

    /**
     * Serelaize old and new data arrays before saving
     *
     * @inheritDoc
     */
    protected function _beforeSave()
    {
        $newData = $this->getNewData(false);
        $this->setNewData(serialize($newData));
        if (!$this->hasCreatedAt()) {
            $this->setCreatedAt($this->_getResource()->formatDate(time(), true));
        }
        return parent::_beforeSave();
    }
}
