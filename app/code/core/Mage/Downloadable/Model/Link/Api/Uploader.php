<?php

/**
 * OpenMage
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available at https://opensource.org/license/osl-3-0-php
 *
 * @category   Mage
 * @package    Mage_Downloadable
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) 2020-2024 The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * File uploader for API
 *
 * @category   Mage
 * @package    Mage_Downloadable
 */
class Mage_Downloadable_Model_Link_Api_Uploader extends Mage_Core_Model_File_Uploader
{
    /**
     * Filename prefix
     *
     * @var string
     */
    protected $_filePrefix = 'Api';

    /**
     * Default file type
     */
    public const DEFAULT_FILE_TYPE = 'application/octet-stream';

    /**
     * Check if the uploaded file exists
     *
     * @throws Exception
     * @param array $file
     */
    public function __construct($file)
    {
        $this->_setUploadFile($file);
        if (!file_exists($this->_file['tmp_name'])) {
            throw new Exception('', 'file_not_uploaded');
        } else {
            $this->_fileExists = true;
        }
    }

    /**
     * Sets uploaded file info and decodes the file
     *
     * @throws Exception
     * @param array $fileInfo
     */
    private function _setUploadFile($fileInfo)
    {
        if (!is_array($fileInfo)) {
            throw new Exception('', 'file_data_not_correct');
        }

        $this->_file = $this->_decodeFile($fileInfo);
        $this->_uploadType = self::SINGLE_STYLE;
    }

    /**
     * Decode uploaded file base64 encoded content
     *
     * @param array $fileInfo
     * @return array
     */
    private function _decodeFile($fileInfo)
    {
        $tmpFileName = $this->_getTmpFilePath();

        $file = new Varien_Io_File();
        $file->open(['path' => sys_get_temp_dir()]);
        $file->streamOpen($tmpFileName);
        $file->streamWrite(base64_decode($fileInfo['base64_content']));
        $file->streamClose();

        return [
            'name' => $fileInfo['name'],
            'type' => $fileInfo['type'] ?? self::DEFAULT_FILE_TYPE,
            'tmp_name' => $tmpFileName,
            'error' => 0,
            'size' => filesize($tmpFileName),
        ];
    }

    /**
     * Generate temporary file name
     *
     * @return string
     */
    private function _getTmpFilePath()
    {
        return tempnam(sys_get_temp_dir(), $this->_filePrefix);
    }

    /**
     * Moves a file
     *
     * @param string $sourceFile
     * @param string $destinationFile
     * @return bool
     */
    protected function _moveFile($sourceFile, $destinationFile)
    {
        return rename($sourceFile, $destinationFile);
    }
}
