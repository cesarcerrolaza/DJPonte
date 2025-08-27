<?php

namespace Tests\Unit\Services;

use App\Models\Djsession;
use App\Models\User;
use App\Services\DjsessionService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\DB;
use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class DjsessionServiceTest extends TestCase
{
    use RefreshDatabase;

    protected DjsessionService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new DjsessionService();
    }

    #[Test]
    #[Group('custom')]
    public function it_activates_a_new_session_with_real_db(): void
    {
        // Creamos DJ y sesiones en la BD
        $dj = User::factory()->create(['djsession_id' => null]);
        $oldSession = Djsession::factory()->create([
            'user_id' => $dj->id,
            'active' => true,
            'current_users' => 5
        ]);
        $dj->djsession_id = $oldSession->id;
        $dj->save();

        $newSession = Djsession::factory()->create([
            'user_id' => $dj->id,
            'active' => false,
            'current_users' => 0
        ]);

        // Act
        $this->service->activate($newSession, $dj);

        $oldSession->refresh();
        $newSession->refresh();
        $dj->refresh();

        // Assert
        $this->assertEquals(0, $oldSession->active, 'La sesión antigua no se desactivó');
        $this->assertEquals(0, $oldSession->current_users, 'Los usuarios antiguos no se resetean');
        $this->assertEquals(1, $newSession->active, 'La nueva sesión no se activó');
        $this->assertEquals($newSession->id, $dj->djsession_id, 'El DJ no tiene la nueva sesión asignada');
    }

    #[Test]
    #[Group('custom')]
    public function it_allows_a_user_to_join_a_session_with_mock(): void
    {
        // Mocks
        DB::shouldReceive('transaction')->once()->andReturnUsing(fn($callback) => $callback());
        Broadcast::shouldReceive('event')->once();

        $djsession = Mockery::spy(new Djsession([
            'id' => 1,
            'current_users' => 0,
            'peak_users' => 0
        ]));
        $djsession->shouldReceive('save')->andReturnTrue();

        $user = Mockery::spy(new User(['djsession_id' => null]));
        $user->shouldReceive('save')->andReturnTrue();
        $user->shouldReceive('getAttribute')->with('djsessionActive')->andReturn(null);

        // Act
        $this->service->join($djsession, $user);

        // Assert
        $this->assertEquals($djsession->id, $user->djsession_id);
        $this->assertEquals(1, $djsession->current_users);
        $this->assertEquals(1, $djsession->peak_users);

        $djsession->shouldHaveReceived('save');
        $user->shouldHaveReceived('save');
    }
}
