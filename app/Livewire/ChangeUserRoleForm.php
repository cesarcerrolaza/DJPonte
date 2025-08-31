<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class ChangeUserRoleForm extends Component
{
    public bool $confirmingRoleChange = false;
    public string $alertFirst;
    public string $alertSecond;

    /**
     * Gestiona el cambio de rol inicial.
     */
    public function confirmChangeRole()
    {
        $user = Auth::user();
        if ($user->role === 'user') {
            $this->alertFirst = 'Tu cuenta será convertida a DJ. Si actualmente estás unido a una sesión como usuario, serás desconectado automáticamente. Ya no podrás unirte a sesiones en calidad de usuario.';
            $this->alertSecond = 'Como DJ podrás crear sesiones y sorteos para tus seguidores, y recibir donaciones vinculando tu cuenta de Stripe. Siempre tendrás la opción de volver al rol de Usuario para unirte a sesiones y sorteos.';
        } elseif ($user->role === 'dj') {
            $this->alertFirst = '¿Seguro que quieres cambiar tu rol a Usuario? Si tienes una sesión activa, esta será desactivada. Con este rol no podrás crear ni gestionar tus sesiones o sorteos.';
            $this->alertSecond = 'Con el rol de Usuario podrás unirte a sesiones y participar en sorteos. Siempre podrás volver al rol de DJ más adelante y recuperar tus sesiones y sorteos.';
        }
        $this->confirmingRoleChange = true;
    }

    /**
     * Maneja la acción de cambio de rol.
     */
    public function changeRole(){
        $user = Auth::user();
        if ($user->role === 'user') {
            $statusMessage = 'Tu cuenta ha sido convertida a DJ. Todos los datos de tus sesiones y sorteos anteriores se han eliminado.';
        } elseif ($user->role === 'dj') {
            $statusMessage = 'Tu cuenta ha sido convertida a Usuario estándar. Los datos de tus sesiones y sorteos se han eliminado.';
        }
        try{
            $user->changeRole();
            session()->flash('status', $statusMessage);
            session()->flash('status_type', 'success');
        } catch (\Exception $e) {
            session()->flash('status', 'Ha ocurrido un error al cambiar el rol. Por favor, inténtalo de nuevo.');
            session()->flash('status_type', 'error');
            Log::error('Error changing user role: '.$e->getMessage());
        }

        $this->confirmingRoleChange = false;
        return $this->redirect(route('settings'), navigate: true);
    }

    public function render()
    {
        return view('livewire.change-user-role-form');
    }
}
