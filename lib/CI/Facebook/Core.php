<?php
class CI_Facebook_Core
{
    static $client;

    /**
     * @return Facebook
     */
    public static function getApiClient()
    {
        if (!self::$client)
        {
            $client = new Facebook(array('appId' => sfConfig::get('app_facebook_api_key'), 'secret' => sfConfig::get('app_facebook_api_secret'), 'cookie' => true));
            self::$client = $client;
        }

        return self::$client;
    }
}