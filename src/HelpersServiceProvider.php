<?php


namespace EMedia\Helpers;

use EMedia\Helpers\Console\Commands\Database\RefreshDatabaseCommand;
use EMedia\Helpers\Console\Commands\Production\ConnectDeployKeysCommand;
use Illuminate\Support\ServiceProvider;

class HelpersServiceProvider extends ServiceProvider
{

	/**
	 * Register any application services.
	 *
	 * @return void
	 */
	public function register()
	{
		if (!app()->environment('production')) {
			$this->commands(RefreshDatabaseCommand::class);
		}

		$this->commands(ConnectDeployKeysCommand::class);
	}

}