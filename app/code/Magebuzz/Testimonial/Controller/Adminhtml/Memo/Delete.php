<?php
/**
 * @copyright Copyright (c) 2016 www.magebuzz.com
 */

namespace Magebuzz\Testimonial\Controller\Adminhtml\Memo;
use Magento\Framework\Exception\LocalizedException;
use Magento\Backend\App\Action;

class Delete extends \Magento\Backend\App\Action
{

    const ADMIN_RESOURCE = 'Magebuzz_Testimonial::post_delete';
    /**
     * @param Action\Context $context
     */
    public function __construct(Action\Context $context)
    {
        parent::__construct($context);
    }

    public function _isAllowed()
    {
        return $this->_authorization->isAllowed(self::ADMIN_RESOURCE);
    }

    /**
     * @return void
     */
    public function execute()
    {
        $id = $this->getRequest()->getParam('mb_testimonial_id');
        if ($id) {
            try {
                /** @var \Magebuzz\Testimonial\Model\Testimonial $model */
                $model = $this->_objectManager->create('Magebuzz\Testimonial\Model\Testimonial');
                $model->load($id);
                $model->delete();
                $this->_redirect('testimonial/*/');
                $this->messageManager->addSuccess(__('Delete Testimonial successfull.'));
                return;
            } catch (LocalizedException $e) {
                $this->messageManager->addError($e->getMessage());
            } catch (\Exception $e) {
                $this->messageManager->addError(
                    __('We can\'t delete this testimonial right now. Please review the log and try again.')
                );
                $this->_objectManager->get('Psr\Log\LoggerInterface')->critical($e);
                $this->_redirect('testimonial/*/edit', ['mb_testimonial_id' => $this->getRequest()->getParam('mb_testimonial_id')]);
                return;
            }
        }
        $this->messageManager->addError(__('We can\'t find a rule to delete.'));
        $this->_redirect('testimonial/*/');
    }
}