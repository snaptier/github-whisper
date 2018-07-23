<?php

namespace Snaptier\GitHubWhisper\Clients;

use Facades\Snaptier\GitHubWhisper\GraphQlHelper;
use GrahamCampbell\GitHub\Facades\GitHub;
use LaravelWhisper\Whisper\ClientInterface;

class Issue implements ClientInterface
{
    public static function delete($identifier) : ?bool
    {
        [$owner, $name] = explode('/', $identifier);

        GitHub::api('repo')->remove($owner, $name);

        return true;
    }

    public static function find($identifier) : array
    {
        [$repo, $number] = func_get_args();

        return GraphQLHelper::run('get-issue', ['owner' => $repo->owner, 'name' => $repo->name, 'id' => $number])['repository']['issue'];
    }

    public static function all() : array
    {
        throw new \RuntimeException("Getting all resources ins't an option available on this entity.");
    }

    public static function update($identifier, array $data) : void
    {
        [$owner, $name] = explode('/', $identifier);

        GitHub::api('repo')->update($owner, $name, $data);
    }

    public static function create(array $data) : array
    {
        extract(array_pull($data, 'repo'));

        return GitHub::api('issue')->create($owner, $name, $data);
    }

    public static function transfer($identifier, array $data, string $transferTo) : void
    {
        [$owner, $name] = explode('/', $identifier);

        GitHub::api('repo')->transfer($owner, $name, $transferTo); // Still not implemented, waiting for KnpLabs/php-github-api#699
    }

    public static function fork($identifier, array $data, array $options) : void
    {
        [$owner, $name] = explode('/', $identifier);

        GitHub::api('repo')->forks()->create($owner, $name, $options);
    }
}
