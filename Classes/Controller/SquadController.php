<?php
namespace MFG\Squad\Controller;

/***************************************************************
 *  Copyright notice
 *  (c) 2013 Matthias Gugel <mail@matthias-gugel.de>
 *  All rights reserved
 *  This script is part of the TYPO3 project. The TYPO3 project is
 *  free software; you can redistribute it and/or modify
 *  it under the terms of the GNU General Public License as published by
 *  the Free Software Foundation; either version 2 of the License, or
 *  (at your option) any later version.
 *  The GNU General Public License can be found at
 *  http://www.gnu.org/copyleft/gpl.html.
 *  This script is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU General Public License for more details.
 *  This copyright notice MUST APPEAR in all copies of the script!
 ***************************************************************/

/**
 * Base controller
 *
 * @package TYPO3
 * @subpackage squaf
 */
class SquadController extends \TYPO3\CMS\Extbase\Mvc\Controller\ActionController {

	/**
	 * List squad
	 * @return void
	 */
	public function listAction() {
		$this->view->assignMultiple(array(
			'hello' => 'Hello, World!'
		));
	}

	/**
	 * Show a player
	 * @return void
	 */
	public function showAction() {
		$this->view->assignMultiple(array(
			'hello' => 'Hello, World!'
		));
	}
}

?>
