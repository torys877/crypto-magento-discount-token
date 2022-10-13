<?php
/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Crypto\MagentoToken\Controller\Customer;

use Magento\Framework\App\RequestInterface;
use Magento\Framework\App\Action\Action;
use Magento\Framework\Controller\Result\JsonFactory;
use Magento\Framework\App\Action\Context;
use Magento\Customer\Model\Session;
use Magento\Customer\Model\Url;
use Crypto\MagentoToken\Model\TokenBalanceRepository;
use Crypto\MagentoToken\Model\WithdrawRequestRepository;
use Crypto\MagentoToken\Api\Data\WithdrawRequestInterfaceFactory;
use Crypto\MagentoToken\Api\Data\WithdrawRequestInterface;

class ClaimRequest extends Action
{
    protected Session $_customerSession;
    protected JsonFactory $jsonFactory;
    protected TokenBalanceRepository $tokenBalanceRepository;
    protected WithdrawRequestRepository $withdrawRequestRepository;
    protected WithdrawRequestInterfaceFactory $withdrawRequestFactory;

    public function __construct(
        Context $context,
        Session $customerSession,
        JsonFactory $jsonFactory,
        TokenBalanceRepository $tokenBalanceRepository,
        WithdrawRequestRepository $withdrawRequestRepository,
        WithdrawRequestInterfaceFactory $withdrawRequestFactory
    ) {
        $this->_customerSession = $customerSession;
        $this->jsonFactory = $jsonFactory;
        $this->tokenBalanceRepository = $tokenBalanceRepository;
        $this->withdrawRequestRepository = $withdrawRequestRepository;
        $this->withdrawRequestFactory = $withdrawRequestFactory;

        parent::__construct($context);
    }

    public function execute()
    {
        $resultJson = $this->jsonFactory->create();

        if (!$this->_customerSession->isLoggedIn()) {
            $errorMessage = __('You\'re not logged in.');
            $this->messageManager->addErrorMessage((string) $errorMessage);

            return $resultJson->setData(
                [
                    'result' => false,
                    'message' => $errorMessage
                ]
            );
        }

        $requestAmount = $this->_request->getParam('request_amount');
        $recipientAddress = $this->_request->getParam('recipient_address');
        $customerId = (string) $this->_customerSession->getCustomerId();
        $customerEmail = (string) $this->_customerSession->getCustomer()->getEmail();
        $tokenBalance = null;
        $resultData = [];

        try {
            $tokenBalance = $this->tokenBalanceRepository->getByCustomerIdOrEmail($customerId, $customerEmail);

            if (!$tokenBalance || !$tokenBalance->getTokenBalanceId()) {
                $errorMessage = __('Token Balance not found');
                $this->messageManager->addErrorMessage((string) $errorMessage);

                return $resultJson->setData(
                    [
                        'result' => false,
                        'message' => $errorMessage
                    ]
                );
            }

            if ($tokenBalance->getAmount() < $requestAmount) {
                $errorMessage = __('Requested amount higher than balance');
                $this->messageManager->addErrorMessage((string) $errorMessage);

                return $resultJson->setData(
                    [
                        'result' => false,
                        'message' => $errorMessage
                    ]
                );
            }

            $date = new \Datetime();
            $dateStr = $date->format('Y-m-d H:i:s');

            /** @var WithdrawRequestInterface $withdrawRequest */
            $withdrawRequest = $this->withdrawRequestFactory->create();
            $withdrawRequest
                ->setTokenBalanceId($tokenBalance->getTokenBalanceId())
                ->setRecipientAddress((string) $recipientAddress)
                ->setMessageHash(hash('sha256', $recipientAddress . $dateStr . $requestAmount))
                ->setNonce((string) (rand(1, 99) . rand(1, 99) . rand(1, 99)))
                ->setStatus(WithdrawRequestInterface::STATUS_NEW)
                ->setAmount((int) $requestAmount)
                ->setCreatedAt($dateStr)
                ->setUpdatedAt($dateStr);

            $this->withdrawRequestRepository->save($withdrawRequest);
        } catch (\Exception $e) {
            $errorMessage = __('Token Balance not found or something went wrong');
            $this->messageManager->addErrorMessage((string) $errorMessage);

            return $resultJson->setData(
                [
                    'result' => false,
                    'message' => $errorMessage
                ]
            );
        }
        $successMessage = __('Claim request was sent. Please wait for approve');
        $this->messageManager->addSuccessMessage((string) $successMessage);

        return $resultJson->setData(
            [
                'result' => true,
                'message' => $successMessage
            ]
        );
    }
}
