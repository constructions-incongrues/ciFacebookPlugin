<?php
/**
 * @package    Facebook
 * @subpackage BackwardCompatibility
 */
class FacebookRestClient
{
    public function getGraphClient()
    {
        return CI_Facebook_Core::getApiClient();
    }

    public function users_getInfo($uids, $fields)
    {
        return $this->getGraphClient()->api(array('method' => 'users.getInfo', 'uids' => $uids, 'fields' => $fields));
    }

    public function friends_getAppUsers()
    {
        return $this->getGraphClient()->api(array('method' => 'friends.getAppUsers'));
    }

    public function fql_query($query)
    {
        return $this->getGraphClient()->api(array('method' => 'fql.query', 'query' => $query));
    }
}
