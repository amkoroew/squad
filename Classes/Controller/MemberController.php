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
	}

	/**
	 * action create
	 *
	 * @param \MFG\Squad\Domain\Model\Member $newMember
	 * @return void
	 */
	public function createAction(\MFG\Squad\Domain\Model\Member $newMember) {
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

}