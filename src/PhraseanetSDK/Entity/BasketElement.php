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

class BasketElement extends AbstractEntity
{
    protected $basketElementId;
    protected $order;
    protected $validationItem;
    protected $record;
    protected $validationChoices;
    protected $note;
    protected $agreement;

    /**
     * The id of the element
     *
     * @return integer
     */
    public function getBasketElementId()
    {
        return $this->basketElementId;
    }

    public function setBasketElementId($basketElementId)
    {
        $this->basketElementId = $basketElementId;
    }

    /**
     * Position of the element in the basket
     *
     * @return integer
     */
    public function getOrder()
    {
        return $this->order;
    }

    public function setOrder($order)
    {
        $this->order = $order;
    }

    /**
     * Tell whether the basket item is a validation item
     *
     * @return Boolean
     */
    public function isValidationItem()
    {
        return $this->validationItem;
    }

    public function setValidationItem($validationItem)
    {
        $this->validationItem = $validationItem;
    }

    /**
     * Get the record associated to the basket item
     *
     * @return Record
     */
    public function getRecord()
    {
        return $this->record;
    }

    /**
     *
     * @param Record $record
     */
    public function setRecord(Record $record)
    {
        $this->record = $record;
    }

    /**
     * Retrieve the choice of all participants that concern the basket element
     * in a collection PhraseanetSDK\Entity\BasketValidationChoice object
     *
     * @return ArrayCollection
     */
    public function getValidationChoices()
    {
        return $this->validationChoices;
    }

    public function setValidationChoices(ArrayCollection $validationChoices)
    {
        $this->validationChoices = $validationChoices;
    }

    /**
     * Get the annotation about the validation of the current authenticated user
     *
     * @return string
     */
    public function getNote()
    {
        return $this->note;
    }

    public function setNote($note)
    {
        $this->note = $note;
    }

    /**
     * Get the agreement of the current authenticated user
     *
     * - null : no response yet
     * - true : accepted
     * - false: rejected
     *
     * @return null|boolean
     */
    public function getAgreement()
    {
        return $this->agreement;
    }

    public function setAgreement($agreement)
    {
        $this->agreement = $agreement;
    }
}
