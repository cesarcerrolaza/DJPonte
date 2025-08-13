<?php

// @formatter:off
// phpcs:ignoreFile
/**
 * A helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace App\Models{
/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string|null $description
 * @property string $venue
 * @property string|null $address
 * @property string|null $city
 * @property string|null $image
 * @property int $active
 * @property int|null $user_id
 * @property string|null $start_time
 * @property string|null $end_time
 * @property int $song_request_timeout
 * @property int $current_users
 * @property int $peak_users
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Raffle|null $currentRaffle
 * @property-read \App\Models\User|null $dj
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Donor> $donors
 * @property-read int|null $donors_count
 * @property-read mixed $image_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Raffle> $raffles
 * @property-read int|null $raffles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SocialPost> $socialPosts
 * @property-read int|null $social_posts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SocialUser> $socialUsers
 * @property-read int|null $social_users_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SongRequest> $songRequests
 * @property-read int|null $song_requests_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tip> $tips
 * @property-read int|null $tips_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $users
 * @property-read int|null $users_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Djsession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Djsession newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Djsession query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Djsession whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Djsession whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Djsession whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Djsession whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Djsession whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Djsession whereCurrentUsers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Djsession whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Djsession whereEndTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Djsession whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Djsession whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Djsession whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Djsession wherePeakUsers($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Djsession whereSongRequestTimeout($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Djsession whereStartTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Djsession whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Djsession whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Djsession whereVenue($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperDjsession {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int|null $djsession_id
 * @property int $amount
 * @property string $currency
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Djsession|null $djsession
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Donor newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Donor newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Donor query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Donor whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Donor whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Donor whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Donor whereDjsessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Donor whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Donor whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Donor whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperDonor {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $dj_id
 * @property int|null $djsession_id
 * @property string|null $winner_type
 * @property int|null $winner_id
 * @property string $prize_name
 * @property int $prize_quantity
 * @property string|null $prize_image
 * @property int $is_current
 * @property string $status
 * @property string|null $description
 * @property int|null $participants_count
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\User> $appParticipants
 * @property-read int|null $app_participants_count
 * @property-read \App\Models\User $dj
 * @property-read \App\Models\Djsession|null $djsession
 * @property-read mixed $prize_image_url
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SocialUser> $socialParticipants
 * @property-read int|null $social_participants_count
 * @property-read \Illuminate\Database\Eloquent\Model|\Eloquent|null $winner
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Raffle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Raffle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Raffle query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Raffle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Raffle whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Raffle whereDjId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Raffle whereDjsessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Raffle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Raffle whereIsCurrent($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Raffle whereParticipantsCount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Raffle wherePrizeImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Raffle wherePrizeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Raffle wherePrizeQuantity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Raffle whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Raffle whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Raffle whereWinnerId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Raffle whereWinnerType($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperRaffle {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property string $platform
 * @property string $account_id
 * @property string $username
 * @property string|null $access_token
 * @property string|null $refresh_token
 * @property string|null $expires_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SocialPost> $socialPosts
 * @property-read int|null $social_posts_count
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialAccount newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialAccount newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialAccount query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialAccount whereAccessToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialAccount whereAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialAccount whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialAccount whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialAccount whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialAccount wherePlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialAccount whereRefreshToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialAccount whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialAccount whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialAccount whereUsername($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSocialAccount {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $djsession_id
 * @property int $social_account_id
 * @property string $platform
 * @property string $media_id El ID del post en la plataforma social.
 * @property int $is_active
 * @property string|null $caption
 * @property string|null $media_url
 * @property string|null $permalink
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SocialPostComment> $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\Djsession|null $djsession
 * @property-read \App\Models\SocialAccount $socialAccount
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialPost newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialPost newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialPost query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialPost whereCaption($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialPost whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialPost whereDjsessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialPost whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialPost whereIsActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialPost whereMediaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialPost whereMediaUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialPost wherePermalink($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialPost wherePlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialPost whereSocialAccountId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialPost whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSocialPost {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $social_post_id
 * @property int|null $social_user_id
 * @property string $media_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\SocialPost|null $post
 * @property-read \App\Models\SocialUser|null $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialPostComment newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialPostComment newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialPostComment query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialPostComment whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialPostComment whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialPostComment whereMediaId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialPostComment whereSocialPostId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialPostComment whereSocialUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialPostComment whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSocialPostComment {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $username
 * @property string $platform
 * @property int $djsession_id
 * @property string $last_request_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Djsession $djsession
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Raffle> $raffles
 * @property-read int|null $raffles_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Raffle> $rafflesParticipated
 * @property-read int|null $raffles_participated_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialUser newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialUser newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialUser query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialUser whereDjsessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialUser whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialUser whereLastRequestAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialUser wherePlatform($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SocialUser whereUsername($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSocialUser {}
}

namespace App\Models{
/**
 * @property int $id
 * @property string $title
 * @property string $artist
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SongRequest> $songRequests
 * @property-read int|null $song_requests_count
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song whereArtist($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Song whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSong {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int|null $song_id
 * @property int $djsession_id
 * @property string|null $custom_title
 * @property string|null $custom_artist
 * @property float $score
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Djsession $djsession
 * @property-read \App\Models\Song|null $song
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SongRequest newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SongRequest newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SongRequest query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SongRequest whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SongRequest whereCustomArtist($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SongRequest whereCustomTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SongRequest whereDjsessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SongRequest whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SongRequest whereScore($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SongRequest whereSongId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|SongRequest whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperSongRequest {}
}

namespace App\Models{
/**
 * @property int $id
 * @property int $user_id
 * @property int $dj_id
 * @property int|null $djsession_id
 * @property int|null $song_id
 * @property string|null $custom_title
 * @property string|null $custom_artist
 * @property int $amount
 * @property string $currency
 * @property string|null $stripe_session_id
 * @property string $status
 * @property string|null $description
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Djsession|null $djsession
 * @property-read mixed $formatted_amount
 * @property-read mixed $status_label
 * @property-read \App\Models\Song|null $song
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tip newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tip newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tip query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tip whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tip whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tip whereCurrency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tip whereCustomArtist($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tip whereCustomTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tip whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tip whereDjId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tip whereDjsessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tip whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tip whereSongId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tip whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tip whereStripeSessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tip whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Tip whereUserId($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperTip {}
}

namespace App\Models{
/**
 * @mixin \Illuminate\Database\Eloquent\Builder
 * @mixin \Laravel\Cashier\Billable
 * @property int $id
 * @property string $name
 * @property string $email
 * @property \Illuminate\Support\Carbon|null $email_verified_at
 * @property string $password
 * @property string|null $two_factor_secret
 * @property string|null $two_factor_recovery_codes
 * @property string|null $two_factor_confirmed_at
 * @property string $role
 * @property string|null $remember_token
 * @property string|null $profile_photo_path
 * @property \Illuminate\Support\Carbon|null $last_request_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property int|null $djsession_id
 * @property string|null $stripe_id
 * @property string|null $pm_type
 * @property string|null $pm_last_four
 * @property string|null $trial_ends_at
 * @property-read \App\Models\Djsession|null $djsessionActive
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Djsession> $djsessions
 * @property-read int|null $djsessions_count
 * @property-read string $profile_photo_url
 * @property-read \Illuminate\Notifications\DatabaseNotificationCollection<int, \Illuminate\Notifications\DatabaseNotification> $notifications
 * @property-read int|null $notifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Raffle> $rafflesCreated
 * @property-read int|null $raffles_created_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Raffle> $rafflesParticipated
 * @property-read int|null $raffles_participated_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Raffle> $rafflesWon
 * @property-read int|null $raffles_won_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\SocialAccount> $socialAccounts
 * @property-read int|null $social_accounts_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Cashier\Subscription> $subscriptions
 * @property-read int|null $subscriptions_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\Tip> $tips
 * @property-read int|null $tips_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Laravel\Sanctum\PersonalAccessToken> $tokens
 * @property-read int|null $tokens_count
 * @method static \Database\Factories\UserFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User hasExpiredGenericTrial()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User onGenericTrial()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereDjsessionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereEmailVerifiedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereLastRequestAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePassword($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePmLastFour($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User wherePmType($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereProfilePhotoPath($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRememberToken($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereRole($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereStripeId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTrialEndsAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorConfirmedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorRecoveryCodes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereTwoFactorSecret($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|User whereUpdatedAt($value)
 * @mixin \Eloquent
 */
	#[\AllowDynamicProperties]
	class IdeHelperUser {}
}

