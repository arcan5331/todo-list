<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Foundation\Auth\EmailVerificationRequest as FormRequest;

class EmailVerificationRequest extends FormRequest
{
    /**
     * @var User|User[]|\LaravelIdea\Helper\App\Models\_IH_User_C|null
     */
    private array|null|\LaravelIdea\Helper\App\Models\_IH_User_C|User $user;

    public function authorize(): bool
    {
        $this->user = User::find($this->route('id'));

        if (!hash_equals(sha1($this->user->getEmailForVerification()), (string)$this->route('hash'))) {
            return false;
        }

        if (!hash_equals(sha1($this->user->getEmailVerificationText()), (string)$this->route('code'))) {
            return false;
        }

        return true;
    }

    public function fulfill(): void
    {
        if (!$this->user->hasVerifiedEmail()) {
            $this->user->markEmailAsVerified();

            event(new Verified($this->user));
        }
    }


}
