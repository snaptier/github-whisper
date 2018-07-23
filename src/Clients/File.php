<?php

namespace Snaptier\GitHubWhisper\Clients;

use GrahamCampbell\GitHub\Facades\GitHub;
use LaravelWhisper\Whisper\ClientInterface;

class File implements ClientInterface
{
    public static function delete($identifier) : ?bool
    {
        [, $options, $attributes] = func_get_args();

        GitHub::api('repo')->contents()->rm($options['owner'], $options['name'], $options['path'], $options['commit'], $attributes['sha'], $options['branch'], $options['author']);

        return true;
    }

    public static function find($identifier) : array
    {
        [$repo, $path] = func_get_args();

        return GitHub::api('repo')->contents()->show($repo->owner, $repo->name, $path, $repo->branch);
    }

    public static function all() : array
    {
        throw new \RuntimeException("Getting all resources ins't an option available on this entity.");
    }

    public static function update($identifier, array $data) : void
    {
        [, , $attributes] = func_get_args();
        extract($data);

        GitHub::api('repo')->contents()->update($repo->owner, $repo->name, $identifier, base64_encode($contents), $commit, $attributes['sha'], $branch, $author);
    }

    public static function create(array $data) : array
    {
        return GitHub::api('repo')->contents()->create($data['owner'], $data['name'], $data['path'], $data['contents'], $data['commit'], $data['branch'], $data['author']);
    }
}
