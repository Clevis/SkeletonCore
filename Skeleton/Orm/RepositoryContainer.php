<?php

namespace Clevis\Skeleton\Orm;

use Nette;
use Orm;


/**
 * Collection of IRepository.
 *
 * Cares about repository initialization.
 * It is the entry point into model from other parts of application.
 * Stores container of services which other objects may need.
 */
class RepositoryContainer extends Orm\RepositoryContainer
{

	/**
	 * Class constuctor – automatically registers repository aliases
	 *
	 * @param Orm\IServiceContainerFactory|Orm\IServiceContainer|NULL
	 * @param array ($alias => $className)
	 */
	public function __construct($containerFactory = NULL, $repositories = array())
	{
		parent::__construct($containerFactory);

		$this->registerAnnotations();

		// registers repositories from config
		foreach ($repositories as $alias => $repositoryClass)
		{
			if (!$this->isRepository($alias))
			{
				$this->register($alias, $repositoryClass);
			}
		}
	}

	/**
	 * Registers repositories from annotations
	 */
	private function registerAnnotations()
	{
		$annotations = Nette\Reflection\ClassType::from($this)->getAnnotations();
		if (isset($annotations['property-read']))
		{
			$c = get_called_class();
			$namespace = substr($c, 0, strrpos($c, '\\'));
			foreach ($annotations['property-read'] as $value)
			{
				if (preg_match('#^([\\\\\\w]+Repository)\\s+\\$(\\w+)$#', $value, $m))
				{
					$class = strpos($m[1], '\\') === FALSE ? $namespace . '\\' . $m[1] : $m[1];
					$this->register($m[2], $class);
				}
			}
		}
	}

}
