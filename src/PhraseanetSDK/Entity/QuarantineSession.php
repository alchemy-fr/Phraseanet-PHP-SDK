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

use PhraseanetSDK\Annotation\ApiField as ApiField;
use PhraseanetSDK\Annotation\ApiRelation as ApiRelation;

class QuarantineSession
{
    /**
     * @ApiField(bind_to="id", type="int")
     */
    protected $id;
    /**
     * @ApiField(bind_to="user", type="relation")
     * @ApiRelation(type="one_to_one", target_entity="User")
     */
    protected $user;

    /**
     * The session id
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
     * The user id
     *
     * @return integer
     */
    public function getUser()
    {
        return $this->user;
    }

    public function setUser($user)
    {
        $this->user = $user;
    }
}
