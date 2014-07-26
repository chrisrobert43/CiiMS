<?php

class CiiAnalytics extends EAnalytics
{
	public $options;

	public $lowerBounceRate;

	/**
	 * Direct overload of EAnalytics::getProviders()
	 * @return array(), Providors from database merges with providers from config
	 */
	public function getProviders()
	{
		return CMap::mergeArray($this->options, $this->getAnalyticsProviders());
	}

	/**
     * Retrieves Analytics.js Providers
     * @return array $providors
     */
    private function getAnalyticsProviders()
    {
        $providers = Yii::app()->cache->get('analyticsjs_providers');

        if ($providers === false)
        {
            $analytics = new AnalyticsSettings;
            $rules = $analytics->rules();
            unset($analytics);

            // Init the array
            $providers = array();

            // Retrieve all the providors that are enabled
            $response = Yii::app()->db->createCommand('SELECT REPLACE(`key`, "analyticsjs_", "") AS `key`, value FROM `configuration` WHERE `key` LIKE "analyticsjs_%_enabled" AND value = 1')->queryAll();
            foreach ($response as $element)
            {
                $k = $element['key'];
                $provider = explode('_', str_replace("__", " " ,str_replace("___", ".", $k)));
                $provider = reset($provider);

                $sqlProvider = str_replace(" ", "__" ,str_replace(".", "___", $provider));
                $data = Yii::app()->db->createCommand('SELECT REPLACE(`key`, "analyticsjs_", "") AS `key`, value FROM `configuration` WHERE `key` LIKE "analyticsjs_' . $sqlProvider .'%" AND `key` != "analyticsjs_' . $sqlProvider .'_enabled"')->queryAll();

                //$provider = str_replace('pwk', 'Piwik', $provider);

                foreach ($data as $el)
                {
                    $k = $el['key'];
                    //$k = str_replace("pwk_", "Piwik_", $k);

                    $v = $el['value'];
                    $p = explode('_', str_replace("__", " " ,str_replace("___", ".", $k)));
                    if ($v !== "")
                    {
                        $thisRule = 'string';
                        foreach ($rules as $rule)
                        {
                            if (strpos($rule[0], 'analyticsjs_' . $k) !== false)
                                $thisRule = $rule[1];
                        }

                        if ($thisRule == 'boolean')
                        {
                            if ($v == "0")
                                $providers[$provider][$p[1]] = (bool)false;
                            else if ($v == "1")
                                $providers[$provider][$p[1]] = (bool)true;
                            else
                                $providers[$provider][$p[1]] = 'null';
                        }
                        else
                        {
                            if ($v == "" || $v == null || $v == "null")
                                $providers[$provider][$p[1]] = null;
                            else
                                $providers[$provider][$p[1]] = $v;
                        }

                    }
                }
            }

            Yii::app()->cache->set('analyticsjs_providers', $providers);
        }

        $providers['CiiMS'] = array(
            'endpoint' => Yii::app()->getBaseUrl(true).'/api/event'
        );
        
        return $providers;
    }
}