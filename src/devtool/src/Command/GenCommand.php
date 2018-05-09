<?php

namespace Swoft\Devtool\Command;

use Swoft\Console\Bean\Annotation\Command;
use Swoft\Console\Helper\ConsoleUtil;
use Swoft\Console\Input\Input;
use Swoft\Console\Output\Output;
use Swoft\Devtool\FileGenerator;
use Swoft\Helper\DirHelper;
use Swoft\Devtool\Model\Logic\EntityLogic;

/**
 * Generate some common application template classes[<cyan>built-in</cyan>]
 * @Command(coroutine=false)
 * @package Swoft\Devtool\Command
 */
class GenCommand
{
    /**
     * @var string
     */
    public $defaultTplPath;

    public function init()
    {
        $this->defaultTplPath = \dirname(__DIR__, 2) . '/res/templates/';
    }

    /**
     * Generate CLI command controller class
     * @Usage {fullCommand} CLASS_NAME SAVE_DIR [--option ...]
     * @Arguments
     *   name       The class name, don't need suffix and ext.(eg. <info>demo</info>)
     *   dir        The class file save dir(default: <info>@app/Commands</info>)
     * @Options
     *   -y, --yes BOOL             No need to confirm when performing file writing. default is: <info>False</info>
     *   -o, --override BOOL        Force override exists file. default is: <info>False</info>
     *   -n, --namespace STRING     The class namespace. default is: <info>App\Commands</info>
     *   --suffix STRING            The class name suffix. default is: <info>Command</info>
     *   --tpl-file STRING          The template file name. default is: <info>command.stub</info>
     *   --tpl-dir STRING           The template file dir path.(default: devtool/res/templates)
     * @Example
     *   <info>{fullCommand} demo</info>     Gen DemoCommand class to `@app/Commands`
     * @param Input $input
     * @param Output $output
     * @return int
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \Leuffen\TextTemplate\TemplateParsingException
     */
    public function command(Input $input, Output $output): int
    {
        list($config, $data) = $this->collectInfo($input, $output, [
            'suffix' => 'Command',
            'namespace' => 'App\\Commands',
            'tplFilename' => 'command',
        ]);

        $data['commandVar'] = '{command}';

        return $this->writeFile('@app/Commands', $data, $config, $output);
    }

    /**
     * Generate HTTP controller class
     * @Usage {fullCommand} CLASS_NAME SAVE_DIR [--option ...]
     * @Arguments
     *   name       The class name, don't need suffix and ext.(eg. <info>demo</info>)
     *   dir        The class file save dir(default: <info>@app/Controllers</info>)
     * @Options
     *   -y, --yes BOOL             No need to confirm when performing file writing. default is: <info>False</info>
     *   -o, --override BOOL        Force override exists file. default is: <info>False</info>
     *   -n, --namespace STRING     The class namespace. default is: <info>App\Controllers</info>
     *   --rest BOOL                The class will contains CURD action. default is: <info>False</info>
     *   --prefix STRING            The route prefix for the controller. default is class name
     *   --suffix STRING            The class name suffix. default is: <info>Controller</info>
     *   --tpl-file STRING          The template file name. default is: <info>command.stub</info>
     *   --tpl-dir STRING           The template file dir path.(default: devtool/res/templates)
     * @Example
     *   <info>{fullCommand} demo --prefix /demo -y</info>          Gen DemoController class to `@app/Controllers`
     *   <info>{fullCommand} user --prefix /users --rest</info>     Gen UserController class to `@app/Controllers`(RESTFul type)
     * @return int
     * @param Input $input
     * @param Output $output
     * @return int
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \Leuffen\TextTemplate\TemplateParsingException
     */
    public function controller(Input $input, Output $output): int
    {
        list($config, $data) = $this->collectInfo($input, $output, [
            'suffix' => 'Controller',
            'namespace' => 'App\\Controllers',
            'tplFilename' => 'controller',
        ]);

        $data['prefix'] = $input->getOpt('prefix') ?: '/' . $data['name'];
        $data['idVar'] = '{id}';

        if ($input->getOpt('rest', false)) {
            $config['tplFilename'] = 'controller-rest';
        }

        return $this->writeFile('@app/Controllers', $data, $config, $output);
    }

