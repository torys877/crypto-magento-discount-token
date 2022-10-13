<?php
/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Crypto\MagentoToken\Model;

use Crypto\MagentoToken\Api\Data\TokenOrderInterface;

use Crypto\MagentoToken\Api\TokenOrderRepositoryInterface;
use Crypto\MagentoToken\Model\ResourceModel\TokenOrder\Collection;
use Crypto\MagentoToken\Model\ResourceModel\TokenOrder\CollectionFactory;
use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Crypto\MagentoToken\Model\ResourceModel\TokenOrder as Resource;
use Crypto\MagentoToken\Model\Data\TokenOrderFactory;
use Crypto\MagentoToken\Model\Data\TokenOrder as TokenOrderEntity;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;


class TokenOrderRepository implements TokenOrderRepositoryInterface
{
    private Resource $resource;
    private TokenOrderFactory $tokenOrderFactory;
    private CollectionProcessorInterface $collectionProcessor;
    private CollectionFactory $collectionFactory;

    public function __construct(
        Resource $resource,
        TokenOrderFactory $tokenOrderFactory,
        CollectionProcessorInterface $collectionProcessor,
        CollectionFactory $collectionFactory
    ) {
        $this->resource = $resource;
        $this->tokenOrderFactory = $tokenOrderFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->collectionFactory = $collectionFactory;
    }

    public function getById(int $tokenOrderId): TokenOrderInterface
    {
        /** @var TokenOrderEntity $tokenOrder */
        $tokenOrder = $this->tokenOrderFactory->create();
        $this->resource->load($tokenOrder, $tokenOrderId);

        if (!$tokenOrder->getTokenOrderId()) {
            throw new NoSuchEntityException(__('The token order with the "%1" ID doesn\'t exist.', $tokenOrderId));
        }

        return $tokenOrder;
    }

    public function getByTokenBalanceId(int $tokenBalanceId): TokenOrderInterface
    {
        /** @var TokenOrderEntity $tokenOrder */
        $tokenOrder = $this->tokenOrderFactory->create();
        $this->resource->load($tokenOrder, $tokenBalanceId, TokenOrderInterface::TOKEN_BALANCE_ID);

        if (!$tokenOrder->getTokenOrderId()) {
            throw new NoSuchEntityException(__('The token order not found for balance ID "%1".', $tokenBalanceId));
        }

        return $tokenOrder;
    }


    public function getByIncrementId(string $incrementId): TokenOrderInterface
    {
        /** @var TokenOrderEntity $tokenOrder */
        $tokenOrder = $this->tokenOrderFactory->create();
        $this->resource->load($tokenOrder, $incrementId, TokenOrderInterface::INCREMENT_ID);

        if (!$tokenOrder->getTokenOrderId()) {
            throw new NoSuchEntityException(__('The token order not found for increment ID "%1".', $incrementId));
        }

        return $tokenOrder;
    }

    public function save(TokenOrderInterface $tokenOrder): TokenOrderInterface
    {
        try {
            $this->resource->save($tokenOrder); // @phpstan-ignore-line
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }

        return $tokenOrder;
    }

    public function getList(SearchCriteriaInterface $searchCriteria): Collection
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        return $collection;
    }
}
