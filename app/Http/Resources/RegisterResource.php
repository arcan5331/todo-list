<?php

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class RegisterResource extends JsonResource
{

    public function __construct(public User $user)
    {
        parent::__construct($user);
    }

    public function toArray(Request $request): array
    {
        return [
            'data' => [
                'user' => $this->user,
                'token' => $this->user->createToken(now())->plainTextToken,
                'email_verify_url' => $this->verificationUrl()
            ]
        ];
    }

    protected function verificationUrl(): string
    {
        if ($this->user->getEmailVerificationText()) {
            $this->user->setRandomEmailVerificationText();
        }
        return URL::temporarySignedRoute(
            'verification.verify.user',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $this->user->getKey(),
                'hash' => sha1($this->user->getEmailForVerification()),
                'code' => sha1($this->user->getEmailVerificationText())
            ]
        );
    }
}
