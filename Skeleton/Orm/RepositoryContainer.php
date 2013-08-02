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
	 * @author Jan Tvrdík
	 * @param  Orm\IServiceContainerFactory|Orm\IServiceContainer|NULL
	 */
	public function __construct($containerFactory = NULL)
	{
		parent::__construct($containerFactory);

		$annotations = Nette\Reflection\ClassType::from($this)->getAnnotations();
		if (isset($annotations['property-read']))
		{
			$class = get_called_class();
			$namespace = substr($class, 0, strrpos($class, '\\'));
			foreach ($annotations['property-read'] as $value)
			{
				if (preg_match('#^(\w+Repository)\s+\$(\w+)$#', $value, $m))
				{
					$this->register($m[2], $namespace . '\\' . $m[1]);
				}
			}
		}
	}

}
