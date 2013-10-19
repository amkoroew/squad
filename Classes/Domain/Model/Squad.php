<?php
namespace MFG\Squad\Domain\Model;

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
 *  the Free Software Foundation; either version 3 of the License, or
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
 *
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 */
class Squad extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * name
	 *
	 * @var \string
	 * @validate NotEmpty
	 */
	protected $name;

	/**
	 * roles
	 *
	 * @var \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\MFG\Squad\Domain\Model\Role>
	 */
	protected $roles;

	/**
	 * __construct
	 *
	 * @return Squad
	 */
	public function __construct() {
		//Do not remove the next line: It would break the functionality
		$this->initStorageObjects();
	}

	/**
	 * Initializes all ObjectStorage properties.
	 *
	 * @return void
	 */
	protected function initStorageObjects() {
		/**
		 * Do not modify this method!
		 * It will be rewritten on each save in the extension builder
		 * You may modify the constructor of this class instead
		 */
		$this->roles = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
	}

	/**
	 * Returns the name
	 *
	 * @return \string $name
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Sets the name
	 *
	 * @param \string $name
	 * @return void
	 */
	public function setName($name) {
		$this->name = $name;
	}

	/**
	 * Adds a Role
	 *
	 * @param \MFG\Squad\Domain\Model\Role $role
	 * @return void
	 */
	public function addRole(\MFG\Squad\Domain\Model\Role $role) {
		$this->roles->attach($role);
	}

	/**
	 * Removes a Role
	 *
	 * @param \MFG\Squad\Domain\Model\Role $roleToRemove The Role to be removed
	 * @return void
	 */
	public function removeRole(\MFG\Squad\Domain\Model\Role $roleToRemove) {
		$this->roles->detach($roleToRemove);
	}

	/**
	 * Returns the roles
	 *
	 * @return \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\MFG\Squad\Domain\Model\Role> $roles
	 */
	public function getRoles() {
		return $this->roles;
	}

	/**
	 * Sets the roles
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\MFG\Squad\Domain\Model\Role> $roles
	 * @return void
	 */
	public function setRoles(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $roles) {
		$this->roles = $roles;
	}

}
