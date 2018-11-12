<?php

namespace WeltPixel\Backend\Helper;


/**
 * @SuppressWarnings(PHPMD.TooManyFields)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class License extends \Magento\Framework\App\Helper\AbstractHelper
{

    /** @var  \WeltPixel\Backend\Model\License */
    protected $license;

    /**
     * @var \WeltPixel\Backend\Model\LicenseFactory
     */
    protected $licenseFactory;

    /**
     * @var \Magento\Backend\Model\UrlInterface
     */
    protected $backendUrl;

    /**
     * Constructor
     *
     * @param \Magento\Framework\App\Helper\Context $context
     * @param \WeltPixel\Backend\Model\License $license
     * @param \WeltPixel\Backend\Model\LicenseFactory $licenseFactory ,
     * @param \Magento\Backend\Model\UrlInterface $backendUrl
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \WeltPixel\Backend\Model\License $license,
        \WeltPixel\Backend\Model\LicenseFactory $licenseFactory,
        \Magento\Backend\Model\UrlInterface $backendUrl
    )
    {
        parent::__construct($context);
        $this->license = $license;
        $this->licenseFactory = $licenseFactory;
        $this->backendUrl = $backendUrl;
    }

    /**
     * @return array
     */
    public function getMdsL()
    {
        return $this->license->getMdsL();
    }

    /**
     * @return array
     */
    public function getModulesListForDisplay()
    {
        $modulesList = $this->getMdsL();
        foreach ($modulesList as $key => $options) {
            if (version_compare($options['version'], \WeltPixel\Backend\Model\License::LICENSE_VERSION) < 0) {
                unset($modulesList[$key]);
            }
        }

        return $modulesList;
    }

    /**
     * @param $license
     * @param $module
     * @return bool
     */
    public function isLcVd($license, $module)
    {
        return $this->license->isLcVd($license, $module);
    }


    /**
     * @param array $licenseOptions
     * @param array $userFriendlyNames
     * @param bool $usePrefix
     * @return string
     */
    public function getLicenseErrorMessage($licenseOptions, $userFriendlyNames, $usePrefix = true)
    {
        $moduleName = isset($userFriendlyNames[$licenseOptions['module_name']]) ? $userFriendlyNames[$licenseOptions['module_name']] : $licenseOptions['module_name'];
        $msgPrefix = '';
        if ($usePrefix) {
            $msgPrefix = '<b>' . $moduleName . '</b>' . ' => ';
        }
        $defaultMsg = 'This license key is invalid, please add a valid license key. If you believe this is a mistake contact us at <a href="mailto:support@weltpixel.com">support@weltpixel.com</a>';

        $license = $licenseOptions['license'];
        $licenseDetails = $this->license->getLicenseDetails($license);
        if (count($licenseDetails) != 5) return $msgPrefix . $defaultMsg;

        $licenseModuleName = isset($userFriendlyNames[$licenseDetails[1]]) ? $userFriendlyNames[$licenseDetails[1]] : $licenseDetails[1];
        if ($licenseDetails['1'] != $licenseOptions['module_name']) {
            return $msgPrefix . 'Current license is for ' . $licenseModuleName . ' and is being used on a ' . $moduleName . ' installation.';
        }
        $magentoVersion = $this->license->getMagentoVersion();
        if (($magentoVersion != $licenseDetails[3]) && ($magentoVersion != "community")) {
            return $msgPrefix . 'Current license is for ' . $licenseDetails[3] . ' and it not valid when used on ' . $magentoVersion;
        }
        $domain = $this->license->getMagentoDomain();
        $isDomainValid = $this->license->checkDomainValidity($domain, $licenseDetails[2]);
        if (!$isDomainValid) {
            return $msgPrefix . 'Your license domain ' . $licenseDetails[2] . ' does not match current domain ' . $domain;
        }

        return $msgPrefix . $defaultMsg;
    }

    /**
     * @TODO might need to check and remove if some modules were meanwhile disabled
     * @return void
     */
    public function checkAndUpdate()
    {
        $modules = $this->getMdsL();
        foreach ($modules as $name => $options) {
            $license = $this->licenseFactory->create();
            try {
                $license->load($name, 'module_name');

                $license->setModuleName($name);
                $license->setLicenseKey($options['license']);
                $license->save();
            } catch (\Exception $ex) {
            }
        }
    }

    public function updMdsInf()
    {
        $this->license->updMdsInf();
    }

    /**
     * @return bool|string
     */
    public function getLicenseMessage()
    {
        if ($this->_request->isAjax()) {
            return false;
        }
        $messages = [];
        $modules = $this->getModulesListForDisplay();

        $userFriendlyNames = $this->license->getUserFriendlyModuleNames();
        foreach ($modules as $name => $options) {
            if (!$this->isLcVd($options['license'], $options['module_name'])) {
                $invalidReasonMsg = $this->getLicenseErrorMessage($options, $userFriendlyNames);

                $messages[] = $invalidReasonMsg;
            }
        }

        if (count($messages)) {
            $moduleList = implode('<br/>', $messages);
            return __('License missing or invalid for the following WeltPixel module(s):') . '<br/>' . $moduleList . '<br/><br/>' .
                __('You can enter the license key(s)') . ' ' . '<a href="' . $this->backendUrl->getUrl('weltpixel_backend/licenses/index') . '">'
                . __('here.') . '</a><br/>' . __('License key can be found under My Downloadable Products section of your weltpixel.com account.');
        }

        return false;
    }
}
