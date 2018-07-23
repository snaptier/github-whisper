<?php

namespace Snaptier\GitHubWhisper\Models;

use Illuminate\Support\Arr;
use LaravelWhisper\Whisper\Whisperer;
use Snaptier\GitHubWhisper\Clients\Repository as RepositoryClient;

class Repository extends Whisperer
{
    /**
     * The client instance.
     *
     * @var \Snaptier\GitHubWhisper\Clients\Repository
     */
    protected static $client = RepositoryResource::class;

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
    protected $primaryKey = 'full_name';

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    public static function fromGraphQL(string $owner, string $name)
    {
        return static::find("$owner/$name");
    }

    public function setDatabaseIdAttribute($value)
    {
        $this->id = $value;
    }

    public function setOwnerAttribute($value)
    {
        $this->owner = is_array($value) ? $value['login'] : $value;
    }

    public function setNameWithOwnerAttribute($value)
    {
        $this->full_name = $value;
    }

    public function setParentAttribute($value)
    {
        $this->parent = is_array($value) ? self::fill($value) : $value;
    }

    public function setHasIssuesEnabledAttribute($value)
    {
        $this->hasIssues = $value;
    }

    public function setDefaultBranchRefAttribute($value)
    {
        $branches = $this->branches ?? [];

        $this->branches = array_unique(array_merge($branches, [$value['name'] => [
            'prefix' => $value['prefix'], 'sha' => $value['target']['oid'],
        ]]), SORT_REGULAR);

        $this->branch = $value['name'];
    }

    public function setIssueAttribute(Issue $issue)
    {
        $issues = $this->issues ?? [];

        $this->setAttribute('issues', Arr::set($issues, $issue->number, $issue));

        return $this;
    }

    public function setFileAttribute(File $file)
    {
        $files = $this->files ?? [];

        $this->setAttribute('files', Arr::set($files, $file->path, $file));

        return $this;
    }

    public function removeIssue($issue)
    {
        $issue = $issue instanceof Issue ? $issue->number : $issue;

        unset($this->attributes['issues'][$issue]);

        return $this;
    }

    public function removeFile($file)
    {
        $file = $file instanceof File ? $file->path : $file;

        unset($this->attributes['files'][$file]);

        return $this;
    }
}
