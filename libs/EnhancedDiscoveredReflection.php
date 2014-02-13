<?php

namespace Nette\Database\Reflection;

use Nette;


/**
 * Reflection metadata class with discovery for a database.
 *
 * @author     Jakub Hrášek
 */
class EnhancedDiscoveredReflection extends DiscoveredReflection
{
	protected $views;


	public function __construct(Nette\Database\Connection $connection, Nette\Caching\IStorage $cacheStorage = NULL, array $views = array())
	{
		parent::__construct($connection, $cacheStorage);
		$this->views = $views;
	}

	public function getPrimary($table)
	{
		$primary = & $this->structure['primary'][strtolower($table)];
		if (isset($primary)) {
			return empty($primary) ? NULL : $primary;
		}

		if (array_key_exists($table, $this->views)) {
			$primary = $this->views[$table];
		}
		else {
			$primary = parent::getPrimary($table);
		}

		return $primary;
	}

}
