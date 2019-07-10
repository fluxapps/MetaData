<?php

/**
 * Class srmdBlockGUI54
 *
 * @author studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 * @author Stefan Wanzenried <sw@studer-raimann.ch>
 */
class srmdBlockGUI54 extends srmdBlockGUI {

	/**
	 * @inheritDoc
	 */
	function getBlockType(): string {
		return self::BLOCK_ID;
	}


	/**
	 * @inheritDoc
	 */
	function isRepositoryObject(): bool {
		return false;
	}
}
