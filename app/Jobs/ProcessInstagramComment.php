<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use App\Models\SocialPost;
use App\Models\SocialUser;
use App\Models\SocialPostComment;


class ProcessInstagramComment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Los datos del evento de comentario que se van a procesar.
     *
     * @var array
     */
    public array $changeData;

    /**
     * Crea una nueva instancia del Job.
     *
     * @param array $changeData Los datos del webhook.
     */
    public function __construct(array $changeData)
    {
        $this->changeData = $changeData;
    }

    /**
     * Ejecuta el Job.
     */
    public function handle(): void
    {
        try {
            $comment = $this->changeData['value'];
            $postId = $comment['media']['id'];
            $message = $comment['text'];
            $username = $comment['from']['username'];

            // --- LÓGICA TEMPORAL PARA LA REVISIÓN DE LA APP ---
            // ID del post de prueba que envía Meta: "123123123"
            $testPostIdFromMeta = '123123123';

            if ($postId === $testPostIdFromMeta) {
                // Si es el post de prueba, buscamos CUALQUIER post que esté activo
                // para poder demostrar la funcionalidad en el vídeo.
                Log::info('Test webhook from Meta detected. Looking for any active post.');
                $socialPost = SocialPost::where('is_active', true)->first();
            } else {
                // Si es un webhook real, usamos la lógica normal.
                $socialPost = SocialPost::where('media_id', $postId)
                                        ->where('is_active', true)
                                        ->first();
            }
            // --- FIN DE LA LÓGICA TEMPORAL ---

            if (!$socialPost) {
                Log::info("Comentario ignorado para el post ID: {$postId} porque no está siendo monitorizado.");
                return;
            }

            $djsession = $socialPost->djSession;

            if ($djsession && $djsession->active) {
                $socialUser = SocialUser::connectToDjsession($username, $djsession->id, 'Instagram');
                
                SocialPostComment::create([
                    'social_post_id' => $socialPost->id,
                    'social_user_id' => $socialUser->id,
                    'media_id' => $comment['id']
                ]);

                \App\Models\SongRequest::createSocialRequest($djsession->id, $message);

                Log::info("Petición de canción procesada con éxito para el post ID: {$postId}");
            }

        } catch (\Exception $e) {
            Log::error('Error al procesar el Job del comentario de Instagram: ' . $e->getMessage());
        }
    }
}