<?php declare(strict_types = 1);

namespace App\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use Saloon\Traits\Plugins\AlwaysThrowOnErrors;

class FetchPlayerRequest  extends Request implements HasBody
{
    use HasJsonBody, AlwaysThrowOnErrors;

    protected Method $method = Method::GET;

    public function __construct(protected string $playerName)
    {
    }

    public function resolveEndpoint(): string
    {
        return 'm=hiscore_oldschool/index_lite.ws';
    }
    public function defaultQuery(): array
    {
        return [
            'player' => $this->playerName // ?player=brokeDevelop
        ];
    }
}