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
 * Test case for class \MFG\Squad\Domain\Model\Member.
 *
 * @version $Id$
 * @copyright Copyright belongs to the respective authors
 * @license http://www.gnu.org/licenses/gpl.html GNU General Public License, version 3 or later
 *
 * @author Matthias Gugel <mail@matthias-gugel.de>
 */
class MemberTest extends \TYPO3\CMS\Extbase\Tests\Unit\BaseTestCase {
	/**
	 * @var \MFG\Squad\Domain\Model\Member
	 */
	protected $fixture;

	public function setUp() {
		$this->fixture = new \MFG\Squad\Domain\Model\Member();
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
	public function getAnswersReturnsInitialValueForAnswer() {
		$newObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$this->assertEquals(
			$newObjectStorage,
			$this->fixture->getAnswers()
		);
	}

	/**
	 * @test
	 */
	public function setAnswersForObjectStorageContainingAnswerSetsAnswers() {
		$answer = new \MFG\Squad\Domain\Model\Answer();
		$objectStorageHoldingExactlyOneAnswers = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneAnswers->attach($answer);
		$this->fixture->setAnswers($objectStorageHoldingExactlyOneAnswers);

		$this->assertSame(
			$objectStorageHoldingExactlyOneAnswers,
			$this->fixture->getAnswers()
		);
	}

	/**
	 * @test
	 */
	public function addAnswerToObjectStorageHoldingAnswers() {
		$answer = new \MFG\Squad\Domain\Model\Answer();
		$objectStorageHoldingExactlyOneAnswer = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$objectStorageHoldingExactlyOneAnswer->attach($answer);
		$this->fixture->addAnswer($answer);

		$this->assertEquals(
			$objectStorageHoldingExactlyOneAnswer,
			$this->fixture->getAnswers()
		);
	}

	/**
	 * @test
	 */
	public function removeAnswerFromObjectStorageHoldingAnswers() {
		$answer = new \MFG\Squad\Domain\Model\Answer();
		$localObjectStorage = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();
		$localObjectStorage->attach($answer);
		$localObjectStorage->detach($answer);
		$this->fixture->addAnswer($answer);
		$this->fixture->removeAnswer($answer);

		$this->assertEquals(
			$localObjectStorage,
			$this->fixture->getAnswers()
		);
	}
}