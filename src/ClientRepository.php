<?php

namespace Stratedge\Visa;

use Illuminate\Support\Str;
use Laravel\Passport\Passport;
use Laravel\Passport\ClientRepository as BaseClientRepository;

class ClientRepository extends BaseClientRepository
{
    /**
     * Store a new client.
     *
     * @param  int  $userId
     * @param  string  $name
     * @param  string  $redirect
     * @param  bool  $personalAccess
     * @param  bool  $password
     * @return \Laravel\Passport\Client
     */
    public function create($userId, $name, $redirect, $personalAccess = false, $password = false)
    {
        if (Visa::$clientUUIDsEnabled) {
            $id = Str::uuid();
        } else {
            $id = str_random(40);
        }

        $client = Passport::client()->forceFill([
            'id' => $id,
            'user_id' => $userId,
            'name' => $name,
            'secret' => str_random(40),
            'redirect' => $redirect,
            'personal_access_client' => $personalAccess,
            'password_client' => $password,
            'revoked' => false,
        ]);

        $client->save();

        return $client;
    }
}
