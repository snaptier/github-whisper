<?php

namespace Snaptier\GitHubWhisper\Models;

use Snaptier\GitHubWhisper\Clients\File as FileClient;
use LaravelWhisper\Whisper\Whisperer;

class File extends Whisperer
{
    /**
     * The client instance.
     *
     * @var \Snaptier\GitHubWhisper\Clients\File
     */
    protected static $client = FileClient::class;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The primary key for the model.
     *
     * @var string
     */
    protected $primaryKey = 'path';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function setContentAttribute($value)
    {
        $this->attributes['content'] = base64_decode($value);
    }
}
