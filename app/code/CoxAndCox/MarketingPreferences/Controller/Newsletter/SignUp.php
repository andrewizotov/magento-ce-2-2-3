<?php
/**
 * CoxAndCox_MarketingPreferences
 *
 * @category    CoxAndCox
 * @package     CoxAndCox_MarketingPreferences
 * @Date        06/2017
 * @license     http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 * @author      @diazwatson
 */
declare(strict_types=1);

namespace CoxAndCox\MarketingPreferences\Controller\Newsletter;

use Magento\Customer\Model\Customer;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use \CoxAndCox\MarketingPreferences\Model\ThirdParty;
use \CoxAndCox\MarketingPreferences\Model\Subscribe;
use Magento\Framework\App\Action\Action;

class SignUp extends Action
{

    const OPTOUT = 19;

    const OPTIN = 18;

    const SUBSCRIBED = 1;

    const UNSUBSCRIBED = 0;

    /**
     * @var ThirdParty
     */
    private $thirdParty;
    /**
     * @var Subscribe
     */
    private $subscribe;
    /**
     * @var Session
     */
    private $session;

    /**
     * @var
     */
    private $customer;

    /**
     * NewsletterSignUp constructor.
     *
     * @param Context    $context
     * @param ThirdParty $thirdParty
     * @param Subscribe  $subscribe
     * @param Session    $session
     */
    public function __construct(
        Context $context,
        ThirdParty $thirdParty,
        Subscribe $subscribe,
        Session $session
    ) {
        $this->thirdParty = $thirdParty;
        $this->subscribe = $subscribe;
        $this->session = $session;
        parent::__construct($context);
    }

    /**
     * Dispatch request
     *
     * @return void
     * @throws \Magento\Framework\Exception\NotFoundException
     */
    public function execute()
    {
        $optInParams = $this->getRequest()->getParams();
        if ($billingEmail = $this->getCustomerEmail()) {
            $this->thirdParty->setThirdPartyDataAgainstCustomer(
                $this->getCustomerId(),
                $this->optedIntoThirdParty($optInParams)
            );
            $this->subscribe->updateSubscriberStatus(
                $this->getCustomerId(),
                $billingEmail,
                $this->canSubscribeToNewsLetter($optInParams)
            );
        }
    }

    /**
     *
     * @return string | null
     */
    private function getCustomerEmail()
    {
        return $this->getCustomer()->getData('email');
    }

    /**
     * @return Customer
     */
    private function getCustomer(): Customer
    {
        if (!$this->customer) {
            $this->customer = $this->session->getCustomer();
        }

        return $this->customer;
    }

    /**
     * @return int|null
     */
    private function getCustomerId()
    {
        $customerId = $this->getCustomer()->getData('entity_id');

        return $customerId ? $customerId : null;
    }

    /**
     * @param $optInParams
     *
     * @return int
     */
    private function optedIntoThirdParty($optInParams): int
    {
        return $optInParams['optIn'] == self::OPTIN ? self::OPTIN : self::OPTOUT;
    }

    /**
     * @param $optInParams
     *
     * @return mixed
     */
    private function canSubscribeToNewsLetter($optInParams)
    {
        return $optInParams['newsletter'] == self::SUBSCRIBED ? self::SUBSCRIBED : self::UNSUBSCRIBED;
    }
}
