<?php
namespace Magento\Framework\Mail\Message;

/**
 * Interceptor class for @see \Magento\Framework\Mail\Message
 */
class Interceptor extends \Magento\Framework\Mail\Message implements \Magento\Framework\Interception\InterceptorInterface
{
    use \Magento\Framework\Interception\Interceptor;

    public function __construct($charset = 'utf-8')
    {
        $this->___init();
        parent::__construct($charset);
    }

    /**
     * {@inheritdoc}
     */
    public function setBody($body)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBody');
        if (!$pluginInfo) {
            return parent::setBody($body);
        } else {
            return $this->___callPlugins('setBody', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBody()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBody');
        if (!$pluginInfo) {
            return parent::getBody();
        } else {
            return $this->___callPlugins('getBody', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setMessageType($type)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setMessageType');
        if (!$pluginInfo) {
            return parent::setMessageType($type);
        } else {
            return $this->___callPlugins('setMessageType', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getCharset()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getCharset');
        if (!$pluginInfo) {
            return parent::getCharset();
        } else {
            return $this->___callPlugins('getCharset', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setType($type)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setType');
        if (!$pluginInfo) {
            return parent::setType($type);
        } else {
            return $this->___callPlugins('setType', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getType');
        if (!$pluginInfo) {
            return parent::getType();
        } else {
            return $this->___callPlugins('getType', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setMimeBoundary($boundary)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setMimeBoundary');
        if (!$pluginInfo) {
            return parent::setMimeBoundary($boundary);
        } else {
            return $this->___callPlugins('setMimeBoundary', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getMimeBoundary()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getMimeBoundary');
        if (!$pluginInfo) {
            return parent::getMimeBoundary();
        } else {
            return $this->___callPlugins('getMimeBoundary', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getEncodingOfHeaders()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getEncodingOfHeaders');
        if (!$pluginInfo) {
            return parent::getEncodingOfHeaders();
        } else {
            return $this->___callPlugins('getEncodingOfHeaders', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaderEncoding()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getHeaderEncoding');
        if (!$pluginInfo) {
            return parent::getHeaderEncoding();
        } else {
            return $this->___callPlugins('getHeaderEncoding', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setEncodingOfHeaders($encoding)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setEncodingOfHeaders');
        if (!$pluginInfo) {
            return parent::setEncodingOfHeaders($encoding);
        } else {
            return $this->___callPlugins('setEncodingOfHeaders', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setHeaderEncoding($encoding)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setHeaderEncoding');
        if (!$pluginInfo) {
            return parent::setHeaderEncoding($encoding);
        } else {
            return $this->___callPlugins('setHeaderEncoding', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBodyText($txt, $charset = null, $encoding = 'quoted-printable')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBodyText');
        if (!$pluginInfo) {
            return parent::setBodyText($txt, $charset, $encoding);
        } else {
            return $this->___callPlugins('setBodyText', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBodyText($textOnly = false)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBodyText');
        if (!$pluginInfo) {
            return parent::getBodyText($textOnly);
        } else {
            return $this->___callPlugins('getBodyText', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setBodyHtml($html, $charset = null, $encoding = 'quoted-printable')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setBodyHtml');
        if (!$pluginInfo) {
            return parent::setBodyHtml($html, $charset, $encoding);
        } else {
            return $this->___callPlugins('setBodyHtml', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getBodyHtml($htmlOnly = false)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getBodyHtml');
        if (!$pluginInfo) {
            return parent::getBodyHtml($htmlOnly);
        } else {
            return $this->___callPlugins('getBodyHtml', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addAttachment(\Zend_Mime_Part $attachment)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addAttachment');
        if (!$pluginInfo) {
            return parent::addAttachment($attachment);
        } else {
            return $this->___callPlugins('addAttachment', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createAttachment($body, $mimeType = 'application/octet-stream', $disposition = 'attachment', $encoding = 'base64', $filename = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'createAttachment');
        if (!$pluginInfo) {
            return parent::createAttachment($body, $mimeType, $disposition, $encoding, $filename);
        } else {
            return $this->___callPlugins('createAttachment', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPartCount()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getPartCount');
        if (!$pluginInfo) {
            return parent::getPartCount();
        } else {
            return $this->___callPlugins('getPartCount', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addTo($email, $name = '')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addTo');
        if (!$pluginInfo) {
            return parent::addTo($email, $name);
        } else {
            return $this->___callPlugins('addTo', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addCc($email, $name = '')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addCc');
        if (!$pluginInfo) {
            return parent::addCc($email, $name);
        } else {
            return $this->___callPlugins('addCc', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addBcc($email)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addBcc');
        if (!$pluginInfo) {
            return parent::addBcc($email);
        } else {
            return $this->___callPlugins('addBcc', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getRecipients()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getRecipients');
        if (!$pluginInfo) {
            return parent::getRecipients();
        } else {
            return $this->___callPlugins('getRecipients', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function clearHeader($headerName)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'clearHeader');
        if (!$pluginInfo) {
            return parent::clearHeader($headerName);
        } else {
            return $this->___callPlugins('clearHeader', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function clearRecipients()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'clearRecipients');
        if (!$pluginInfo) {
            return parent::clearRecipients();
        } else {
            return $this->___callPlugins('clearRecipients', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setFrom($email, $name = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setFrom');
        if (!$pluginInfo) {
            return parent::setFrom($email, $name);
        } else {
            return $this->___callPlugins('setFrom', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setReplyTo($email, $name = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setReplyTo');
        if (!$pluginInfo) {
            return parent::setReplyTo($email, $name);
        } else {
            return $this->___callPlugins('setReplyTo', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getFrom()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getFrom');
        if (!$pluginInfo) {
            return parent::getFrom();
        } else {
            return $this->___callPlugins('getFrom', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getReplyTo()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getReplyTo');
        if (!$pluginInfo) {
            return parent::getReplyTo();
        } else {
            return $this->___callPlugins('getReplyTo', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function clearFrom()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'clearFrom');
        if (!$pluginInfo) {
            return parent::clearFrom();
        } else {
            return $this->___callPlugins('clearFrom', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function clearReplyTo()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'clearReplyTo');
        if (!$pluginInfo) {
            return parent::clearReplyTo();
        } else {
            return $this->___callPlugins('clearReplyTo', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setFromToDefaultFrom()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setFromToDefaultFrom');
        if (!$pluginInfo) {
            return parent::setFromToDefaultFrom();
        } else {
            return $this->___callPlugins('setFromToDefaultFrom', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setReplyToFromDefault()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setReplyToFromDefault');
        if (!$pluginInfo) {
            return parent::setReplyToFromDefault();
        } else {
            return $this->___callPlugins('setReplyToFromDefault', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setReturnPath($email)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setReturnPath');
        if (!$pluginInfo) {
            return parent::setReturnPath($email);
        } else {
            return $this->___callPlugins('setReturnPath', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getReturnPath()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getReturnPath');
        if (!$pluginInfo) {
            return parent::getReturnPath();
        } else {
            return $this->___callPlugins('getReturnPath', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function clearReturnPath()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'clearReturnPath');
        if (!$pluginInfo) {
            return parent::clearReturnPath();
        } else {
            return $this->___callPlugins('clearReturnPath', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setSubject($subject)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setSubject');
        if (!$pluginInfo) {
            return parent::setSubject($subject);
        } else {
            return $this->___callPlugins('setSubject', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getSubject()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getSubject');
        if (!$pluginInfo) {
            return parent::getSubject();
        } else {
            return $this->___callPlugins('getSubject', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function clearSubject()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'clearSubject');
        if (!$pluginInfo) {
            return parent::clearSubject();
        } else {
            return $this->___callPlugins('clearSubject', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDate($date = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setDate');
        if (!$pluginInfo) {
            return parent::setDate($date);
        } else {
            return $this->___callPlugins('setDate', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getDate()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getDate');
        if (!$pluginInfo) {
            return parent::getDate();
        } else {
            return $this->___callPlugins('getDate', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function clearDate()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'clearDate');
        if (!$pluginInfo) {
            return parent::clearDate();
        } else {
            return $this->___callPlugins('clearDate', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setMessageId($id = true)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setMessageId');
        if (!$pluginInfo) {
            return parent::setMessageId($id);
        } else {
            return $this->___callPlugins('setMessageId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getMessageId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getMessageId');
        if (!$pluginInfo) {
            return parent::getMessageId();
        } else {
            return $this->___callPlugins('getMessageId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function clearMessageId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'clearMessageId');
        if (!$pluginInfo) {
            return parent::clearMessageId();
        } else {
            return $this->___callPlugins('clearMessageId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function createMessageId()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'createMessageId');
        if (!$pluginInfo) {
            return parent::createMessageId();
        } else {
            return $this->___callPlugins('createMessageId', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addHeader($name, $value, $append = false)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addHeader');
        if (!$pluginInfo) {
            return parent::addHeader($name, $value, $append);
        } else {
            return $this->___callPlugins('addHeader', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getHeaders()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getHeaders');
        if (!$pluginInfo) {
            return parent::getHeaders();
        } else {
            return $this->___callPlugins('getHeaders', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function send($transport = null)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'send');
        if (!$pluginInfo) {
            return parent::send($transport);
        } else {
            return $this->___callPlugins('send', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getParts()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getParts');
        if (!$pluginInfo) {
            return parent::getParts();
        } else {
            return $this->___callPlugins('getParts', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setParts($parts)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setParts');
        if (!$pluginInfo) {
            return parent::setParts($parts);
        } else {
            return $this->___callPlugins('setParts', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function addPart(\Zend_Mime_Part $part)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'addPart');
        if (!$pluginInfo) {
            return parent::addPart($part);
        } else {
            return $this->___callPlugins('addPart', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function isMultiPart()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'isMultiPart');
        if (!$pluginInfo) {
            return parent::isMultiPart();
        } else {
            return $this->___callPlugins('isMultiPart', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setMime(\Zend_Mime $mime)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'setMime');
        if (!$pluginInfo) {
            return parent::setMime($mime);
        } else {
            return $this->___callPlugins('setMime', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getMime()
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getMime');
        if (!$pluginInfo) {
            return parent::getMime();
        } else {
            return $this->___callPlugins('getMime', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function generateMessage($EOL = '
')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'generateMessage');
        if (!$pluginInfo) {
            return parent::generateMessage($EOL);
        } else {
            return $this->___callPlugins('generateMessage', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPartHeadersArray($partnum)
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getPartHeadersArray');
        if (!$pluginInfo) {
            return parent::getPartHeadersArray($partnum);
        } else {
            return $this->___callPlugins('getPartHeadersArray', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPartHeaders($partnum, $EOL = '
')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getPartHeaders');
        if (!$pluginInfo) {
            return parent::getPartHeaders($partnum, $EOL);
        } else {
            return $this->___callPlugins('getPartHeaders', func_get_args(), $pluginInfo);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getPartContent($partnum, $EOL = '
')
    {
        $pluginInfo = $this->pluginList->getNext($this->subjectType, 'getPartContent');
        if (!$pluginInfo) {
            return parent::getPartContent($partnum, $EOL);
        } else {
            return $this->___callPlugins('getPartContent', func_get_args(), $pluginInfo);
        }
    }
}