    /**
     * Generate WebSocket controller class
     * @Usage {fullCommand} CLASS_NAME SAVE_DIR [--option ...]
     * @Arguments
     *   name       The class name, don't need suffix and ext.(eg. <info>demo</info>)
     *   dir        The class file save dir(default: <info>@app/WebSocket</info>)
     * @Options
     *   -y, --yes BOOL             No need to confirm when performing file writing. default is: <info>False</info>
     *   -o, --override BOOL        Force override exists file. default is: <info>False</info>
     *   -n, --namespace STRING     The class namespace. default is: <info>App\WebSocket</info>
     *   --prefix STRING            The route path for the controller. default is class name
     *   --suffix STRING            The class name suffix. default is: <info>Controller</info>
     *   --tpl-file STRING          The template file name. default is: <info>ws-controller.stub</info>
     *   --tpl-dir STRING           The template file dir path.(default: devtool/res/templates)
     * @Example
     *   <info>{fullCommand} echo --prefix /echo -y</info>         Gen EchoController class to `@app/WebSocket`
     *   <info>{fullCommand} chat --prefix /chat</info>     Gen ChatController class to `@app/WebSocket`
     * @return int
     * @param Input $input
     * @param Output $output
     * @return int
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \Leuffen\TextTemplate\TemplateParsingException
     */
    public function websocket(Input $input, Output $output): int
    {
        list($config, $data) = $this->collectInfo($input, $output, [
            'suffix' => 'Controller',
            'namespace' => 'App\\WebSocket',
            'tplFilename' => 'ws-controller',
        ]);

        $data['prefix'] = $input->getOpt('prefix') ?: '/' . $data['name'];

        return $this->writeFile('@app/WebSocket', $data, $config, $output);
    }

    /**
     * Generate RPC service class
     * @return int
     */
    public function rpcService(): int
    {
        \output()->writeln('un-completed ...');
        return 0;
    }

    /**
     * Generate an event listener class
     * @Usage {fullCommand} CLASS_NAME SAVE_DIR [--option ...]
     * @Arguments
     *   name       The class name, don't need suffix and ext.(eg. <info>demo</info>)
     *   dir        The class file save dir(default: <info>@app/Listener</info>)
     * @Options
     *   -y, --yes BOOL             No need to confirm when performing file writing. default is: <info>False</info>
     *   -o, --override BOOL        Force override exists file. default is: <info>False</info>
     *   -n, --namespace STRING     The class namespace. default is: <info>App\Listener</info>
     *   --suffix STRING            The class name suffix. default is: <info>Listener</info>
     *   --tpl-file STRING          The template file name. default is: <info>listener.stub</info>
     *   --tpl-dir STRING           The template file dir path.(default: devtool/res/templates)
     * @Example
     *   <info>{fullCommand} demo</info>     Gen DemoListener class to `@app/Listener`
     * @param Input $input
     * @param Output $output
     * @return int
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \Leuffen\TextTemplate\TemplateParsingException
     */
    public function listener(Input $input, Output $output): int
    {
        list($config, $data) = $this->collectInfo($input, $output, [
            'suffix' => 'Listener',
            'namespace' => 'App\\Listener',
            'tplFilename' => 'listener',
        ]);

        return $this->writeFile('@app/Listener', $data, $config, $output);
    }

