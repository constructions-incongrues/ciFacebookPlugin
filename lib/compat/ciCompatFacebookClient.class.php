<?php
class wkCompatFacebookClient
{
    public function get_loggedin_user()
    {
        return WK_Facebook_Core::getApiClient()->getUser();
    }
}