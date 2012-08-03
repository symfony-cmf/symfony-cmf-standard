<?php

namespace Acme\MainBundle\Document;

use Doctrine\ODM\PHPCR\Mapping\Annotations as PHPCRODM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Cmf\Bundle\SimpleCmsBundle\Document\Page;

/**
 * @PHPCRODM\Document(translator="attribute")
 */
class MultilangPage extends Page
{
    /**
     * @Assert\NotBlank
     * @PHPCRODM\String(translated=true)
     */
    public $title;

    /**
     * @PHPCRODM\String(translated=true)
     */
    public $body;

    /**
     * @PHPCRODM\Locale
     */
    public $locale;
}
