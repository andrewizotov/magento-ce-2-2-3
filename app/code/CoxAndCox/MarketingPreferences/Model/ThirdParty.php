<?php

namespace CoxAndCox\MarketingPreferences\Model;

use \Magento\Customer\Api\CustomerRepositoryInterface;

class ThirdParty
{
    private $customerRepositoryInterface;
    const OPT_IN = 19;
    const OPT_OUT = 18;

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
}
