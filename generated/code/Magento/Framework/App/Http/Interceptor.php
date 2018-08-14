<?php
namespace Magento\Framework\App\Http;

/**
 * Interceptor class for @see \Magento\Framework\App\Http
 */
class Interceptor extends \Magento\Framework\App\Http implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\ObjectManagerInterface $objectManager, \Magento\Framework\Event\Manager $eventManager, \Magento\Framework\App\AreaList $areaList, \Magento\Framework\App\Request\Http $request, \Magento\Framework\App\Response\Http $response, \Magento\Framework\ObjectManager\ConfigLoaderInterface $configLoader, \Magento\Framework\App\State $state, \Magento\Framework\Filesystem $filesystem, \Magento\Framework\Registry $registry)
    {
        $this->___init();
        parent::__construct($objectManager, $eventManager, $areaList, $request, $response, $configLoader, $state, $filesystem, $registry);
    }

    /**
     * {@inheritdoc}
     */
    public function catchException(\Magento\Framework\App\Bootstrap $bootstrap, \Exception $exception)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'catchException');
        if (!$pluginInfo) {
            return parent::catchException($bootstrap, $exception);
        } else {
            return $this->___callPlugins('catchException', func_get_args(), $pluginInfo);
        }
    }
}
