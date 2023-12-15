<?php

declare(strict_types=1);

namespace Supremis\ForceLogin\Plugin\Customer\Controller\Account;

use Exception;
use Magento\Customer\Controller\Account\LoginPost as BaseLoginPost;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Magento\Store\Model\StoreManagerInterface;
use Supremis\ForceLogin\Service\ConfigService;

class LoginPost
{
    /**
     * @param ConfigService $configService
     * @param RedirectFactory $redirectFactory
     */
    public function __construct(
        private ConfigService $configService,
        private RedirectFactory $redirectFactory,
        private StoreManagerInterface $storeManager
    ) {}

    /**
     * @param BaseLoginPost $subject
     * @param ResultInterface $result
     *
     * @return ResultInterface
     */
    public function afterExecute(BaseLoginPost $subject, ResultInterface $result): ResultInterface
    {
        if ($this->configService->isEnabled()) {
            try {
                $store = $this->storeManager->getStore();
            } catch (Exception $e) {
                return $result;
            }

            return $this->redirectFactory->create()->setPath($store->getBaseUrl());
        }

        return $result;
    }
}
