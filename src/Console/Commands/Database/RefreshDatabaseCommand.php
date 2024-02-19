<?php

namespace EMedia\Helpers\Console\Commands\Database;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class RefreshDatabaseCommand extends Command
{
	/**
	 * The name and signature of the console command.
	 *
	 * @var string
	 */
	protected $signature = 'db:refresh {--nomigrate} {--noseed} {--modules}';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Remove all tables, migrate and seed all data';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function handle()
	{
		$this->call('db:wipe');

		if ($this->option('nomigrate')) return;

		$this->call('migrate');
		if ($this->option('modules')) $this->call('module:migrate');

		if (!$this->option('noseed')) {
			$this->call('db:seed');
			if ($this->option('modules')) $this->call('module:seed');
		}
	}
}
