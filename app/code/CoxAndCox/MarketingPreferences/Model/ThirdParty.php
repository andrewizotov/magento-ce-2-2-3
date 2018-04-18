<?php

namespace CoxAndCox\MarketingPreferences\Model;

use \Magento\Customer\Api\CustomerRepositoryInterface;

class ThirdParty
{
    private $customerRepositoryInterface;
    const OPT_IN = 1;
    const OPT_OUT = 0;

    public function __construct(
        CustomerRepositoryInterface $customerRepositoryInterface
    ) {
        $this->customerRepositoryInterface = $customerRepositoryInterface;
    }

    /**
     * @param $customerId
     * @param $optedIntoThirdParty
     */
    public function setThirdPartyDataAgainstCustomer($customerId, $optedIntoThirdParty)
    {
        $allowedValues = [
            self::OPT_IN,
            self::OPT_OUT
        ];

        if (!in_array($optedIntoThirdParty, $allowedValues) || !$customerId) {
            return false;
        }

        $customer = $this->customerRepositoryInterface->getById($customerId);

        if ($customer) {
            $customer->setCustomAttribute(
                'third_party',
                $optedIntoThirdParty
            );

            $this->customerRepositoryInterface->save($customer);
            return true;
        }

        return false;
    }

    /**
     * @param $customerId
     */
    public function getThirdPartyDataAgainstCustomer($customerId)
    {
        $customer = $this->customerRepositoryInterface->getById($customerId);

        $valueThirdParty = false;
        if ($customer) {
            $valueThirdParty = $customer->getCustomAttribute('third_party');
            $valueThirdParty = $valueThirdParty->getValue();
        }

        return $valueThirdParty;
    }
}
