<?php

namespace App\Http\Controllers;

use App\Models\SocialUser;
use Illuminate\Http\Request;
use App\Models\SongRequest;

class InstagramWebhookController extends Controller
{

    public function handle(Request $request)
    {
        // 📌 Verificación del webhook
        if ($request->has('hub_mode') && $request->input('hub_mode') === 'subscribe' && $request->isMethod('get')) {
            return $this->handleVerificationRequest($request);
        }

        // 📌 Notificaciones de eventos (como comentarios)
        if ($request->has('entry') && $request->isMethod('post')) {
            return $this->handleEventNotification($request);
        }

        // 📌 Si no coincide con nada, devolver 400 Bad Request
        return response()->json(['error' => 'Invalid request'], 400);
    }


    public function handleVerificationRequest(Request $request)
    {
        if ($request->input('hub_verify_token') === env('META_VERIFY_TOKEN')) {
            return response($request->input('hub_challenge'), 200);
        };
        return response()->json(['error' => 'Verification failed'], 403);
    }

    public function handleEventNotification(Request $request)
    {
        //Log::info('Instagram Webhook Event:', $request->all());
        // Aquí puedes filtrar más tipos de eventos
        foreach ($request->input('entry', []) as $entry) {
            foreach ($entry['changes'] as $change) {
                if ($change['field'] === 'comments') {
                    // Llamar a un controlador especializado en manejar comentarios
                    return $this->handleCommentEvent($change);
                }
            }
        }

        return response()->json(['message' => 'Event received'], 200);
    }



    public function handleCommentEvent(array $change)
    {
        $comment = $change['value'];
        $postId = $comment['media']['id'];
        $message = $comment['text'];
        //$userId = $comment['from']['id'];
        $username = $comment['from']['username'];

        $socialPost = \App\Models\SocialPost::where('media_id', $postId)->first();
        if (!$socialPost) {
            //return response()->json(['error' => 'Post not found'], 404);
            $socialPost = \App\Models\SocialPost::create([
                'djsession_id' => 1,
                'platform' => 'Instagram',
                'media_id' => $postId
            ]);
        }

        $djsession = \App\Models\Djsession::where('id', $socialPost->djsession_id)->first();

        if ($djsession && $djsession->active) {
            $socialUser = SocialUser::connectToDjsession($username, $djsession->id, 'Instagram');
            \App\Models\SocialPostComment::create([
                'social_post_id' => $socialPost->id,
                'social_user_id' => $socialUser->id,
                'media_id' => $comment['id']
            ]);
            SongRequest::createSocialRequest($djsession->id, $message);
        }
        return response()->json(['status' => 'ok']);
    }
}
