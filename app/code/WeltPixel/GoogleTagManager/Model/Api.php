<?php
namespace WeltPixel\GoogleTagManager\Model;

use WeltPixel\GoogleTagManager\lib\Google\Client as Google_Client;

/**
 * Class \WeltPixel\GoogleTagManager\Model\Api
 */
class Api extends \Magento\Framework\Model\AbstractModel
{
    /**
     * Item types
     */
    const TYPE_VARIABLE_DATALAYER = 'v';
    const TYPE_VARIABLE_CONSTANT = 'c';
    const TYPE_TRIGGER_CUSTOM_EVENT = 'customEvent';
    const TYPE_TRIGGER_LINK_CLICK = 'linkClick';
    const TYPE_TRIGGER_PAGEVIEW = 'pageview';
    const TYPE_TRIGGER_DOMREADY = 'domReady';
    const TYPE_TAG_UA = 'ua';
    const TYPE_TAG_AWCT = 'awct';
    const TYPE_TAG_SP = 'sp';

    /**
     * Variable names
     */
    const VARIABLE_UA_TRACKING = 'WP - UA Tracking ID';
    const VARIABLE_EVENTLABEL = 'WP - Event Label';
    const VARIABLE_EVENTVALUE = 'WP - Event Value';

    /**
     * Trigger names
     */
    const TRIGGER_PRODUCT_CLICK = 'WP - Product Click';
    const TRIGGER_GTM_DOM = 'WP - gtm.dom';
    const TRIGGER_ADD_TO_CART = 'WP - Add To Cart';
    const TRIGGER_REMOVE_FROM_CART = 'WP - Remove From Cart';
    const TRIGGER_ALL_PAGES = 'WP - All Pages';
    const TRIGGER_EVENT_IMPRESSION = 'WP - Event Impression';
    const TRIGGER_PROMOTION_CLICK = 'WP - Promotion Click';
    const TRIGGER_CHECKOUT_OPTION = 'WP - Checkout Option';
    const TRIGGER_CHECKOUT_STEPS = 'WP - Checkout Steps';
    const TRIGGER_PROMOTION_VIEW = 'WP - Promotion View';
    const TRIGGER_ADD_TO_WISHLIST = 'WP - Add To Wishlist';
    const TRIGGER_ADD_TO_COMPARE = 'WP - Add To Compare';

    /**
     * Tag names
     */
    const TAG_GOOGLE_ANALYTICS = 'WP - Google Analytics';
    const TAG_PRODUCT_EVENT_CLICK = 'WP - Product Event - Click';
    const TAG_PRODUCT_EVENT_ADD_TO_CART = 'WP - Product Event - Add to Cart';
    const TAG_PRODUCT_EVENT_REMOVE_FROM_CART = 'WP - Product Event - Remove from Cart';
    const TAG_PRODUCT_EVENT_PRODUCT_IMPRESSIONS = 'WP - Product Event - Product Impressions';
    const TAG_CHECKOUT_STEP_OPTION = 'WP - Checkout Step Option';
    const TAG_CHECKOUT_STEP = 'WP - Checkout Step';
    const TAG_PROMOTION_IMPRESSION = 'WP - Promotion Impression';
    const TAG_PROMOTION_CLICK = 'WP - Promotion Click';
    const TAG_PRODUCT_EVENT_ADD_TO_WISHLIST = 'WP - Product Event - Add to Wishlist';
    const TAG_PRODUCT_EVENT_ADD_TO_COMPARE = 'WP - Product Event - Add to Compare';

    /**
     * @var string
     */
    private $oauthUrl = 'http://www.oauth.weltpixel.com';

    /**
     * @var string
     */
    private $clientId = '343821107733-2ctqe2qsq8j80on7pe5k9idtqf76lhk1.apps.googleusercontent.com';

    /**
     * @var string
     */
    private $clientSecret = 'GhEXBWj7Rcrdvs438XFxv3tn';

    /**
     * @var \Google_Client
     */
    private $client = null;

    /**
     * @var array
     */
    private $scopes = array
    (
        'https://www.googleapis.com/auth/userinfo.profile',
        'https://www.googleapis.com/auth/tagmanager.readonly',
        'https://www.googleapis.com/auth/tagmanager.edit.containers',
    );

    /**
     * @var string
     */
    protected $apiUrl = 'https://www.googleapis.com/tagmanager/v1/';

    /**
     * Url Builder
     *
     * @var \Magento\Framework\UrlInterface
     */
    protected $_urlBuilder;

    /**
     * @var \Magento\Backend\Model\Session
     */
    protected $_backendSession;

    /**
     * Http Client Factory
     *
     * @var \Magento\Framework\HTTP\ZendClientFactory
     */
    protected $httpClientFactory;

    /**
     * @var \Magento\Framework\App\Request\Http
     */
    protected $request;

