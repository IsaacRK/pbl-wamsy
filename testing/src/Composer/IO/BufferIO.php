<?php











namespace Composer\IO;

use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Output\StreamOutput;
use Symfony\Component\Console\Formatter\OutputFormatterInterface;
use Symfony\Component\Console\Input\StreamableInputInterface;
use Symfony\Component\Console\Input\StringInput;
use Symfony\Component\Console\Helper\HelperSet;




class BufferIO extends ConsoleIO
{

protected $input;

protected $output;






public function __construct($input = '', $verbosity = StreamOutput::VERBOSITY_NORMAL, OutputFormatterInterface $formatter = null)
{
$input = new StringInput($input);
$input->setInteractive(false);

$output = new StreamOutput(fopen('php://memory', 'rw'), $verbosity, $formatter ? $formatter->isDecorated() : false, $formatter);

parent::__construct($input, $output, new HelperSet(array(
new QuestionHelper(),
)));
}




public function getOutput()
{
fseek($this->output->getStream(), 0);

$output = stream_get_contents($this->output->getStream());

$output = preg_replace_callback("{(?<=^|\n|\x08)(.+?)(\x08+)}", function ($matches) {
$pre = strip_tags($matches[1]);

if (strlen($pre) === strlen($matches[2])) {
return '';
}


return rtrim($matches[1])."\n";
}, $output);

return $output;
}








public function setUserInputs(array $inputs)
{
if (!$this->input instanceof StreamableInputInterface) {
throw new \RuntimeException('Setting the user inputs requires at least the version 3.2 of the symfony/console component.');
}

$this->input->setStream($this->createStream($inputs));
$this->input->setInteractive(true);
}






private function createStream(array $inputs)
{
$stream = fopen('php://memory', 'r+');

foreach ($inputs as $input) {
fwrite($stream, $input.PHP_EOL);
}

rewind($stream);

return $stream;
}
}