    /**
     * Generate HTTP middleware class
     * @Usage {fullCommand} CLASS_NAME SAVE_DIR [--option ...]
     * @Arguments
     *   name       The class name, don't need suffix and ext.(eg. <info>demo</info>)
     *   dir        The class file save dir(default: <info>@app/Middlewares</info>)
     * @Options
     *   -y, --yes BOOL             No need to confirm when performing file writing. default is: <info>False</info>
     *   -o, --override BOOL        Force override exists file. default is: <info>False</info>
     *   -n, --namespace STRING     The class namespace. default is: <info>App\Middlewares</info>
     *   --suffix STRING            The class name suffix. default is: <info>Middleware</info>
     *   --tpl-file STRING          The template file name. default is: <info>middleware.stub</info>
     *   --tpl-dir STRING           The template file dir path.(default: devtool/res/templates)
     * @Example
     *   <info>{fullCommand} demo</info>     Gen DemoMiddleware class to `@app/Middlewares`
     * @param Input $input
     * @param Output $output
     * @return int
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \Leuffen\TextTemplate\TemplateParsingException
     */
    public function middleware(Input $input, Output $output): int
    {
        list($config, $data) = $this->collectInfo($input, $output, [
            'suffix' => 'Middleware',
            'namespace' => 'App\\Middlewares',
            'tplFilename' => 'middleware',
        ]);

        return $this->writeFile('@app/Middlewares', $data, $config, $output);
    }

    /**
     * Generate user task class
     * @Usage {fullCommand} CLASS_NAME SAVE_DIR [--option ...]
     * @Arguments
     *   name       The class name, don't need suffix and ext.(eg. <info>demo</info>)
     *   dir        The class file save dir(default: <info>@app/Tasks</info>)
     * @Options
     *   -y, --yes BOOL             No need to confirm when performing file writing. default is: <info>False</info>
     *   -o, --override BOOL        Force override exists file. default is: <info>False</info>
     *   -n, --namespace STRING     The class namespace. default is: <info>App\Tasks</info>
     *   --suffix STRING            The class name suffix. default is: <info>Task</info>
     *   --tpl-file STRING          The template file name. default is: <info>task.stub</info>
     *   --tpl-dir STRING           The template file dir path.(default: devtool/res/templates)
     * @Example
     *   <info>{fullCommand} demo</info>     Gen DemoTask class to `@app/Tasks`
     * @param Input $input
     * @param Output $output
     * @return int
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \Leuffen\TextTemplate\TemplateParsingException
     */
    public function task(Input $input, Output $output): int
    {
        list($config, $data) = $this->collectInfo($input, $output, [
            'suffix' => 'Task',
            'namespace' => 'App\\Tasks',
            'tplFilename' => 'task',
        ]);

        return $this->writeFile('@app/Tasks', $data, $config, $output);
    }

    /**
     * Generate user custom process class
     * @Usage {fullCommand} CLASS_NAME SAVE_DIR [--option ...]
     * @Arguments
     *   name       The class name, don't need suffix and ext.(eg. <info>demo</info>)
     *   dir        The class file save dir(default: <info>@app/Process</info>)
     * @Options
     *   -y, --yes BOOL             No need to confirm when performing file writing. default is: <info>False</info>
     *   -o, --override BOOL        Force override exists file. default is: <info>False</info>
     *   -n, --namespace STRING     The class namespace. default is: <info>App\Process</info>
     *   --suffix STRING            The class name suffix. default is: <info>Process</info>
     *   --tpl-file STRING          The template file name. default is: <info>process.stub</info>
     *   --tpl-dir STRING           The template file dir path.(default: devtool/res/templates)
     * @Example
     *   <info>{fullCommand} demo</info>     Gen DemoProcess class to `@app/Process`
     * @param Input $input
     * @param Output $output
     * @return int
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \Leuffen\TextTemplate\TemplateParsingException
     */
    public function process(Input $input, Output $output): int
    {
        list($config, $data) = $this->collectInfo($input, $output, [
            'suffix' => 'Process',
            'namespace' => 'App\\Process',
            'tplFilename' => 'process',
        ]);

        return $this->writeFile('@app/Process', $data, $config, $output);
    }


