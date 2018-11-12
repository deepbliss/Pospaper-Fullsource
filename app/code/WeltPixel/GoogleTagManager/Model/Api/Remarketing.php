<?php
namespace WeltPixel\GoogleTagManager\Model\Api;

/**
 * Class \WeltPixel\GoogleTagManager\Model\Api\Remarketing
 */
class Remarketing extends \WeltPixel\GoogleTagManager\Model\Api
{
    /**
     * Variable names
     */
    const VARIABLE_REMARKETING_GOOGLE_TAG = 'WP - Google Tag Params';

    /**
     * Tag names
     */
    const TAG_REMARKETING_ADWORDS_REMARKETING = 'WP - AdWords Remarketing';

    /**
     * Field names used in sending data to dataLayer
     */
    const FIELD_REMARKEING_GOOGLE_TAG = 'google_tag_params';


    const ECOMM_PAGETYPE_HOME = 'home';
    const ECOMM_PAGETYPE_CATEGORY = 'category';
    const ECOMM_PAGETYPE_SEARCHRESULTS = 'searchresults';
    const ECOMM_PAGETYPE_PRODUCT = 'product';
    const ECOMM_PAGETYPE_CART = 'cart';
    const ECOMM_PAGETYPE_PURCHASE = 'purchase';
    const ECOMM_PAGETYPE_OTHER = 'other';


    /**
     * @param array $params
     * @return array
     */
    public function createRemarketing($params)
    {
        $result = [];
        $result = array_merge($result, $this->_createRemarketingVariables($params));
        $result = array_merge($result, $this->_createRemarketingTags($params));

        return $result;
    }


    /**
     * @param array $params
     * @return array
     */
    protected function _createRemarketingVariables($params)
    {
        $accountId = $params['account_id'];
        $containerId = $params['container_id'];
        $existingVariables = $this->_getExistingVariables($accountId, $containerId);
        $result = [];
        $variableFlags = [];

        foreach ($existingVariables as $variable) {
            $variableFlags[$variable['name']] = true;
        }

        $variablesToCreate = $this->_getRemarketingVariables();

        foreach ($variablesToCreate as $name => $options) {
            /** Ignore already created variables */
            if (isset($variableFlags[$name])) continue;
            try {
                $response = $this->_createVariable($accountId, $containerId, $options);
                if ($response['variableId']) {
                    $result[] = __('Successfully created Remarketing variable: ') . $response['name'];
                } else {
                    $result[] = __('Error creating Remarketing variable: ') . $response['name'];
                }
            } catch (\Exception $ex) {
                $result[] = $ex->getMessage();
            }
        }

        return $result;
    }

    /**
     * @param array $params
     * @return array
     */
    protected function _createRemarketingTags($params)
    {
        $accountId = $params['account_id'];
        $containerId = $params['container_id'];
        $existingTags = $this->_getExistingTags($accountId, $containerId);
        $result = [];
        $tagFlags = [];


        foreach ($existingTags as $tag) {
            $tagFlags[$tag['name']] = true;
        }

        $triggersMapping = $this->_getTriggersMapping($accountId, $containerId);
        $tagsToCreate = $this->_getRemarketingTags($triggersMapping, $params);

        foreach ($tagsToCreate as $name => $options) {
            /** Ignore already created tags */
            if (isset($tagFlags[$name])) continue;
            try {
                $response = $this->_createTag($accountId, $containerId, $options);
                if ($response['tagId']) {
                    $result[] = __('Successfully created Remarketing Tracking tag: ') . $response['name'];
                } else {
                    $result[] = __('Error creating Remarketing Tracking tag: ') . $response['name'];
                }
            } catch (\Exception $ex) {
                $result[] = $ex->getMessage();
            }
        }

        return $result;
    }

    /**
     * Return list of variables for remarketing
     * @return array
     */
    private function _getRemarketingVariables()
    {
        $variables = array
        (
            self::VARIABLE_REMARKETING_GOOGLE_TAG => array
            (
                'name' => self::VARIABLE_REMARKETING_GOOGLE_TAG,
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
                        'value' => self::FIELD_REMARKEING_GOOGLE_TAG
                    )
                )
            )
        );

        return $variables;
    }

    /**
     * Return a list of tags for remarketing
     * @param array $triggers
     * @param array $params
     * @return array
     */
    private function _getRemarketingTags($triggers, $params)
    {
        $tags = array
        (
            self::TAG_REMARKETING_ADWORDS_REMARKETING => array
            (
                'name' => self::TAG_REMARKETING_ADWORDS_REMARKETING,
                'firingTriggerId' => array
                (
                    $triggers[self::TRIGGER_ALL_PAGES]
                ),
                'type' => self::TYPE_TAG_SP,
                'tagFiringOption' => 'oncePerEvent',
                'parameter' => array
                (
                    array
                    (
                        'type' => 'template',
                        'key' => 'dataLayerVariable',
                        'value' => '{{' . self::VARIABLE_REMARKETING_GOOGLE_TAG . '}}'
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'customParamsFormat',
                        'value' => 'DATA_LAYER'
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'conversionId',
                        'value' => $params['conversion_code']
                    ),
                    array
                    (
                        'type' => 'template',
                        'key' => 'conversionLabel',
                        'value' => $params['conversion_label']
                    )
                )
            )
        );

        return $tags;
    }
}
