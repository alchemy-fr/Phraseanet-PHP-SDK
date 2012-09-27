<?php

namespace PhraseanetSDK\Entity;

class Cgus extends AbstractEntity implements EntityInterface
{
    protected $locale;
    protected $terms;

    public function getLocale()
    {
        return $this->locale;
    }

    public function setLocale($locale)
    {
        $this->locale = $locale;
    }

    public function getTerms()
    {
        return $this->terms;
    }

    public function setTerms($terms)
    {
        $this->terms = $terms;
    }

}
