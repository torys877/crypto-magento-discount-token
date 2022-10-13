<?php
/**
 * Copyright © Ihor Oleksiienko (https://github.com/torys877)
 * See LICENSE for license details.
 */
declare(strict_types=1);

namespace Crypto\MagentoToken\Model;

use Magento\Framework\Api\SearchCriteriaInterface;
use Magento\Framework\Exception\CouldNotSaveException;
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\Api\SearchCriteria\CollectionProcessorInterface;
use Crypto\MagentoToken\Api\Data\WithdrawRequestInterface;
use Crypto\MagentoToken\Api\WithdrawRequestRepositoryInterface;
use Crypto\MagentoToken\Model\ResourceModel\WithdrawRequest\Collection;
use Crypto\MagentoToken\Model\ResourceModel\WithdrawRequest\CollectionFactory;
use Crypto\MagentoToken\Model\ResourceModel\WithdrawRequest as Resource;
use Crypto\MagentoToken\Model\Data\WithdrawRequestFactory;
use Crypto\MagentoToken\Model\Data\WithdrawRequest as WithdrawRequestEntity;

class WithdrawRequestRepository implements WithdrawRequestRepositoryInterface
{
    private Resource $resource;
    private WithdrawRequestFactory $requestFactory;
    private CollectionProcessorInterface $collectionProcessor;
    private CollectionFactory $collectionFactory;

    public function __construct(
        Resource $resource,
        WithdrawRequestFactory $requestFactory,
        CollectionProcessorInterface $collectionProcessor,
        CollectionFactory $collectionFactory
    ) {
        $this->resource = $resource;
        $this->requestFactory = $requestFactory;
        $this->collectionProcessor = $collectionProcessor;
        $this->collectionFactory = $collectionFactory;
    }

    public function getById(int $requestId): WithdrawRequestInterface
    {
        /** @var WithdrawRequestEntity $withdrawRequest */
        $withdrawRequest = $this->requestFactory->create();
        $this->resource->load($withdrawRequest, $requestId);

        if (!$withdrawRequest->getRequestId()) {
            throw new NoSuchEntityException(__('The request with the "%1" ID doesn\'t exist.', $requestId));
        }

        return $withdrawRequest;
    }

    public function save(WithdrawRequestInterface $withdrawRequest): WithdrawRequestInterface
    {
        try {
            $this->resource->save($withdrawRequest); // @phpstan-ignore-line
        } catch (\Exception $e) {
            throw new CouldNotSaveException(__($e->getMessage()));
        }

        return $withdrawRequest;
    }

    public function getList(SearchCriteriaInterface $searchCriteria): Collection
    {
        $collection = $this->collectionFactory->create();
        $this->collectionProcessor->process($searchCriteria, $collection);

        return $collection;
    }
}
