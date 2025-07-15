<?php declare(strict_types=1);

namespace App\Connectors;

use Saloon\Http\Connector;

class OsrsConnector extends Connector
{

    public function resolveBaseUrl(): string
    {
        return 'https://secure.runescape.com/';
    }
}