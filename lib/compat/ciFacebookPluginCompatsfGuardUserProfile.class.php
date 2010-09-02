<?php
class ciFacebookPluginCompatsfGuardUserProfile extends sfGuardUserProfile
{
    public function save(Doctrine_Connection $conn = null)
    {
        if ($this->isNew())
        {
            $username = sprintf('Facebook_%s', $this->facebook_uid);
            if (!Doctrine_Core::getTable('sfGuardUser')->findOneBy('username', $username, Doctrine_Core::HYDRATE_ARRAY))
            {
                $user = new sfGuardUser();
                $user->username = $username;
                $user->setProfile($this);
                $user->save($conn);
                $this->user_id = $user->id;
            }
        }
        parent::save($conn);
    }
}