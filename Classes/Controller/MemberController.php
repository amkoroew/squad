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
		$questions = $this->questionRepository->findAll();
		$this->view->assign('questions', $questions);
	}

	/**
	 * action create
	 *
	 * @param \MFG\Squad\Domain\Model\Member $newMember
	 * @param \array $answers
	 * @return void
	 */
	public function createAction(\MFG\Squad\Domain\Model\Member $newMember, array $answers = array()) {
		$propertyMapper = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Extbase\Property\PropertyMapper');

		foreach ($answers['text'] as $key => $text) {
			if (!empty($text)) {
				$question = $answers['question'][$key];
				$answer = $propertyMapper->convert(array('text' => $text, 'question' => $question), '\MFG\Squad\Domain\Model\Answer');
				$newMember->addAnswer($answer);
			}
		}

		$this->memberRepository->add($newMember);
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