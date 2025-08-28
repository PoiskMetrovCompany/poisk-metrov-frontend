<?php

namespace App\Services\Filter;

use App\Core\Interfaces\Services\FilterCommandInterface;
use Illuminate\Database\Eloquent\Builder;


class CommandInvoker
{
    /**
     * @var FilterCommandInterface[]
     */
    private array $commands = [];

    /**
     * @param FilterCommandInterface $command
     * @return self
     */
    public function addCommand(FilterCommandInterface $command): self
    {
        $this->commands[] = $command;
        return $this;
    }

    /**
     * @param Builder $query
     * @param array $filterValues
     * @return Builder
     */
    public function executeChain(Builder $query, array $filterValues): Builder
    {
        foreach ($this->commands as $index => $command) {
            $filterKey = $this->getFilterKeyForCommand($command);
            $value = $filterValues[$filterKey] ?? null;


            if ($command->canExecute($value)) {
                $query = $command->execute($query, $value);
            }
        }
        return $query;
    }

    /**
     * @param FilterCommandInterface $command
     * @return string
     */
    private function getFilterKeyForCommand(FilterCommandInterface $command): string
    {
        $className = get_class($command);
        $className = str_replace('App\\Services\\Filter\\Commands\\', '', $className);
        $className = str_replace('FilterCommand', '', $className);

        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $className));
    }

    /**
     * @return self
     */
    public function clearCommands(): self
    {
        $this->commands = [];
        return $this;
    }

    /**
     * @return int
     */
    public function getCommandCount(): int
    {
        return count($this->commands);
    }
}
