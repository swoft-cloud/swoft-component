<?php
declare(strict_types=1);
/**
 * This file is part of Swoft.
 *
 * @link     https://swoft.org
 * @document https://doc.swoft.org
 * @contact  group@swoft.org
 * @license  https://github.com/swoft-cloud/swoft/blob/master/LICENSE
 */

namespace Swoft\Devtool\Command;

use Swoft\Console\Bean\Annotation\Command;
use Swoft\Console\Bean\Annotation\Mapping;
use Swoft\Console\Input\Input;
use Swoft\Console\Output\Output;
use Swoft\Helper\ProcessHelper;

/**
 * Internal tool for swoft team developer
 * @package Swoft\InternalDev\Command
 * @author inhere
 * @Command("sdev", coroutine=false, enabled=true)
 */
class SwoftDevCommand
{
    const TYPE_SSL = 'git@github.com:';

    const TYPE_HTTPS = 'https://github.com/';

    /**
     * @var string
     * https eg. https://github.com/swoft-cloud/swoft-devtool.git
     * ssl eg. git@github.com:swoft-cloud/swoft-devtool.git
     */
    public $gitUrl = '%sswoft-cloud/swoft-%s.git';

    /**
     * @var array
     */
    public $components = [];

    /**
     * @var string
     */
    public $componentDir;

    /**
     * List all swoft component names in the swoft/swoft-component
     * @Mapping("list-components")
     * @Options
     *   --show-repo BOOL       Display remote git repository address.
     * @Example
     *   {fullCommand}
     *   {fullCommand} --show-repo
     * @param Input $input
     * @param Output $output
     * @return int
     */
    public function listComponents(Input $input, Output $output): int
    {
        $this->checkEnv();

        $output->colored('Components Total: ' . \count($this->components), 'info');

        $buffer = [];
        $showRepo = (bool)$input->getOpt('show-repo');

        foreach ($this->components as $component) {
            if (!$showRepo) {
                $buffer[] = " $component";
                continue;
            }

            $remote = \sprintf($this->gitUrl, self::TYPE_HTTPS, $component);
            $component = \str_pad($component, 20);
            $buffer[] = \sprintf('  <comment>%s</comment> -  %s', $component, $remote);
        }

        $output->writeln($buffer);

        return 0;
    }

    /**
     * Update component directory code from git repo by 'git subtree pull'
     * @Mapping
     * @Usage {fullCommand} [COMPONENTS ...] [--OPTION ...]
     * @Arguments
     *   Component[s]   The existing component name[s], allow multi by space.
     * @Options
     *   --squash BOOL       Add option '--squash' in git subtree pull command. default: <info>True</info>
     *   --dry-run BOOL      Just print all the commands, but do not execute them. default: <info>False</info>
     *   -y, --yes BOOL      Do not confirm when execute git subtree push. default: <info>False</info>
     * @Example
     *   {fullCommand} devtool              Pull the devtool from it's git repo
     *   {fullCommand} devtool console      Pull multi component
     * @param Input $input
     * @param Output $output
     * @return int
     * @throws \RuntimeException
     */
    public function pull(Input $input, Output $output): int
    {
        $this->checkEnv();
        $output->writeln("<comment>Component Directory</comment>:\n $this->componentDir");

        $names = \array_filter($input->getArgs(), function ($key) {
            return \is_int($key);
        }, ARRAY_FILTER_USE_KEY);

        if ($names) {
            $back = $names;
            $names = \array_intersect($names, $this->components);

            if (!$names) {
                throw new \RuntimeException('Invalid component name entered: ' . \implode(', ', $back));
            }
        }

        $output->writeln([
            '<comment>Will pulled components</comment>:',
            ' <info>' . \implode(', ', $names) . '</info>'
        ]);

        $tryRun = (bool)$input->getOpt('dry-run', false);
        $squash = $input->getOpt('squash', false) ? ' --squash' : '';
        $workDir = $this->componentDir;

        // eg. git subtree pull --prefix=src/view git@github.com:swoft-cloud/swoft-view.git master [--squash]
        $output->writeln("\n<comment>Execute the pull command</comment>:");

        foreach ($names as $name) {
            $remote = \sprintf($this->gitUrl, self::TYPE_HTTPS, $name);
            $command = \sprintf('git subtree pull --prefix=src/%s %s master%s', $name, $remote, $squash);

            $output->writeln("> <cyan>$command</cyan>");
            $output->writeln("Pulling '$name' ...", false);

            // if '--dry-run' is true. do not exec.
            if (!$tryRun) {
                list($code, $ret, ) = ProcessHelper::run($command, $workDir);

                if ($code !== 0) {
                    throw new \RuntimeException("Exec command failed. command: $command return: \n$ret");
                }
            }

            $output->colored(' OK', 'success');
            // $output->writeln($ret);
        }

        $output->colored(\sprintf(
            "\nOK, A total of 【%s】 components were successfully pulled",
            \count($names)
        ), 'success');

        return 0;
    }