    /**
     * Generate entity class
     * @Usage {fullCommand} -d test [--option ...]
     *
     * @Options
     *   -d, --database STRING      Must to set database. `,` symbol is used  to separated by multiple databases
     *   -i, --include STRING       Set the included tables, `,` symbol is used  to separated by multiple tables. default is: <info>all tables</info>
     *   -e, --exclude STRING       Set the excluded tables, `,` symbol is used  to separated by multiple tables. default is: <info>empty</info>
     *   -p, --path STRING          Specified entity generation path, default is: <info>@app/Models/Entity</info>
     *   --driver STRING            Specify database driver(mysql/pgsql/mongodb), default is: <info>mysql</info>
     *   --table-prefix STRING      Specify the table prefix that needs to be removed, default is: <info>empty</info>
     *   --field-prefix STRING      Specify the field prefix that needs to be removed, default is: <info>empty</info>
     *   --tpl-file STRING          The template file name. default is: <info>entity.stub</info>
     *   --tpl-dir STRING           The template file dir path.(default: devtool/res/templates)
     * @Example
     *   <info>{fullCommand} -d test</info>     Gen DemoProcess class to `@app/Models/Entity`
     *
     * @param Input $in
     * @param Output $out
     *
     * @return int
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     * @throws \Leuffen\TextTemplate\TemplateParsingException
     */
    public function entity(Input $in, Output $out)
    {
        $params = [
            'test',
            '',
            '',
            '@app/Models/Entity',
            'mysql',
            '',
            '',
            'entity',
            $this->defaultTplPath
        ];

        /* @var EntityLogic $logic*/
        $logic = bean(EntityLogic::class);
        $logic->generate($params);
    }

    /**
     * @param Input $input
     * @param Output $output
     * @param array $defaults
     * @return array
     */
    private function collectInfo(Input $input, Output $output, array $defaults = []): array
    {
        $config = [
            'tplFilename' => $input->getOpt('tpl-file') ?: $defaults['tplFilename'],
            'tplDir' => $input->getOpt('tpl-dir') ?: $this->defaultTplPath,
        ];

        if (!$name = $input->getArg(0)) {
            $name = $input->read('Please input class name(no suffix and ext. eg. test): ');
        }

        if (!$name) {
            $output->writeln('<error>No class name input! Quit</error>', true, 1);
        }

        $sfx = $input->getOpt('suffix') ?: $defaults['suffix'];
        $data = [
            'name' => $name,
            'suffix' => $sfx,
            'namespace' => $input->sameOpt(['n','namespace']) ?: $defaults['namespace'],
            'className' => \ucfirst($name) . $sfx,
        ];

        return [$config, $data];
    }

    /**
     * @param string $defaultDir
     * @param array $data
     * @param array $config
     * @param Output $output
     * @return int
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \Leuffen\TextTemplate\TemplateParsingException
     */
    private function writeFile(string $defaultDir, array $data, array $config, Output $output): int
    {
        // $output->writeln("Some Info: \n" . \json_encode($config, \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES));
        $output->writeln("Class data: \n" . \json_encode($data, \JSON_PRETTY_PRINT | \JSON_UNESCAPED_SLASHES));

        if (!$saveDir = \input()->getArg(1)) {
            $saveDir = $defaultDir;
        }

        $saveDir = \alias($saveDir);
        $file = $saveDir . '/' . $data['className'] . '.php';

        $output->writeln("Target File: <info>$file</info>\n");

        // check file exists
        if (\file_exists($file)) {
            $override = \input()->sameOpt(['o', 'override']);

            if (null === $override) {
                if (!ConsoleUtil::confirm('Target file has been exists, override?', false)) {
                    $output->writeln(' Quit, Bye!');

                    return 0;
                }
            } elseif (!$override) {
                $output->writeln(' Quit, Bye!');

                return 0;
            }
        }

        $yes = \input()->sameOpt(['y', 'yes'], false);

        // check save dir
        if (!\file_exists($saveDir)) {
            if (!$yes && !ConsoleUtil::confirm('Target file dir is not exists! Create it')) {
                $output->writeln(' Quit, Bye!');

                return 0;
            }

            DirHelper::mkdir($saveDir);
        }

        if (!$yes && !ConsoleUtil::confirm('Now, will write content to file, ensure continue?')) {
            $output->writeln(' Quit, Bye!');

            return 0;
        }

        $ger = new FileGenerator($config);

        if ($ok = $ger->renderAs($file, $data)) {
            $output->writeln("\n<success>OK, Write file successfully!</success>");
        } else {
            $output->writeln("\n<error>NO, Failed to write file!</error>");
        }

        return 0;
    }
}
