<?php

declare(strict_types=1);

namespace App\Constants;

class HttpStatus
{
    const OK = 200;
    const CREATED = 201;
    const NOT_FOUND = 404;
    const BAD_REQUEST = 400;
    const UNPROCESSABLE_ENTITY = 422;
    const INTERNAL_SERVER_ERROR = 500;
}