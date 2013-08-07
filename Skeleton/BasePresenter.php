<?php

namespace Clevis\Skeleton\Core;

use Nette\Templating\ITemplate;
use Nette\Application\UI\Presenter;
use Nette\NotImplementedException;
use Clevis\Skeleton\TemplateFactory;


/**
 * Ancestor of applications BasePresenter
 */
abstract class BasePresenter extends Presenter
{

	/**
	 * Creates configured template
	 *
	 * @return ITemplate
	 */
	protected function createTemplate($class = NULL)
	{
		if ($class !== NULL)
		{
			throw new NotImplementedException('Specifying template class is not yet implemented.');
		}

		return $this->context->getService('templateFactory')->createTemplate(NULL, $this);
	}

	/**
	 * @return TemplateFactory
	 */
	public function getTemplateFactory()
	{
		return $this->context->getService('templateFactory');
	}

	/**
	 * Formats template file names
	 *
	 * Support for templates installed from skeleton package
	 *
	 * @return array
	 */
	public function formatTemplateFiles()
	{
		$params = $this->context->getParameters();
		$name = $this->getName();
		$presenter = substr($name, strrpos(':' . $name, ':'));
		$dir = dirname($params['appDir'] . '/' . preg_replace('/(([^:]):)/', '\2Module/', $name));

		$dir = is_dir("$dir/templates") ? $dir : dirname($dir);
		$files = array(
			"$dir/templates/$presenter/$this->view.latte",
			"$dir/templates/$presenter/package/$this->view.latte",
		);

		return $files + parent::formatTemplateFiles();
	}

}
