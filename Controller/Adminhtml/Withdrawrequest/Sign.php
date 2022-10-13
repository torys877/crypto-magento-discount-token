<?php
/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Crypto\MagentoToken\Controller\Adminhtml\Withdrawrequest;

use Crypto\MagentoToken\Controller\Adminhtml\Entity;
use Magento\Backend\Model\View\Result\Page;
use Magento\Backend\Model\View\Result\Redirect;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\Controller\ResultInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Controller\Result\JsonFactory;
use Crypto\MagentoToken\Api\Data\WithdrawRequestInterface;

class Sign extends Entity implements HttpGetActionInterface, HttpPostActionInterface
{
    /**
     * @return ResultInterface
     */
    public function execute()
    {
        /** @var int $requestId */
        $requestId = (int) $this->getRequest()->getParam('request_id', 0);
        /** @var string $signedMessage */
        $signedMessage = (string) $this->getRequest()->getParam('signed_message', '');
        $result = [];

        try {
            $request = $this->withdrawRequestRepository->getById($requestId);
            $request->setSignedMessage((string) $signedMessage);
            $request->setStatus(WithdrawRequestInterface::STATUS_APPROVED);
            $date = new \Datetime();
            $dateStr = $date->format('Y-m-d H:i:s');
            $request->setUpdatedAt($dateStr);
            $this->withdrawRequestRepository->save($request);

            $message = __('Withdraw Request Signed');
            $this->messageManager->addSuccessMessage($message);

            $result['success'] = true;
        } catch (\Exception $e) {
            $message = __('Cannot save request');
            $this->messageManager->addErrorMessage($message);
            $result['success'] = false;
        }
        $result['message'] = $message;

        $resultPage = $this->jsonFactory->create();
        $resultPage->setData($result);

        return $resultPage;
    }
}
