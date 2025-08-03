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

            $socialPost = SocialPost::where('media_id', $postId)
                                    ->where('is_active', true)
                                    ->first();

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