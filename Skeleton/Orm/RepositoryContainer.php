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
 *
 * @author Jan Tvrdík
 */
class RepositoryContainer extends Orm\RepositoryContainer
{

	/**
	 * Class constuctor – automatically registers repositories aliases
	 *
	 * @param Orm\IServiceContainerFactory|Orm\IServiceContainer|NULL
	 * @param array ($alias => $className)
	 */
	public function __construct($containerFactory = NULL, $repositories = array())
	{
		parent::__construct($containerFactory);

		foreach ($repositories as $alias => $repositoryClass)
		{
			$this->register($alias, $repositoryClass);
		}

		$annotations = Nette\Reflection\ClassType::from($this)->getAnnotations();
		if (isset($annotations['property-read']))
		{
			$c = get_called_class();
			$namespace = substr($c, 0, strrpos($c, '\\'));
			foreach ($annotations['property-read'] as $value)
			{
				if (preg_match('#^([\\\w]+Repository)\s+\$(\w+)$#', $value, $m) && !$this->isRepository($m[2]))
				{
					$class = strpos($m[1], '\\') === FALSE ? $namespace . '\\' . $m[1] : $m[1];
					$this->register($m[2], $class);
				}
			}
		}
	}

}
