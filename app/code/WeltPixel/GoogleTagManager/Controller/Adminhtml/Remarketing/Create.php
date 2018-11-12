<?php
namespace WeltPixel\GoogleTagManager\Controller\Adminhtml\Remarketing;

/**
 * Class \WeltPixel\GoogleTagManager\Controller\Adminhtml\Remarketing\Create
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
     * @param \WeltPixel\GoogleTagManager\Model\Api\Remarketing $apiModel
     * @param \Magento\Backend\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\JsonFactory $resultJsonFactory
     */
    public function __construct(
        \WeltPixel\GoogleTagManager\Model\Api\Remarketing $apiModel,
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
                $result = $this->apiModel->createRemarketing($params);
                $msg = array_merge($msg, $result);
            } catch (\Exception $ex) {
                $msg[] = $ex->getMessage();
            }
        }

        if (!count($msg)) {
            $msg[] = __('Nothing was done, remarketing items were already created.');
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
        $conversionCode             = $params['conversion_code'];
        $conversionLabel            = $params['conversion_label'];

        $msg = [];

        if (!strlen(trim($accountId))) {
            $msg[] = __('Account Id must be specified');
        }

        if (!strlen(trim($containerId))) {
            $msg[] = __('Container Id must be specified');
        }

        if (!strlen(trim($conversionCode))) {
            $msg[] = __('Conversion Code must be specified');
        }
        /**
        if (!strlen(trim($conversionLabel))) {
            $msg[] = __('Conversion Label must be specified');
        }
        */
        return $msg;
    }

}
