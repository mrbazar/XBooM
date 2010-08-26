<?php

use \Core_Model_Domain_User as User;

/**
 * Description of User
 *
 * @author yugeon
 */
class Core_Service_UserService extends Xboom_Service_AbstractService
{

    /**
     * @var Doctrine\ORM\EntityManager
     */
    protected $_em;

    public function __construct(Doctrine\ORM\EntityManager $em)
    {
        $this->_em = $em;
    }

    /**
     * Get all users as array of objects.
     *
     * @return array
     */
    public function getUsersList()
    {
        return $this->_em->createQuery('SELECT u FROM Core_Model_Domain_User u')
                ->getResult();
    }

    /**
     * Return user by id. All relations not loaded!
     *
     * @param int $userId
     * @return App_Model_Domain_User 
     */
    public function getUserById($userId)
    {
        return $this->_em->find('Core_Model_Domain_User', (int) $userId);
    }

    /**
     * Register new user. Login must be present in $data.
     *
     * @param array $data
     * @param boolean $flush If true then flush EntityManager
     * @return User
     */
    public function registerNewUser(array $data, $flush = true)
    {
        // TODO: ACL        !!!
        // TODO: Validation !!!

        $user = new User($data);
        $this->_em->persist($user);

        if ($flush)
        {
            $this->_em->flush();
        }

        return $user;
    }

}
