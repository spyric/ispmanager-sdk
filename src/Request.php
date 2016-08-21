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
        $client = new Client([
            'curl' => [
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
            ],
        ]);
        $response = $client->get($this->link($action, $data))->getBody()->getContents();

        if ($jsonResponse = json_decode($response, true)) {
            return $jsonResponse;
        }

        return $response;
    }

    public function link($action, $data)
    {
        $data = array_merge([
            'func'     => $action,
            'authinfo' => $this->user . ':' . $this->password,
            'out'      => 'JSONdata',
        ], $data);

        $data = collect($data)
            ->reject(function ($item) {
                return is_null($item);
            })->all();

        return 'https://' . $this->host . ':' . $this->port . '/ispmgr?' . http_build_query($data);
    }

}