<?php

namespace Tests\Unit\Models;

use App\Models\Song;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Group;

class SongDataTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    #[DataProvider('commentProvider')]
    #[Group('custom')]
    public function it_correctly_parses_song_data_from_various_comment_formats(string $comment, array $expectedData, bool $songShouldExist): void
    {
        // Arrange
        if ($songShouldExist) {
            Song::factory()->create([
                'title' => 'Uptown Funk',
                'artist' => 'Mark Ronson ft. Bruno Mars',
            ]);
        }

        // Act
        $result = Song::getSongDataFromComment($comment);

        // Assert
        $this->assertEquals($expectedData, $result);
    }

    public static function commentProvider(): array
    {
        return [
            'standard format song exists' => [
                // parámetros posicionales: comment, expectedData, songShouldExist
                'djponte Mark Ronson ft. Bruno Mars - Uptown Funk',
                ['songId' => 1, 'title' => 'Uptown Funk', 'artist' => 'Mark Ronson ft. Bruno Mars'],
                true,
            ],
            'standard format song does not exist' => [
                'djponte Daft Punk - Around the World',
                ['songId' => null, 'title' => 'Around the World', 'artist' => 'Daft Punk'],
                false,
            ],
            // sólo título (existe)
            'title only song exists' => [
                'Uptown Funk',
                ['songId' => 1, 'title' => 'Uptown Funk', 'artist' => 'Mark Ronson ft. Bruno Mars'],
                true,
            ],
            // título only not found
            'title only does not exist' => [
                'Canción Inexistente',
                ['songId' => null, 'title' => 'Canción Inexistente', 'artist' => null],
                false,
            ],
            // with extra spaces
            'comment with extra spaces' => [
                '   Mark Ronson ft. Bruno Mars   -   Uptown Funk  ',
                ['songId' => 1, 'title' => 'Uptown Funk', 'artist' => 'Mark Ronson ft. Bruno Mars'],
                true,
            ],
            // empty comment
            'empty comment' => [
                '',
                ['songId' => null, 'title' => null, 'artist' => null],
                false,
            ],
        ];
    }
}
