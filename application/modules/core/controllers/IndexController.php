<?php
/**
 *  CMF for web applications based on Zend Framework 1 and Doctrine 2
 *  Copyright (C) 2010  Eugene Gruzdev aka yugeon
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation, either version 3 of the License, or
 *  (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  You should have received a copy of the GNU General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @copyright  Copyright (c) 2010 yugeon <yugeon.ru@gmail.com>
 * @license    http://www.gnu.org/licenses/gpl-3.0.html  GNU GPLv3
 */
use \Core\Model\Domain\User,
    \Core\Model\Domain\Group;

class Core_IndexController extends Zend_Controller_Action
{
//    /**
//     *
//     * @var Doctrine\ORM\EntityManager
//     */
//    protected  $em;

    /**
     * Service container
     *
     * @var sfServiceContainer
     */
    protected $sc;

    public function init()
    {
        $this->sc = $this->getInvokeArg('bootstrap')->getContainer();
//        $this->em = $this->sc->getService('doctrine.orm.entitymanager');
    }

    public function indexAction()
    {
//        $aclService = new \Xboom\Model\Service\Acl\AclService($this->em);
//        $user = $this->em->find('\\Core\\Model\\Domain\\User', 1);
//        $acl = $aclService->getAcl($user);
//        //\Doctrine\Common\Util\Debug::dump($acl, 8);
//        $result = $acl->isAllowed($user, 'Concrete Resource', 'edit');
//        var_dump($result);
    }
}

