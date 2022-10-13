<?php
/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Crypto\MagentoToken\Controller\Adminhtml\Tokenbalance;

use Crypto\MagentoToken\Controller\Adminhtml\Entity;
use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;

class Edit extends Entity implements HttpGetActionInterface
{
    /**
     * @return ResultInterface
     */
    public function execute()
    {
        /** @var int $tokenBalanceId */
        $tokenBalanceId = (int) $this->getRequest()->getParam('token_balance_id', 0);

        if ($tokenBalanceId) {
            try {
                $this->tokenBalanceRepository->getById($tokenBalanceId);
            } catch (LocalizedException $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());

                /** @var Redirect $resultRedirect */
                $resultRedirect = $this->resultFactory->create($this->resultFactory::TYPE_REDIRECT);
                return $resultRedirect->setPath('*/*');
            }
        }

        /** @var Page $resultPage */
        $resultPage = $this->resultFactory->create($this->resultFactory::TYPE_PAGE);

        $resultPage->setActiveMenu('Crypto_MagentoToken::token_balance');
        $resultPage->getConfig()->getTitle()->prepend(
            (string) __($tokenBalanceId ? 'Edit Token Balance' : 'New Token Balance')
        );

        return $resultPage;
    }
}
