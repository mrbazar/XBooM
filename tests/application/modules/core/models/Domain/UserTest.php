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

namespace test\Core\Model\Domain;
use \Core\Model\Domain\User,
    \Mockery as m;

class UserTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @var Application_Model_Domain_User
     */
    protected $object;

    protected function setUp()
    {
        $this->object = new User;
    }

    protected function tearDown()
    {
        parent::tearDown();
        m::close();
    }

    public function testGetSetUserName()
    {
        $testName = 'Vasya';
        $this->object->setName($testName);
        $this->assertEquals($testName, $this->object->getName());
    }

    public function testGetSetUserEmail()
    {
        $testEmail = 'Vasya@mail.com';
        $this->object->setEmail($testEmail);
        $this->assertEquals($testEmail, $this->object->getEmail());
    }

    public function testCanAssignToGroup()
    {
        $group = m::mock('Group');
        $this->assertEquals($this->object, $this->object->setGroup($group));
        $this->assertEquals($group, $this->object->getGroup());
    }

    public function testShouldImplementZendAclRoleInterface()
    {
        $this->assertType('Zend_Acl_Role_Interface', $this->object);
    }

    public function _testDefaultUserShouldReturnGuestRole()
    {
        $this->markTestSkipped();
        $expected = array(
            'Guest',
        );
        $this->assertEquals($expected, $this->object->getRoleId());
    }

    public function  _testCanAssignToPersonalRole()
    {
        $role = m::mock('Role');
        $role->shouldReceive('markPersonal');
        $this->object->setRole($role);
        $this->assertEquals($role, $this->object->getRole());
    }

    public function testGetRoleIdShouldReturnAllRolesAssignedToUser()
    {
        $groupRoles = array(
            m::mock('Role'),
            m::mock('Role'),
        );
        $userGroup = m::mock('Group');
        $userGroup->shouldReceive('getRoleId')->andReturn($groupRoles);
//        $userRole = m::mock('Role');
//        $userRole->shouldReceive('markPersonal');
        $this->object->setId(545);
//        $this->object->setRole($userRole);
        $this->object->setGroup($userGroup);

        $expected = $groupRoles;
//        $expected[] = $userRole;
        
        $this->assertSame($expected, $this->object->getRoleId());
    }

    public function _testPersonalRoleShouldBeEspecially()
    {
        $userRole = m::mock('Role');
        $userRole->shouldReceive('markPersonal')->with(true)->once();
        $userRole->shouldReceive('isPersonal')->andReturn(true);
        $this->object->setRole($userRole);

        $this->assertTrue($this->object->getRole()->isPersonal());
    }

    public function testShouldImplementZendAclResourceInterface()
    {
        $this->assertType('Zend_Acl_Resource_Interface', $this->object);
    }

    public function testGetResourceId()
    {
        $resourceId = 326;
        $resource = m::mock('Zend_Acl_Resource_Interface');
        $resource->shouldReceive('getId')->andReturn($resourceId);
        $this->object->setResource($resource);
        $this->assertEquals('User-' . $resourceId, $this->object->getResourceId());
    }

    /**
     * @expectedException \Xboom\Model\Exception
     */
    public function testShouldRaiseExceptionIfResourceNotAssign()
    {
        $this->object->getResourceId();
    }

    public function testEncryptPassword()
    {
        $password = 'meg@SecurePa$$';
        $this->assertNotEquals($password, $this->object->encryptPassword($password));
    }

    public function testFailedAuthenticateShouldReturnFalse()
    {
        $password = 'badPassword';
        $this->assertFalse((boolean)$this->object->authenticate($password));
    }

    public function testSuccessedAuthenticateShouldReturnIdentity()
    {
        $testId    = 2;
        $testName  = 'Vasya';
        $testEmail = 'vasya@mail.com';
        $password  = 'validPassword';

        $this->object->setId($testId);
        $this->object->setName($testName);
        $this->object->setEmail($testEmail);
        $this->object->setPassword($this->object->encryptPassword($password));

        $role1 = m::mock('Role');
        $role1->shouldReceive('getRoleId')->andReturn('1');
        $role2 = m::mock('Role');
        $role2->shouldReceive('getRoleId')->andReturn('2');

        $roles = array(
            $role1,
            $role2
        );

        $group = m::mock('Group');
        $group->shouldReceive('getRoleId')->andReturn($roles);
        $this->object->setGroup($group);

        $expectedIdentity = array(
            'id'    => $testId,
            'name' => $testName,
            'email' => $testEmail,
            'roles' => array('1', '2'),
        );

        $this->assertTrue( (boolean) $this->object->authenticate($password));
        $this->assertEquals($expectedIdentity, $this->object->authenticate($password));
    }
}