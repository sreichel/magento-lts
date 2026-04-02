<?php

/**
 * @copyright  For copyright and license information, read the COPYING.txt file.
 * @link       /COPYING.txt
 * @license    Open Software License (OSL 3.0)
 * @package    OpenMage_Rector
 */

declare(strict_types=1);

namespace OpenMage\Rector\Migration\Varien\Data;

use Rector\Arguments\ValueObject\ReplaceArgumentDefaultValue;
use Varien_Data_Collection;

final class Collection
{
    /**
     * @return ReplaceArgumentDefaultValue[]
     */
    public static function replaceArgumentDefaultValue(): array
    {
        return [
            new ReplaceArgumentDefaultValue(Varien_Data_Collection::class, 'addOrder', 1, 'asc', 'Varien_Data_Collection::SORT_ORDER_ASC'),
            new ReplaceArgumentDefaultValue(Varien_Data_Collection::class, 'addOrder', 1, 'desc', 'Varien_Data_Collection::SORT_ORDER_DESC'),
            new ReplaceArgumentDefaultValue(Varien_Data_Collection::class, 'addOrder', 1, 'ASC', 'Varien_Data_Collection::SORT_ORDER_ASC'),
            new ReplaceArgumentDefaultValue(Varien_Data_Collection::class, 'addOrder', 1, 'DESC', 'Varien_Data_Collection::SORT_ORDER_DESC'),
            new ReplaceArgumentDefaultValue(Varien_Data_Collection::class, 'setOrder', 1, 'asc', 'Varien_Data_Collection::SORT_ORDER_ASC'),
            new ReplaceArgumentDefaultValue(Varien_Data_Collection::class, 'setOrder', 1, 'desc', 'Varien_Data_Collection::SORT_ORDER_DESC'),
            new ReplaceArgumentDefaultValue(Varien_Data_Collection::class, 'setOrder', 1, 'ASC', 'Varien_Data_Collection::SORT_ORDER_ASC'),
            new ReplaceArgumentDefaultValue(Varien_Data_Collection::class, 'setOrder', 1, 'DESC', 'Varien_Data_Collection::SORT_ORDER_DESC'),
        ];
    }
}
