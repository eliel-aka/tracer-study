<?php

namespace App\Http\Requests\Auth;

use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    /**
     * Attempt to authenticate the request's credentials.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        if (! Auth::attempt($this->only('email', 'password'), $this->boolean('remember'))) {
            $user = \App\Models\User::where('email', $this->input('email'))->first();
            if ($user && in_array($user->role, ['lulusan', 'pengguna_lulusan'])) {
                $inputPassword = $this->input('password');
                $nipBaru = $user->lulusan->nip_baru ?? $user->penggunaLulusan->nip_baru ?? null;
                $nipLama = $user->lulusan->nip_lama ?? $user->penggunaLulusan->nip_lama ?? null;
                if (!$nipBaru && !$nipLama) {
                    $rawNip = $user->lulusan->nip ?? $user->penggunaLulusan->nip ?? '';
                    if (strlen($rawNip) === 18) {
                        $nipBaru = $rawNip;
                    } elseif (strlen($rawNip) === 9) {
                        $nipLama = $rawNip;
                    } else {
                        $nipBaru = $rawNip;
                    }
                }

                $primaryPw = \App\Models\User::generateDefaultPassword($user->name, $nipBaru, $nipLama);
                $altPw = \App\Models\User::generateAlternativeDefaultPassword($user->name, $nipBaru, $nipLama);

                if ($inputPassword === $primaryPw || $inputPassword === $altPw) {
                    $user->update(['password' => \Illuminate\Support\Facades\Hash::make($inputPassword)]);
                    Auth::login($user, $this->boolean('remember'));
                    RateLimiter::clear($this->throttleKey());
                    return;
                }
            }

            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    /**
     * Ensure the login request is not rate limited.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    /**
     * Get the rate limiting throttle key for the request.
     */
    public function throttleKey(): string
    {
        return Str::transliterate(Str::lower($this->string('email')).'|'.$this->ip());
    }
}
