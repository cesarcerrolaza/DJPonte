<?php

namespace Tests\Feature\Raffle;

use App\Events\CurrentRaffleDeleted;
use App\Events\NewRaffleParticipant;
use App\Events\RaffleOperation;
use App\Events\RaffleWinner;
use App\Models\Djsession;
use App\Models\Raffle;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
use Livewire\Livewire;
use Tests\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;

class RaffleFlowTest extends TestCase
{
    use RefreshDatabase;

    private User $dj;
    private User $participantUser;
    private Djsession $djsession;

    /**
     * Preparamos el entorno base: un DJ, un usuario y una sesión activa.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->dj = User::factory()->create(['role' => 'dj']);
        $this->participantUser = User::factory()->create(['role' => 'user']);

        $this->djsession = Djsession::factory()->create([
            'user_id' => $this->dj->id,
            'active' => true,
        ]);
        
        $this->participantUser->djsession_id = $this->djsession->id;
        $this->participantUser->save();
    }

    /**
     * Simula el ciclo de vida completo de un sorteo ("camino feliz").
     */
    #[Test]
    #[Group('custom')]
    public function it_simulates_the_full_raffle_lifecycle_and_broadcasts_events()
    {
        Event::fake();

        Livewire::actingAs($this->dj)
            // Componente correcto para el formulario de creación
            ->test('raffle-manager-form', ['djsessionId' => $this->djsession->id])
            ->set('prize_name', 'Camiseta Djponte')
            ->set('prize_quantity', 2)
            ->call('save');

        $raffle = Raffle::first();
        $this->assertDatabaseHas('raffles', [
            'prize_name' => 'Camiseta Djponte',
            'prize_quantity' => 2,
            'status' => 'draft',
            'djsession_id' => $this->djsession->id
        ]);
        $this->assertNotNull($raffle);

        Livewire::actingAs($this->dj)
            ->test('raffles-management', ['djsessionId' => $this->djsession->id])
            ->call('setCurrentRaffle', $raffle->id);

        $this->assertDatabaseHas('raffles', ['id' => $raffle->id, 'is_current' => true]);
        Event::assertDispatched(RaffleOperation::class, function ($event) use ($raffle) {
            $payload = $event->broadcastWith();
            return $event->djsessionId === $raffle->djsession_id
                && $payload['raffle_id'] === $raffle->id
                && $payload['operation'] === 'set_current';
        });

        Livewire::actingAs($this->dj)
            ->test('raffles-management', ['djsessionId' => $this->djsession->id])
            ->call('openRaffle');

        $this->assertDatabaseHas('raffles', ['id' => $raffle->id, 'status' => 'open']);
        Event::assertDispatched(RaffleOperation::class, function ($event) use ($raffle) {
            return $event->djsessionId === $raffle->djsession_id && $event->broadcastWith()['raffle_id'] === $raffle->id && $event->broadcastWith()['operation'] === Raffle::STATUS_OPEN;
        });

        Livewire::actingAs($this->participantUser)

            ->test('raffle-entry-form', ['djsession' => $this->djsession])
            ->call('participate');

        $this->assertDatabaseHas('raffle_user', [
            'raffle_id' => $raffle->id,
            'user_id' => $this->participantUser->id
        ]);
        Event::assertDispatched(NewRaffleParticipant::class, function ($event) use ($raffle) {
            return $event->djsessionId === $raffle->djsession_id
                && $event->broadcastWith()['raffle_id'] === $raffle->id
                && $event->broadcastWith()['participant_name'] === $this->participantUser->name;
        });

        Livewire::actingAs($this->dj)
            ->test('raffles-management', ['djsessionId' => $this->djsession->id])
            ->call('closeRaffle');

        $this->assertDatabaseHas('raffles', ['id' => $raffle->id, 'status' => 'closed']);
        Event::assertDispatched(RaffleOperation::class, function ($event) use ($raffle) {
            return $event->djsessionId === $raffle->djsession_id && $event->broadcastWith()['raffle_id'] === $raffle->id && $event->broadcastWith()['operation'] === Raffle::STATUS_CLOSED;
        });

        Livewire::actingAs($this->dj)
            ->test('raffles-management', ['djsessionId' => $this->djsession->id])
            ->call('drawRaffle');

        $this->assertDatabaseHas('raffles', ['id' => $raffle->id, 'status' => Raffle::STATUS_CLOSED]);
        $this->assertNotNull($raffle->fresh()->winner_id); // Comprueba que hay un ganador
        Event::assertDispatched(RaffleWinner::class, function ($event) use ($raffle) {
            $payload = $event->broadcastWith();
            return $event->djsessionId === $raffle->djsession_id
                && $payload['raffle_id'] === $raffle->id
                && $payload['winner_id'] === $raffle->fresh()->winner_id;
        });

        Livewire::actingAs($this->dj)
            ->test('raffles-management', ['djsessionId' => $this->djsession->id])
            ->call('terminateRaffle', $raffle->id);

        $this->assertDatabaseHas('raffles', ['id' => $raffle->id, 'is_current' => false]);

        Event::assertDispatched(RaffleOperation::class, function ($event) use ($raffle) {
            $payload = $event->broadcastWith();
            return $event->djsessionId === $raffle->djsession_id
                && $payload['raffle_id'] === $raffle->id
                && $payload['operation'] === Raffle::STATUS_TERMINATED;
        });
    }

