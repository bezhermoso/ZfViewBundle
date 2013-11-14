<?php
/**
 * Copyright 2013 Bezalel Hermoso <bezalelhermoso@gmail.com>
 * 
 * This project is free software released under the MIT license:
 * http://www.opensource.org/licenses/mit-license.php 
 */

namespace Bez\ZfViewBundle\Templating;

use Symfony\Bundle\FrameworkBundle\Templating\TemplateReference;
use Symfony\Component\Templating\TemplateNameParserInterface;
use Symfony\Component\Templating\TemplateReferenceInterface;

/**
 * Class TemplateNameParser
 *
 * Overrides default functionality by making the 'format' part of a template name optional in .phtml files.
 *
 * @author Bezalel Hermoso <bezalelhermoso@gmail.com>
 * @package Bez\ZfViewBundle\Templating
 */
class TemplateNameParser implements TemplateNameParserInterface
{

    protected $parser;

    public function __construct(TemplateNameParserInterface $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Convert a template name to a TemplateReferenceInterface instance.
     *
     * @param string $name A template name
     *
     * @throws \InvalidArgumentException
     * @return TemplateReferenceInterface A template
     *
     * @api
     */
    public function parse($name)
    {
        if ($name instanceof TemplateReferenceInterface) {
            return $name;
        }

        if (preg_match('/\.phtml$/', $name)) {

            if (!preg_match('/^([^:]*):([^:]*):(.+)\.([^\.]+)$/', $name)
            && !preg_match('/^([^:]*):([^:]*):(.+)\.([^\.]+)\.([^\.]+)$/', $name)) {
                throw new \InvalidArgumentException(sprintf('Template name "%s" is not valid (format is "bundle:section:template.format.engine").', $name));
            }

            $segments = explode(':', $name);
            $fileSegments = explode('.', $segments[2]);

            $reference = new PhtmlTemplateReference(
                                    $name,
                                    $segments[0],
                                    $segments[1],
                                    reset($fileSegments),
                                    'html',
                                    end($fileSegments));

            if (count($fileSegments) == 3) {
                $reference->set('format', $fileSegments[1]);
            }

            return $reference;

        } else {
            return $this->parser->parse($name);
        }
    }
}