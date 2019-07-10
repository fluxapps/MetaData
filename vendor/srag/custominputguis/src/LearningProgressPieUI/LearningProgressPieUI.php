<?php

namespace srag\CustomInputGUIs\MetaData\LearningProgressPieUI;

use srag\DIC\MetaData\DICTrait;

/**
 * Class LearningProgressPieUI
 *
 * @package srag\CustomInputGUIs\MetaData\LearningProgressPieUI
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
class LearningProgressPieUI {

	use DICTrait;


	/**
	 * LearningProgressPieUI constructor
	 */
	public function __construct() {

	}


	/**
	 * @return CountLearningProgressPieUI
	 */
	public function count() {
		return new CountLearningProgressPieUI();
	}


	/**
	 * @return ObjIdsLearningProgressPieUI
	 */
	public function objIds() {
		return new ObjIdsLearningProgressPieUI();
	}


	/**
	 * @return UsrIdsLearningProgressPieUI
	 */
	public function usrIds() {
		return new UsrIdsLearningProgressPieUI();
	}
}
