<?php

namespace Tests\Feature\Webhooks;

use App\Events\NewTip;
use App\Jobs\ProcessTip;
use App\Models\Djsession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Bus;
use Illuminate\Support\Facades\Event;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use Stripe\Webhook;
use Mockery;

class StripeWebhookTest extends TestCase
{
    use RefreshDatabase;

    private User $dj;
    private User $donor;
    private Djsession $djsession;

    protected function setUp(): void
    {
        parent::setUp();

        $this->dj = User::factory()->create(['role' => 'dj', 'stripe_id' => 'acct_test_123']);
        $this->donor = User::factory()->create(['role' => 'user', 'stripe_id' => 'cus_test_123']);
        $this->djsession = Djsession::factory()->create(['user_id' => $this->dj->id, 'active' => true]);
    }

    #[Test]
    #[Group('custom')]
    public function it_correctly_processes_a_checkout_session_completed_webhook_from_stripe()
    {
        Bus::fake();
        Event::fake();

        // Creamos un tip pendiente que será actualizado por el webhook
        $tip = \App\Models\Tip::create([
            'dj_id' => $this->dj->id,
            'user_id' => $this->donor->id,
            'djsession_id' => $this->djsession->id,
            'amount' => 500,
            'currency' => 'eur',
            'status' => 'pending',
            'custom_title' => 'Canción personalizada',
            'custom_artist' => 'Artista personalizado',
            'song_id' => null,
            'stripe_session_id' => 'cs_test_123', // <- coincide con payload
            'donor_name' => 'Test Donante',
            'description' => '¡Gran sesión!',
        ]);

        $payload = [
            'id' => 'evt_test_123',
            'object' => 'event',
            'type' => 'checkout.session.completed',
            'data' => [
                'object' => [
                    'id' => 'cs_test_123',
                    'object' => 'checkout.session',
                    'payment_intent' => 'pi_test_123',
                    'metadata' => [
                        'dj_id' => (string) $this->dj->id,
                        'djsession_id' => (string) $this->djsession->id,
                        'donor_name' => 'Test Donante',
                        'description' => '¡Gran sesión!',
                    ],
                ],
            ],
        ];

        // IMPORTANTE: devolver un objeto, no un array -> evitará el 500
        Mockery::mock('alias:' . Webhook::class)
            ->shouldReceive('constructEvent')
            ->andReturn(json_decode(json_encode($payload))); // <-- devuelve StdClass con propiedades anidadas

        // Llamada al endpoint (no importa el body real porque ya mockeamos constructEvent)
        $response = $this->postJson(route('stripe.webhook'), [
            'id' => 'evt_test_123',
        ], [
            'Stripe-Signature' => 'fake_signature',
        ]);

        // Si falla aquí, inspecciona contenido con $response->getContent()
        $response->assertOk();

        // Basta con comprobar que el Job fue despachado; no intentamos leer $job->data aquí.
        Bus::assertDispatched(ProcessTip::class);

        // Ejecutamos manualmente el job (simula que la cola lo procesó)
        $job = new ProcessTip('cs_test_123', 'paid');
        $job->handle();

        // Comprobamos efectos en BD
        $this->assertDatabaseHas('tips', [
            'dj_id' => $this->dj->id,
            'djsession_id' => $this->djsession->id,
            'amount' => 500,
            'description' => '¡Gran sesión!',
            'status' => 'paid',
        ]);

        // Verificamos el evento broadcast (esto comprueba que ProcessTip disparó NewTip)
        Event::assertDispatched(NewTip::class, function ($event) use($tip){
            // Ajusta según cómo construyas el canal en NewTip (ej. 'private-dj.{id}' o similar)
            return $event->broadcastWith()['tip_id'] == $tip->id &&
            $event->broadcastWith()['djsession_id'] == $tip->djsession_id &&
            $event->broadcastWith()['user_id'] == $tip->user_id &&
            $event->broadcastWith()['status'] == 'paid' &&
            $event->broadcastWith()['tip_amount'] == $tip->amount && //
            $event->userId == $tip->user_id &&
            $event->djsessionId == $tip->djsession_id;
        });
    }
}
