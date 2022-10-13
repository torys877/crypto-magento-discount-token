<?php
/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Crypto\MagentoToken\Controller\Adminhtml\Withdrawrequest;

use Crypto\MagentoToken\Controller\Adminhtml\Entity;
use Magento\Backend\Model\View\Result\Page;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;

class Index extends Entity implements HttpGetActionInterface, HttpPostActionInterface
{
    /**
     * @return ResultInterface
     */
    public function execute()
    {
        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create($this->resultFactory::TYPE_PAGE);

        $resultPage->setActiveMenu('Crypto_MagentoToken::withdraw_request');
        $resultPage->getConfig()->getTitle()->prepend((string) __('Token Withdraw Requests'));

        return $resultPage;
    }
}
