<?php namespace Anomaly\Streams\Addon\Module\Users\User\Command;

class CreateUserCommand
{
    protected $credentials;

    function __construct(array $credentials)
    {
        $this->credentials = $credentials;
    }

    /**
     * @return mixed
     */
    public function getCredentials()
    {
        return $this->credentials;
    }
}
 