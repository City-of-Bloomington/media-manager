<?php
/**
 * @copyright 2014 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE.txt
 * @author Cliff Ingham <inghamn@bloomington.in.gov>
 */
use Zend\Permissions\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole as Role;
use Zend\Permissions\Acl\Resource\GenericResource as Resource;

$ZEND_ACL = new Acl();
$ZEND_ACL->addRole(new Role('Anonymous'))
		 ->addRole(new Role('Public'),        'Anonymous')
		 ->addRole(new Role('Staff'),         'Public')
		 ->addRole(new Role('Administrator'), 'Staff');

/**
 * Declare all the resources
 */
$ZEND_ACL->addResource(new Resource('index'));
$ZEND_ACL->addResource(new Resource('people'));
$ZEND_ACL->addResource(new Resource('users'));
$ZEND_ACL->addResource(new Resource('login'));
$ZEND_ACL->addResource(new Resource('departments'));
$ZEND_ACL->addResource(new Resource('media'));
$ZEND_ACL->addResource(new Resource('uploads'));
$ZEND_ACL->addResource(new Resource('derivatives'));


/**
 * Assign permissions to the resources
 */
$ZEND_ACL->allow(null,'login');

// Permissions for unauthenticated browsing
$ZEND_ACL->allow(null,
				['index', 'derivatives'],
				['index']);
$ZEND_ACL->allow(null, 'media', ['view', 'derivative']);

// Allow Staff to do stuff
$ZEND_ACL->allow('Staff',
				['people','departments'],
				['index','view']);
$ZEND_ACL->allow('Staff', 'uploads');

// Administrator is allowed access to everything
$ZEND_ACL->allow('Administrator');
