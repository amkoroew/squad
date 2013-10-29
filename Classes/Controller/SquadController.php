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
	public function initializeAction() {
		if ($this->arguments->hasArgument('newSquad')) {
			$this->arguments->getArgument('newSquad')->getPropertyMappingConfiguration()->setTargetTypeForSubProperty('image', 'array');
		}
	}

	/**
	 * action create
	 *
	 * @param \MFG\Squad\Domain\Model\Squad $newSquad
	 * @return void
	 */
	public function createAction(\MFG\Squad\Domain\Model\Squad $newSquad) {
		$imageName = $newSquad->getImage()['name'];
		$imageType = $newSquad->getImage()['type'];
		$imageTmpName = $newSquad->getImage()['tmp_name'];
		$imageError = $newSquad->getImage()['error'];
		$imageSize = $newSquad->getImage()['size'];

		$storageRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Resource\StorageRepository');
		$storage = $storageRepository->findByUid(1);
		$fileObject = $storage->addFile($imageTmpName, $storage->getRootLevelFolder(), $imageName);

		$fileObjectIdentifier = $fileObject->getIdentifier();

		$newSquad->setImage(basename($fileObjectIdentifier));

		$this->squadRepository->add($newSquad);

		$persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
		$persistenceManager->persistAll();

		$newSquadUid = $newSquad->getUid();

		$newSysFields = array(
			'pid' => 0,
			'identifier' => $fileObjectIdentifier,
			'mime_type' => $imageType,
			'name' => $fileObjectIdentifier,
			'size' => $imageSize,
			'storage' => 1,
		);

		$newSysRes = $GLOBALS['TYPO3_DB']->exec_INSERTquery('sys_file', $newSysFields);
		$uid_local = $GLOBALS['TYPO3_DB']->sql_insert_id($newSysRes);

		$data = array();
		$data['sys_file_reference'][$newSquad->getImage()] = array(
			'uid_local' => $uid_local,
			'uid_foreign' => $newSquadUid,
			'tablenames' => 'tx_squad_domain_model_squad',
			'fieldname' => 'image',
			'pid' => 69, // parent id of the parent page <-- TODO: Remove constant value!
			'table_local' => 'sys_file',
			'crdate' => $GLOBALS['EXEC_TIME'],
			'tstamp' => $GLOBALS['EXEC_TIME']
		);

		$tce = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\DataHandling\DataHandler'); // create TCE instance
		$tce->start($data, array(), $new_BE_USER);
		$tce->process_datamap();
		/*if ($tce->errorLog) $content .= 'TCE->errorLog:' . t3lib_utility_Debug::viewArray($tce->errorLog);
		else $content .= 'image changed <br>' . t3lib_utility_Debug::viewArray($data);
		*/

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
	 * action update
	 *
	 * @param \MFG\Squad\Domain\Model\Squad $squad
	 * @return void
	 */
	public function updateAction(\MFG\Squad\Domain\Model\Squad $squad) {
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