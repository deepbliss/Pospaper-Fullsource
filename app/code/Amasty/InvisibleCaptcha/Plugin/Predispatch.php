<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2018 Amasty (https://www.amasty.com)
 * @package Amasty_InvisibleCaptcha
 */


namespace Amasty\InvisibleCaptcha\Plugin;

class Predispatch
{
    /**
     * @var \Magento\Framework\UrlInterface
     */
    protected $urlBuilder;

    /**
     * @var \Magento\Framework\Message\ManagerInterface
     */
    protected $messageManager;

    /**
     * @var \Magento\Framework\App\Response\RedirectInterface
     */
    private $redirect;

    /**
     * @var \Magento\Framework\App\ResponseFactory
     */
    private $responseFactory;

    /**
     * Captcha model instance
     *
     * @var \Amasty\InvisibleCaptcha\Model\Captcha
     */
    private $captchaModel;

    /**
     * Predispatch constructor.
     *
     * @param \Magento\Framework\UrlInterface                   $urlBuilder
     * @param \Magento\Framework\Message\ManagerInterface       $messageManager
     * @param \Magento\Framework\App\Response\RedirectInterface $redirect
     * @param \Magento\Framework\App\ResponseFactory            $responseFactory
     * @param \Amasty\InvisibleCaptcha\Model\Captcha            $captchaModel
     */
    public function __construct(
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\Message\ManagerInterface $messageManager,
        \Magento\Framework\App\Response\RedirectInterface $redirect,
        \Magento\Framework\App\ResponseFactory $responseFactory,
        \Amasty\InvisibleCaptcha\Model\Captcha $captchaModel
    ) {
        $this->urlBuilder = $urlBuilder;
        $this->messageManager = $messageManager;
        $this->redirect = $redirect;
        $this->responseFactory = $responseFactory;
        $this->captchaModel = $captchaModel;
    }

    /**
     * @param \Magento\Framework\App\FrontControllerInterface $subject
     * @param \Closure                                        $proceed
     * @param \Magento\Framework\App\RequestInterface         $request
     *
     * @return \Magento\Framework\App\ResponseInterface|mixed
     */
    public function aroundDispatch(
        \Magento\Framework\App\FrontControllerInterface $subject,
        \Closure $proceed,
        \Magento\Framework\App\RequestInterface $request
    ) {
        if ($this->captchaModel->isNeedToShowCaptcha()) {
            foreach ($this->captchaModel->getUrls() as $captchaUrl) {
                if ($request->isPost()
                    && false !== strpos($this->urlBuilder->getCurrentUrl(), $captchaUrl)
                ) {
                    $token = $request->getPost('amasty_invisible_token');
                    $validation = $this->captchaModel->verify($token);
                    if (!$validation['success']) {
                        $this->messageManager->addErrorMessage($validation['error']);
                        $response = $this->responseFactory->create();
                        $response->setRedirect($this->redirect->getRefererUrl());
                        $response->setNoCacheHeaders();

                        return $response;
                    }
                    break;
                }
            }
        }
        $result = $proceed($request);

        return $result;
    }
}
