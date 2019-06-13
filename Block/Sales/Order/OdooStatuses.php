<?php
/**
 * Created by PhpStorm.
 * User: hosein
 * Date: 2019-06-10
 * Time: 12:25
 */

namespace Netzexpert\OdooOrderStatus\Block\Sales\Order;


use Magento\Framework\Stdlib\DateTime;
use Magento\Framework\View\Element\Template;
use Magento\Sales\Model\Order;

class OdooStatuses extends \Magento\Framework\View\Element\Template
{
    /**
     * @var \Magento\Framework\Registry
     */
    private $registry;

    /**
     * @var \Magento\Framework\Stdlib\DateTime\TimezoneInterface
     */
    private $timezone;

    public function __construct(
        Template\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\Stdlib\DateTime\TimezoneInterface $timezone,
        array $data = [])
    {
        $this->registry = $registry;
        $this->timezone = $timezone;
        parent::__construct(
            $context,
            $data);
    }

    public function getStatusesList()
    {
        /** @var $currentOrder Order */
        $currentOrder = $this->registry->registry('current_order');
        $attributes = $currentOrder->getExtensionAttributes();
        if (!$currentOrder) {
            return '';
        }
        $odooStatuses = $attributes->getOdooOrderStatuses();
        return $odooStatuses;
    }

    public function convertDate()
    {
        $date = $this->getStatusesList()->getData();
        foreach ($date as $item) {
            $time = $this->timezone->date($item['date'])
                ->format(DateTime::DATETIME_PHP_FORMAT);
            return $time;
        }
        return '';
    }
}