<?php

namespace Nextopia\Search\Controller\Index;

use \Nextopia\Search\Helper\Data as NsearchHelperData;
use \Magento\Catalog\Controller\Category\View as Action;
use \Magento\Catalog\Api\CategoryRepositoryInterface;
use \Magento\Framework\View\Result\PageFactory;
use Magento\Catalog\Model\Layer\Resolver;

class Index extends Action
{
    /** @var  \Magento\Framework\View\Result\Page */
    protected $resultPageFactory;

    /** @var \Nextopia\Search\Helper\Data  */
    protected $nsearchHelperData;

    /** @var \Magento\Catalog\Model\Design   */
    protected $catalogDesign;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Catalog\Model\Design $catalogDesign
     * @param \Magento\Catalog\Model\Session $catalogSession
     * @param \Magento\Framework\Registry $coreRegistry
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\CatalogUrlRewrite\Model\CategoryUrlPathGenerator $categoryUrlPathGenerator
     * @param \Magento\Framework\View\Result\PageFactory $resultPageFactory
     * @param \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory
     * @param Resolver $layerResolver
     * @param CategoryRepositoryInterface $categoryRepository
     * @SuppressWarnings(PHPMD.ExcessiveParameterList)
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Catalog\Model\Design $catalogDesign,
        \Magento\Catalog\Model\Session $catalogSession,
        \Magento\Framework\Registry $coreRegistry,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\CatalogUrlRewrite\Model\CategoryUrlPathGenerator $categoryUrlPathGenerator,
        PageFactory $resultPageFactory,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
        Resolver $layerResolver,
        CategoryRepositoryInterface $categoryRepository, 
        NsearchHelperData $nsearchHelperData
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->nsearchHelperData = $nsearchHelperData;
        $this->catalogDesign = $catalogDesign;

        parent::__construct($context, $catalogDesign, $catalogSession, $coreRegistry, $storeManager, 
                $categoryUrlPathGenerator, $resultPageFactory, $resultForwardFactory, $layerResolver, $categoryRepository);
    }

    /**
     * Blog Index, shows a list of recent blog posts.
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $storeId  = $this->nsearchHelperData->getCurrentStoreId();
        $resultsPage = $this->resultPageFactory->create();

        if(!$this->nsearchHelperData->isEnabled($storeId) && !$this->nsearchHelperData->isDemo($storeId)) {

            $resultsPage->setPath("catalogsearch/result/index", $this->getRequest()->getParams());
            
            return $resultsPage;
        }
        
        /**
         * Since we don't have catalogsearch_result_index in Nxt extension,
         * the next line takes the catalog result layout settings from the client's theme,
         * We need it to have the current catalog layout and all fonctionalities of the sidebar(Add to wishlist, Add to compage...)
         */
        $resultsPage->addHandle("catalogsearch_result_index");
        $keywords = $this->getRequest()->getParam("q");
        $resultsPage->getConfig()->getTitle()->set($this->nsearchHelperData->getLabelSearchResultPage($storeId)." ".$keywords);

        return $resultsPage;
    }

}
