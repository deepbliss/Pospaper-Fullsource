<?php
/**
* Copyright 2016 aheadWorks. All rights reserved.
* See LICENSE.txt for license details.
*/

namespace Aheadworks\RewardPoints\Block\Html;

use Magento\Framework\Api\SearchResultsInterface;

/**
 * Html pager block
 * @SuppressWarnings(PHPMD.ExcessivePublicCount)
 */
class Pager extends \Magento\Framework\View\Element\Template
{
    /**
     * Current template name
     *
     * @var string
     */
    protected $_template = 'Aheadworks_RewardPoints::html/pager.phtml';

    /**
     * @var SearchResultsInterface
     */
    private $searchResults;

    /**
     * @var string
     */
    private $pageVarName = 'p';

    /**
     * @var string
     */
    private $limitVarName = 'limit';

    /**
     * The list of available pager limits
     *
     * @var array
     */
    private $availableLimit = [10 => 10, 20 => 20, 50 => 50];

    /**
     * @var int
     */
    private $displayPages = 5;

    /**
     * @var bool
     */
    private $showPerPage = true;

    /**
     * @var int
     */
    private $limit;

    /**
     * @var bool
     */
    private $outputRequired = true;

    /**
     * Pages quantity per frame
     *
     * @var int
     */
    private $frameLength = 5;

    /**
     * Next/previous page position relatively to the current frame
     *
     * @var int
     */
    private $jump = 5;

    /**
     * Frame initialization flag
     *
     * @var bool
     */
    private $frameInitialized = false;

    /**
     * Start page position in frame
     *
     * @var int
     */
    private $frameStart;

    /**
     * Finish page position in frame
     *
     * @var int
     */
    private $frameEnd;

    /**
     * Url Fragment for pagination
     *
     * @var string|null
     */
    private $fragment = null;

    /**
     * Set pager data
     *
     * @return void
     */
    protected function _construct()
    {
        parent::_construct();
        $this->setData('show_amounts', true);
        $this->setData('use_container', true);
    }

    /**
     * Return current page
     *
     * @param  int $displacement
     * @return int
     */
    public function getCurrentPage($displacement = 0)
    {
        $currentPage = (int)$this->getRequest()->getParam($this->getPageVarName(), 1);

        if ($displacement == 0) {
            return $currentPage;
        } elseif ($currentPage + $displacement < 1) {
            return 1;
        } elseif ($currentPage + $displacement > $this->getLastPageNum()) {
            return $this->getLastPageNum();
        } else {
            return $currentPage + $displacement;
        }
    }

    /**
     * Return current page limit
     *
     * @return int
     */
    public function getLimit()
    {
        if ($this->limit !== null) {
            return $this->limit;
        }

        $limits = $this->getAvailableLimit();
        if ($limit = $this->getRequest()->getParam($this->getLimitVarName())) {
            if (isset($limits[$limit])) {
                return $limit;
            }
        }

        $limits = array_keys($limits);
        return $limits[0];
    }

    /**
     * Setter for limit items per page
     *
     * @param int $limit
     * @return $this
     */
    public function setLimit($limit)
    {
        $this->limit = $limit;
        return $this;
    }

    /**
     * Set collection for pagination
     *
     * @param SearchResultsInterface $searchResults
     * @return $this
     */
    public function setSearchResults($searchResults)
    {
        $this->searchResults = $searchResults;
        $this->_setFrameInitialized(false);
        return $this;
    }

    /**
     * @return SearchResultsInterface
     */
    public function getSearchResults()
    {
        return $this->searchResults;
    }

    /**
     * @param string $varName
     * @return $this
     */
    public function setPageVarName($varName)
    {
        $this->pageVarName = $varName;
        return $this;
    }

    /**
     * @return string
     */
    public function getPageVarName()
    {
        return $this->pageVarName;
    }

    /**
     * @param bool $varName
     * @return $this
     */
    public function setShowPerPage($varName)
    {
        $this->showPerPage = $varName;
        return $this;
    }

    /**
     * @return bool
     */
    public function isShowPerPage()
    {
        if (sizeof($this->getAvailableLimit()) <= 1) {
            return false;
        }
        return $this->showPerPage;
    }

    /**
     * Set the name for pager limit data
     *
     * @param string $varName
     * @return $this
     */
    public function setLimitVarName($varName)
    {
        $this->limitVarName = $varName;
        return $this;
    }

    /**
     * Retrieve name for pager limit data
     *
     * @return string
     */
    public function getLimitVarName()
    {
        return $this->limitVarName;
    }

    /**
     * Set pager limit
     *
     * @param array $limits
     * @return $this
     */
    public function setAvailableLimit(array $limits)
    {
        $this->availableLimit = $limits;
        return $this;
    }

    /**
     * Retrieve pager limit
     *
     * @return array
     */
    public function getAvailableLimit()
    {
        return $this->availableLimit;
    }

    /**
     * @return int
     */
    public function getFirstNum()
    {
        return $this->getLimit() * ($this->getCurrentPage() - 1) + 1;
    }

    /**
     * @return int
     */
    public function getLastNum()
    {
        return $this->getLimit() * ($this->getCurrentPage() - 1) + count($this->searchResults->getItems());
    }

