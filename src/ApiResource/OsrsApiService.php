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
    protected function getStartingXp(int $level): float {
        // Validate input: level must be between 1 and 99
        if ($level < 1 || $level > 99) {
            return 0; // Return 0 for invalid levels
        }

        $cumulativeXp = 0; // Initialize cumulative XP
        // Sum XP from level 1 to level-1
        for ($k = 1; $k < $level; $k++) {
            // OSRS formula: floor((k + 300 * 2^(k/7)) / 4)
            $term = floor($k + 300 * pow(2, $k / 7));
            $cumulativeXp += floor($term / 4); // Add XP for this level
        }

        return $cumulativeXp;
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

        foreach ($skillNames as $index => $skill) {
            $skillData = explode(',', $lines[$index]);
            $stats[$skill] = [
                'rank' => (int)$skillData[0],
                'level' => (int)$skillData[1],
                'xp' => (int)$skillData[2],
                'totalXpToNext' => $this->getXpNextLevel((int)$skillData[1]),
                'startingXp' => $this->getStartingXp((int)$skillData[1])
            ];
        }

        return $stats;
    }

    protected function calculateTotalXP($parsedData)
    {
        $totalXP = 0;
        foreach ($parsedData as $skill => $data) {
            if ($skill !== 'Overall') {
                $totalXP += $data['xp'];
            }
        }
        return $totalXP;
    }


    /**
     * # Function to calculate XP needed for next level
     * @param int $currentLevel
     * @return float
     */
    protected function getXpNextLevel(int $currentLevel): float
    {
        // If at max level (99), no more XP is needed
        if ($currentLevel >= 99) {
            return 0;
        }

        // Calculate total XP required for the next level
        $nextLevel = $currentLevel + 1;
        $totalXpForNextLevel = 0;
        for ($k = 1; $k < $nextLevel; $k++) {
            $term = floor($k + 300 * pow(2, $k / 7));
            $totalXpForNextLevel += floor($term / 4);
        }

        return $totalXpForNextLevel;
    }
}