    /**
     * @param \Magento\Framework\Model\Context $context
     * @param \Magento\Framework\Registry $registry
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param \Magento\Backend\Model\Session $backendSession
     * @param \Magento\Framework\HTTP\ZendClientFactory $httpClientFactory
     * @param \Magento\Framework\App\Request\Http $request
     */
    public function __construct(
        \Magento\Framework\Model\Context $context,
        \Magento\Framework\Registry $registry,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Backend\Model\Session $backendSession,
        \Magento\Framework\HTTP\ZendClientFactory $httpClientFactory,
        \Magento\Framework\App\Request\Http $request
    )
    {
        parent::__construct($context, $registry);
        $this->_urlBuilder = $urlBuilder;
        $this->_backendSession = $backendSession;
        $this->httpClientFactory = $httpClientFactory;
        $this->request = $request;
//        set_time_limit(0);
    }

    /**
     * Get Google_Client
     *
     * @return \Google_Client
     */
    public function getClient()
    {
        if (!$this->client) {
            $this->client = new Google_Client();

            $this->client->setApplicationName('WeltPixel-GTM');
            $this->client->setClientId($this->clientId);
            $this->client->setClientSecret($this->clientSecret);
            $this->client->setScopes($this->scopes);

            $storeId = $this->request->getParam('store');
            $websiteId = $this->request->getParam('website');
            $redirectUrl = $this->_urlBuilder->getUrl("adminhtml/system_config/edit", array('section' => 'weltpixel_googletagmanager', 'website' => $websiteId, 'store' => $storeId));
            $this->client->setState($redirectUrl);
            $this->client->setRedirectUri($this->oauthUrl);

            $code = $this->request->getParam('code');
            if ($code) {
                try {
                    $this->getClient()->authenticate($code);
                    $this->_backendSession->setAccessToken($this->client->getAccessToken());
                } catch (\Exception $ex) {};

                header('Location: ' . $redirectUrl);
                return;
            }

            $token = $this->_backendSession->getAccessToken();
            if ($token) {
                $this->client->setAccessToken($token);
            }
        }

        return $this->client;
    }

    /**
     * @return string
     */
    protected function getApiUrl()
    {
        return $this->apiUrl;
    }

    /**
     * @param $option
     * @param $accountId
     * @param $containerId
     * @param $uaTrackingId
     * @param $displayAdvertising
     * @return array|void
     */
    public function createItem($option, $accountId, $containerId, $uaTrackingId, $ipAnonymization, $displayAdvertising)
    {
        $result = [];
        switch ($option) {
            case 'variables':
                $result = $this->_createVariables($accountId, $containerId, $uaTrackingId);
                break;
            case 'triggers':
                $result = $this->_createTriggers($accountId, $containerId);
                break;
            case 'tags':
                $result = $this->_createTags($accountId, $containerId, $ipAnonymization, $displayAdvertising);
                break;
        }

        return $result;
    }

    /**
     * Create the variables using the API
     * @param $accountId
     * @param $containerId
     * @param $uaTrackingId
     * @return array
     */
    protected function _createVariables($accountId, $containerId, $uaTrackingId)
    {
        $existingVariables = $this->_getExistingVariables($accountId, $containerId);

        $result = [];
        $variableFlags = [];

        foreach ($existingVariables as $variable) {
            $variableFlags[$variable['name']] = true;
        }

        $variablesToCreate = $this->_getVariables($uaTrackingId);

        foreach ($variablesToCreate as $name => $options) {
            /** Ignore already created variables */
            if (isset($variableFlags[$name])) continue;
            try {
                $response = $this->_createVariable($accountId, $containerId, $options);
                if ($response['variableId']) {
                    $result[] = __('Successfully created variable: ') . $response['name'];
                } else {
                    $result[] = __('Error creating variable: ') . $response['name'];
                }
            } catch (\Exception $ex) {
                $result[] = $ex->getMessage();
            }
        }

        return $result;
    }

    /**
     * Create the triggers using the API
     * @param $accountId
     * @param $containerId
     * @return array
     */
    protected function _createTriggers($accountId, $containerId)
    {
        $existingTriggers = $this->_getExistingTriggers($accountId, $containerId);

        $result = [];
        $triggerFlags = [];

        foreach ($existingTriggers as $trigger) {
            $triggerFlags[$trigger['name']] = true;
        }

        $triggersToCreate = $this->_getTriggers();

        foreach ($triggersToCreate as $name => $options) {
            /** Ignore already created triggers */
            if (isset($triggerFlags[$name])) continue;
            try {
                $response = $this->_createTrigger($accountId, $containerId, $options);
                if ($response['triggerId']) {
                    $result[] = __('Successfully created trigger: ') . $response['name'];
                } else {
                    $result[] = __('Error creating trigger: ') . $response['name'];
                }
            } catch (\Exception $ex) {
                $result[] = $ex->getMessage();
            }
        }

        return $result;
    }

