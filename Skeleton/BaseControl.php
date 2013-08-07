<?php

namespace Clevis\Skeleton;

use Nette\Application\UI\Control;
use Nette\Templating\ITemplate;
use Nette\NotImplementedException;


/**
 * Base control for all application controls
 */
abstract class BaseControl extends Control
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

		return $this->getPresenter()->getTemplateFactory()->createTemplate(NULL, $this);
	}

}
