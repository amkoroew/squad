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
 * Test case for class \MFG\Squad\Domain\Model\MemberQuestion.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Matthias Gugel <mail@matthias-gugel.de>
 */
class MemberQuestionTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {
	/**
	 * @var \MFG\Squad\Domain\Model\MemberQuestion
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = new \MFG\Squad\Domain\Model\MemberQuestion();
	}

	public function tearDown() {
		unset($this->fixture);
	}

	/**
	 * @test
	 */
	public function getAnswerReturnsInitialValueForString() {
		$this->assertSame(
			NULL,
			$this->fixture->getAnswer()
		);
	}

	/**
	 * @test
	 */
	public function setAnswerForStringSetsAnswer() {
		$this->fixture->setAnswer('Conceived at T3CON10');

		$this->assertSame(
			'Conceived at T3CON10',
			$this->fixture->getAnswer()
		);
	}
	/**
	 * @test
	 */
	public function getMemberReturnsInitialValueForMember() {	}

	/**
	 * @test
	 */
	public function setMemberForMemberSetsMember() {	}
	/**
	 * @test
	 */
	public function getQuestionReturnsInitialValueForQuestion() {	}

	/**
	 * @test
	 */
	public function setQuestionForQuestionSetsQuestion() {	}
}