    /**
     * Simula la eliminación de un sorteo actual y verifica la emisión del evento.
     */
    #[Test]
    #[Group('custom')]
    public function it_broadcasts_event_when_current_raffle_is_deleted()
    {
        Event::fake();
        // Crear un sorteo actual
        $raffle = Raffle::factory()->create([
            'djsession_id' => $this->djsession->id,
            'is_current' => true,
            'status' => 'open',
        ]);
        // Eliminar el sorteo
        $raffleId = $raffle->id;
        Livewire::actingAs($this->dj)
            ->test('raffles-management', ['djsessionId' => $this->djsession->id])
            ->call('deleteRaffle', $raffleId);
        // Verificar que se emitió el evento
        Event::assertDispatched(CurrentRaffleDeleted::class, function ($event) use ($raffle) {
            return $event->djsessionId === $raffle->djsession_id;
        });
        // Verificar que el sorteo fue eliminado de la base de datos
        $this->assertDatabaseMissing('raffles', ['id' => $raffleId]);
    }

    // --- PRUEBAS DE FLUJOS DE ERROR ("CAMINOS TRISTES") ---
    
    /**
     * @dataProvider invalidRaffleStatusProvider
     * Un usuario no puede participar en un sorteo que no está abierto.
     * @param string $invalidStatus
     */
    #[Test]
    #[DataProvider('invalidRaffleStatusProvider')]
    #[Group('custom')]
    public function a_user_cannot_join_a_raffle_that_is_not_open(string $invalidStatus)
    {
        // Arrange: Creamos un sorteo actual, pero con un estado inválido para participar.
        $raffle = Raffle::factory()->create([
            'djsession_id' => $this->djsession->id,
            'is_current' => true,
            'status' => $invalidStatus,
        ]);

        // Act: El usuario intenta participar.
        Livewire::actingAs($this->participantUser)
            ->test('raffle-entry-form', ['djsession' => $this->djsession])
            ->call('participate');

        // Assert: Verificamos que NO se creó el registro de participación.
        $this->assertDatabaseMissing('raffle_user', [
            'raffle_id' => $raffle->id,
            'user_id' => $this->participantUser->id,
        ]);
    }

    /**
     * Un usuario que no pertenece a la sesión no puede participar en el sorteo.
     */
    #[Test]
    #[Group('custom')]
    public function a_user_not_in_the_session_cannot_join_a_raffle()
    {
        // Arrange: Creamos un sorteo válido y un usuario fuera de la sesión.
        $raffle = Raffle::factory()->create([
            'djsession_id' => $this->djsession->id,
            'is_current' => true,
            'status' => 'open',
        ]);
        $outsiderUser = User::factory()->create();

        // Act: El usuario externo intenta participar.
        Livewire::actingAs($outsiderUser)
            ->test('raffle-entry-form', ['djsession' => $this->djsession])
            ->call('participate');

        // Assert: Verificamos que NO se creó el registro de participación.
        $this->assertDatabaseMissing('raffle_user', [
            'raffle_id' => $raffle->id,
            'user_id' => $outsiderUser->id,
        ]);
    }

    /**
     * Data Provider para estados de sorteo inválidos para la participación.
     */
    public static function invalidRaffleStatusProvider(): array
    {
        return [
            'draft status' => ['draft'],
            'closed status' => ['closed'],
            'finished status' => ['terminated'],
        ];
    }
}