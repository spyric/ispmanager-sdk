<?php
namespace Spyric\ISPManager;

use Spyric\ISPManager\Objects\Database;
use Spyric\ISPManager\Objects\FTPUser;
use Spyric\ISPManager\Objects\User;
use Spyric\ISPManager\Objects\WWWDomain;

class Manager
{
    protected $api;

    /** @noinspection MoreThanThreeArgumentsInspection
     *
     * WWWDomain constructor.
     *
     * @param string|Request $host Host or other api object
     * @param $port
     * @param $user
     * @param $password
     */
    public function __construct($host, $port, $user, $password)
    {
        if (is_object($host)) {
            $this->api = $host;
        } else {
            $this->api = new Request($host, $port, $user, $password);
        }
    }

    /**
     * @return User
     */
    public function User()
    {
        return (new User())->setApi($this->api);
    }
    /**
     * @return FTPUser
     */
    public function FTPUser()
    {
        return (new FTPUser())->setApi($this->api);
    }

    /**
     * @return WWWDomain
     */
    public function WWWDomain()
    {
        return (new WWWDomain())->setApi($this->api);
    }

    /**
     * @return Database
     */
    public function Database()
    {
        return (new Database())->setApi($this->api);
    }

    public function getApi()
    {
        if ($this->api === null) {
            $this->api = new Request();
        }

        return $this->api;
    }
}