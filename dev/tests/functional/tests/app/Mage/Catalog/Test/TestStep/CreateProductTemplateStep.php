<?php
/**
 * OpenMage
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * https://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@magento.com so we can send you a copy immediately.
 *
 * @category    Tests
 * @package     Tests_Functional
 * @copyright  Copyright (c) 2006-2020 Magento, Inc. (https://www.magento.com)
 * @license    https://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

namespace Mage\Catalog\Test\TestStep;

use Mage\Catalog\Test\Fixture\CatalogAttributeSet;
use Magento\Mtf\Fixture\FixtureFactory;
use Magento\Mtf\TestStep\TestStepInterface;

/**
 * Create product attribute template using handler.
 */
class CreateProductTemplateStep implements TestStepInterface
{
    /**
     * Catalog attribute set data.
     *
     * @var string
     */
    protected $productTemplate;

    /**
     * Template data.
     *
     * @var array
     */
    protected $templateData;

    /**
     * Factory for Fixtures.
     *
     * @var FixtureFactory
     */
    protected $fixtureFactory;

    /**
     * @constructor
     * @param FixtureFactory $fixtureFactory
     * @param array $productTemplate
     * @param array $templatesData [optional]
     */
    public function __construct(FixtureFactory $fixtureFactory, array $productTemplate, array $templatesData = [])
    {
        $this->productTemplate = $productTemplate;
        $this->templatesData = $templatesData;
        $this->fixtureFactory = $fixtureFactory;
    }

    /**
     * Create product attribute template.
     *
     * @return array
     */
    public function run()
    {
        $attributeSet = $this->fixtureFactory->createByCode(
            'catalogAttributeSet',
            [
                'dataset' => $this->productTemplate['dataset'],
                'data' => ['assigned_attributes' => $this->templatesData]
            ]
        );
        $attributeSet->persist();

        return ['productTemplate' => $attributeSet];
    }
}
