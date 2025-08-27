<?php

namespace Tests\Feature\Djsession;

use App\Events\DjsessionUpdate;
use App\Models\Djsession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;


class SessionFlowTest extends TestCase
{
    use RefreshDatabase;

    private User $dj;
    private User $regularUser;

    /**
     * Preparamos los usuarios base para las pruebas.
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->dj = User::factory()->create(['role' => 'dj']);
        $this->regularUser = User::factory()->create(['role' => 'user']);
    }

    /**
     * Un DJ puede crear una nueva sesión.
     */
    #[Test]
    #[Group('custom')]
    public function a_dj_can_create_djsession(): void
    {
        $response = $this->actingAs($this->dj)->post(route('djsessions.store'), [
            'name' => 'Test Session',
            'code' => '',
            'active' => true,
            'song_request_timeout' => 300,
            'timeout_unit' => 'seconds',
            'venue' => 'Sala de Ensayo',
        ]);

        $response->assertRedirect(route('djsessions.index'));

        $this->dj->refresh();
        $djsession = \App\Models\Djsession::latest()->first();
        $this->assertDatabaseHas('djsessions', [
            'id' => $djsession->id,
            'name' => 'Test Session',
            'user_id' => $this->dj->id,
        ]);
        $this->assertNotEmpty($djsession->code, 'El código no se generó automáticamente');
        $this->assertStringStartsWith('DJ-', $djsession->code, 'El código generado no tiene el prefijo esperado');
        $this->assertEquals(300, $djsession->song_request_timeout, 'El tiempo de espera no se guardó correctamente');



        $this->assertNotNull($this->dj->djsession_id);
    }

    /**
     * @test
     * Un DJ puede activar una nueva sesión desactivada.
     */
    #[Test]
    #[Group('custom')]
    public function a_dj_can_activate_an_existing_session_from_the_manager(): void
    {
        Event::fake();

        // 1. Arrange: Creamos una sesión para el DJ, pero inactiva.
        $inactiveSession = Djsession::factory()->create([
            'user_id' => $this->dj->id,
            'active' => false,
        ]);

        // 2. Act: Simulamos que el DJ, viendo su panel, activa la sesión.
        Livewire::actingAs($this->dj)
            ->test('djsession-manager', ['djsession' => $inactiveSession]) // Pasamos la sesión al componente
            ->call('toggleStatus'); // Llamamos a la acción de activar

        // 3. Assert
        $this->assertDatabaseHas('djsessions', [
            'id' => $inactiveSession->id,
            'active' => true,
        ]);

        $this->dj->refresh();
        $this->assertEquals($inactiveSession->id, $this->dj->djsession_id);

        Event::assertDispatched(DjsessionUpdate::class, function ($event) use ($inactiveSession) {
            $payload = $event->broadcastWith();
            return property_exists($event, 'djsession_id') &&
                   $event->djsession_id === $inactiveSession->id &&
                   $payload['active'] === true;
        });
    }

    /**
     * @test
     * Un usuario puede unirse a una sesión activa y se emite el evento de actualización.
     */
    #[Test]
    #[Group('custom')]
    public function a_user_can_join_an_active_session_and_an_update_event_is_broadcasted(): void
    {
        // 1. Arrange
        Event::fake();

        $djsession = Djsession::factory()->create([
            'user_id' => $this->dj->id,
            'active' => true,
            'current_users' => 0,
        ]);

        // 2. Act
        $response = $this->actingAs($this->regularUser)->get(route('djsessions.join', $djsession));

        // 3. Assert
        $response->assertRedirect(route('djsessions.index'));

        $this->assertDatabaseHas('djsessions', [
            'id' => $djsession->id,
            'current_users' => 1,
        ]);

        $this->regularUser->refresh();
        $this->assertEquals($djsession->id, $this->regularUser->djsession_id);

        Event::assertDispatched(DjsessionUpdate::class, function ($event) use ($djsession) {
            $payload = $event->broadcastWith();
            return $event->broadcastOn()->name === 'djsession.' . $djsession->id &&
                   $payload['current_users'] === 1;
        });
    }
}