    /**
     * Retrieve total number of pages
     *
     * @return int
     */
    public function getTotalNum()
    {
        return $this->searchResults->getTotalCount();
    }

    /**
     * Check if current page is a first page in collection
     *
     * @return bool
     */
    public function isFirstPage()
    {
        return $this->getCurrentPage() == 1;
    }

    /**
     * Retrieve number of last page
     *
     * @return int
     */
    public function getLastPageNum()
    {
        if (is_object($this->searchResults)) {
            $pages = $this->searchResults->getTotalCount() / $this->getLimit();
        } else {
            $pages = 1;
        }

        $lastPageNumber = intval($pages);
        if ($pages > $lastPageNumber) {
            $lastPageNumber++;
        }
        return $lastPageNumber;
    }

    /**
     * Check if current page is a last page in collection
     *
     * @return bool
     */
    public function isLastPage()
    {
        return $this->getCurrentPage() >= $this->getLastPageNum();
    }

    /**
     * @param int $limit
     * @return bool
     */
    public function isLimitCurrent($limit)
    {
        return $limit == $this->getLimit();
    }

    /**
     * @param int $page
     * @return bool
     */
    public function isPageCurrent($page)
    {
        return $page == $this->getCurrentPage();
    }

    /**
     * @return array
     */
    public function getPages()
    {
        if ($this->getLastPageNum() <= $this->displayPages) {
            return range(1, $this->getLastPageNum());
        } else {
            $start = 1;
            $finish = $this->getLastPageNum();
            $half = ceil($this->displayPages / 2);
            if ($this->getCurrentPage() >= $half &&
                $this->getCurrentPage() <= $this->getLastPageNum() - $half
            ) {
                $start = $this->getCurrentPage() - $half + 1;
                $finish = $start + $this->displayPages - 1;
            } elseif ($this->getCurrentPage() < $half) {
                $start = 1;
                $finish = $this->displayPages;
            } elseif ($this->getCurrentPage() > $this->getLastPageNum() - $half) {
                $finish = $this->getLastPageNum();
                $start = $finish - $this->displayPages + 1;
            }
            return range($start, $finish);
        }
    }

    /**
     * @return string
     */
    public function getFirstPageUrl()
    {
        return $this->getPageUrl(1);
    }

    /**
     * Retrieve previous page URL
     *
     * @return string
     */
    public function getPreviousPageUrl()
    {
        return $this->getPageUrl($this->getCurrentPage(-1));
    }

    /**
     * Retrieve next page URL
     *
     * @return string
     */
    public function getNextPageUrl()
    {
        return $this->getPageUrl($this->getCurrentPage(+1));
    }

    /**
     * Retrieve last page URL
     *
     * @return string
     */
    public function getLastPageUrl()
    {
        return $this->getPageUrl($this->getLastPageNum());
    }

    /**
     * Retrieve page URL
     *
     * @param string $page
     * @return string
     */
    public function getPageUrl($page)
    {
        return $this->getPagerUrl([$this->getPageVarName() => $page]);
    }

    /**
     * @param int $limit
     * @return string
     */
    public function getLimitUrl($limit)
    {
        return $this->getPagerUrl([$this->getLimitVarName() => $limit]);
    }

    /**
     * Retrieve page URL by defined parameters
     *
     * @param array $params
     * @return string
     */
    public function getPagerUrl($params = [])
    {
        $urlParams = [];
        $urlParams['_current'] = true;
        $urlParams['_escape'] = true;
        $urlParams['_use_rewrite'] = true;
        $urlParams['fragment'] = $this->getFragment();
        $urlParams['_query'] = $params;

        return $this->getUrl($this->getPath(), $urlParams);
    }

    /**
     * @return string
     */
    protected function getPath()
    {
        return $this->_getData('path') ?: '*/*/*';
    }

    /**
     * Getter for $frameStart
     *
     * @return int
     */
    public function getFrameStart()
    {
        $this->_initFrame();
        return $this->frameStart;
    }

    /**
     * Getter for $frameEnd
     *
     * @return int
     */
    public function getFrameEnd()
    {
        $this->_initFrame();
        return $this->frameEnd;
    }

    /**
     * Return array of pages in frame
     *
     * @return array
     */
    public function getFramePages()
    {
        $start = $this->getFrameStart();
        $end = $this->getFrameEnd();
        return range($start, $end);
    }

    /**
     * Return page number of Previous jump
     *
     * @return int|null
     */
    public function getPreviousJumpPage()
    {
        if (!$this->getJump()) {
            return null;
        }

        $frameStart = $this->getFrameStart();
        if ($frameStart - 1 > 1) {
            return max(2, $frameStart - $this->getJump());
        }

        return null;
    }

    /**
     * Prepare URL for Previous Jump
     *
     * @return string
     */
    public function getPreviousJumpUrl()
    {
        return $this->getPageUrl($this->getPreviousJumpPage());
    }

