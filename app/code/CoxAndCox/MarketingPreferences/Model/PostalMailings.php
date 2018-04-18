<?php

namespace CoxAndCox\MarketingPreferences\Model;

use \Magento\Customer\Api\CustomerRepositoryInterface;

class PostalMailings
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
    public function setPostalMailingsDataAgainstCustomer($customerId, $optedIntoPostalMailings)
    {
        $allowedValues = [
            self::OPT_IN,
            self::OPT_OUT
        ];

        if (!in_array($optedIntoPostalMailings, $allowedValues) || !$customerId) {
            return false;
        }

        $customer = $this->customerRepositoryInterface->getById($customerId);

        if ($customer) {
            $customer->setCustomAttribute(
                'postal_mailings',
                $optedIntoPostalMailings
            );

            $this->customerRepositoryInterface->save($customer);
            return true;
        }

        return false;
    }

    /**
     * @param $customerId
     */
    public function getPostalMailingsDataAgainstCustomer($customerId)
    {
        $customer = $this->customerRepositoryInterface->getById($customerId);

        $valuePostalMailings = false;
        if ($customer) {
            $valuePostalMailings = $customer->getCustomAttribute('postal_mailings');
            $valuePostalMailings = $valuePostalMailings->getValue();
        }

        return $valuePostalMailings;
    }
}
