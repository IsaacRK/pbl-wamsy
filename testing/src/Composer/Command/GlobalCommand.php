<?php











namespace Composer\Command;

use Composer\Factory;
use Composer\Util\Filesystem;
use Composer\Util\Platform;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Output\OutputInterface;




class GlobalCommand extends BaseCommand
{



protected function configure()
{
$this
->setName('global')
->setDescription('Allows running commands in the global composer dir ($COMPOSER_HOME).')
->setDefinition(array(
new InputArgument('command-name', InputArgument::REQUIRED, ''),
new InputArgument('args', InputArgument::IS_ARRAY | InputArgument::OPTIONAL, ''),
))
->setHelp(
<<<EOT
Use this command as a wrapper to run other Composer commands
within the global context of COMPOSER_HOME.

You can use this to install CLI utilities globally, all you need
is to add the COMPOSER_HOME/vendor/bin dir to your PATH env var.

COMPOSER_HOME is c:\Users\<user>\AppData\Roaming\Composer on Windows
and /home/<user>/.composer on unix systems.

If your system uses freedesktop.org standards, then it will first check
XDG_CONFIG_HOME or default to /home/<user>/.config/composer

Note: This path may vary depending on customizations to bin-dir in
composer.json or the environmental variable COMPOSER_BIN_DIR.

Read more at https://getcomposer.org/doc/03-cli.md#global
EOT
)
;
}





public function run(InputInterface $input, OutputInterface $output)
{
if (!method_exists($input, '__toString')) {
throw new \LogicException('Expected an Input instance that is stringable, got '.get_class($input));
}


$tokens = preg_split('{\s+}', $input->__toString());
$args = array();
foreach ($tokens as $token) {
if ($token && $token[0] !== '-') {
$args[] = $token;
if (count($args) >= 2) {
break;
}
}
}


if (count($args) < 2) {
return parent::run($input, $output);
}


if (getenv('COMPOSER')) {
Platform::clearEnv('COMPOSER');
}


$config = Factory::createConfig();
$home = $config->get('home');

if (!is_dir($home)) {
$fs = new Filesystem();
$fs->ensureDirectoryExists($home);
if (!is_dir($home)) {
throw new \RuntimeException('Could not create home directory');
}
}

try {
chdir($home);
} catch (\Exception $e) {
throw new \RuntimeException('Could not switch to home directory "'.$home.'"', 0, $e);
}
$this->getIO()->writeError('<info>Changed current directory to '.$home.'</info>');


$input = new StringInput(preg_replace('{\bg(?:l(?:o(?:b(?:a(?:l)?)?)?)?)?\b}', '', $input->__toString(), 1));
$this->getApplication()->resetComposer();

return $this->getApplication()->run($input, $output);
}




public function isProxyCommand()
{
return true;
}
}