    /**
     * Return page number of Next jump
     *
     * @return int|null
     */
    public function getNextJumpPage()
    {
        if (!$this->getJump()) {
            return null;
        }

        $frameEnd = $this->getFrameEnd();
        if ($this->getLastPageNum() - $frameEnd > 1) {
            return min($this->getLastPageNum() - 1, $frameEnd + $this->getJump());
        }

        return null;
    }

    /**
     * Prepare URL for Next Jump
     *
     * @return string
     */
    public function getNextJumpUrl()
    {
        return $this->getPageUrl($this->getNextJumpPage());
    }

    /**
     * Getter for $frameLength
     *
     * @return int
     */
    public function getFrameLength()
    {
        return $this->frameLength;
    }

    /**
     * Getter for $jump
     *
     * @return int
     */
    public function getJump()
    {
        return $this->jump;
    }

    /**
     * Setter for $frameLength
     *
     * @param int $frame
     * @return $this
     */
    public function setFrameLength($frame)
    {
        $frame = abs(intval($frame));
        if ($frame == 0) {
            $frame = $this->frameLength;
        }
        if ($this->getFrameLength() != $frame) {
            $this->_setFrameInitialized(false);
            $this->frameLength = $frame;
        }

        return $this;
    }

    /**
     * Setter for $jump
     *
     * @param int $jump
     * @return $this
     */
    public function setJump($jump)
    {
        $jump = abs(intval($jump));
        if ($this->getJump() != $jump) {
            $this->_setFrameInitialized(false);
            $this->jump = $jump;
        }

        return $this;
    }

    /**
     * Whether to show first page in pagination or not
     *
     * @return bool
     */
    public function canShowFirst()
    {
        return $this->getJump() > 1 && $this->getFrameStart() > 1;
    }

    /**
     * Whether to show last page in pagination or not
     *
     * @return bool
     */
    public function canShowLast()
    {
        return $this->getJump() > 1 && $this->getFrameEnd() < $this->getLastPageNum();
    }

    /**
     * Whether to show link to Previous Jump
     *
     * @return bool
     */
    public function canShowPreviousJump()
    {
        return $this->getPreviousJumpPage() !== null;
    }

    /**
     * Whether to show link to Next Jump
     *
     * @return bool
     */
    public function canShowNextJump()
    {
        return $this->getNextJumpPage() !== null;
    }

    /**
     * Initialize frame data, such as frame start, frame start etc.
     *
     * @return $this
     */
    protected function _initFrame()
    {
        if (!$this->isFrameInitialized()) {
            $start = 0;
            $end = 0;

            if ($this->getLastPageNum() <= $this->getFrameLength()) {
                $start = 1;
                $end = $this->getLastPageNum();
            } else {
                $half = ceil($this->getFrameLength() / 2);
                if ($this->getCurrentPage() >= $half &&
                    $this->getCurrentPage() <= $this->getLastPageNum() - $half
                ) {
                    $start = $this->getCurrentPage() - $half + 1;
                    $end = $start + $this->getFrameLength() - 1;
                } elseif ($this->getCurrentPage() < $half) {
                    $start = 1;
                    $end = $this->getFrameLength();
                } elseif ($this->getCurrentPage() > $this->getLastPageNum() - $half) {
                    $end = $this->getLastPageNum();
                    $start = $end - $this->getFrameLength() + 1;
                }
            }
            $this->frameStart = $start;
            $this->frameEnd = $end;

            $this->_setFrameInitialized(true);
        }

        return $this;
    }

    /**
     * Setter for flag frameInitialized
     *
     * @param bool $flag
     * @return $this
     */
    protected function _setFrameInitialized($flag)
    {
        $this->frameInitialized = (bool)$flag;
        return $this;
    }

    /**
     * Check if frame data was initialized
     *
     * @return bool
     */
    public function isFrameInitialized()
    {
        return $this->frameInitialized;
    }

    /**
     * Getter for alternative text for Previous link in pagination frame
     *
     * @return string
     */
    public function getAnchorTextForPrevious()
    {
        return $this->_scopeConfig->getValue(
            'design/pagination/anchor_text_for_previous',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Getter for alternative text for Next link in pagination frame
     *
     * @return string
     */
    public function getAnchorTextForNext()
    {
        return $this->_scopeConfig->getValue(
            'design/pagination/anchor_text_for_next',
            \Magento\Store\Model\ScopeInterface::SCOPE_STORE
        );
    }

    /**
     * Set whether output of the pager is mandatory
     *
     * @param bool $isRequired
     * @return $this
     */
    public function setIsOutputRequired($isRequired)
    {
        $this->outputRequired = (bool)$isRequired;
        return $this;
    }

    /**
     * Determine whether the pagination should be eventually rendered
     *
     * @return string
     */
    protected function _toHtml()
    {
        if ($this->outputRequired || $this->getTotalNum() > $this->getLimit()) {
            return parent::_toHtml();
        }
        return '';
    }

    /**
     * Get the URL fragment
     *
     * @return string|null
     */
    public function getFragment()
    {
        return $this->fragment;
    }

    /**
     * Set the URL fragment
     *
     * @param string|null $fragment
     * @return $this
     */
    public function setFragment($fragment)
    {
        $this->fragment = $fragment;
        return $this;
    }
}
