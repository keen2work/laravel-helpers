<?php


namespace EMedia\Helpers\Console\Commands;


use Illuminate\Console\Command;
use Illuminate\Support\Composer;

class ComposerAutoload extends Command
{

	protected $signature = 'composer:dump-autoload';
	protected $description = 'Compose dump-autoload wrapper';

	/**
	 * The Composer instance.
	 *
	 * @var \Illuminate\Foundation\Composer
	 */
	protected $composer;

	/**
	 * Create a new command instance.
	 *
	 * @param Composer $composer
	 * @return void
	 */
	public function __construct(Composer $composer)
	{
		parent::__construct();

		$this->composer = $composer;
	}

	/**
	 * Execute the console command.
	 *
	 * @return void
	 */
	public function handle()
	{
		$this->composer->dumpAutoloads();
		// $this->composer->dumpOptimized();
	}

}