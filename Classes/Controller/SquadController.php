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

}
?>
