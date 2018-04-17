<?php

namespace CoxAndCox\MarketingPreferences\Setup;


use Magento\Framework\Setup\InstallDataInterface;
use Magento\Framework\Setup\ModuleContextInterface;
use Magento\Framework\Setup\ModuleDataSetupInterface;
use Magento\Customer\Setup\CustomerSetupFactory;
use Magento\Customer\Setup\CustomerSetup;

/**
 * @codeCoverageIgnore
 */
class InstallData implements InstallDataInterface
{
    /**
     * Customer setup factory
     *
     * @var CustomerSetupFactory
     */
    private $customerSetupFactory;

    /**
     * Init
     *
     * @param CustomerSetupFactory $customerSetupFactory
     */
    public function __construct(CustomerSetupFactory $customerSetupFactory)
    {
        $this->customerSetupFactory = $customerSetupFactory;
    }

    /**
     * {@inheritdoc}
     * @SuppressWarnings(PHPMD.ExcessiveMethodLength)
     */
    public function install(ModuleDataSetupInterface $setup, ModuleContextInterface $context)
    {
        /** @var CustomerSetup $customerSetup */
        $customerSetup = $this->customerSetupFactory->create(['setup' => $setup]);

        $setup->startSetup();

        $attributes = $customerSetup->getEavConfig()->getEntityAttributes('customer');

        if (!isset($attributes['third_party'])) {
            $customerSetup->addAttribute('customer', 'third_party', [
                    'input' => 'boolean',
                    'type' => 'int',
                    'system' => 0,
                    'position' => 200,
                    'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                    'label' => 'Third Party Mailings',
                    'unique' => 0,
                    'required' => 0
                ]
            );
        }

        $thirdParty = $customerSetup->getEavConfig()->getAttribute('customer', 'third_party');

        if ($thirdParty) {
            $thirdParty->setData('used_in_forms', ['adminhtml_customer']);
            $thirdParty->save();
        }

        if (!isset($attributes['postal_mailings'])) {
            $customerSetup->addAttribute('customer', 'postal_mailings', [
                    'input' => 'boolean',
                    'type' => 'int',
                    'system' => 0,
                    'position' => 210,
                    'source' => 'Magento\Eav\Model\Entity\Attribute\Source\Boolean',
                    'label' => 'Postal Mailings',
                    'unique' => 0,
                    'required' => 0
                ]
            );
        }

        $postalMailings = $customerSetup->getEavConfig()->getAttribute('customer', 'postal_mailings');

        if ($postalMailings) {
            $postalMailings->setData('used_in_forms', ['adminhtml_customer']);
            $postalMailings->save();
        }

        $setup->endSetup();
    }
}
