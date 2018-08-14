<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_Base
 */


namespace Amasty\Base\Block;

use Magento\Framework\App\State;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Info extends \Magento\Config\Block\System\Config\Form\Fieldset
{
    /**
     * @var \Magento\Framework\View\LayoutFactory
     */
    private $_layoutFactory;
    
    /**
     * @var \Magento\Cron\Model\ResourceModel\Schedule\CollectionFactory
     */
    private $cronFactory;

    /**
     * @var \Magento\Framework\App\Filesystem\DirectoryList
     */
    private $directoryList;

    /**
     * @var \Magento\Framework\App\ResourceConnection
     */
    private $resourceConnection;

    /**
     * @var \Magento\Framework\App\ProductMetadataInterface
     */
    private $productMetadata;

    /**
     * @var \Magento\Framework\App\DeploymentConfig\Reader
     */
    private $reader;

    /**
     * Info constructor.
     *
     * @param \Magento\Backend\Block\Context                               $context
     * @param \Magento\Backend\Model\Auth\Session                          $authSession
     * @param \Magento\Framework\View\Helper\Js                            $jsHelper
     * @param \Magento\Framework\View\LayoutFactory                        $layoutFactory
     * @param \Magento\Cron\Model\ResourceModel\Schedule\CollectionFactory $cronFactory
     * @param \Magento\Framework\App\Filesystem\DirectoryList              $directoryList
     * @param \Magento\Framework\App\DeploymentConfig\Reader               $reader
     * @param \Magento\Framework\App\ResourceConnection                    $resourceConnection
     * @param \Magento\Framework\App\ProductMetadataInterface              $productMetadata
     * @param array                                                        $data
     */
    public function __construct(
        \Magento\Backend\Block\Context $context,
        \Magento\Backend\Model\Auth\Session $authSession,
        \Magento\Framework\View\Helper\Js $jsHelper,
        \Magento\Framework\View\LayoutFactory $layoutFactory,
        \Magento\Cron\Model\ResourceModel\Schedule\CollectionFactory $cronFactory,
        \Magento\Framework\App\Filesystem\DirectoryList $directoryList,
        \Magento\Framework\App\DeploymentConfig\Reader $reader,
        \Magento\Framework\App\ResourceConnection $resourceConnection,
        \Magento\Framework\App\ProductMetadataInterface $productMetadata,
        array $data = []
    ) {
        parent::__construct($context, $authSession, $jsHelper, $data);

        $this->_layoutFactory = $layoutFactory;
        $this->_scopeConfig   = $context->getScopeConfig();
        $this->cronFactory = $cronFactory;
        $this->directoryList = $directoryList;
        $this->resourceConnection = $resourceConnection;
        $this->productMetadata = $productMetadata;
        $this->reader = $reader;
    }

    /**
     * Render fieldset html
     *
     * @param AbstractElement $element
     * @return string
     */
    public function render(AbstractElement $element)
    {
        $html = $this->_getHeaderHtml($element);

        $html .= $this->getMagentoMode($element);
        $html .= $this->getMagentoPathInfo($element);
        $html .= $this->getOwnerInfo($element);
        $html .= $this->getSystemTime($element);
        $html .= $this->getCronInfo($element);

        $html .= $this->_getFooterHtml($element);

        return $html;
    }

    /**
     * @return \Magento\Framework\View\Element\BlockInterface
     */
    private function _getFieldRenderer()
    {
        if (empty($this->_fieldRenderer)) {
            $layout = $this->_layoutFactory->create();

            $this->_fieldRenderer = $layout->createBlock(
                \Magento\Config\Block\System\Config\Form\Field::class
            );
        }

        return $this->_fieldRenderer;
    }

    /**
     * @return string
     */
    private function getMagentoMode($fieldset)
    {
        $label = __("Magento Mode");

        $env = $this->reader->load();
        $mode = isset($env[State::PARAM_MODE]) ? $env[State::PARAM_MODE] : '';

        return $this->getFieldHtml($fieldset, 'magento_mode', $label, ucfirst($mode));
    }

    /**
     * @return string
     */
    private function getMagentoPathInfo($fieldset)
    {
        $label = __("Magento Path");
        $path = $this->directoryList->getRoot();

        return $this->getFieldHtml($fieldset, 'magento_path', $label, $path);
    }

    /**
     * @param $fieldset
     * @return string
     */
    private function getOwnerInfo($fieldset)
    {
        if (function_exists('posix_getpwuid') && function_exists('posix_geteuid')) {
            $userInfo = posix_getpwuid(posix_geteuid());
            $userInfo = isset($userInfo['name']) ? $userInfo['name'] : '';
            return $this->fieldHtml($userInfo, $fieldset);
        }

        if (function_exists('exec')) {
            $userInfo = exec('whoami');
            return $this->fieldHtml($userInfo, $fieldset);
        }

        return $this->fieldHtml(get_current_user(), $fieldset);
    }

    /**
     * @param AbstractElement $fieldset
     * @return string
     */
    private function getSystemTime($fieldset)
    {
        if (version_compare($this->productMetadata->getVersion(), '2.2', '>=')) {
            $time = $this->resourceConnection->getConnection()->fetchOne("select now()");
        } else {
            $time = $this->_localeDate->date()->format('H:i:s');
        }
        return $this->getFieldHtml($fieldset, 'mysql_current_date_time', __("Current Time"), $time);
    }

    /**
     * @param $userInfo
     * @param $fieldset
     * @return string
     */
    private function fieldHtml($userInfo, $fieldset)
    {
        $label = __("Server User");

        return $this->getFieldHtml(
            $fieldset,
            'magento_user',
            $label,
            $userInfo
        );
    }

    /**
     * @param AbstractElement $fieldset
     * @return string
     */
    private function getCronInfo($fieldset)
    {
        $crontabCollection = $this->cronFactory->create();
        $crontabCollection->setOrder('schedule_id')->setPageSize(5);

        if ($crontabCollection->count() === 0) {
            $value = '<div class="red">';
            $value .= __('No cron jobs found') . "</div>";
            $value .=
                "<a target='_blank'
                  href='https://support.amasty.com/index.php?/Knowledgebase/Article/View/72/24/magento-cron'>" .
                __("Learn more") .
                "</a>";
        } else {
            $value = '<table>';
            foreach ($crontabCollection as $crontabRow) {
                $value .=
                    '<tr>' .
                        '<td>' . $crontabRow['job_code'] . '</td>' .
                        '<td>' . $crontabRow['status'] . '</td>' .
                        '<td>' . $crontabRow['created_at'] . '</td>' .
                    '</tr>';
            }
            $value .= '</table>';
        }

        $label = __('Cron (Last 5)');

        return $this->getFieldHtml($fieldset, 'cron_configuration', $label, $value);
    }

    /**
     * @param AbstractElement $fieldset
     * @param string $fieldName
     * @param string $label
     * @param string $value
     * @return string
     */
    private function getFieldHtml($fieldset, $fieldName, $label = '', $value = '')
    {
        $field = $fieldset->addField($fieldName, 'label', [
            'name'  => 'dummy',
            'label' => $label,
            'after_element_html' => $value,
        ])->setRenderer($this->_getFieldRenderer());

        return $field->toHtml();
    }
}
