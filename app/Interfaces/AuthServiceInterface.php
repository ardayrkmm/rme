<?php

namespace App\Interfaces;

interface AuthServiceInterface
{
    /**
     * Register a new user.
     *
     * @param array $data
     * @return array
     */
    public function register(array $data): array;

    /**
     * Authenticate a user and return token.
     *
     * @param array $credentials
     * @return array
     * @throws \Exception
     */
    public function login(array $credentials): array;

    /**
     * Logout the authenticated user.
     *
     * @param \Illuminate\Foundation\Auth\User $user
     * @return bool
     */
    public function logout($user): bool;

    /**
     * Change user password.
     *
     * @param \Illuminate\Foundation\Auth\User $user
     * @param string $currentPassword
     * @param string $newPassword
     * @return bool
     * @throws \Exception
     */
    public function changePassword($user, string $currentPassword, string $newPassword): bool;

    /**
     * Send forgot password token.
     *
     * @param string $email
     * @return bool
     * @throws \Exception
     */
    public function forgotPassword(string $email): bool;

    /**
     * Reset password using token.
     *
     * @param string $email
     * @param string $token
     * @param string $password
     * @return bool
     * @throws \Exception
     */
    public function resetPassword(string $email, string $token, string $password): bool;
}
