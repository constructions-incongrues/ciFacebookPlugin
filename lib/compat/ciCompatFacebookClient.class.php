<?php
class ciCompatFacebookClient
{
    public function get_loggedin_user()
    {
        return CI_Facebook_Core::getApiClient()->getUser();
    }
}