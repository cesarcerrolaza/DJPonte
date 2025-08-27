<?php

namespace Tests\Feature\Webhooks;

use App\Jobs\ProcessInstagramComment;
use App\Models\Djsession;
use App\Models\SocialAccount;
use App\Models\SocialPost;
use App\Models\Song;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use App\Http\Middleware\VerifyFacebookSignature;

class InstagramWebhookTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * Procesa un webhook de Instagram teniendo en cuenta el estado completo de la BD.
     */
    #[Test]
    #[Group('custom')]
    public function it_correctly_processes_an_instagram_comment_webhook()
    {
        // 1. Arrange (Preparación)
        Bus::fake();

        // a) Creamos el estado necesario en la base de datos
        $dj = User::factory()->create(['role' => 'dj']);
        $djsession = Djsession::factory()->create(['user_id' => $dj->id, 'active' => true]);
        
        $socialAccount = SocialAccount::factory()->create([
            'user_id' => $dj->id
        ]);

        $socialPost = SocialPost::factory()->create([
            'social_account_id' => $socialAccount->id,
            'djsession_id' => $djsession->id,
            'media_id' => '123123123', // Este ID debe coincidir con el del payload
        ]);

        Song::factory()->create([
            'title' => 'Around The World',
            'artist' => 'Daft Punk',
        ]);

        // b) Construimos el payload correcto y completo, como indicaste
        $payload = [
            'entry' => [
                [
                    'changes' => [
                        [
                            'field' => 'comments',
                            'value' => [
                                'from' => [
                                    'id' => '232323232',
                                    'username' => 'testuser',
                                ],
                                'media' => [
                                    'id' => $socialPost->media_id, // Usamos el ID del post que creamos
                                    'media_product_type' => 'FEED',
                                ],
                                'id' => '17865799348089039',
                                'text' => 'Me pones Daft Punk - Around The World porfa',
                            ],
                        ],
                    ],
                ],
            ],
        ];

        // 2. Act
        $response = $this->withoutMiddleware(VerifyFacebookSignature::class)
                         ->postJson(route('instagram.webhook'), $payload);

        // 3. Assert
        $response->assertOk();

        // Verificamos que se despachó el job con el payload correcto.
        Bus::assertDispatched(ProcessInstagramComment::class, function ($job) {
            // La aserción clave es verificar que el job se despachó con los datos
            // correctos extraídos de la estructura anidada del payload.
            return $job->changeData['value']['media']['id'] === '123123123' &&
                   $job->changeData['value']['text'] === 'Me pones Daft Punk - Around The World porfa';
        });
    }
}