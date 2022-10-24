<?php

namespace Voronin\Cars\Ui\DataProvider\Form;

use Magento\Ui\DataProvider\AbstractDataProvider;
use Voronin\Cars\Model\ResourceModel\Cars\CollectionFactory;

class EditDataProvider extends AbstractDataProvider
{

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        string $name,
        string $primaryFieldName,
        string $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        parent::__construct(
            $name,
            $primaryFieldName,
            $requestFieldName,
            $meta,
            $data
        );
    }

    /**
     * Get Meta
     *
     * @return array
     */
    public function getMeta(): array
    {
        return $this->meta;
    }
}
