<?php

namespace App\Traits;

trait SortsSongRequests
{
    /**
     * Ordena peticiones de canciones por status y luego por score.
     *
     * @param  array  $requests
     * @param  int    $limit
     * @return array
     */
    protected function sortRequests(array $requests, int $limit = 10): array
    {
        $statusOrder = [
            'pending' => 1,
            'attended' => 2,
            'rejected' => 3,
        ];

        usort($requests, function ($a, $b) use ($statusOrder) {
            $statusComparison = $statusOrder[$a['status']] <=> $statusOrder[$b['status']];
            if ($statusComparison === 0) {
                return $b['score'] <=> $a['score'];
            }
            return $statusComparison;
        });

        return array_slice($requests, 0, $limit);
    }
}
