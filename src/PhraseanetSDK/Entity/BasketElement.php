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

class BasketElement
{
    /**
     * @ApiField(bind_to="basket_element_id", type="int")
     */
    protected $id;
    /**
     * @ApiField(bind_to="order", type="int")
     */
    protected $order;
    /**
     * @ApiField(bind_to="validation_item", type="boolean")
     */
    protected $validationItem;
    /**
     * @ApiField(bind_to="record", type="relation")
     * @ApiRelation(type="one_to_one", target_entity="Record")
     */
    protected $record;
    /**
     * @ApiField(bind_to="basket_validation_choices", type="relation")
     * @ApiRelation(type="one_to_many", target_entity="BasketValidationChoice")
     */
    protected $validationChoices;

    /**
     * The id of the element
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
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
}
