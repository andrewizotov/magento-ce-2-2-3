<?php

namespace Space48\ThirdParty\Model;

use \Magento\Newsletter\Model\SubscriberFactory;

class Subscribe
{
    private $subscriberFactory;

    public function __construct(
        SubscriberFactory $subscriberFactory
    ) {
        $this->subscriberFactory= $subscriberFactory;
    }

    /**
     * @param $customerId
     * @param $billingEmail
     * @param $subscribeToNewsletter
     * @return mixed
     */
    public function updateSubscriberStatus($customerId, $billingEmail, $subscribeToNewsletter)
    {
        if (!$customerId && !$billingEmail) {
            return false;
        }

        return $customerId
            ? $this->updateCustomerSubscription($subscribeToNewsletter, $customerId)
            : $this->updateSubscriberSubscription($subscribeToNewsletter, $billingEmail);
    }

    /**
     * @param $hasRequestedSubscription
     * @param $customerId
     * @return mixed
     */
    public function updateCustomerSubscription($hasRequestedSubscription, $customerId)
    {
        return $hasRequestedSubscription ? $this->subscriberFactory
            ->create()
            ->subscribeCustomerById($customerId) : $this->subscriberFactory
            ->create()
            ->unsubscribeCustomerById($customerId);
    }

    /**
     * @param $hasRequestedSubscription
     * @param $billingEmail
     * @return mixed
     */
    public function updateSubscriberSubscription($hasRequestedSubscription, $billingEmail)
    {
        return $hasRequestedSubscription ? $this->subscriberFactory
                ->create()
                ->subscribe($billingEmail) : $this->subscriberFactory
                ->create()
                ->loadByEmail($billingEmail)
                ->unsubscribe();
    }
}
