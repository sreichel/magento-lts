<?php

/**
 * OpenMage
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available at https://opensource.org/license/osl-3-0-php
 *
 * @category   Mage
 * @package    Mage_Review
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @copyright  Copyright (c) 2022-2024 The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

/**
 * Review/Rating module upgrade. Both modules tables must be installed.
 * @see app/etc/modules/Mage_All.xml - Review comes after Rating
 */

$this->startSetup();

$voteTable   = $this->getTable('rating_option_vote');
$reviewTable = $this->getTable('review');

$this->run("
DELETE FROM `{$voteTable}` WHERE `review_id` NOT IN (SELECT review_id FROM `{$reviewTable}`);
");

$this->run("
ALTER TABLE `{$voteTable}`
ADD CONSTRAINT `FK_RATING_OPTION_REVIEW_ID` FOREIGN KEY (`review_id`) REFERENCES `{$reviewTable}` (`review_id`)
ON DELETE CASCADE ON UPDATE CASCADE;
");

$this->endSetup();
