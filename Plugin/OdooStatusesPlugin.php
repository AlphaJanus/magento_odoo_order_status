<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-06-11
 * Time: 17:39
 */

namespace Netzexpert\OdooOrderStatus\Plugin;

use Netzexpert\OdooOrderStatus\Api\Data\OdooOrderStatusInterface;
use Netzexpert\OdooOrderStatus\Model\OdooStatusRepository;

class OdooStatusesPlugin
{
    const ODOO_PATH = 'odoo_order_status/general';

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Netzexpert\OdooOrderStatus\Model\SendEmailNotification
     */
    private $sendEmailNotification;

    public function __construct(
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Netzexpert\OdooOrderStatus\Model\SendEmailNotification $sendEmailNotification
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->storeManager = $storeManager;
        $this->sendEmailNotification = $sendEmailNotification;
    }

    /**
     * @param OdooStatusRepository $odooStatusRepository
     * @param int $result
     * @return OdooOrderStatusInterface
     */
    public function afterSaveNewStatus(
        OdooStatusRepository $odooStatusRepository,
        $oddooStatusId
    ) {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        $scope = $this->scopeConfig->getValue(self::ODOO_PATH, $storeScope);
        if ($scope['send_email'] == 1) {
            $this->sendEmailNotification->sendEmail($oddooStatusId);
        }
        return $oddooStatusId;
    }
}
