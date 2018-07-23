<?php

namespace Snaptier\GitHubWhisper\Clients;

use Facades\Snaptier\GitHubWhisper\GraphQlHelper;
use GrahamCampbell\GitHub\Facades\GitHub;
use LaravelWhisper\Whisper\ClientInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class Repository implements ClientInterface
{
    public static function delete($identifier) : ?bool
    {
        [$owner, $name] = explode('/', $identifier);

        GitHub::api('repo')->remove($owner, $name);

        return true;
    }

    public static function find($identifier) : array
    {
        [$owner, $name] = explode('/', $identifier);

        return GraphQLHelper::run('get-repo', compact('owner', 'name'))['repository'];
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
        $resolver = new OptionsResolver;
        $data = $resolver->setRequired('title')->resolve($data);

        return GitHub::api('repo')->create($data['name'], $data['description'] ?? '', $data['homepage'] ?? '', $data['public'] ?? true, $data['organization'] ?? null, $data['hasIssues'] ?? false, $data['hasWiki'] ?? false, $data['hasDownloads'] ?? false, $data['teamId'] ?? null, $data['autoInit'] ?? false);
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
