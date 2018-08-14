<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\Freeshippinglabel\Ui\DataProvider;

use Magento\Framework\View\Element\UiComponent\DataProvider\DataProviderInterface;
use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;
use Magento\Framework\App\RequestInterface;
use Aheadworks\Freeshippinglabel\Model\ResourceModel\Label\CollectionFactory;

/**
 * Class LabelDataProvider
 * @package Aheadworks\Freeshippinglabel\Ui\DataProvider
 */
class LabelDataProvider extends AbstractDataProvider implements DataProviderInterface
{
    /**
     * @var RequestInterface
     */
    private $request;

    /**
     * @var DataPersistorInterface
     */
    private $dataPersistor;

    /**
     * @param string $name
     * @param string $primaryFieldName
     * @param string $requestFieldName
     * @param CollectionFactory $collectionFactory
     * @param RequestInterface $request
     * @param DataPersistorInterface $dataPersistor
     * @param array $meta
     * @param array $data
     */
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        RequestInterface $request,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
        $this->request = $request;
        $this->dataPersistor = $dataPersistor;
    }

    /**
     * {@inheritdoc}
     */
    public function getData()
    {
        $data = [];
        $dataFromForm = $this->dataPersistor->get('aw_fslabel_label');
        if (!empty($dataFromForm)) {
            $data[$dataFromForm['id']] = $dataFromForm;
            $this->dataPersistor->clear('aw_fslabel_label');
        } else {
            $id = $this->request->getParam($this->getRequestFieldName());
            /** @var \Aheadworks\Freeshippinglabel\Model\Label $label */
            foreach ($this->getCollection()->getItems() as $label) {
                if ($id == $label->getId()) {
                    $data[$id] = $label->getData();
                }
            }
        }
        return $data;
    }
}
