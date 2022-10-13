<?php
/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Crypto\MagentoToken\Controller\Customer;

use Crypto\MagentoToken\Api\Data\TokenBalanceHistoryInterface;
use Crypto\MagentoToken\Model\TokenBalanceRepository;
use Crypto\MagentoToken\Model\WithdrawRequestRepository;
use Magento\Framework\App\Action\Action;
use Crypto\MagentoToken\Api\Data\WithdrawRequestInterfaceFactory;
use Crypto\MagentoToken\Api\Data\WithdrawRequestInterface;
use Magento\Customer\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Controller\Result\JsonFactory;

use Crypto\MagentoToken\Model\Data\TokenHistory;
use Crypto\MagentoToken\Model\Data\TokenHistoryFactory;
use Crypto\MagentoToken\Api\TokenHistoryRepositoryInterface;

class ClaimTokens extends Action
{
    protected Session $_customerSession;
    protected JsonFactory $jsonFactory;
    protected WithdrawRequestRepository $withdrawRequestRepository;
    protected WithdrawRequestInterfaceFactory $withdrawRequestFactory;
    protected TokenBalanceRepository $tokenBalanceRepository;
    protected TokenHistoryRepositoryInterface $tokenHistoryRepository;
    protected TokenHistoryFactory $tokenHistoryFactory;

    public function __construct(
        Context $context,
        Session $customerSession,
        JsonFactory $jsonFactory,
        WithdrawRequestInterfaceFactory $withdrawRequestFactory,
        WithdrawRequestRepository $withdrawRequestRepository,
        TokenBalanceRepository $tokenBalanceRepository,
        TokenHistoryRepositoryInterface $tokenHistoryRepository,
        TokenHistoryFactory $tokenHistoryFactory
    ) {
        $this->_customerSession = $customerSession;
        $this->withdrawRequestRepository = $withdrawRequestRepository;
        $this->withdrawRequestFactory = $withdrawRequestFactory;
        $this->jsonFactory = $jsonFactory;
        $this->tokenBalanceRepository = $tokenBalanceRepository;
        $this->tokenHistoryRepository = $tokenHistoryRepository;
        $this->tokenHistoryFactory = $tokenHistoryFactory;

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

        try {
            $requestId = (int) $this->_request->getParam('request_id');

            if (!$requestId) {
                $errorMessage = __('Request Id is not set');
                $this->messageManager->addErrorMessage((string) $errorMessage);

                return $resultJson->setData(
                    [
                        'result' => false,
                        'message' => $errorMessage
                    ]
                );
            }

            $withdrawRequest = $this->withdrawRequestRepository->getById($requestId);

            if ($withdrawRequest->getRequestId()) {
                $date = new \Datetime();
                $dateStr = $date->format('Y-m-d H:i:s');

                $withdrawRequest->setStatus(WithdrawRequestInterface::STATUS_CLAIMED);
                $withdrawRequest->setUpdatedAt($dateStr);
                $this->withdrawRequestRepository->save($withdrawRequest);

                $tokenBalance = $this->tokenBalanceRepository->getById($withdrawRequest->getTokenBalanceId());

                if ($tokenBalance->getTokenBalanceId()) {
                    $tokenBalance->setAmount($tokenBalance->getAmount() - $withdrawRequest->getAmount());
                    $this->tokenBalanceRepository->save($tokenBalance);
                    $tokenHistory = $this->tokenHistoryFactory->create();

                    $tokenHistory
                        ->setTokenBalanceId($tokenBalance->getTokenBalanceId())
                        ->setAmount((int) round($tokenBalance->getAmount()))
                        ->setAction(TokenBalanceHistoryInterface::ACTION_WITHDRAW)
                        ->setDelta($withdrawRequest->getAmount())
                        ->setAdditionalInfo(__('Tokens Claimed'))
                        ->setUpdatedAt($dateStr);

                    $this->tokenHistoryRepository->save($tokenHistory);
                }
            }

        } catch (\Exception $e) {
            return $resultJson->setData(
                [
                    'result' => false,
                    'message' => $e->getMessage()
                ]
            );
        }

        $successMessage = __('Tokens are claimed');

        return $resultJson->setData(
            [
                'result' => true,
                'message' => $successMessage
            ]
        );
    }
}
