<?php

/**
 * This file is part of the Bono CMS
 * 
 * Copyright (c) No Global State Lab
 * 
 * For the full copyright and license information, please view
 * the license file that was distributed with this source code.
 */

namespace Search\View\Widget;

use Krystal\Application\InputInterface;
use Krystal\Form\NodeElement;
use Krystal\InstanceManager\DependencyInjectionContainerInterface;
use Krystal\Widget\WidgetInterface;
use Krystal\Widget\Bootstrap5\Offcanvas\OffcanvasMaker;

final class OffcanvasWidget implements WidgetInterface
{
    /**
     * Offcanvas defaults
     * 
     * @var array
     */
    private $options = [
        'text' => '<i class="bi bi-search"></i>',
        'placement' => 'top',
        'header' => 'Search on site',
        'placeholder' => 'Type a keyword and press Enter',
        'btnClass' => 'btn btn-primary'
    ];

    /**
     * State initialization
     * 
     * @param array $options
     * @return void
     */
    public function __construct(array $options = [])
    {
        $this->options = array_merge($this->options, $options);
    }

    /**
     * Renders search form
     * 
     * @param string $placeholder Input placeholder
     * @return string
     */
    private function renderForm($placeholder)
    {
        $input = new NodeElement();
        $input->openTag('input')
              ->addAttributes([
                'name' => 'query',
                'class' => 'form-control form-control-lg',
                'placeholder' => $placeholder,
                'value' => null
              ])
              ->finalize();

        $form = new NodeElement();
        $form->openTag('form')
             ->addAttribute('action', '/search')
             ->finalize();

        $form->appendChild($input)
             ->closeTag();

        return $form->render();
    }

    /**
     * Renders a wigdet
     * 
     * @param \Krystal\InstanceManager\DependencyInjectionContainerInterface $container
     * @param \Krystal\Application\InputInterface $input
     * @return array
     */
    public function render(DependencyInjectionContainerInterface $container, InputInterface $input)
    {
        $translator = $container->get('translator');

        $offcanvas = new OffcanvasMaker('search', [
            'placement' => $this->options['placement']
        ]);

        $header = $translator->translate($this->options['header']);
        $body = $this->renderForm($translator->translate($this->options['placeholder']));

        return [
            'button' => $offcanvas->renderButton($this->options['text'], $this->options['btnClass']),
            'offcanvas' => $offcanvas->renderOffcanvas($header, $body)
        ];
    }
}
