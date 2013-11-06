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
class MemberController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * memberRepository
	 *
	 * @var \MFG\Squad\Domain\Repository\MemberRepository
	 * @inject
	 */
	protected $memberRepository;

	/**
	 * memberRepository
	 *
	 * @var \MFG\Squad\Domain\Repository\SquadRepository
	 * @inject
	 */
	protected $squadRepository;

	/**
	 * questionRepository
	 *
	 * @var \MFG\Squad\Domain\Repository\QuestionRepository
	 * @inject
	 */
	protected $questionRepository;

	/**
	 * action list
	 *
	 * @return void
	 */
	public function listAction() {
		$members = $this->memberRepository->findAll();
		$this->view->assign('members', $members);
	}

	/**
	 * action show
	 *
	 * @param \MFG\Squad\Domain\Model\Member $member
	 * @return void
	 */
	public function showAction(\MFG\Squad\Domain\Model\Member $member) {
		$this->view->assign('member', $member);
	}

	/**
	 * action new
	 *
	 * @param \MFG\Squad\Domain\Model\Member $newMember
	 * @dontvalidate $newMember
	 * @return void
	 */
	public function newAction(\MFG\Squad\Domain\Model\Member $newMember = NULL) {
		$this->view->assign('newMember', $newMember);
		$squads = $this->squadRepository->findAll();
		$this->view->assign('squads', $squads);
		$questions = $this->questionRepository->findAll();
		$this->view->assign('questions', $questions);
	}

	/**
	 * initialize create action
	 */
	public function initializeAction() {
		if ($this->arguments->hasArgument('newMember')) {
			$newMember = $this->arguments->getArgument('newMember');
			$propertyMapper = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Property\PropertyMapper');
			$answers = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Persistence\ObjectStorage');

			$propertyMappingConfiguration = $newMember->getPropertyMappingConfiguration();
			$propertyMappingConfiguration->setTargetTypeForSubProperty('image', 'array');
			$propertyMappingConfiguration->allowProperties('answers');

			if ($this->arguments->hasArgument('newMember')) {
				$newAnswers = $this->request->getArgument('answers');
				$answerTexts = $newAnswers['text'];
				$answerTexts = array_map('trim', $answerTexts);
				$answerTexts = array_filter($answerTexts);
				$questions = $newAnswers['question'];

				foreach ($answerTexts as $key => $text) {
					$question = $questions[$key];
					$answer = $propertyMapper->convert(array('text' => $text, 'question' => $question), '\MFG\Squad\Domain\Model\Answer');
					$answers->attach($answer);
				}
			}

			$newMember = $this->request->getArgument('newMember');
			$newMember['answers'] = $answers;
			$this->request->setArgument('newMember', $newMember);
		}
	}

	/**
	 * action create
	 *
	 * @param \MFG\Squad\Domain\Model\Member $newMember
	 * @return void
	 */
	public function createAction(\MFG\Squad\Domain\Model\Member $newMember) {
		$propertyMapper = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Property\PropertyMapper');

		if ($this->arguments->hasArgument('newMember')) {
			$roleUids = $this->request->getArgument('roles');

			$squads = $this->squadRepository->findAll();
			foreach ($squads as $squad) {
				foreach ($squad->getRoles() as $tmpRole) {
					foreach ($roleUids as $roleUid) {
						if ($tmpRole->getUid() === (int)$roleUid) {
							$tmpRole->addMember($newMember);
							$this->squadRepository->update($squad);
						}
					}
				}
			}
		}

		$imageName = $newMember->getImage()['name'];
		$imageType = $newMember->getImage()['type'];
		$imageTmpName = $newMember->getImage()['tmp_name'];
		$imageError = $newMember->getImage()['error'];
		$imageSize = $newMember->getImage()['size'];

		if ($imageError === UPLOAD_ERR_OK) {
			$storageRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Resource\StorageRepository');
			$storage = $storageRepository->findByUid(1);
			$fileObject = $storage->addFile($imageTmpName, $storage->getRootLevelFolder(), $imageName);

			$fileObjectIdentifier = $fileObject->getIdentifier();

			$newMember->setImage(basename($fileObjectIdentifier));

			$this->memberRepository->add($newMember);

			$persistenceManager = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Persistence\Generic\PersistenceManager');
			$persistenceManager->persistAll();

			$newMemberUid = $newMember->getUid();

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
			$data['sys_file_reference'][$newMember->getImage()] = array(
				'uid_local' => $uid_local,
				'uid_foreign' => $newMemberUid,
				'tablenames' => 'tx_squad_domain_model_member',
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
		} else {
			$newMember->setImage(NULL);
			$this->memberRepository->add($newMember);
		}

		$this->flashMessageContainer->add('Your new Member was created.');
		$this->redirect('list');
	}

	/**
	 * action edit
	 *
	 * @param \MFG\Squad\Domain\Model\Member $member
	 * @dontvalidate $member
	 * @return void
	 */
	public function editAction(\MFG\Squad\Domain\Model\Member $member) {
		$this->view->assign('member', $member);
	}

	/**
	 * action update
	 *
	 * @param \MFG\Squad\Domain\Model\Member $member
	 * @return void
	 */
	public function updateAction(\MFG\Squad\Domain\Model\Member $member) {
		$this->memberRepository->update($member);
		$this->flashMessageContainer->add('Your Member was updated.');
		$this->redirect('list');
	}

	/**
	 * action delete
	 *
	 * @param \MFG\Squad\Domain\Model\Member $member
	 * @return void
	 */
	public function deleteAction(\MFG\Squad\Domain\Model\Member $member) {
		$this->memberRepository->remove($member);
		$this->flashMessageContainer->add('Your Member was removed.');
		$this->redirect('list');
	}

}