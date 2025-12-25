<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Collection;
use Symfony\Component\Process\Process;

class RunScriptsCommand extends Command
{
    protected $signature = 'translation:scripts:run
        {--path= : Absolute or relative path to the scripts directory}
        {--dry-run : List the scripts that would run without executing them}
        {--continue-on-error : Continue running remaining scripts even if one fails}';

    protected $description = 'Run every script within the scripts directory sequentially.';

    public function handle(): int
    {
        $scriptsDir = $this->resolveScriptsDirectory();

        if (!$scriptsDir) {
            $this->error('Scripts directory could not be found.');
            return self::FAILURE;
        }

        $scripts = $this->discoverScripts($scriptsDir);

        if ($scripts->isEmpty()) {
            $this->warn('No scripts detected in '.$scriptsDir);
            return self::SUCCESS;
        }

        if ($this->option('dry-run')) {
            $this->info('Scripts that would run:');
            $scripts->each(fn (string $script) => $this->line(' - '.basename($script)));
            return self::SUCCESS;
        }

        $this->info('Executing scripts from '.$scriptsDir);

        $failures = [];
        $continueOnError = (bool) $this->option('continue-on-error');

        foreach ($scripts as $script) {
            $label = basename($script);
            $this->line(PHP_EOL.'<comment>▶ '.$label.'</comment>');

            $process = $this->buildProcess($script);
            $this->runProcess($process, $label);

            if (!$process->isSuccessful()) {
                $failures[] = $label;
                $this->error($label.' failed with exit code '.$process->getExitCode());

                if (!$continueOnError) {
                    $this->warn('Halting because a script failed. Use --continue-on-error to run remaining scripts.');
                    return self::FAILURE;
                }
            }
        }

        if (empty($failures)) {
            $this->info('All scripts completed successfully.');
            return self::SUCCESS;
        }

        $this->warn('Completed with failures: '.implode(', ', $failures));
        return self::FAILURE;
    }

    protected function resolveScriptsDirectory(): ?string
    {
        $option = $this->option('path');
        $candidates = $option
            ? [$option, base_path($option)]
            : [base_path('scripts')];

        foreach ($candidates as $candidate) {
            if ($candidate && is_dir($candidate)) {
                return realpath($candidate) ?: $candidate;
            }
        }

        return null;
    }

    protected function discoverScripts(string $directory): Collection
    {
        $entries = scandir($directory) ?: [];

        return collect($entries)
            ->reject(fn ($entry) => in_array($entry, ['.', '..'], true))
            ->map(fn ($entry) => $directory.DIRECTORY_SEPARATOR.$entry)
            ->filter(fn ($path) => is_file($path))
            ->sortBy(fn ($path) => strtolower($path))
            ->values();
    }

    protected function buildProcess(string $script): Process
    {
        $command = $this->commandForScript($script);
        $process = new Process($command, dirname($script));
        $process->setTimeout(null);

        return $process;
    }

    protected function commandForScript(string $script): array
    {
        $extension = strtolower(pathinfo($script, PATHINFO_EXTENSION));

        switch ($extension) {
            case 'php':
                return ['php', $script];
            case 'py':
                return ['python', $script];
            case 'js':
                return ['node', $script];
            case 'ts':
                return ['npx', 'ts-node', $script];
            case 'sh':
                return ['bash', $script];
            case 'ps1':
                return ['powershell', '-ExecutionPolicy', 'Bypass', '-File', $script];
            case 'bat':
                return [$script];
            default:
                return [$script];
        }
    }

    protected function runProcess(Process $process, string $label): void
    {
        $process->run(function (string $type, string $buffer) use ($label): void {
            $outputType = $type === Process::ERR ? '<fg=red>'.$label.'</>' : '<info>'.$label.'</info>';
            $this->output->write($outputType.' '.$buffer);
        });
    }
}

