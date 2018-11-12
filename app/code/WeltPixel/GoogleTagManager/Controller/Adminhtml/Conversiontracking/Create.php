<?php
namespace WeltPixel\GoogleTagManager\Controller\Adminhtml\Conversiontracking;

/**
 * Class \WeltPixel\GoogleTagManager\Controller\Adminhtml\Conversiontracking\Create
 */
class Create extends \Magento\Backend\App\Action {

    /**
     * @var \Magento\Framework\Controller\Result\JsonFactory
     */
    protected $resultJsonFactory;

    /**
     * @var \WeltPixel\GoogleTagManager\Model\Api\ConversionTracking
     */
    protected $apiModel = null;

    /**
     * Version constructor.
     *
     * @param \WeltPixel\GoogleTagManager\Model\Api\ConversionTracking $apiModel
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        \WeltPixel\GoogleTagManager\Model\Api\ConversionTracking $apiModel,
        \Magento\Backend\App\Action\Context $context,
        \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
    )
    {
        $this->resultJsonFactory = $resultJsonFactory;
        $this->apiModel = $apiModel;
        parent::__construct($context);
    }

    /**
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $params = $this->getRequest()->getParams();
        $msg = $this->_validateParams($params);

        if (!count($msg)) {
            try {
                $result = $this->apiModel->createConversionTracking($params);
                $msg = array_merge($msg, $result);
            } catch (\Exception $ex) {
                $msg[] = $ex->getMessage();
            }
        }

        if (!count($msg)) {
            $msg[] = __('Nothing was done, conversion tracking items were already created.');
        }


        $resultJson = $this->resultJsonFactory->create();
        $resultJson->setData($msg);
        return $resultJson;
    }


    /**
     * @param $params
     * @return array
     */
    private function _validateParams($params) {
        $accountId                  = $params['account_id'];
        $containerId                = $params['container_id'];
        $conversionId               = $params['conversion_id'];
        $conversionLabel            = $params['conversion_label'];
        $conversionCurrencyCode     = $params['conversion_currency_code'];

        $msg = [];

        if (!strlen(trim($accountId))) {
            $msg[] = __('Account Id must be specified');
        }

        if (!strlen(trim($containerId))) {
            $msg[] = __('Container Id must be specified');
        }

        if (!strlen(trim($conversionId))) {
            $msg[] = __('Conversion Id must be specified');
        }

        if (!strlen(trim($conversionLabel))) {
            $msg[] = __('Conversion Label must be specified');
        }

        if (!strlen(trim($conversionCurrencyCode))) {
            $msg[] = __('Conversion Currency Code must be specified');
        }

        return $msg;
    }

}
