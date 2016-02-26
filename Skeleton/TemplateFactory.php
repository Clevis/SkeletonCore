<?php

namespace Clevis\Skeleton;

use Nette;
use Nette\Application\UI;
use Nette\Localization\ITranslator;


/**
 * Templates factory. Used to create templates in both presenters and components.
 *
 * @author Jan Tvrdík
 * @author Petr Procházka
 */
class TemplateFactory
{

	/** @var ITranslator|NULL */
	private $translator;

	/** @var UI\ITemplateFactory */
	private $baseFactory;


	/**
	 * @param UI\ITemplateFactory $baseFactory
	 * @param ITranslator|NULL    $translator
	 */
	public function __construct(UI\ITemplateFactory $baseFactory, ITranslator $translator = NULL)
	{
		$this->translator = $translator;
		$this->baseFactory = $baseFactory;
	}


	public function createTemplate(UI\Control $control = NULL)
	{
		$template = $this->baseFactory->createTemplate($control);
		$template->setTranslator($this->translator);

		return $template;
	}

}
