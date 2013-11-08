<?php
namespace MFG\Squad\Utility;

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
class FalUtility {

	/**
	 * Upload a file and add to storage
	 *
	 * @param array $file
	 * @param int $storageUid
	 * @return array
	 */
	public static function uploadFile($file, $storageUid) {
		$fileObjectIdentifier = NULL;

		if ($file['error'] === UPLOAD_ERR_OK) {
			$storageRepository = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\Resource\StorageRepository');
			$storage = $storageRepository->findByUid($storageUid);
			$fileObject = $storage->addFile($file['tmp_name'], $storage->getRootLevelFolder(), $file['name']);
			$fileObjectIdentifier = $fileObject->getIdentifier();
		}

		return $fileObjectIdentifier;
	}

	/**
	 * Insert a FAL file record into table sys_file
	 *
	 * @param array $file
	 * @param int $storageUid
	 * @param string $fileObjectIdentifier
	 * @return array
	 */
	public static function insertFile($file, $storageUid, $fileObjectIdentifier) {

		$newSysFields = array(
			'pid' => 0,
			'identifier' => $fileObjectIdentifier,
			'mime_type' => $file['type'],
			'name' => $fileObjectIdentifier,
			'size' => $file['size'],
			'storage' => $storageUid
		);

		$newSysRes = $GLOBALS['TYPO3_DB']->exec_INSERTquery('sys_file', $newSysFields);
		return $GLOBALS['TYPO3_DB']->sql_insert_id($newSysRes);
	}

	/**
	 * Insert a FAL file record into table sys_file_reference
	 *
	 * @param int $localUid
	 * @param int $foreignUid
	 * @param string $tablenames
	 * @param string $fieldname
	 * @param string $fileObjectIdentifier
	 * @param int $storagePid
	 * @return void
	 */
	public static function insertFileReference($localUid, $foreignUid, $tablenames, $fieldname, $fileObjectIdentifier, $storagePid) {
		$data = array();
		$data['sys_file_reference'][basename($fileObjectIdentifier)] = array(
			'uid_local' => $localUid,
			'uid_foreign' => $foreignUid,
			'tablenames' => $tablenames,
			'fieldname' => $fieldname,
			'pid' => $storagePid,
			'table_local' => 'sys_file',
			'crdate' => $GLOBALS['EXEC_TIME'],
			'tstamp' => $GLOBALS['EXEC_TIME']
		);

		$tce = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance('TYPO3\CMS\Core\DataHandling\DataHandler');
		$tce->start($data, array(), $new_BE_USER);
		$tce->process_datamap();
	}
}