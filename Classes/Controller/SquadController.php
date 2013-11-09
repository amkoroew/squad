<?php
namespace MFG\Squad\Controller;

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
class SquadController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * squadRepository
	 *
	 * @var \MFG\Squad\Domain\Repository\SquadRepository
	 * @inject
	 */
	protected $squadRepository;

	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {
		$squads = $this->squadRepository->findAll();
		$this->view->assign('squads', $squads);
	}

	/**
	 * action show
	 *
	 * @param \MFG\Squad\Domain\Model\Squad $squad
	 * @return void
	 */
	public function showAction(\MFG\Squad\Domain\Model\Squad $squad) {
		$this->view->assign('squad', $squad);
	}

	/**
	 * action new
	 *
	 * @param \MFG\Squad\Domain\Model\Squad $newSquad
	 * @dontvalidate $newSquad
	 * @return void
	 */
	public function newAction(\MFG\Squad\Domain\Model\Squad $newSquad = NULL) {
		$this->view->assign('newSquad', $newSquad);
	}

	/**
	 * initialize create action
	 */
	public function initializeCreateAction() {
		if ($this->arguments->hasArgument('newSquad')) {
			$this->arguments->getArgument('newSquad')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('image', 'array');

			$roles =  \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Persistence\ObjectStorage');

			$newSquad = $this->request->getArgument('newSquad');
			$roleNames = explode(',', $newSquad['roles']);
			$roleNames = array_map('trim', $roleNames);
			$roleNames = array_filter($roleNames);

			foreach($roleNames as $roleName) {
				$role = new \MFG\Squad\Domain\Model\Role();
				$role->setName($roleName);
				$roles->attach($role);
			}
			$newSquad['roles'] = $roles;

			$this->request->setArgument('newSquad', $newSquad);
		}
	}

	/**
	 * action create
	 *
	 * @param \MFG\Squad\Domain\Model\Squad $newSquad
	 * @return void
	 */
	public function createAction(\MFG\Squad\Domain\Model\Squad $newSquad) {
		$file = $newSquad->getImage();
		$newSquad->setImage('');
		$persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
		$persistenceManager->persistAll();

		$fileObjectIdentifier = \MFG\Squad\Utility\FalUtility::uploadFile($file, 1);
		if ($fileObjectIdentifier !== NULL) {
			$newSquad->setImage(basename($fileObjectIdentifier));
			$fileUid = \MFG\Squad\Utility\FalUtility::insertFile($file, 1, $fileObjectIdentifier);
			\MFG\Squad\Utility\FalUtility::insertFileReference($fileUid, $newSquad->getUid(), 'tx_squad_domain_model_squad', 'image', $fileObjectIdentifier, $this->settings['storagePid']);
		}

		$this->squadRepository->add($newSquad);
		$this->flashMessageContainer->add('Your new Squad was created.');
		$this->redirect('list');
	}

	/**
	 * action edit
	 *
	 * @param \MFG\Squad\Domain\Model\Squad $squad
	 * @dontvalidate $squad
	 * @return void
	 */
	public function editAction(\MFG\Squad\Domain\Model\Squad $squad) {
		$this->view->assign('squad', $squad);
	}

	/**
	 * initialize update action
	 */
	public function initializeUpdateAction() {
		if ($this->arguments->hasArgument('squad')) {
			$squad = $this->arguments->getArgument('squad');
			$propertyMappingConfiguration = $squad->getPropertyMappingConfiguration();
			$propertyMappingConfiguration->setTargetTypeForSubProperty('image', 'array');
			$propertyMappingConfiguration->allowProperties('roles');

			$propertyMapper = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Property\PropertyMapper');

			if($this->request->hasArgument('roles')) {
				$roleUids = $this->request->getArgument('roles');
				foreach($roleUids as $roleUid => $data) {
					$input = array(
						'__identity' => (string)$roleUid,
						'name' => trim($data['name']),
					);
					$propertyMapper->convert($input, '\MFG\Squad\Domain\Model\Role');
				}
			}
		}
	}

	/**
	 * action update
	 *
	 * @param \MFG\Squad\Domain\Model\Squad $squad
	 * @return void
	 */
	public function updateAction(\MFG\Squad\Domain\Model\Squad $squad) {
		$file = $squad->getImage();
		$squad->setImage('');
		$persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
		$persistenceManager->persistAll();

		$fileObjectIdentifier = \MFG\Squad\Utility\FalUtility::uploadFile($file, 1);
		if ($fileObjectIdentifier !== NULL) {
			$squad->setImage(basename($fileObjectIdentifier));
			$fileUid = \MFG\Squad\Utility\FalUtility::insertFile($file, 1, $fileObjectIdentifier);
			$squadUid = $squad->getUid();
			\MFG\Squad\Utility\FalUtility::deleteOldFileReference($squadUid);
			\MFG\Squad\Utility\FalUtility::insertFileReference($fileUid, $squadUid, 'tx_squad_domain_model_squad', 'image', $fileObjectIdentifier, $this->settings['storagePid']);
		} else {
			$propertyMapper = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Property\PropertyMapper');
			$input = array(
				'__identity' => (string)$squad->getUid(),
				'name' => trim($squad->getName()),
			);
			$squad = $propertyMapper->convert($input, '\MFG\Squad\Domain\Model\Squad');
		}

		$this->squadRepository->update($squad);
		$this->flashMessageContainer->add('Your Squad was updated.');
		$this->redirect('list');
	}

	/**
	 * action delete
	 *
	 * @param \MFG\Squad\Domain\Model\Squad $squad
	 * @return void
	 */
	public function deleteAction(\MFG\Squad\Domain\Model\Squad $squad) {
		$this->squadRepository->remove($squad);
		$this->flashMessageContainer->add('Your Squad was removed.');
		$this->redirect('list');
	}

}