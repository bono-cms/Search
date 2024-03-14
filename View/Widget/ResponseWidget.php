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
/**
 * Search response widget
 * 
 * Used to display results
 */
use Krystal\Application\InputInterface;
use Krystal\InstanceManager\DependencyInjectionContainerInterface;
use Krystal\Templating\StringTemplate;
use Krystal\Form\NodeElement;
use Krystal\Widget\WidgetInterface;
use Krystal\Widget\Bootstrap5\ListInline\ListMaker;

final class ResponseWidget implements WidgetInterface
{
    /**
     * An array of entity of results
     * 
     * @var array
     */
    private $results = [];

    /**
     * Errors, if any
     * 
     * @var array
     */
    private $errors = [];

    /**
     * Widget defaults
     * 
     * @var array
     */
    private $options = [
        'count' => 0, // Item counter
        'keyword' => null, // Default keyword
        'items' => [
            'wrapper_class' => 'mb-4 p-4 bg-white shadow',
            'link_class' => 'd-block mb-2 fw-bold text-decoration-none'
        ],
        'header' => [
            'tag' => 'h2',
            'class' => 'text-muted',
            'wrapper_class' => 'mb-5'
        ],
        'messages' => [
            'results' => 'Search results for "{$keyword}" ({$count})',
            'empty' => 'No results can be found for "{$keyword}"'
        ]
    ];

    /**
     * State initialization
     * 
     * @param array $results
     * @param array $errors
     * @param array $options
     * @return void
     */
    public function __construct(array $results = [], array $errors = [], array $options = [])
    {
        $this->results = $results;
        $this->errors = $errors;
        $this->options = array_merge($this->options, $options);
    }

    /**
     * Renders the header
     * 
     * @param string $text
     * @return string
     */
    private function renderHeader($text)
    {
        $header = new NodeElement();
        $header->openTag($this->options['header']['tag'])
               ->addAttribute('class', $this->options['header']['class'])
               ->setText($text)
               ->closeTag();

        $div = new NodeElement();
        $div->openTag('div')
            ->addAttribute('class', $this->options['header']['wrapper_class']);

        $div->appendChild($header)
            ->closeTag();

        return $div;
    }

    /**
     * Renders empty message
     * 
     * @param string $text
     * @return string
     */
    private function renderEmpty($text)
    {
        $div = new NodeElement();
        $div->openTag('div')
            ->addAttribute('class', 'py-5');

        $header = new NodeElement();
        $header->openTag('h5')
               ->addAttribute('class', 'text-muted')
               ->setText($text)
               ->closeTag();

        $div->appendChild($header)
            ->closeTag();

        return $div;
    }

    /**
     * Renders a single result
     * 
     * @param array $results
     * @return string
     */
    private function renderResults(array $results)
    {
        $content = null;

        foreach ($results as $result) {
            $wrap = new NodeElement();
            $wrap->openTag('div')
                 ->addAttribute('class', $this->options['items']['wrapper_class']);

            $a = new NodeElement();
            $a->openTag('a')
              ->addAttributes([
                'class' => $this->options['items']['link_class'],
                'href' => $result->getUrl()
              ])
              ->setText($result->getTitle())
              ->closeTag();

            $div = new NodeElement();
            $div->openTag('div')
                ->setText($result->getContent());

            $wrap->appendChild($a)
                 ->appendChild($div);

            $content .= $wrap->render();
        }

        return $content;
    }

    /**
     * Render errors
     * 
     * @param array $errors
     * @return string
     */
    private function renderErrors(array $errors)
    {
        $items = [];

        foreach ($errors as $error) {
            $items[] = [
                'text' => $error
            ];
        }

        $lm = new ListMaker($items, [
            'li' => 'd-block'
        ]);

        return $lm->render();
    }

    /**
     * Renders a wigdet
     * 
     * @param \Krystal\InstanceManager\DependencyInjectionContainerInterface $container
     * @param \Krystal\Application\InputInterface $input
     * @return string
     */
    public function render(DependencyInjectionContainerInterface $container, InputInterface $input)
    {
        $translator = $container->get('translator');

        $output = '';

        if (empty($this->errors)) {
            // Header text
            $text = StringTemplate::template($this->options['messages']['results'], [
                'keyword' => $this->options['keyword'],
                'count' => $this->options['count']
            ]);

            $output .= $this->renderHeader($translator->translate($text));
        }

        if (!empty($this->results)) {
            $output .= $this->renderResults($this->results);
        } else {
            $emptyText = StringTemplate::template($this->options['messages']['empty'], [
                'keyword' => $this->options['keyword']
            ]);

            $output .= $this->renderEmpty($translator->translate($emptyText));
        }

        // Errors
        if (!empty($this->errors)) {
            $output .= $this->renderErrors($this->errors);
        }

        return $output;
    }
}
