<?php

namespace Spyric\ISPManager\Objects;

class FTPUser extends BaseObject
{
    protected $objectAction = 'ftp.user.edit';
    protected $allAction = ' ftp.user';
    protected $elidAnalog = 'name';
}