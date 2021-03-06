<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'first_name',
        'middle_name',
        'last_name',
        'role',
        'status',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public function getFullNameAttribute(){
        if(empty($this->middle_name)){
            return ucfirst($this->first_name) .' '. ucfirst($this->last_name);
        }else{
            return ucfirst($this->first_name) .' '.ucfirst($this->middle_name)[0] .'. '. ucfirst($this->last_name);
        }
    }

    public function getRoleNameAttribute(){
        switch($this->role){
            case 1:
                return 'Front Desk';
            case 2:
                return 'Cashier';
            case 3:
                return 'Client';
            default:
                return 'Admin';
        }
    }

    // public function plot()
    // {
    //     return $this->belongsTo(Plot::class);
    // }
}
