<?php
namespace Magento\Eav\Model\Entity\Attribute\Backend\JsonEncoded;

/**
 * Interceptor class for @see \Magento\Eav\Model\Entity\Attribute\Backend\JsonEncoded
 */
class Interceptor extends \Magento\Eav\Model\Entity\Attribute\Backend\JsonEncoded implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct(\Magento\Framework\Serialize\Serializer\Json $jsonSerializer)
    {
        $this->___init();
        parent::__construct($jsonSerializer);
    }

    /**
     * {@inheritdoc}
     */
    public function validate($object)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'validate');
        if (!$pluginInfo) {
            return parent::validate($object);
        } else {
            return $this->___callPlugins('validate', func_get_args(), $pluginInfo);
        }
    }
}
