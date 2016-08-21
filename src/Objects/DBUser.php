<?php

namespace Spyric\ISPManager\Objects;

class DBUser extends BaseObject
{
    protected $objectAction = 'db.users.edit';
    protected $allAction = 'db.users';
    protected $elidAnalog = 'name';
}