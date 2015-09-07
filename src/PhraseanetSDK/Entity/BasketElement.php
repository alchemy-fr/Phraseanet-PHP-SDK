<?php

/*
 * This file is part of Phraseanet SDK.
 *
 * (c) Alchemy <info@alchemy.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PhraseanetSDK\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use PhraseanetSDK\Annotation\ApiField as ApiField;
use PhraseanetSDK\Annotation\ApiRelation as ApiRelation;
use PhraseanetSDK\Annotation\Id as Id;

class BasketElement
{

    /**
     * @var \stdClass
     */
    protected $source;

    /**
     * @var Record
     */
    protected $record;

    /**
     * @var ArrayCollection|BasketValidationChoice[]
     */
    protected $validationChoices;

    /**
     * @param \stdClass $source
     */
    public function __construct(\stdClass $source)
    {
        $this->source = $source;
    }

    /**
     * The id of the element
     *
     * @return integer
     */
    public function getId()
    {
        return $this->source->basket_element_id;
    }

    /**
     * Position of the element in the basket
     *
     * @return integer
     */
    public function getOrder()
    {
        return $this->source->order;
    }

    /**
     * Tell whether the basket item is a validation item
     *
     * @return bool
     */
    public function isValidationItem()
    {
        return $this->source->validation_item;
    }

    /**
     * Get the record associated to the basket item
     *
     * @return Record
     */
    public function getRecord()
    {
        return $this->record ?: $this->record = Record::fromValue($this->source->record);
    }

    /**
     * Retrieve the choice of all participants that concern the basket element
     * in a collection PhraseanetSDK\Entity\BasketValidationChoice object
     *
     * @return ArrayCollection
     */
    public function getValidationChoices()
    {
        if (! isset($this->source->validation_choices)) {
            $this->validationChoices = new ArrayCollection();
        }

        return $this->validationChoices ?: $this->validationChoices = BasketValidationChoice::fromList(
            $this->source->validation_choices
        );
    }

    /**
     * Get the annotation about the validation of the current authenticated user
     *
     * @return string
     */
    public function getNote()
    {
        return $this->source->note;
    }

    /**
     * Get the agreement of the currently authenticated user
     *
     * - null : no response yet
     * - true : accepted
     * - false: rejected
     *
     * @return null|boolean
     */
    public function getAgreement()
    {
        return $this->source->agreement;
    }
}
