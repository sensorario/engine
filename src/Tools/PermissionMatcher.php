<?php

namespace Sensorario\Tools;

class PermissionMatcher
{
    private bool $match = false;

    private array $customs = [];

    public function __construct(array $needles, array $haystack)
    {
        sort($haystack);

        $this->customs = array_filter($needles, function ($action) {
            return strpos($action, '@') === 0;
        });

        $this->match = [] != array_intersect(
            $haystack,
            $needles,
        );
    }

    public function areNeedlesInHayStack(): bool
    {
        return $this->match;
    }

    public function listExplicitCustomItems(): array
    {
        return $this->customs;
    }

    public function containsValidActions(): bool
    {
        return $this->areNeedlesInHayStack()
            || count($this->listExplicitCustomItems()) > 0;
    }
}
