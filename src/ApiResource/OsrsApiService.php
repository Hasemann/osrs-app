<?php declare(strict_types=1);

namespace App\ApiResource;

use AllowDynamicProperties;
use App\Connectors\OsrsConnector;
use App\Requests\FetchPlayerRequest;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AllowDynamicProperties]
class OsrsApiService
{
    public function __construct(
        protected HttpClientInterface $httpClient
    ) {
        $this->preCumulativeXp = $this->xpForeachLevel();
    }

    /**
     * @throws FatalRequestException
     * @throws RequestException|\JsonException
     */
    public function getFormattedPlayerStats(string $playerName): array
    {
        $connector = new OsrsConnector();

        // / Splits the response string into an array of lines, trimming whitespace.
        $response = $connector->send(new FetchPlayerRequest($playerName));
        // Get the raw body as a string and format the stats
        return $this->formatPlayerStats($response->body());
    }

    /**
     * Handle the plain text response into a structured array.
     *
     * @param string $body
     * @return array
     */
    protected function formatPlayerStats(string $body): array
    {

        $lines = explode("\n", trim($body));
        $stats = [];

        // Define skill names in the order returned by the API
        $skillNames = [
            'overall', 'attack', 'defence', 'strength', 'hitpoints', 'ranged',
            'prayer', 'magic', 'cooking', 'woodcutting', 'fletching', 'fishing',
            'firemaking', 'crafting', 'smithing', 'mining', 'herblore', 'agility',
            'thieving', 'slayer', 'farming', 'runecrafting', 'hunter', 'construction'
        ];

//        foreach ($lines as $index => $line) {
//            if (empty($line) || $index >= count($skillNames)) {
//                continue;
//            }
//            // Splits the line into an array of values (rank, level, xp) using comma as the delimiter.
//            $values = explode(',', $line);
//            dd(values: $values);
//            $stats[$skillNames[$index]] = [
//                'level' => $this->currentLevel((int)$values[2]),
//                'xp' => (int)$values[2],
//                'xpToNext' => $this->xpToNextLevel((int)$values[2]),
//            ];
//
//        }


        return $stats;
    }

    /**
     * Function to find current level from XP
     * @param int $currentExp
     * @return int
     */
    protected function currentLevel(int $currentExp): int
    {
        for ($level = 1; $level < 100; $level++) {
            if ($currentExp < $this->preCumulativeXp[$level]) {
                return $level - 1;
            }
        }
        return 99;
    }

    /**
     * # Function to calculate XP needed for next level
     * @param int $currentExp
     * @return float
     */
    protected function xpToNextLevel(int $currentExp): float
    {
        $currentLevel = $this->currentLevel($currentExp);
        if ($currentLevel >= 99) {
            return 0;
        }
        return $this->preCumulativeXp[$currentLevel + 1] - $currentExp;
    }

    /**
     * Cumulative XP for each level (1 to 99)
     * @return array
     */
    protected function xpForeachLevel(): array
    {
        // Initialize array with 100 zeros (index 0 to 99)
        $cumulative_xp = array_fill(0, 100, 0);

        // Loop from level 1 to 98
        for ($level = 1; $level < 99; $level++) {
            // Calculate XP needed for this level
            $xp_needed = floor(($level + 300 * pow(2, $level / 7.0)) / 4);
            // Update cumulative XP for the next level
            $cumulative_xp[$level + 1] = $cumulative_xp[$level] + $xp_needed;
        }

        return $cumulative_xp;
    }
}