<?php

namespace Passioncoder\SimpleCurl;


class Facade extends \Illuminate\Support\Facades\Facade {

    protected static function getFacadeAccessor() { return 'Curl'; }
}
