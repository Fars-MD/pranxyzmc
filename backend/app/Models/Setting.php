<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'website_name',
        'logo',
        'favicon',
        'hero_title',
        'hero_subtitle',
        'whatsapp_number',
        'discord_url',
        'youtube_url',
        'instagram_url',
        'tiktok_url',
    ];

    public $timestamps = true;
}
