<?php

namespace Klloom\CurrencyConverter\Helper;

/**
 * Class Config
 * @package Klloom\FreeRates\Helper
 */
class Config
{
    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Config constructor.
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    )
    {
        $this->scopeConfig = $scopeConfig;
    }

    /**
     * @return int
     */
    public function getDelay()
    {
        $result = (int)$this->scopeConfig->getValue('currency/fcc/delay');
        return $result;
    }

    /**
     * @return int
     */
    public function getTimeout()
    {
        $result = (int)$this->scopeConfig->getValue('currency/fcc/timeout');
        return $result;
    }
}