<?php

namespace Sensorario\Tools;

class PermissionMatcher
{
    private bool $match = false;

    public function __construct(array $needles, array $haystack)
    {
        sort($haystack);

        $this->match = $needles === array_intersect(
            $haystack,
            $needles,
        );
    }

    public function areNeedlesInHayStack(): bool
    {
        return $this->match;
    }
}
