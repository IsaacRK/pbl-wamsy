<?php











namespace Composer\Util;

use React\Promise\CancellablePromiseInterface;
use Symfony\Component\Console\Helper\ProgressBar;
use React\Promise\PromiseInterface;




class Loop
{

private $httpDownloader;

private $processExecutor;

private $currentPromises = array();

private $waitIndex = 0;

public function __construct(HttpDownloader $httpDownloader, ProcessExecutor $processExecutor = null)
{
$this->httpDownloader = $httpDownloader;
$this->httpDownloader->enableAsync();

$this->processExecutor = $processExecutor;
if ($this->processExecutor) {
$this->processExecutor->enableAsync();
}
}




public function getHttpDownloader()
{
return $this->httpDownloader;
}




public function getProcessExecutor()
{
return $this->processExecutor;
}






public function wait(array $promises, ProgressBar $progress = null)
{

$uncaught = null;

\React\Promise\all($promises)->then(
function () {
},
function ($e) use (&$uncaught) {
$uncaught = $e;
}
);



$waitIndex = $this->waitIndex++;
$this->currentPromises[$waitIndex] = $promises;

if ($progress) {
$totalJobs = 0;
$totalJobs += $this->httpDownloader->countActiveJobs();
if ($this->processExecutor) {
$totalJobs += $this->processExecutor->countActiveJobs();
}
$progress->start($totalJobs);
}

$lastUpdate = 0;
while (true) {
$activeJobs = 0;

$activeJobs += $this->httpDownloader->countActiveJobs();
if ($this->processExecutor) {
$activeJobs += $this->processExecutor->countActiveJobs();
}

if ($progress && microtime(true) - $lastUpdate > 0.1) {
$lastUpdate = microtime(true);
$progress->setProgress($progress->getMaxSteps() - $activeJobs);
}

if (!$activeJobs) {
break;
}
}


if ($progress) {
$progress->finish();
}

unset($this->currentPromises[$waitIndex]);
if ($uncaught) {
throw $uncaught;
}
}




public function abortJobs()
{
foreach ($this->currentPromises as $promiseGroup) {
foreach ($promiseGroup as $promise) {
if ($promise instanceof CancellablePromiseInterface) {
$promise->cancel();
}
}
}
}
}
