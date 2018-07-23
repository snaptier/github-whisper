<?php

namespace Snaptier\GitHubWhisper;

use GrahamCampbell\GitHub\Facades\GitHub;

class GraphQlHelper
{
    public function run(string $name, array $params = []) : array
    {
        return GitHub::api('graphql')->fromFile(__DIR__."/../storage/queries/$name.graphql", $params)['data'];
    }
}
