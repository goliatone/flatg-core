<?php namespace Dotink\Sage {

	include __DIR__ . DIRECTORY_SEPARATOR . 'vendor/autoload.php';

    use TokenReflection\Broker as TBroker;
	use TokenReflection\Broker\Backend\Memory as TMemory;
    
	if (!isset($argv[1])) {
		echo 'Usage: php sage.php <input_path> [<output_path>]' . PHP_EOL;
		exit(1);
	}

	$backend             = new TMemory();
	$broker              = new TBroker($backend);
	$sage                = new Generator($broker);
	$document_collection = $sage->run($argv[1]);
	$template_directory  = __DIR__ . DIRECTORY_SEPARATOR . 'vendor/dotink/sage/templates';
    $arg1   = isset($argv[2]) ? $argv[2] : NULL; 
    $writer = new Writer($arg1, $template_directory);
	$writer->setExternalDocs($sage->getConfig('external_docs'))
		->setTemplateData($sage->getConfig('template_data'))
		->buildDocumentation($document_collection);

	exit(0);
}