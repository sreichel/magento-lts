<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    Varien_File
 */

/**
 * File Object
 * *
 * @package    Varien_File
 */

require_once('Varien/Object.php');
require_once('Varien/Directory/IFactory.php');

class Varien_File_Object extends SplFileObject implements IFactory
{
    protected $_filename;
    protected $_path;
    protected $_filter;
    protected $_isCorrect = true; # - pass or not filter checking
    protected $filtered;

    /**
     * Constructor
     *
     * @param   string $path - path to directory
     * @return  none
     */
    public function __construct($path)
    {
        parent::__construct($path);
        $this->_path = $path;
        $this->_filename = basename($path);
    }
    /**
     * add file name to array
     *
     * @param   array &$files - array of files
     * @return  none
     */
    public function getFilesName(&$files)
    {
        $this->getFileName($files);
    }
    /**
     * add file name to array
     *
     * @param   array &$files - array of files
     * @return  none
     */
    public function getFileName(&$files = null)
    {
        if ($this->_isCorrect) {
            if ($files === null) {
                return $this->_filename;
            }
            $files[] = $this->_filename;
        }
    }
    /**
     * add file path to array
     *
     * @param   array &$paths - array of paths
     * @return  none
     */
    public function getFilesPaths(&$paths)
    {
        if ($this->_isCorrect) {
            $paths[] = (string) $this->_path;
        }
    }
    /**
     * add file path to array
     *
     * @param   array &$paths - array of paths
     * @return  none
     */
    public function getFilePath(&$path = null)
    {
        if ($this->_isCorrect) {
            if ($path === null) {
                return $this->_path;
            }
            $paths[] = $this->_path;
        }
    }
    /**
     * use filter
     *
     * @param   bool $useFilter - use or not filter
     * @return  none
     */
    public function useFilter($useFilter)
    {
        if ($useFilter) {
            $this->renderFilter();
        } else {
            $this->_isCorrect = true;
            $this->filtered = false;
        }
    }
    /**
     * add file object to array
     *
     * @param   array &$objs - array of gile objects
     * @return  none
     */
    public function getFilesObj(&$objs)
    {
        if ($this->_isCorrect) {
            $objs[] = $this;
        }
    }
    /**
     * nothing
     *
     * @param   array &$dirs - array of dirs
     * @return  none
     */
    public function getDirsName(&$dirs)
    {
        return Varien_Directory_Collection::getLastDir($this->_path);
    }
    /**
     * nothing
     *
     * @param   array &$dirs - array of dirs
     * @return  none
     */
    public function getDirName()
    {
        return Varien_Directory_Collection::lastDir($this->_path);
    }
    /**
     * set file filter
     *
     * @param   array $filter - array of filter
     * @return  none
     */
    public function setFilesFilter($filter)
    {
        $this->addFilter($filter);
    }
    /**
     * set file filter
     *
     * @param   array $filter - array of filter
     * @return  none
     */
    public function addFilter($filter)
    {
        $this->_filter = $filter;
    }
    /**
     * get extension of file
     *
     * @return  string - extension of file
     */
    public function getExtension()
    {
        return self::getExt($this->_filename);
    }
    /**
     * get extension of file
     *
     * @param   string $fileName - name of file
     * @return  string - extension of file
     */
    public static function getExt($fileName)
    {
        $path_parts = pathinfo($fileName);
        if (isset($path_parts['extension'])) {
            return $path_parts['extension'];
        } else {
            return '';
        }
    }
    /**
     * get name of file
     *
     * @return  string - name of file
     */
    public function getName()
    {
        return basename($this->_filename, '.' . $this->getExtension());
    }
    /**
     * render filters
     *
     * @return  none
     */
    public function renderFilter()
    {
        if (isset($this->_filter) && count($this->_filter) > 0 && $this->filtered == false) {
            $this->filtered = true;
            if (isset($this->_filter['extension'])) {
                $filter = $this->_filter['extension'];
                if ($filter != null) {
                    if (is_array($filter)) {
                        if (!in_array($this->getExtension(), $filter)) {
                            $this->_isCorrect = false;
                        }
                    } elseif ($this->getExtension() != $filter) {
                        $this->_isCorrect = false;
                    }
                }
            }
            if (isset($this->_filter['name'])) {
                $filter = $this->_filter['name'];
                if ($filter != null) {
                    if (is_array($filter)) {
                        if (!in_array($this->getName(), $filter)) {
                            $this->_isCorrect = false;
                        }
                    } elseif ($this->getName() != $filter) {
                        $this->_isCorrect = false;
                    }
                }
            }

            if (isset($this->_filter['regName'])) {
                $filter = $this->_filter['regName'];

                if ($filter != null) {
                    foreach ($filter as $value) {
                        if (!preg_match($value, $this->getName())) {
                            $this->_isCorrect = false;
                        }
                    }
                }
            }
        }
    }
    /**
     * add to array file name
     *
     * @param   array &$arr -export array
     * @return  none
     */
    public function toArray(&$arr)
    {
        if ($this->_isCorrect) {
            $arr['files_in_dirs'][] = $this->_filename;
        }
    }
    /**
     * add to xml file name
     *
     * @param   array &$xml -export xml
     * @param   int $recursionLevel - level of recursion
     * @param   bool $addOpenTag - nothing
     * @param   string $rootName - nothing
     * @return  none
     */
    public function toXml(&$xml, $recursionLevel = 0, $addOpenTag = true, $rootName = 'Struct')
    {
        if ($this->_isCorrect) {
            $xml .= str_repeat("\t", $recursionLevel + 2) . '<fileName>' . $this->_filename . '</fileName>' . "\n";
        }
    }
}
