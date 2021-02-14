<?php

namespace myPHPnotes\Microsoft\Models;

use GuzzleHttp\Exception\ClientException;
use Microsoft\Graph\Model\User as MicrosoftUser;


/**
 * User Model
 */
class User extends BaseModel
{
    public $data;
    function __construct()
    {
        $this->checkAuthentication();
        $this->fetch();
    }
    protected function fetch()
    {
        $url =  "/me";
        try {
            $user = $this->graph()->createRequest("get",$url)
                ->setReturnType(MicrosoftUser::class)
                ->execute();   
        } catch (ClientException $e) {
            throw new \Exception("Cannot connect make sure you have asked User.Read permission from the authenticated user.", 1);
            return false;
            
        }
        $this->data = $user;
        return $this->data;;
    }
}