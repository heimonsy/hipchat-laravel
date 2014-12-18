<?php

use Illuminate\Support\Facades\Facade;


class HipChatFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'hipchat';
    }
}
