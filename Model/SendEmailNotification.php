<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-06-12
 * Time: 11:51
 */

namespace Netzexpert\OdooOrderStatus\Model;

use Magento\Store\Model\ScopeInterface;
use Psr\Log\LoggerInterface;

class SendEmailNotification
{
    const XML_PATH_EMAIL_TEMPLATE_FIELD = 'odoo_order_status/general';

    /**
     * @var OdooStatusRepository
     */
    private $odooStatusRepository;

    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var \Magento\Sales\Api\OrderRepositoryInterface
     */
    private $orderRepository;


    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    private $scopeConfig;


    /**
     * SendEmailNotification constructor.
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param OdooStatusRepository $odooStatusRepository
     */
    public function __construct(
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        \Netzexpert\OdooOrderStatus\Model\OdooStatusRepository $odooStatusRepository,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Sales\Api\OrderRepositoryInterface $orderRepository,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig,
        LoggerInterface $logger
    )
    {
        $this->transportBuilder         = $transportBuilder;
        $this->odooStatusRepository     = $odooStatusRepository;
        $this->storeManager             = $storeManager;
        $this->orderRepository          = $orderRepository;
        $this->scopeConfig              = $scopeConfig;
        $this->logger                   = $logger;

    }

    public function sendEmail($orderid) {
        try {
            $odooOrderStatus = $this->odooStatusRepository->get($orderid);
            $order = $this->orderRepository->get($odooOrderStatus->getOrderId());
            $storeScope = $this->storeManager->getStore();
            $transport = $this->transportBuilder->setTemplateIdentifier('status_change_notification')
                ->setTemplateOptions(['area' => 'frontend', 'store' => \Magento\Store\Model\Store::DEFAULT_STORE_ID])
                ->setTemplateVars(
                    [
                        'orderId'       => $orderid,
                        'store'         => $storeScope,
                        'name'          => $order->getCustomerFirstname(),
                        'status'        => $odooOrderStatus->getData('status')
                    ]
                )
                ->setFrom('sales')
                ->addTo($order->getCustomerEmail())
                ->getTransport();
            $transport->sendMessage();
        } catch (
            \Exception $e
        ) {
            $this->logger->alert('Could not send email notification');
        }
    }

}