    /**
     * Push component[s] directory code to component's repo by 'git subtree push'
     * @Mapping
     * @Usage {fullCommand} [COMPONENTS ...] [--OPTION ...]
     * @Arguments
     *   Component[s]   The existing component name[s], allow multi by space.
     * @Options
     *   --type STRING       Remote git repository address usage protocol. allow: https, ssl. default: <info>https</info>
     *   -a, --all BOOL      Push all components to them's git repo. default: <info>False</info>
     *   -y, --yes BOOL      Do not confirm when execute git subtree push. default: <info>False</info>
     *   --dry-run BOOL      Just print all the commands, but do not execute them. default: <info>False</info>
     *   --squash BOOL       Add option '--squash' in git subtree push command. default: <info>false</info>
     * @Example
     *   {fullCommand} devtool              Push the devtool to it's git repo
     *   {fullCommand} devtool console      Push multi component. devtool and console
     *   {fullCommand} --all                Push all components
     *   {fullCommand} --all --dry-run      Push all components, but do not execute.
     * @param Input $input
     * @param Output $output
     * @return int
     * @throws \RuntimeException
     */
    public function push(Input $input, Output $output): int
    {
        $this->checkEnv();

        $output->writeln("<comment>Component Directory</comment>:\n $this->componentDir");

        $names = \array_filter($input->getArgs(), function ($key) {
            return \is_int($key);
        }, ARRAY_FILTER_USE_KEY);

        if ($names) {
            $back = $names;
            $names = \array_intersect($names, $this->components);

            if (!$names) {
                throw new \RuntimeException('Invalid component name entered: ' . \implode(', ', $back));
            }
        } elseif ($input->getSameOpt(['a', 'all'], false)) {
            $names = $this->components;
        } else {
            throw new \RuntimeException('Please enter the name of the component that needs to be pushed');
        }

        $output->writeln([
            '<comment>Will pushed components</comment>:',
            ' <info>' . \implode(', ', $names) . '</info>'
        ]);

        $tryRun = (bool)$input->getOpt('dry-run', false);
        $squash = $input->getOpt('squash', false) ? ' --squash' : '';

        $protoType = $input->getOpt('type') ?: 'https';
        $protoHost = $protoType === 'ssl' ? self::TYPE_SSL : self::TYPE_HTTPS;
        $workDir = $this->componentDir;

        // eg. git subtree push --prefix=src/view git@github.com:swoft-cloud/swoft-view.git master [--squash]
        $output->writeln("\n<comment>Execute the push command</comment>:");

        foreach ($names as $name) {
            $remote = \sprintf($this->gitUrl, $protoHost, $name);
            $command = \sprintf('git subtree push --prefix=src/%s %s master%s', $name, $remote, $squash);

            $output->writeln("> <cyan>$command</cyan>");
            $output->writeln("Pushing '$name' ...", false);

            // if '--dry-run' is true. do not exec.
            if (!$tryRun) {
                list($code, $ret, ) = ProcessHelper::run($command, $workDir);

                if ($code !== 0) {
                    throw new \RuntimeException("Exec command failed. command: $command return: \n$ret");
                }
            }

            $output->colored(' OK', 'success');
            // $output->writeln($ret);
        }

        $output->colored(\sprintf(
            "\nOK, A total of 【%s】 components are pushed to their respective git repositories",
            \count($names)
        ), 'success');

        return 0;
    }

    /**
     * Generate classes API documents by 'sami/sami'
     * @Mapping("gen-api")
     * @Options
     *   --sami STRING       The sami.phar package absolute path.
     *   --force BOOL        The option forces a rebuild docs. default: <info>False</info>
     *   --dry-run BOOL      Just print all the commands, but do not execute them. default: <info>False</info>
     *   --show-result BOOL  Display result for the docs generate. default: <info>False</info>
     * @Example
     *   {fullCommand} --sami ~/Workspace/php/tools/sami.phar --force --show-result
     *
     *   About sami:
     *    - An API documentation generator
     *    - github https://github.com/FriendsOfPHP/Sami
     *    - download `curl -O http://get.sensiolabs.org/sami.phar`
     * @param Input $input
     * @param Output $output
     * @return int
     * @throws \RuntimeException
     */
    public function genApi(Input $input, Output $output): int
    {
        $this->checkEnv();

        $option = '';

        if (!$samiPath = $input->getOpt('sami')) {
            $output->colored("Please input the sami.phar path by option '--sami'", 'error');

            return -1;
        }

        if (!\is_file($samiPath)) {
            $output->colored('The sami.phar file is not exists! File: ' . $samiPath, 'error');

            return -1;
        }

        $tryRun = (bool)$input->getOpt('dry-run', false);
        $config = COMPONENT_DIR . '/sami.doc.inc';
        $workDir = $this->componentDir;

        if ($input->getOpt('force')) {
            $option .= ' --force';
        }

        // php ~/Workspace/php/tools/sami.phar render --force
        $command = \sprintf(
            'php ~/Workspace/php/tools/sami.phar %s %s%s',
            'update',
            $config,
            $option
        );

        $output->writeln("> <cyan>$command</cyan>");

        // if '--dry-run' is true. do not exec.
        if (!$tryRun) {
            list($code, $ret, ) = ProcessHelper::run($command, $workDir);

            if ($code !== 0) {
                throw new \RuntimeException("Exec command failed. command: $command return: \n$ret");
            }

            if ($input->getOpt('show-result')) {
                $output->writeln(\PHP_EOL . $ret);
            }
        }

        $output->colored("\nOK, Classes reference documents generated!");

        return 0;
    }

    private function checkEnv()
    {
        if (!\defined('COMPONENT_DIR') || !COMPONENT_DIR) {
            \output()->writeln('<error>Missing the COMPONENT_DIR define</error>', true, true);
        }

        $this->componentDir = COMPONENT_DIR;

        $file = COMPONENT_DIR . '/components.inc';

        if (!\is_file($file)) {
            \output()->writeln('<error>Missing the components config.</error>', true, true);
        }

        $this->components = include $file;
    }
}
