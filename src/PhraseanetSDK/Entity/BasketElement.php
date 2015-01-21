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
use JMS\Serializer\Annotation\ExclusionPolicy as ExclusionPolicy;
use JMS\Serializer\Annotation\Expose as Expose;
use JMS\Serializer\Annotation\VirtualProperty as VirtualProperty;
use JMS\Serializer\Annotation\SerializedName as SerializedName;
use JMS\Serializer\Annotation\Type as Type;

/**
 * @ExclusionPolicy("all")
 */
class BasketElement
{
    /**
     * @Expose
     * @Id
     * @Type("integer")
     * @ApiField(bind_to="basket_element_id", type="int")
     */
    protected $id;
    /**
     * @Expose
     * @Type("integer")
     * @ApiField(bind_to="order", type="int")
     */
    protected $order;
    /**
     * @Expose
     * @Type("boolean")
     * @ApiField(bind_to="validation_item", type="boolean")
     */
    protected $validationItem;
    /**
     * @Expose
     * @Type("PhraseanetSDK\Entity\Record")
     * @ApiField(bind_to="record", type="relation")
     * @ApiRelation(type="one_to_one", target_entity="Record")
     */
    protected $record;
    /**
     * @Expose
     * @Type("ArrayCollection<PhraseanetSDK\Entity\BasketValidationChoice>")
     * @ApiField(bind_to="validation_choices", type="relation")
     * @ApiRelation(type="one_to_many", target_entity="BasketValidationChoice")
     */
    protected $validationChoices;
    /**
     * @Expose
     * @Type("integer")
     * @ApiField(bind_to="note", type="int")
     */
    protected $note;
    /**
     * @Expose
     * @Type("boolean")
     * @ApiField(bind_to="agreement", type="boolean")
     */
    protected $agreement;

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

    public function setValidationChoices(ArrayCollection $validationChoices = null)
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
