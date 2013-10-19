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
class MemberQuestion extends \TYPO3\CMS\Extbase\DomainObject\AbstractEntity {

	/**
	 * answer
	 *
	 * @var \string
	 * @validate NotEmpty
	 */
	protected $answer;

	/**
	 * member
	 *
	 * @var \MFG\Squad\Domain\Model\Member
	 */
	protected $member;

	/**
	 * question
	 *
	 * @var \MFG\Squad\Domain\Model\Question
	 */
	protected $question;

	/**
	 * Returns the answer
	 *
	 * @return \string $answer
	 */
	public function getAnswer() {
		return $this->answer;
	}

	/**
	 * Sets the answer
	 *
	 * @param \string $answer
	 * @return void
	 */
	public function setAnswer($answer) {
		$this->answer = $answer;
	}

	/**
	 * Returns the member
	 *
	 * @return \MFG\Squad\Domain\Model\Member $member
	 */
	public function getMember() {
		return $this->member;
	}

	/**
	 * Sets the member
	 *
	 * @param \MFG\Squad\Domain\Model\Member $member
	 * @return void
	 */
	public function setMember(\MFG\Squad\Domain\Model\Member $member) {
		$this->member = $member;
	}

	/**
	 * Returns the question
	 *
	 * @return \MFG\Squad\Domain\Model\Question $question
	 */
	public function getQuestion() {
		return $this->question;
	}

	/**
	 * Sets the question
	 *
	 * @param \MFG\Squad\Domain\Model\Question $question
	 * @return void
	 */
	public function setQuestion(\MFG\Squad\Domain\Model\Question $question) {
		$this->question = $question;
	}

}
