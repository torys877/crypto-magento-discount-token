<?php
/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Crypto\MagentoToken\Controller\Adminhtml\Tokenbalance;

use Crypto\MagentoToken\Api\Data\TokenBalanceInterface;
use Crypto\MagentoToken\Controller\Adminhtml\Entity;
use Crypto\MagentoToken\Model\Data\TokenBalance;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;

class Save extends Entity implements HttpPostActionInterface
{
    /**
     * @return ResultInterface
     */
    public function execute()
    {
        /** @var Redirect $resultRedirect */
        $resultRedirect = $this->resultFactory->create($this->resultFactory::TYPE_REDIRECT);
        $resultRedirect->setPath('*/*');

        /** @var array $requestData */
        $requestData = $this->getRequest()->getParam('general') ?? [];
        if ($requestData) {
            try {
                $tokenBalanceId = isset($requestData[TokenBalanceInterface::TOKEN_BALANCE_ID])
                    ? (int) $requestData[TokenBalanceInterface::TOKEN_BALANCE_ID]
                    : null;

                if ($tokenBalanceId) {
                    $tokenBalance = $this->tokenBalanceRepository->getById((int) $tokenBalanceId);
                } else {
                    $tokenBalance = $this->tokenBalanceFactory->create();
                }

                $tokenBalance->setCustomerId((int) $requestData[TokenBalanceInterface::CUSTOMER_ID] ?? $tokenBalance->getCustomerId());
                $tokenBalance->setCustomerEmail((string) $requestData[TokenBalanceInterface::CUSTOMER_EMAIL] ?? $tokenBalance->getCustomerEmail());
                $tokenBalance->setAmount((int) round($requestData[TokenBalanceInterface::AMOUNT] ?? $tokenBalance->getAmount()));
                $tokenBalance = $this->tokenBalanceRepository->save($tokenBalance);

                $this->messageManager->addSuccessMessage((string) __(
                    'Token Balance has been successfully saved.'
                ));

                if ($this->getRequest()->getParam('back')) {
                    $resultRedirect->setPath($this->_redirect->getRefererUrl());
                } elseif ($this->getRequest()->getParam('redirect_to_new')) {
                    $resultRedirect->setPath('*/*/edit', [TokenBalanceInterface::TOKEN_BALANCE_ID => $tokenBalance->getTokenBalanceId()]);
                }
            } catch (LocalizedException $exception) {
                $this->messageManager->addErrorMessage($exception->getMessage());
                $resultRedirect->setPath($this->_redirect->getRefererUrl());
            }
        }

        return $resultRedirect;
    }
}
