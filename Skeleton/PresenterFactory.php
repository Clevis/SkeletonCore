<?php

namespace Clevis\Skeleton;

use Nette;


/**
 * Custom presenter factory with base namespace support.
 */
class PresenterFactory extends Nette\Application\PresenterFactory
{

	/** @var array registered presenter namespaces */
	private $namespaces = array();

	/** @var array ($presenter => $class) cached presenter names */
	private $presenters = array();

	/**
	 * Registers presenter namespace from package
	 *
	 * @param string
	 */
	public function registerNamespace($namespace)
	{
		if (in_array($namespace, $this->namespaces))
		{
			throw new \Nette\InvalidStateException("Presenter namespace $namespace is already registered. Pleas check installed packages.");
		}
		$this->namespaces[] = $namespace;
	}

	/**
	 * Formats presenter class name from its name
	 *
	 * @param string
	 * @return string
	 */
	public function formatPresenterClass($presenter)
	{
		if (substr_compare($presenter, 'Nette:', 0, 6) === 0)
		{
			return parent::formatPresenterClass($presenter);
		}

		if (empty($this->presenters[$presenter]))
		{
			foreach ($this->namespaces as $namespace)
			{
				$class = $namespace . '\\' . str_replace(':', '\\', $presenter) . 'Presenter';
				if (class_exists($class))
				{
					$this->presenters[$presenter] = $class;
					break;
				}
			}
		}

		return isset($this->presenters[$presenter]) ? $this->presenters[$presenter] : NULL;
	}

	/**
	 * Formats presenter name from class name
	 *
	 * @param  string class name
	 * @return string presenter name
	 */
	public function unformatPresenterClass($class)
	{
		$namespace = substr($class, 0, strrpos($class, '\\'));
		foreach ($this->namespaces as $ns)
		{
			if (strncmp($namespace, $ns, strlen($ns)) === 0)
			{
				$presenter = str_replace('\\', ':', substr($class, strlen($ns) + 1, -9));
				if (empty($this->presenters[$presenter]))
				{
					$this->presenters[$presenter] = $class;
				}
				return $presenter;
			}
		}

		// fallback
		return parent::unformatPresenterClass($class);
	}

}
