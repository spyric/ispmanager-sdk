<?php
namespace Spyric\ISPManager\Objects;


use Spyric\ISPManager\Request;

/**
 * @property mixed|null elidAnalog
 */
class BaseObject
{
    protected $api;

    protected $objectAction;
    protected $allAction;

    protected $data = [];
    protected $fullyLoaded = false;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function __isset($name)
    {
        return array_key_exists($name, $this->data);
    }

    public function __unset($name)
    {
        if (isset($this->{$name})) {
            unset($this->data[$name]);
        }
    }

    public function __get($name)
    {
        if (isset($this->{$name})) {
            return $this->data[$name];
        }

        if (!$this->fullyLoaded && array_key_exists($this->getElidField(), $this->data)) {
            /** @noinspection ImplicitMagicMethodCallInspection */
            return $this
                ->find($this->{$this->getElidField()})
                ->__get($name);
        }

        return null;
    }

    public function __set($name, $value)
    {
        $this->data[$name] = $value;

    }

    private function getElidField()
    {
        if (array_key_exists('elid', $this->data)) {
            return 'elid';
        }

        if (property_exists(static::class, 'elidAnalog')) {
            return $this->elidAnalog;
        }

        return null;
    }

    public function find($elid)
    {
        $this
            ->getApi()
            ->send($this->objectAction, ['elid' => $elid]);

        return $this;
    }

    public function getApi()
    {
        if ($this->api === null) {
            $this->api = new Request();
        }

        return $this->api;
    }

    public function setApi($api)
    {
        $this->api = $api;

        return $this;
    }

    public function save()
    {
        $data = $this->toArray();
        $data['sok'] = 'ok';

        return $this->getApi()->send($this->objectAction, $data);
    }

    public function toArray()
    {
        return $this->data;
    }

    public function all(array $filter = [])
    {
        $data = $this->getApi()->send($this->allAction, $filter);
        return collect($data)->map(function ($item) {
            return (new static($item))->setApi($this->api);
        });
    }

    public function toJSON()
    {
        return json_encode($this->toArray());
    }

}