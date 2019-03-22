<?php
/**
 * Copyright Wagento Creative LLC ©, All rights reserved.
 * See COPYING.txt for license details.
 */
namespace Wagento\Zendesk\Setup\Patch\Data;

use Magento\Customer\Model\Customer;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Framework\Setup\Patch\DataPatchInterface;
use Magento\Customer\Model\ResourceModel\Attribute;

class AddCustomerZenCustomerIdAttribute implements DataPatchInterface
{
    const ZD_USER_ID = 'zd_user_id';

    /**
     * @var ModuleDataSetupInterface
     */
    private $moduleDataSetup;
    /**
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;
    /**
     * @var Attribute
     */
    private $attribute;

    /**
     * AddCustomerZenCustomerIdAttribute constructor.
     * @param ModuleDataSetupInterface $moduleDataSetup
     * @param CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(
        ModuleDataSetupInterface $moduleDataSetup,
        CustomerSetupFactory $customerSetupFactory,
        Attribute $attribute
    ) {
        $this->moduleDataSetup = $moduleDataSetup;
        $this->customerSetupFactory = $customerSetupFactory;
        $this->attribute = $attribute;
    }

    /**
     * {@inheritdoc}
     */
    public function apply()
    {
        $customerSetup = $this->customerSetupFactory->create(['setup' => $this->moduleDataSetup]);
        $customerSetup->addAttribute(
            Customer::ENTITY,
            self::ZD_USER_ID,
            [
                'type' => 'varchar',
                'label' => 'Zendesk User Id',
                'input' => 'text',
                'required' => false,
                'sort_order' => 87,
                'visible' => true,
                'system' => false,
                'position' => 87
            ]
        );
        $zenAttribute = $customerSetup->getEavConfig()->getAttribute(Customer::ENTITY, self::ZD_USER_ID);
        $zenAttribute->setData(
            'used_in_forms',
            ['adminhtml_customer']
        );
        $this->attribute->save($zenAttribute);
    }

    /**
     * {@inheritdoc}
     */

    public static function getDependencies()
    {
        return [];
    }

    /**
     * {@inheritdoc}
     */
    public function getAliases()
    {
        return [];
    }

}
