<?php
/**
 * Copyright © SUPREMIS SP Z O O All rights reserved.
 * See LICENSE_SUPREMIS for license details.
 */

declare(strict_types=1);

namespace Supremis\ForceLogin\Plugin\Controller;

use Magento\Customer\Controller\Account\Create;
use Magento\Framework\Controller\Result\RedirectFactory;
use Magento\Framework\Controller\ResultInterface;
use Supremis\ForceLogin\Service\ConfigService;

class DisableCreate
{
    /** @var ConfigService */
    private ConfigService $configService;

    /** @var RedirectFactory */
    private RedirectFactory $resultRedirectFactory;

    /**
     * @param ConfigService $configService
     * @param RedirectFactory $resultRedirectFactory
     */
    public function __construct(
        ConfigService $configService,
        RedirectFactory $resultRedirectFactory
    ) {
        $this->configService = $configService;
        $this->resultRedirectFactory = $resultRedirectFactory;
    }

    /**
     * @param Create $subject
     * @param ResultInterface $result
     *
     * @return ResultInterface
     */
    public function afterExecute(Create $subject, ResultInterface $result): ResultInterface
    {
        if ($this->configService->isEnabled()) {
            return $this->resultRedirectFactory->create()->setPath('no-route');
        }

        return $result;
    }
}
