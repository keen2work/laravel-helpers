<?php


namespace EMedia\Helpers\Console\Commands\Traits;


use EMedia\PHPHelpers\DateTime\Timing;
use Illuminate\Support\Facades\File;
use EMedia\PHPHelpers\Files\Loader;

trait CopiesStubFiles
{

	protected function copyMigrationFile($dirRoot, $stubFileName, $className)
	{
		// because Laravel 5.7 doesn't auto-load migration classes, manually load them
		$migrationsPath = database_path('migrations');
		Loader::includeAllFilesFromDir($migrationsPath);

		$source = $dirRoot . '/../../../database/migrations/' . $stubFileName;
		if (!file_exists($source)) {
			$this->error("Unable to find the file {$source}");
			return;
		}

		$pathinfo = pathinfo($source);

		preg_match('/[\d]{1,4}_(.*)/', $pathinfo['basename'], $matches);
		if (!is_countable($matches) || count($matches) < 1) {
			$this->error("Unable to parse the filename {$source}. Please ");
			return;
		}

		$filename = Timing::microTimestamp() . '_' . $matches[1];
		$destinationPath = database_path("migrations/{$filename}");

		$this->copyFile($source, $destinationPath, $className);
	}

	protected function copySeedFile($dirRoot, $stubFileName, $className)
	{
		$source = $dirRoot . '/../../../database/seeds/' . $stubFileName;
		$destination = database_path('seeds/' . $stubFileName);

		$result = $this->copyFile($source, $destination, $className);

		if ($result) {
			$this->info("Add {$stubFileName} to DatabaseSeeder.php");
		}
	}

	protected function copyFile($source, $destination, $className = null)
	{
		if ($className && class_exists($className, false)) {
			$this->info("{$className} class already exists. Skipped...");
			return false;
		}

		if (!File::copy($source, $destination)) {
			$this->error("Unable to copy the file {$destination}");
			return false;
		}
		return true;
	}

	protected function dumpAutoload()
	{
		$this->call('composer:dump-autoload');
	}

}
