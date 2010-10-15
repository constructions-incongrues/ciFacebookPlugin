<?php
/**
 * Filter for authorizing users on Facebook.
 *
 * @package    Facebook
 * @subpackage Filter
 */
class CI_Facebook_Filter_Authorize extends sfFilter
{
    /**
     * @see http://github.com/facebook/php-sdk/blob/v2.1.1/examples/example.php
     *
     * @sql read  search user corresponding to logged in user Facebook UID
     * @sql write (optionnaly) create user in database if he doesn't exist
     */
    public function execute(sfFilterChain $filterChain)
    {
        if ($this->isFirstCall())
        {
            // Find out if a session already exist. If so, check that it is still valid.
            $me = null;
            $facebook = CI_Facebook_Core::getApiClient();
            $session = $facebook->getSession();
            if ($session)
            {
                // Make sure that session is still valid
                $uid = $facebook->getUser();
                $me = $facebook->api('/me');
            }

            // Session either does not exist or is invalid. Let's redirect user to login url.
            if (!$me)
            {
               $loginUrl = $facebook->getLoginUrl(array('canvas' => 1, 'fbconnect' => 0));
               exit("<script type='text/javascript'>top.location.href = '$loginUrl';</script>");
            }
            else
            {
                // Search for corresponding player in database
                $model_alias = $this->getParameter('model_alias', 'sfGuardUserProfile');
                $model_uid_field = $this->getParameter('model_uid_field', 'facebook_uid');
                $user = Doctrine_Core::getTable($model_alias)->findOneBy($model_uid_field, $uid, Doctrine_Core::HYDRATE_ARRAY);

                // If no corresponding player is found, create one
                if (!$user)
                {
                    $user = new $model_alias();
                    $user->$model_uid_field = $uid;
                    $user->save();
                    $user_id = $user->id;
                }
                else
                {
                    $user_id = $user['id'];
                }

                // Store facebook data into session for later reuse
                $this->getContext()->getUser()->setAttribute('facebook', array('me' => $me));

                // Store system user informations
                $this->getContext()->getUser()->setAttribute('system', array('id' => $user_id));

                // TODO : optionnaly store social graph
                // TODO : make system extendable by broadcasting an event
            }
        }

        // Execute next filter
        $filterChain->execute();
    }
}
