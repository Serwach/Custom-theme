<?php
/**
 * Copyright © SUPREMIS SP Z O O All rights reserved.
 * See LICENSE_SUPREMIS for license details.
 */

declare(strict_types=1);

namespace Supremis\ForceLogin\Observer;

use Magento\Customer\Model\Context as CustomerContext;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Http\Context;
use Magento\Framework\App\Request\Http as HttpRequest;
use Magento\Framework\Event\Observer;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\StoreManagerInterface;
use Supremis\ForceLogin\Service\ConfigService;

class ForceLoginObserver implements ObserverInterface
{
    /** @var ConfigService */
    private ConfigService $configService;

    /** @var Session */
    private Session $session;

    /** @var Context */
    private Context $httpContext;

    /** @var StoreManagerInterface */
    private StoreManagerInterface $storeManager;

    /**
     * @param ConfigService $configService
     * @param Session $session
     * @param Context $httpContext
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ConfigService $configService,
        Session $session,
        Context $httpContext,
        StoreManagerInterface $storeManager
    ) {
        $this->configService = $configService;
        $this->session = $session;
        $this->httpContext = $httpContext;
        $this->storeManager = $storeManager;
    }

    /**
     * @param Observer $observer
     *
     * @return $this
     * @throws NoSuchEntityException
     */
    public function execute(Observer $observer): self
    {
        if ($this->configService->isEnabled()) {
            /** @var HttpRequest $request */
            $request = $observer->getData('request');

            $writer = new \Zend_Log_Writer_Stream(BP . '/var/log/test.log');
            $logger = new \Zend_Log();
            $logger->addWriter($writer);
            $logger->info('inside');
            $logger->info($request->getFullActionName());

            if ($this->session->isLoggedIn()
                || $this->httpContext->getValue(CustomerContext::CONTEXT_AUTH)
                || str_contains($request->getFullActionName(), 'customer_account')
            ) {
                return $this;
            }

            $observer->getData('response')
                ->setRedirect(
                    $this->storeManager->getStore()->getBaseUrl()
                    . 'customer/account/login/'
                )
                ->sendHeaders();
        }

        return $this;
    }
}
