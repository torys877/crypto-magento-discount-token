<?php
/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Crypto\MagentoToken\Model;

use Crypto\MagentoToken\Api\Data\TokenBalanceHistoryInterface;

use Crypto\MagentoToken\Api\TokenHistoryRepositoryInterface;
use Crypto\MagentoToken\Model\ResourceModel\TokenHistory\Collection;
use Crypto\MagentoToken\Model\ResourceModel\TokenHistory\CollectionFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Crypto\MagentoToken\Model\ResourceModel\TokenHistory as Resource;
use Crypto\MagentoToken\Model\Data\TokenHistoryFactory;
use Crypto\MagentoToken\Model\Data\TokenHistory as TokenHistoryEntity;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;

class TokenHistoryRepository implements TokenHistoryRepositoryInterface
{
    private Resource $resource;
    private TokenHistoryFactory $tokenHistoryFactory;
    private CollectionProcessorInterface $collectionProcessor;
    private CollectionFactory $collectionFactory;

    public function __construct(
        Resource $resource,
        TokenHistoryFactory $tokenHistoryFactory,
        CollectionProcessorInterface $collectionProcessor,
        CollectionFactory $collectionFactory
    ) {
        $this->resource = $resource;
        $this->tokenHistoryFactory = $tokenHistoryFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->collectionFactory = $collectionFactory;
    }

    public function getById(int $tokenHistoryId): TokenBalanceHistoryInterface
    {
        /** @var TokenHistoryEntity $tokenHistory */
        $tokenHistory = $this->tokenHistoryFactory->create();
        $this->resource->load($tokenHistory, $tokenHistoryId);

        if (!$tokenHistory->getTokenHistoryId()) {
            throw new NoSuchEntityException(__('The token history with the "%1" ID doesn\'t exist.', $tokenHistoryId));
        }

        return $tokenHistory;
    }

    public function getByTokenBalanceId(int $tokenBalanceId): TokenBalanceHistoryInterface
    {
        /** @var TokenHistoryEntity $tokenHistory */
        $tokenHistory = $this->tokenHistoryFactory->create();
        $this->resource->load($tokenHistory, $tokenBalanceId, TokenBalanceHistoryInterface::TOKEN_BALANCE_ID);

        if (!$tokenHistory->getTokenHistoryId()) {
            throw new NoSuchEntityException(__('The token history not found for balance ID "%1" doesn\'t.', $tokenBalanceId));
        }

        return $tokenHistory;
    }

    public function save(TokenBalanceHistoryInterface $tokenHistory): TokenBalanceHistoryInterface
    {
        try {
            $this->resource->save($tokenHistory); // @phpstan-ignore-line
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }

        return $tokenHistory;
    }

    public function getList(SearchCriteriaInterface $searchCriteria): Collection
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        return $collection;
    }
}
