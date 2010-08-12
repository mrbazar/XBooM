<?php

class Core_UserController extends Zend_Controller_Action
{
    /**
     *
     * @var Doctrine\ORM\EntityManager
     */
    protected  $em;

    public function init()
    {
        $sc = $this->getInvokeArg('bootstrap')->getContainer();
        $this->em = $sc->getService('doctrine.orm.entitymanager');
    }

    public function indexAction()
    {
        $results = $this->em->createQuery('SELECT u FROM Application_Model_Domain_User u')
                            ->getResult();
        $this->view->users = $results;
    }

    public function addAction()
    {
        $user = new Application_Model_Domain_User();
        $user->setName('TestName' . rand(1, 100));

        $this->em->persist($user);
        $this->em->flush();

        return $this->_helper->getHelper('Redirector')
                             ->gotoUrl($this->getRequest()->getControllerName());
    }

    public function deleteAction()
    {
        $user = $this->em->find('Application_Model_Domain_User', (int) $this->_getParam('id'));

        $this->em->remove($user);
        $this->em->flush();

        return $this->_helper->getHelper('Redirector')
                             ->gotoUrl($this->getRequest()->getControllerName());
    }
}

