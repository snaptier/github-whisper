<?php

namespace Snaptier\GitHubWhisper\Models;

use Snaptier\GitHubWhisper\Clients\Issue as IssueClient;
use LaravelWhisper\Whisper\Whisperer;

class Issue extends Whisperer
{
    /**
     * The client instance.
     *
     * @var \Snaptier\GitHubWhisper\Clients\Issue
     */
    protected static $client = IssueClient::class;

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
    protected $primaryKey = 'number';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public function setAuthorAttribute($value)
    {
        $this->author = is_array($value) ? $value['login'] : $value;
    }

    public function setOwnerAttribute($value)
    {
        $this->author = is_array($value) ? $value['login'] : $value;
    }

    public function setLabelsAttribute($value)
    {
        $this->attributes['labels'] = array_merge($this->labels ?? [], collect($value['nodes'] ?? $value)->values()->map(function ($item) {
            return $item['name'];
        })->toArray());
    }

    public function setClosedAttribute($value)
    {
        $this->isClosed = $value;
    }

    public function setClosedAtAttribute($value)
    {
        $this->isClosed = ! is_null($value);
    }

    public function setLockedAttribute($value)
    {
        $this->isLocked = $value;
    }

    public function setAuthorAssociationAttribute($value)
    {
        $this->viewerDidAuthor = $value == 'OWNER';
    }
}
