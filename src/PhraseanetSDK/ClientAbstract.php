<?php

namespace PhraseanetSDK;

/**
 * PhaseanetClient do not handle how access token is stored on client side
 * It's up to you to implement your favorite method (session, database, file etc ..)
 *
 * Define getAccessToken in the way you want retrieve a previous stored token
 * Define setAccessToken in the way you want store your access stoken
 *
 */
abstract class ClientAbstract
{

    /**
     * Retieve a stored token
     * @return mixed
     */
    public abstract function getAccessToken();

    /**
     * Store a fresh acces token
     * return PhraseanetApi
     */
    public abstract function setAccessToken($token);
}
