<?php
if (!defined('TYPO3_MODE')) {
	die ('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
	'MFG.' . $_EXTKEY,
	'Squad',
	array(
		'Squad' => 'list, show',
		'Member' => 'show',

	),
	// non-cacheable actions
	array(
		
	)
);
