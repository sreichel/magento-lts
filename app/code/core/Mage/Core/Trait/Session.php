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
 * @copyright  Copyright (c) 2024 The OpenMage Contributors (https://www.openmage.org)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

declare(strict_types=1);

/**
 * Session trait
 *
 * @category   Mage
 * @package    Mage_Core
 */
trait Mage_Core_Trait_Session
{
    use Mage_Core_Trait_Session_Admin;
    use Mage_Core_Trait_Session_Adminhtml;
    use Mage_Core_Trait_Session_Api;
    use Mage_Core_Trait_Session_Catalog;
    use Mage_Core_Trait_Session_CatalogSearch;
    use Mage_Core_Trait_Session_Centinal;
    use Mage_Core_Trait_Session_Checkout;
    use Mage_Core_Trait_Session_Core;
    use Mage_Core_Trait_Session_Customer;
    use Mage_Core_Trait_Session_Install;
    use Mage_Core_Trait_Session_Newsletter;
    use Mage_Core_Trait_Session_Paypal;
    use Mage_Core_Trait_Session_Reports;
    use Mage_Core_Trait_Session_Review;
    use Mage_Core_Trait_Session_Rss;
    use Mage_Core_Trait_Session_Tag;
    use Mage_Core_Trait_Session_Wishlist;
}
