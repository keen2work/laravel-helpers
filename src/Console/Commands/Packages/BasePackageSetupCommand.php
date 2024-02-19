<?php


namespace EMedia\Helpers\Console\Commands\Packages;


use EMedia\Helpers\Console\Commands\Traits\CopiesStubFiles;
use Illuminate\Console\Command;
use EMedia\PHPHelpers\Files\FileEditor;

abstract class BasePackageSetupCommand extends Command
{

	use CopiesStubFiles;

	// any name to display to the user
	protected $packageName;

	protected $updateRoutesFile = false;

	public function handle()
	{
		$this->checkSetupVariables();

		if (method_exists($this, 'beforeMigrations')) {
			$this->beforeMigrations();
		}

		$this->generateMigrations();

		if (method_exists($this, 'beforeSeeds')) {
			$this->beforeSeeds();
		}

		$this->generateSeeds();

		if (method_exists($this, 'publishPackageFiles')) {
			$this->publishPackageFiles();
		}

		if ($this->updateRoutesFile) $this->updateRouteFiles();

		$this->dumpAutoload();
	}

	protected function checkSetupVariables()
	{
		if (empty($this->packageName)) throw new \InvalidArgumentException("packageName variable must be set");
	}

	/**
	 *
	 * Any name to display to the user
	 *
	 * @return mixed
	 */
	// abstract protected function setPackageName();

	abstract protected function generateMigrations();

	abstract protected function generateSeeds();

	protected function getPackageBaseDir()
	{
		$class = new \ReflectionClass($this);
		$pathinfo = pathinfo($class->getFileName());

		return $pathinfo['dirname'] . '/../..';
	}

	/**
	 *
	 * Get the stub and the associated route file
	 *
	 * @param string $filename
	 * @return array|bool
	 */
	protected function getRouteStubFilePaths($filename = 'web')
	{
		$stubPath = "{$this->getPackageBaseDir()}/Stubs/routes/{$filename}.php.stub";

		if (!file_exists($stubPath)) return false;

		return [
			'routes' => base_path("routes/{$filename}.php"),
			'stub' => $stubPath,
		];
	}

	/**
	 *
	 * Get stub files and update the route files
	 *
	 * @return bool
	 * @throws \EMedia\PHPHelpers\Exceptions\FileSystem\FileNotFoundException
	 */
	protected function updateRouteFiles()
	{
		// allow multiple route files to be updated
		$allowedRouteFilenames = ['api', 'channels', 'web', 'console'];

		foreach ($allowedRouteFilenames as $filename)
		{
			$filePaths = $this->getRouteStubFilePaths($filename);
			if (!$filePaths) continue;

			try
			{
				$bytes = FileEditor::appendStubIfSectionNotFound($filePaths['routes'], $filePaths['stub'], null, null, true);
			}
			catch (\EMedia\PHPHelpers\Exceptions\FileSystem\SectionAlreadyExistsException $ex)
			{
				if (!$this->confirm($this->packageName . " package routes are already in `{$filename}.php`. Add again?", false))
				{
					return false;
				}

				$bytes = FileEditor::appendStub($filePaths['routes'], $filePaths['stub']);
			}
		}
	}

}
