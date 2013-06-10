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

class QuarantineSession extends AbstractEntity
{
    protected $id;
    protected $usrId;

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
    public function getUsrId()
    {
        return $this->usrId;
    }

    public function setUsrId($usrId)
    {
        $this->usrId = $usrId;
    }
}