    /**
     * @param $accountId
     * @param $containerId
     * @param $ipAnonymization
     * @param $displayAdvertising
     * @return array
     */
    protected function _createTags($accountId, $containerId, $ipAnonymization, $displayAdvertising)
    {
        $ipAnonymization = ($ipAnonymization) ? 'true' : 'false';
        $displayAdvertising = ($displayAdvertising) ? 'true' : 'false';
        $existingTags = $this->_getExistingTags($accountId, $containerId);

        $result = [];
        $tagFlags = [];

        foreach ($existingTags as $tag) {
            $tagFlags[$tag['name']] = true;
        }

        $triggersMapping = $this->_getTriggersMapping($accountId, $containerId);
        $tagsToCreate = $this->_getTags($triggersMapping, $ipAnonymization, $displayAdvertising);

        foreach ($tagsToCreate as $name => $options) {
            /** Ignore already created tags */
            if (isset($tagFlags[$name])) continue;
            try {
                $response = $this->_createTag($accountId, $containerId, $options);
                if ($response['tagId']) {
                    $result[] = __('Successfully created tag: ') . $response['name'];
                } else {
                    $result[] = __('Error creating tag: ') . $response['name'];
                }
            } catch (\Exception $ex) {
                $result[] = $ex->getMessage();
            }
        }

        return $result;

    }

    /**
     * Return array with trigger name and trigger id, for usage in tags creation
     * @param $accountId
     * @param $containerId
     * @return array
     */
    protected function _getTriggersMapping($accountId, $containerId)
    {
        $triggers = $this->_getExistingTriggers($accountId, $containerId);
        $triggersMap = [];

        foreach ($triggers as $trigger) {
            $triggersMap[$trigger['name']] = $trigger['triggerId'];
        }

        return $triggersMap;
    }

    /**
     * Return list of variables for api creation
     * @param $uaTrackingId
     * @return array
     */
    private function _getVariables($uaTrackingId)
    {
        $variables = array
        (
            self::VARIABLE_UA_TRACKING => array
            (
                'name' => self::VARIABLE_UA_TRACKING,
                'type' => self::TYPE_VARIABLE_CONSTANT,
                'parameter' => array
                (
                    array
                    (
                        'type' => 'template',
                        'key' => 'value',
                        'value' => $uaTrackingId
                    )
                )
            ),
            self::VARIABLE_EVENTLABEL => array
            (
                'name' => self::VARIABLE_EVENTLABEL,
                'type' => self::TYPE_VARIABLE_DATALAYER,
                'parameter' => array
                (
                    array
                    (
                        'type' => 'integer',
                        'key' => 'dataLayerVersion',
                        'value' => 2
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'setDefaultValue',
                        'value' => false
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'name',
                        'value' => 'eventLabel'
                    )
                )
            ),
            self::VARIABLE_EVENTVALUE => array
            (
                'name' => self::VARIABLE_EVENTVALUE,
                'type' => self::TYPE_VARIABLE_DATALAYER,
                'parameter' => array
                (
                    array
                    (
                        'type' => 'integer',
                        'key' => 'dataLayerVersion',
                        'value' => 2
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'setDefaultValue',
                        'value' => false
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'name',
                        'value' => 'eventValue'
                    )
                )
            )
        );

        return $variables;
    }

