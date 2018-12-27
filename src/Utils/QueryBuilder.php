<?php

namespace SDK\Boilerplate\Utils;

use Symfony\Component\HttpFoundation\Request as SymfonyRequest;

class QueryBuilder
{

    public static function build(array $parameters)
    {

        return SymfonyRequest::normalizeQueryString(http_build_query($parameters, '', '&'));

    }

}