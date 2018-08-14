<?php
/**
 * BSS Commerce Co.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the EULA
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://bsscommerce.com/Bss-Commerce-License.txt
 *
 * =================================================================
 *                 MAGENTO EDITION USAGE NOTICE
 * =================================================================
 * This package designed for Magento COMMUNITY edition
 * BSS Commerce does not guarantee correct work of this extension
 * on any other Magento edition except Magento COMMUNITY edition.
 * BSS Commerce does not provide extension support in case of
 * incorrect edition usage.
 * =================================================================
 *
 * @category   BSS
 * @package    Bss_Reindex
 * @author     Extension Team
 * @copyright  Copyright (c) 2015-2016 BSS Commerce Co. ( http://bsscommerce.com )
 * @license    http://bsscommerce.com/Bss-Commerce-License.txt
 */
namespace Bss\Reindex\Controller\Adminhtml\Indexer;

class MassReindexData extends \Magento\Backend\App\Action
{
    protected function _isAllowed()
    {
        if ($this->_request->getActionName() == 'massReindexData') {
            return $this->_authorization->isAllowed('Bss_Reindex::reindexdata');
        }
        return false;
    }
    
	public function execute()
    {
        $indexerIds = $this->getRequest()->getParam('indexer_ids');
        if (!is_array($indexerIds)) {
            $this->messageManager->addError(__('Please select indexers.'));
        } else {
        	$startTime = microtime(true);
            foreach ($indexerIds as $indexerId) {
            	try {
                    $indexer = $this->_objectManager->get('Magento\Framework\Indexer\IndexerRegistry')->get($indexerId);
                    $indexer->reindexAll();
                    $resultTime = microtime(true) - $startTime;
                    $this->messageManager->addSuccess(
	                    '<div class="bss-reindex-info">' . $indexer->getTitle() . ' index has been rebuilt successfully in ' . gmdate('H:i:s', $resultTime) . '</div>'
	                );
                } catch (\Magento\Framework\Exception\LocalizedException $e) {
	                $this->messageManager->addError(
                        $indexer->getTitle() . ' indexer process unknown error:',
                        $e
                    );
	            } catch (\Exception $e) {
                    $this->messageManager->addException(
                        $e,
                        __("We couldn't reindex data because of an error.")
                    );
	            }
            }
            $this->messageManager->addSuccess(
                __('%1 indexer(s) have been rebuilt successfully <a href="javascript:void(0)" class="bss-reindex-show">Show detail</a>', count($indexerIds))
            );
        }
        $this->_redirect('indexer/indexer/list');
    }
}
