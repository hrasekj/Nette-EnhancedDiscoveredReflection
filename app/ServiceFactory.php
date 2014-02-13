<?php

class ServiceFactory extends Nette\Object
{

	/**
	 * @return Nette\Database\Connection
	 */
	public function createDatabase($db, Nette\Caching\IStorage $cacheStorage = NULL)
	{
		$service = new Nette\Database\Connection(
			$db['driver'].":host=".$db['host'].";dbname=".$db['dbname'],
			$db['username'], $db['password'], $db['options']
		);

		$reflection = new Nette\Database\Reflection\EnhancedDiscoveredReflection($service, $cacheStorage, $db['views']);
		$service->setSelectionFactory(new Nette\Database\SelectionFactory($service, $reflection, $cacheStorage));
		Nette\Diagnostics\Debugger::getBlueScreen()->addPanel('Nette\\Database\\Diagnostics\\ConnectionPanel::renderException');
		Nette\Database\Helpers::createDebugPanel($service, TRUE, 'default');
		return $service;
	}


	/**
	 * @return Nette\Database\SelectionFactory
	 */
	public function createSelectionFactory(Nette\Database\Connection $database)
	{
		$service = $database->getSelectionFactory();
		if (!$service instanceof Nette\Database\SelectionFactory) {
			throw new Nette\UnexpectedValueException('Unable to create service \'selectionFactory\', value returned by factory is not Nette\\Database\\SelectionFactory type.');
		}
		return $service;
	}
}
