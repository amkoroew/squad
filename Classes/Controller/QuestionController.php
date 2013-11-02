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
class QuestionController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

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
		$questions = $this->questionRepository->findAll();
		$this->view->assign('questions', $questions);
	}

	/**
	 * action show
	 *
	 * @param \MFG\Squad\Domain\Model\Question $question
	 * @return void
	 */
	public function showAction(\MFG\Squad\Domain\Model\Question $question) {
		$this->view->assign('question', $question);
	}

	/**
	 * action new
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\MFG\Squad\Domain\Model\Question> $newQuestions
	 * @dontvalidate $newQuestion
	 * @return void
	 */
	public function newAction(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $newQuestions = NULL) {
		$this->view->assign('newQuestions', $newQuestions);
	}

	/**
	 * initialize create action
	 */
	public function initializeCreateAction() {
		if ($this->arguments->hasArgument('newQuestions')) {

			$questions = new \TYPO3\CMS\Extbase\Persistence\ObjectStorage();

			$questionTexts = explode("\n", $this->request->getArgument('newQuestions')['text']);
			$questionTexts = array_map('trim', $questionTexts);
			$questionTexts = array_filter($questionTexts);

			foreach ($questionTexts as $questionText) {
				$question = new \MFG\Squad\Domain\Model\Question();
				$question->setText($questionText);
				$questions->attach($question);
			}

			$this->request->setArgument('newQuestions', $questions);
		}
	}

	/**
	 * action create
	 *
	 * @param \TYPO3\CMS\Extbase\Persistence\ObjectStorage<\MFG\Squad\Domain\Model\Question> $newQuestions
	 * @return void
	 */
	public function createAction(\TYPO3\CMS\Extbase\Persistence\ObjectStorage $newQuestions) {
		foreach ($newQuestions as $newQuestion) {
			$this->questionRepository->add($newQuestion);
		}
		$this->flashMessageContainer->add('Your new Questions were created.');
		$this->redirect('list');
	}

	/**
	 * action edit
	 *
	 * @param \MFG\Squad\Domain\Model\Question $question
	 * @dontvalidate $question
	 * @return void
	 */
	public function editAction(\MFG\Squad\Domain\Model\Question $question) {
		$this->view->assign('question', $question);
	}

	/**
	 * action update
	 *
	 * @param \MFG\Squad\Domain\Model\Question $question
	 * @return void
	 */
	public function updateAction(\MFG\Squad\Domain\Model\Question $question) {
		$this->questionRepository->update($question);
		$this->flashMessageContainer->add('Your Question was updated.');
		$this->redirect('list');
	}

	/**
	 * action delete
	 *
	 * @param \MFG\Squad\Domain\Model\Question $question
	 * @return void
	 */
	public function deleteAction(\MFG\Squad\Domain\Model\Question $question) {
		$this->questionRepository->remove($question);
		$this->flashMessageContainer->add('Your Question was removed.');
		$this->redirect('list');
	}

}
