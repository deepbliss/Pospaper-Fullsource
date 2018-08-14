<?php

namespace Nextopia\Search\Controller\Index;
use \Magento\Framework\App\Action\Action;
use \Magento\Framework\App\Action\Context;
use \Magento\Framework\View\Result\PageFactory;
use \Nextopia\Search\Helper\Data as NsearchHelperData;

class Index extends Action
{
    /** @var  \Magento\Framework\View\Result\Page */
    protected $resultPageFactory;

    /** @var \Nextopia\Search\Helper\Data  */
    protected $nsearchHelperData;
    
    /**
     * @param \Magento\Framework\App\Action\Context $context
     */
    public function __construct(Context $context,
                                PageFactory $resultPageFactory,
                                NsearchHelperData $nsearchHelperData
                                )
    {
        $this->resultPageFactory = $resultPageFactory;
        $this->nsearchHelperData = $nsearchHelperData;
        parent::__construct($context);
    }

    /**
     * Blog Index, shows a list of recent blog posts.
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        if(!$this->nsearchHelperData->isEnabled() && !$this->nsearchHelperData->isDemo()) {

            $resultsRedirect = $this->resultRedirectFactory->create();
            $resultsRedirect->setPath("catalogsearch/result/index", $this->getRequest()->getParams());
            return $resultsRedirect;
        }
        $resultsPage = $this->resultPageFactory->create();
        $resultsPage->addHandle("nsearch_index_index-".$this->nsearchHelperData->getLayoutHandlePostfix());
        $keywords = $this->getRequest()->getParam("q");
        $resultsPage->getConfig()->getTitle()->set($this->nsearchHelperData->getLabelSearchResultPage()." ".$keywords);
        return $resultsPage;
    }

}
