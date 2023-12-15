<?php

declare(strict_types=1);

namespace Supremis\ForceLogin\Service;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Store\Model\ScopeInterface;
use Magento\Store\Model\StoreManagerInterface;
use Supremis\ForceLogin\Enum\ConfigEnum;

class ConfigService
{
    /** @var ScopeConfigInterface */
    private ScopeConfigInterface $scopeConfig;

    /** @var StoreManagerInterface */
    private StoreManagerInterface $storeManager;

    /**
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        StoreManagerInterface $storeManager
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
    }

    /**
     * @return bool
     * @throws NoSuchEntityException
     */
    public function isEnabled(): bool
    {
        $website = $this->storeManager->getStore()->getWebsite();

        return $this->scopeConfig->isSetFlag(
            ConfigEnum::IS_ENABLED,
            ScopeInterface::SCOPE_WEBSITE,
            $website->getCode()
        );
    }
}
