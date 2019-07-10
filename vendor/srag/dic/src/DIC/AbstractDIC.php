<?php

namespace srag\DIC\MetaData\DIC;

use ILIAS\DI\Container;
use srag\DIC\MetaData\Database\DatabaseDetector;
use srag\DIC\MetaData\Database\DatabaseInterface;

/**
 * Class AbstractDIC
 *
 * @package srag\DIC\MetaData\DIC
 *
 * @author  studer + raimann ag - Team Custom 1 <support-custom1@studer-raimann.ch>
 */
abstract class AbstractDIC implements DICInterface {

	/**
	 * @var Container
	 */
	protected $dic;


	/**
	 * @inheritDoc
	 */
	public function __construct(Container &$dic) {
		$this->dic = &$dic;
	}


	/**
	 * @inheritdoc
	 */
	public function database() {
		return DatabaseDetector::getInstance($this->databaseCore());
	}
}