    /**
     * Return list of triggers for api creation
     * @return array
     */
    private function _getTriggers()
    {
        $triggers = array
        (
            self::TRIGGER_PRODUCT_CLICK => array
            (
                'name' => self::TRIGGER_PRODUCT_CLICK,
                'type' => self::TYPE_TRIGGER_CUSTOM_EVENT,
                'customEventFilter' => array
                (
                    array
                    (
                        'type' => 'equals',
                        'parameter' => array
                        (
                            array
                            (
                                'type' => 'template',
                                'key' => 'arg0',
                                'value' => '{{_event}}'
                            ),
                            array
                            (
                                'type' => 'template',
                                'key' => 'arg1',
                                'value' => 'productClick'
                            )
                        )
                    )
                )
            ),
            self::TRIGGER_GTM_DOM => array
            (
                'name' => self::TRIGGER_GTM_DOM,
                'type' => self::TYPE_TRIGGER_DOMREADY
            ),
            self::TRIGGER_ADD_TO_CART => array
            (
                'name' => self::TRIGGER_ADD_TO_CART,
                'type' => self::TYPE_TRIGGER_CUSTOM_EVENT,
                'customEventFilter' => array
                (
                    array
                    (
                        'type' => 'equals',
                        'parameter' => array
                        (
                            array
                            (
                                'type' => 'template',
                                'key' => 'arg0',
                                'value' => '{{_event}}'
                            ),
                            array
                            (
                                'type' => 'template',
                                'key' => 'arg1',
                                'value' => 'addToCart'
                            )
                        )
                    )
                )
            ),
            self::TRIGGER_REMOVE_FROM_CART => array
            (
                'name' => self::TRIGGER_REMOVE_FROM_CART,
                'type' => self::TYPE_TRIGGER_CUSTOM_EVENT,
                'customEventFilter' => array
                (
                    array
                    (
                        'type' => 'equals',
                        'parameter' => array
                        (
                            array
                            (
                                'type' => 'template',
                                'key' => 'arg0',
                                'value' => '{{_event}}'
                            ),
                            array
                            (
                                'type' => 'template',
                                'key' => 'arg1',
                                'value' => 'removeFromCart'
                            )
                        )
                    )
                )
            ),
            self::TRIGGER_ALL_PAGES => array
            (
                'name' => self::TRIGGER_ALL_PAGES,
                'type' => self::TYPE_TRIGGER_PAGEVIEW
            ),
            self::TRIGGER_EVENT_IMPRESSION => array
            (
                'name' => self::TRIGGER_EVENT_IMPRESSION,
                'type' => self::TYPE_TRIGGER_CUSTOM_EVENT,
                'customEventFilter' => array
                (
                    array
                    (
                        'type' => 'equals',
                        'parameter' => array
                        (
                            array
                            (
                                'type' => 'template',
                                'key' => 'arg0',
                                'value' => '{{_event}}'
                            ),
                            array
                            (
                                'type' => 'template',
                                'key' => 'arg1',
                                'value' => 'impression'
                            )
                        )
                    )
                )
            ),
            self::TRIGGER_PROMOTION_CLICK => array
            (
                'name' => self::TRIGGER_PROMOTION_CLICK,
                'type' => self::TYPE_TRIGGER_CUSTOM_EVENT,
                'customEventFilter' => array
                (
                    array
                    (
                        'type' => 'equals',
                        'parameter' => array
                        (
                            array
                            (
                                'type' => 'template',
                                'key' => 'arg0',
                                'value' => '{{_event}}'
                            ),
                            array
                            (
                                'type' => 'template',
                                'key' => 'arg1',
                                'value' => 'promotionClick'
                            )
                        )
                    )
                )
            ),
            self::TRIGGER_CHECKOUT_OPTION => array
            (
                'name' => self::TRIGGER_CHECKOUT_OPTION,
                'type' => self::TYPE_TRIGGER_CUSTOM_EVENT,
                'customEventFilter' => array
                (
                    array
                    (
                        'type' => 'equals',
                        'parameter' => array
                        (
                            array
                            (
                                'type' => 'template',
                                'key' => 'arg0',
                                'value' => '{{_event}}'
                            ),
                            array
                            (
                                'type' => 'template',
                                'key' => 'arg1',
                                'value' => 'checkoutOption'
                            )
                        )
                    )
                )
            ),
            self::TRIGGER_CHECKOUT_STEPS => array
            (
                'name' => self::TRIGGER_CHECKOUT_STEPS,
                'type' => self::TYPE_TRIGGER_CUSTOM_EVENT,
                'customEventFilter' => array
                (
                    array
                    (
                        'type' => 'equals',
                        'parameter' => array
                        (
                            array
                            (
                                'type' => 'template',
                                'key' => 'arg0',
                                'value' => '{{_event}}'
                            ),
                            array
                            (
                                'type' => 'template',
                                'key' => 'arg1',
                                'value' => 'checkout'
                            )
                        )
                    )
                )
            ),
            self::TRIGGER_PROMOTION_VIEW => array
            (
                'name' => self::TRIGGER_PROMOTION_VIEW,
                'type' => self::TYPE_TRIGGER_CUSTOM_EVENT,
                'customEventFilter' => array
                (
                    array
                    (
                        'type' => 'equals',
                        'parameter' => array
                        (
                            array
                            (
                                'type' => 'template',
                                'key' => 'arg0',
                                'value' => '{{_event}}'
                            ),
                            array
                            (
                                'type' => 'template',
                                'key' => 'arg1',
                                'value' => 'promotionView'
                            )
                        )
                    )
                )
            ),
            self::TRIGGER_ADD_TO_WISHLIST => array
            (
                'name' => self::TRIGGER_ADD_TO_WISHLIST,
                'type' => self::TYPE_TRIGGER_CUSTOM_EVENT,
                'customEventFilter' => array
                (
                    array
                    (
                        'type' => 'equals',
                        'parameter' => array
                        (
                            array
                            (
                                'type' => 'template',
                                'key' => 'arg0',
                                'value' => '{{_event}}'
                            ),
                            array
                            (
                                'type' => 'template',
                                'key' => 'arg1',
                                'value' => 'addToWishlist'
                            )
                        )
                    )
                )
            ),
            self::TRIGGER_ADD_TO_COMPARE => array
            (
                'name' => self::TRIGGER_ADD_TO_COMPARE,
                'type' => self::TYPE_TRIGGER_CUSTOM_EVENT,
                'customEventFilter' => array
                (
                    array
                    (
                        'type' => 'equals',
                        'parameter' => array
                        (
                            array
                            (
                                'type' => 'template',
                                'key' => 'arg0',
                                'value' => '{{_event}}'
                            ),
                            array
                            (
                                'type' => 'template',
                                'key' => 'arg1',
                                'value' => 'addToCompare'
                            )
                        )
                    )
                )
            )
        );
        return $triggers;
    }

