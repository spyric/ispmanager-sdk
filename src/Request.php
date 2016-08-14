<?php
namespace Spyric\ISPManager;

use GuzzleHttp\Client;

class Request
{
    public static $defaultsCredentials = [
        'host'     => 'localhost',
        'port'     => '1500',
        'username' => 'root',
        'password' => 'P@55word@123',
    ];

    protected $host;
    protected $port;
    protected $user;
    protected $password;

    /** @noinspection MoreThanThreeArgumentsInspection
     * @param null $host
     * @param null $port
     * @param null $username
     * @param null $password
     */
    public function __construct($host = null, $port = null, $username = null, $password = null)
    {
        if ($host === null) {
            extract(self::$defaultsCredentials, EXTR_OVERWRITE);
        }

        $this->host = $host;
        $this->port = $port;
        $this->user = $username;
        $this->password = $password;
    }


    /**
     * @param $action
     * @param $data
     * @return array|string
     */
    public function send($action, $data)
    {
        /** @noinspection SpellCheckingInspection */
        $url = 'https://' . $this->host . ':' . $this->port . '/ispmgr';

        /** @noinspection SpellCheckingInspection */
        $defaultData = [
            'func'     => $action,
            'authinfo' => $this->user . ':' . $this->password,
            'out'      => 'JSONdata',
        ];

        $data = array_merge($defaultData, $data);
        $url = $url . '?' . http_build_query($data);

        $client = new Client([
            'curl' => [
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
            ],
        ]);
        $response = $client->get($url)->getBody()->getContents();

        if ($jsonResponse = json_decode($response, true)) {
            return $jsonResponse;
        }

        return $response;
    }

}