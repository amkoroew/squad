<?php

namespace MFG\Squad\Tests;
/***************************************************************
 *  Copyright notice
 *
 *  (c) 2013 Matthias Gugel <mail@matthias-gugel.de>
 *  			
 *  All rights reserved
 *
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Test case for class \MFG\Squad\Domain\Model\Squad.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Matthias Gugel <mail@matthias-gugel.de>
 */
class SquadTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {
	/**
	 * @var \MFG\Squad\Domain\Model\Squad
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = new \MFG\Squad\Domain\Model\Squad();
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function getNameReturnsInitialValueForString() {
		$this->assertSame(
			NULL,
			$this->fixture->getName()
		);
	}

	/**
	 * @test
	 */
	public function setNameForStringSetsName() {
		$this->fixture->setName('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getName()
		);
	}
	/**
	 * @test
	 */
	public function getImageReturnsInitialValueForString() {
		$this->assertSame(
			NULL,
			$this->fixture->getImage()
		);
	}

	/**
	 * @test
	 */
	public function setImageForStringSetsImage() {
		$this->fixture->setImage('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getImage()
		);
	}
	/**
	 * @test
	 */
	public function getRolesReturnsInitialValueForRole() {
		$newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->assertEquals(
			$newObjectStorage,
			$this->fixture->getRoles()
		);
	}

	/**
	 * @test
	 */
	public function setRolesForObjectStorageContainingRoleSetsRoles() {
		$role = new \MFG\Squad\Domain\Model\Role();
		$objectStorageHoldingExactlyOneRoles = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneRoles->attach($role);
		$this->fixture->setRoles($objectStorageHoldingExactlyOneRoles);

		$this->assertSame(
			$objectStorageHoldingExactlyOneRoles,
			$this->fixture->getRoles()
		);
	}

	/**
	 * @test
	 */
	public function addRoleToObjectStorageHoldingRoles() {
		$role = new \MFG\Squad\Domain\Model\Role();
		$objectStorageHoldingExactlyOneRole = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneRole->attach($role);
		$this->fixture->addRole($role);

		$this->assertEquals(
			$objectStorageHoldingExactlyOneRole,
			$this->fixture->getRoles()
		);
	}

	/**
	 * @test
	 */
	public function removeRoleFromObjectStorageHoldingRoles() {
		$role = new \MFG\Squad\Domain\Model\Role();
		$localObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$localObjectStorage->attach($role);
		$localObjectStorage->detach($role);
		$this->fixture->addRole($role);
		$this->fixture->removeRole($role);

		$this->assertEquals(
			$localObjectStorage,
			$this->fixture->getRoles()
		);
	}
}