    /**
     * Return list of tags for api creation
     * @param array $triggers
     * @param bool $ipAnonymization
     * @param bool $displayAdvertising
     * @return array
     */
    private function _getTags($triggers, $ipAnonymization, $displayAdvertising)
    {
        $tags = array
        (
            self::TAG_PRODUCT_EVENT_CLICK => array
            (
                'name' => self::TAG_PRODUCT_EVENT_CLICK,
                'firingTriggerId' => array
                (
                    $triggers[self::TRIGGER_PRODUCT_CLICK]
                ),
                'type' => self::TYPE_TAG_UA,
                'tagFiringOption' => 'oncePerEvent',
                'parameter' => array
                (
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'nonInteraction',
                        'value' => false
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'useEcommerceDataLayer',
                        'value' => true
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'doubleClick',
                        'value' => $displayAdvertising
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'setTrackerName',
                        'value' => false
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'useDebugVersion',
                        'value' => false
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'eventCategory',
                        'value' => 'Ecommerce'
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'trackType',
                        'value' => 'TRACK_EVENT'
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'enableLinkId',
                        'value' => false
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'eventAction',
                        'value' => 'Product Click'
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'enableEcommerce',
                        'value' => true
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'overrideGaSettings',
                        'value' => true
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'eventLabel',
                        'value' => '{{' . self::VARIABLE_EVENTLABEL . '}}'
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'trackingId',
                        'value' => '{{' . self::VARIABLE_UA_TRACKING . '}}'
                    )
                )
            ),
            self::TAG_PRODUCT_EVENT_ADD_TO_CART => array
            (
                'name' => self::TAG_PRODUCT_EVENT_ADD_TO_CART,
                'firingTriggerId' => array
                (
                    $triggers[self::TRIGGER_ADD_TO_CART]
                ),
                'type' => self::TYPE_TAG_UA,
                'tagFiringOption' => 'oncePerEvent',
                'parameter' => array
                (
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'nonInteraction',
                        'value' => false
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'useEcommerceDataLayer',
                        'value' => true
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'doubleClick',
                        'value' => $displayAdvertising
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'setTrackerName',
                        'value' => false
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'useDebugVersion',
                        'value' => false
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'eventCategory',
                        'value' => 'Ecommerce'
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'trackType',
                        'value' => 'TRACK_EVENT'
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'enableLinkId',
                        'value' => false
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'eventAction',
                        'value' => 'Add to Cart'
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'enableEcommerce',
                        'value' => true
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'overrideGaSettings',
                        'value' => true
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'evenValue',
                        'value' => '{{' . self::VARIABLE_EVENTVALUE . '}}'
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'eventLabel',
                        'value' => '{{' . self::VARIABLE_EVENTLABEL . '}}'
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'trackingId',
                        'value' => '{{' . self::VARIABLE_UA_TRACKING . '}}'
                    )
                )
            ),
            self::TAG_PRODUCT_EVENT_REMOVE_FROM_CART => array
            (
                'name' => self::TAG_PRODUCT_EVENT_REMOVE_FROM_CART,
                'firingTriggerId' => array
                (
                    $triggers[self::TRIGGER_REMOVE_FROM_CART]
                ),
                'type' => self::TYPE_TAG_UA,
                'tagFiringOption' => 'oncePerEvent',
                'parameter' => array
                (
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'nonInteraction',
                        'value' => false
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'useEcommerceDataLayer',
                        'value' => true
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'doubleClick',
                        'value' => $displayAdvertising
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'setTrackerName',
                        'value' => false
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'useDebugVersion',
                        'value' => false
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'eventCategory',
                        'value' => 'Ecommerce'
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'trackType',
                        'value' => 'TRACK_EVENT'
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'enableLinkId',
                        'value' => false
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'eventAction',
                        'value' => 'Remove from Cart'
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'enableEcommerce',
                        'value' => true
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'enableLinkId',
                        'value' => false
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'overrideGaSettings',
                        'value' => true
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'evenValue',
                        'value' => '{{' . self::VARIABLE_EVENTVALUE . '}}'
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'eventLabel',
                        'value' => '{{' . self::VARIABLE_EVENTLABEL . '}}'
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'trackingId',
                        'value' => '{{' . self::VARIABLE_UA_TRACKING . '}}'
                    )
                )
            ),
            self::TAG_PRODUCT_EVENT_PRODUCT_IMPRESSIONS => array
            (
                'name' => self::TAG_PRODUCT_EVENT_PRODUCT_IMPRESSIONS,
                'firingTriggerId' => array
                (
                    $triggers[self::TRIGGER_EVENT_IMPRESSION]
                ),
                'type' => self::TYPE_TAG_UA,
                'tagFiringOption' => 'oncePerEvent',
                'parameter' => array
                (
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'nonInteraction',
                        'value' => true
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'useEcommerceDataLayer',
                        'value' => true
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'doubleClick',
                        'value' => $displayAdvertising
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'setTrackerName',
                        'value' => false
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'useDebugVersion',
                        'value' => false
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'eventCategory',
                        'value' => 'Ecommerce'
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'trackType',
                        'value' => 'TRACK_EVENT'
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'enableLinkId',
                        'value' => false
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'eventAction',
                        'value' => 'Impression'
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'enableEcommerce',
                        'value' => true
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'overrideGaSettings',
                        'value' => true
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'eventLabel',
                        'value' => '{{' . self::VARIABLE_EVENTLABEL . '}}'
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'trackingId',
                        'value' => '{{' . self::VARIABLE_UA_TRACKING . '}}'
                    )
                )
            ),
            self::TAG_GOOGLE_ANALYTICS => array
            (
                'name' => self::TAG_GOOGLE_ANALYTICS,
                'firingTriggerId' => array
                (
                    $triggers[self::TRIGGER_ALL_PAGES]
                ),
                'tagFiringOption' => 'oncePerLoad',
                'type' => self::TYPE_TAG_UA,
                'parameter' => array
                (
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'useEcommerceDataLayer',
                        'value' => true
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'doubleClick',
                        'value' => $displayAdvertising
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'setTrackerName',
                        'value' => false
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'useDebugVersion',
                        'value' => false
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'useHashAutoLink',
                        'value' => false
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'trackType',
                        'value' => 'TRACK_PAGEVIEW'
                    ),
                    array(
                        'type' => 'boolean',
                        'key' => 'decorateFormsAutoLink',
                        'value' => false
                    ),
                    array(
                        'type' => 'boolean',
                        'key' => 'enableLinkId',
                        'value' => false
                    ),
                    array(
                        'type' => 'boolean',
                        'key' => 'enableEcommerce',
                        'value' => true
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'overrideGaSettings',
                        'value' => true
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'trackingId',
                        'value' => '{{' . self::VARIABLE_UA_TRACKING . '}}'
                    ),
                    array
                    (
                        'type' => 'list',
                        'key' => 'fieldsToSet',
                        'list' => array
                        (
                            array
                            (
                                'type' => 'map',
                                'map' => array
                                (
                                    array
                                    (
                                        'type' => 'template',
                                        'key' => 'fieldName',
                                        'value' => 'anonymizeIp'
                                    ),
                                    array
                                    (
                                        'type' => 'template',
                                        'key' => 'value',
                                        'value' => $ipAnonymization
                                    )
                                )
                            )
                        )
                    )
                )
            ),
            self::TAG_CHECKOUT_STEP_OPTION => array
            (
                'name' => self::TAG_CHECKOUT_STEP_OPTION,
                'firingTriggerId' => array
                (
                    $triggers[self::TRIGGER_CHECKOUT_OPTION]
                ),
                'type' => self::TYPE_TAG_UA,
                'tagFiringOption' => 'oncePerEvent',
                'parameter' => array
                (
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'nonInteraction',
                        'value' => false
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'useEcommerceDataLayer',
                        'value' => true
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'eventCategory',
                        'value' => 'Ecommerce'
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'trackType',
                        'value' => 'TRACK_EVENT'
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'eventAction',
                        'value' => 'Checkout Option'
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'enableEcommerce',
                        'value' => true
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'overrideGaSettings',
                        'value' => true
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'doubleClick',
                        'value' => $displayAdvertising
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'eventLabel',
                        'value' => '{{' . self::VARIABLE_EVENTLABEL . '}}'
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'trackingId',
                        'value' => '{{' . self::VARIABLE_UA_TRACKING . '}}'
                    )
                )
            ),
            self::TAG_CHECKOUT_STEP => array
            (
                'name' => self::TAG_CHECKOUT_STEP,
                'firingTriggerId' => array
                (
                    $triggers[self::TRIGGER_CHECKOUT_STEPS]
                ),
                'type' => self::TYPE_TAG_UA,
                'tagFiringOption' => 'oncePerEvent',
                'parameter' => array
                (
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'nonInteraction',
                        'value' => false
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'useEcommerceDataLayer',
                        'value' => true
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'eventCategory',
                        'value' => 'Ecommerce'
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'trackType',
                        'value' => 'TRACK_EVENT'
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'eventAction',
                        'value' => 'Checkout'
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'enableEcommerce',
                        'value' => true
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'overrideGaSettings',
                        'value' => true
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'doubleClick',
                        'value' => $displayAdvertising
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'eventLabel',
                        'value' => '{{' . self::VARIABLE_EVENTLABEL . '}}'
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'trackingId',
                        'value' => '{{' . self::VARIABLE_UA_TRACKING . '}}'
                    )
                )
            ),
            self::TAG_PROMOTION_IMPRESSION => array
            (
                'name' => self::TAG_PROMOTION_IMPRESSION,
                'firingTriggerId' => array
                (
                    $triggers[self::TRIGGER_PROMOTION_VIEW]
                ),
                'type' => self::TYPE_TAG_UA,
                'tagFiringOption' => 'oncePerEvent',
                'parameter' => array
                (
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'nonInteraction',
                        'value' => true
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'useEcommerceDataLayer',
                        'value' => true
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'eventCategory',
                        'value' => 'Promotion'
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'trackType',
                        'value' => 'TRACK_EVENT'
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'eventAction',
                        'value' => 'Promotion View'
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'enableEcommerce',
                        'value' => true
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'overrideGaSettings',
                        'value' => true
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'doubleClick',
                        'value' => $displayAdvertising
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'eventLabel',
                        'value' => '{{' . self::VARIABLE_EVENTLABEL . '}}'
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'trackingId',
                        'value' => '{{' . self::VARIABLE_UA_TRACKING . '}}'
                    )
                )
            ),
            self::TAG_PROMOTION_CLICK => array
            (
                'name' => self::TAG_PROMOTION_CLICK,
                'firingTriggerId' => array
                (
                    $triggers[self::TRIGGER_PROMOTION_CLICK]
                ),
                'type' => self::TYPE_TAG_UA,
                'tagFiringOption' => 'oncePerEvent',
                'parameter' => array
                (
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'nonInteraction',
                        'value' => false
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'useEcommerceDataLayer',
                        'value' => true
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'eventCategory',
                        'value' => 'Ecommerce'
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'trackType',
                        'value' => 'TRACK_EVENT'
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'eventAction',
                        'value' => 'Promotion Click'
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'enableEcommerce',
                        'value' => true
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'overrideGaSettings',
                        'value' => true
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'doubleClick',
                        'value' => $displayAdvertising
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'eventLabel',
                        'value' => '{{' . self::VARIABLE_EVENTLABEL . '}}'
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'trackingId',
                        'value' => '{{' . self::VARIABLE_UA_TRACKING . '}}'
                    )
                )
            ),
            self::TAG_PRODUCT_EVENT_ADD_TO_WISHLIST => array
            (
                'name' => self::TAG_PRODUCT_EVENT_ADD_TO_WISHLIST,
                'firingTriggerId' => array
                (
                    $triggers[self::TRIGGER_ADD_TO_WISHLIST]
                ),
                'type' => self::TYPE_TAG_UA,
                'tagFiringOption' => 'oncePerEvent',
                'parameter' => array
                (
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'nonInteraction',
                        'value' => false
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'useEcommerceDataLayer',
                        'value' => true
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'eventCategory',
                        'value' => 'Ecommerce'
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'trackType',
                        'value' => 'TRACK_EVENT'
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'eventAction',
                        'value' => 'Wishlist'
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'enableEcommerce',
                        'value' => true
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'overrideGaSettings',
                        'value' => true
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'doubleClick',
                        'value' => $displayAdvertising
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'eventLabel',
                        'value' => '{{' . self::VARIABLE_EVENTLABEL . '}}'
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'trackingId',
                        'value' => '{{' . self::VARIABLE_UA_TRACKING . '}}'
                    )
                )
            ),
            self::TAG_PRODUCT_EVENT_ADD_TO_COMPARE => array
            (
                'name' => self::TAG_PRODUCT_EVENT_ADD_TO_COMPARE,
                'firingTriggerId' => array
                (
                    $triggers[self::TRIGGER_ADD_TO_COMPARE]
                ),
                'type' => self::TYPE_TAG_UA,
                'tagFiringOption' => 'oncePerEvent',
                'parameter' => array
                (
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'nonInteraction',
                        'value' => false
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'useEcommerceDataLayer',
                        'value' => true
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'eventCategory',
                        'value' => 'Ecommerce'
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'trackType',
                        'value' => 'TRACK_EVENT'
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'eventAction',
                        'value' => 'Compare'
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'enableEcommerce',
                        'value' => true
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'overrideGaSettings',
                        'value' => true
                    ),
                    array
                    (
                        'type' => 'boolean',
                        'key' => 'doubleClick',
                        'value' => $displayAdvertising
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'eventLabel',
                        'value' => '{{' . self::VARIABLE_EVENTLABEL . '}}'
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'trackingId',
                        'value' => '{{' . self::VARIABLE_UA_TRACKING . '}}'
                    )
                )
            )
        );

        return $tags;
    }

    /**
     * @param string $accountId
     * @param string $containerId
     * @return mixed
     * @throws \Exception
     */
    protected function _getExistingVariables($accountId, $containerId)
    {
        /** @var \Magento\Framework\HTTP\ZendClient $client */
        $client = $this->httpClientFactory->create();
        $tokenInfo = json_decode($this->getClient()->getAccessToken());

        $client->setUri($this->getApiUrl() . 'accounts/' . $accountId . '/containers/' . $containerId . '/variables')
            ->setConfig(['timeout' => 30])
            ->setMethod(\Zend_Http_Client::GET)
            ->setHeaders('Authorization', 'Bearer ' . $tokenInfo->access_token);

        try {
            $response = $client->request();
        } catch (\Exception $e) {
            throw new \Exception(__('Api error on variable listing: ') . $e->getMessage());
        }

        $responseBody = json_decode($response->getBody(), true);
        if ($response->getStatus() != 200) {
            throw new \Exception(__('Api error on variable listing: ') . $responseBody['error']['message']);
        }

        $existingVariables = (isset($responseBody['variables'])) ? $responseBody['variables'] : [];

        return $existingVariables;
    }

    /**
     * @param string $accountId
     * @param string $containerId
     * @return mixed
     * @throws \Exception
     */
    protected function _getExistingTriggers($accountId, $containerId)
    {
        /** @var \Magento\Framework\HTTP\ZendClient $client */
        $client = $this->httpClientFactory->create();
        $tokenInfo = json_decode($this->getClient()->getAccessToken());

        $client->setUri($this->getApiUrl() . 'accounts/' . $accountId . '/containers/' . $containerId . '/triggers')
            ->setConfig(['timeout' => 30])
            ->setMethod(\Zend_Http_Client::GET)
            ->setHeaders('Authorization', 'Bearer ' . $tokenInfo->access_token);

        try {
            $response = $client->request();
        } catch (\Exception $e) {
            throw new \Exception(__('Api error on trigger listing: ') . $e->getMessage());
        }

        $responseBody = json_decode($response->getBody(), true);
        if ($response->getStatus() != 200) {
            throw new \Exception(__('Api error on trigger listing: ') . $responseBody['error']['message']);
        }

        $existingTriggers = (isset($responseBody['triggers'])) ? $responseBody['triggers'] : [];

        return $existingTriggers;

    }

    /**
     * @param string $accountId
     * @param string $containerId
     * @return mixed
     * @throws \Exception
     */
    protected function _getExistingTags($accountId, $containerId)
    {
        /** @var \Magento\Framework\HTTP\ZendClient $client */
        $client = $this->httpClientFactory->create();
        $tokenInfo = json_decode($this->getClient()->getAccessToken());

        $client->setUri($this->getApiUrl() . 'accounts/' . $accountId . '/containers/' . $containerId . '/tags')
            ->setConfig(['timeout' => 30])
            ->setMethod(\Zend_Http_Client::GET)
            ->setHeaders('Authorization', 'Bearer ' . $tokenInfo->access_token);

        try {
            $response = $client->request();
        } catch (\Exception $e) {
            throw new \Exception(__('Api error on tag listing: ' ) . $e->getMessage());
        }

        $responseBody = json_decode($response->getBody(), true);
        if ($response->getStatus() != 200) {
            throw new \Exception(__('Api error on tag listing: ') . $responseBody['error']['message']);
        }

        $existingTags = (isset($responseBody['tags'])) ? $responseBody['tags'] : [];

        return $existingTags;
    }

    /**
     * @param string $accountId
     * @param string $containerId
     * @param array $options
     * @return mixed
     * @throws \Exception
     */
    protected function _createVariable($accountId, $containerId, $options)
    {
        /** @var \Magento\Framework\HTTP\ZendClient $client */
        $client = $this->httpClientFactory->create();
        $tokenInfo = json_decode($this->getClient()->getAccessToken());

        $client->setUri($this->getApiUrl() . 'accounts/' . $accountId . '/containers/' . $containerId . '/variables')
            ->setConfig(['timeout' => 30])
            ->setMethod(\Zend_Http_Client::POST)
            ->setRawData(json_encode($options), 'application/json')
            ->setHeaders('Authorization', 'Bearer ' . $tokenInfo->access_token);

        try {
            $response = $client->request();
        } catch (\Exception $e) {
            throw new \Exception(__('Api error on variable creation: ') . $options['name'] . ' ' . $e->getMessage());
        }

        $responseBody = json_decode($response->getBody(), true);
        if ($response->getStatus() != 200) {
            throw new \Exception(__('Api error on variable creation: ') . $options['name'] . ' ' . $responseBody['error']['message']);
        }

        return $responseBody;
    }

    /**
     * @param string $accountId
     * @param string $containerId
     * @param array $options
     * @return mixed
     * @throws \Exception
     */
    protected function _createTrigger($accountId, $containerId, $options)
    {
        /** @var \Magento\Framework\HTTP\ZendClient $client */
        $client = $this->httpClientFactory->create();
        $tokenInfo = json_decode($this->getClient()->getAccessToken());

        $client->setUri($this->getApiUrl() . 'accounts/' . $accountId . '/containers/' . $containerId . '/triggers')
            ->setConfig(['timeout' => 30])
            ->setMethod(\Zend_Http_Client::POST)
            ->setRawData(json_encode($options), 'application/json')
            ->setHeaders('Authorization', 'Bearer ' . $tokenInfo->access_token);

        try {
            $response = $client->request();
        } catch (\Exception $e) {
            throw new \Exception(__('Api error on trigger creation: ') . $options['name'] . ' ' . $e->getMessage());
        }

        $responseBody = json_decode($response->getBody(), true);
        if ($response->getStatus() != 200) {
            throw new \Exception(__('Api error on trigger creation: ') . $options['name'] . ' ' . $responseBody['error']['message']);
        }

        return $responseBody;
    }

    /**
     * @param string $accountId
     * @param string $containerId
     * @param array $options
     * @return mixed
     * @throws \Exception
     */
    protected function _createTag($accountId, $containerId, $options)
    {
        /** @var \Magento\Framework\HTTP\ZendClient $client */
        $client = $this->httpClientFactory->create();
        $tokenInfo = json_decode($this->getClient()->getAccessToken());

        $client->setUri($this->getApiUrl() . 'accounts/' . $accountId . '/containers/' . $containerId . '/tags')
            ->setConfig(['timeout' => 30])
            ->setMethod(\Zend_Http_Client::POST)
            ->setRawData(json_encode($options), 'application/json')
            ->setHeaders('Authorization', 'Bearer ' . $tokenInfo->access_token);

        try {
            $response = $client->request();
        } catch (\Exception $e) {
            throw new \Exception(__('Api error on tag creation: ') . $options['name'] . ' ' . $e->getMessage());
        }

        $responseBody = json_decode($response->getBody(), true);
        if ($response->getStatus() != 200) {
            throw new \Exception(__('Api error on tag creation: ') . $options['name'] . ' ' . $responseBody['error']['message']);
        }

        return $responseBody;
    }

}
