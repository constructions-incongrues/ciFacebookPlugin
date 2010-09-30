<?php
/**
 * @package    Facebook
 * @subpackage BackwardCompatibility
 */
class sfFacebook
{
    /**
     * Gets the currently logged sfGuardUser using Facebook Session.
     *
     * @param boolean $create   Not used
     * @param boolean $isActive Not used
     *
     * @return sfGuardUser
     */
    public static function getSfGuardUserByFacebookSession($create = true, $isActive = true)
    {
        $user = null;
        $uid = CI_Facebook_Core::getApiClient()->getUser();
        $q = Doctrine_Query::create()
            ->from('UserGameData u')
            ->where('u.facebook_uid = ?', $uid);

        if ($q->count())
        {
            $user = $q->fetchOne();
        }

        return $user;
    }

    public static function getOrCreateUserByFacebookUid($facebook_uid, $isActive = true)
    {
        $q = Doctrine_Query::create()
            ->from('UserGameData u')
            ->where('u.facebook_uid = ?', $facebook_uid);

        if ($q->count())
        {
            $user = $q->fetchOne();
        }
        else
        {
            $user = new UserGameData();
            $user->facebook_uid = $facebook_uid;
            $user->save();
        }

        return $user;
    }

    /**
     * @return FacebookRestClient
     */
    public static function getFacebookApi()
    {
        return new FacebookRestClient();
    }

    /**
     * @return wkCompatFacebookClient
     */
    public static function getFacebookClient()
    {
        return new ciCompatFacebookClient();
    }
}
