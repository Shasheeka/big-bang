<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Tenant;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\App;

class TenantMigrations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
       protected $signature = 'tenant:migrate  {--subdomain=} {--seed} ';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command use for run custom migrations for tenant db';

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
        try {
            if ($this->input->getOption('subdomain') != null) {
                $subdomain  = $this->input->getOption('subdomain');

                $tenants [] = Tenant::where('subdomain', $subdomain)->firstOrFail();

            } else {
                $tenants = Tenant::all();
            }

            if (!$tenants) {
                throw \Exception('Migration failed , at Command\MigrationCommand. Tenant database configuration not found.');
            }

            $connection = '';

            foreach ($tenants as $tenant) {
                $dbcon = $tenant->dbname;
                $seeds = '';
                \Config::set('database.connections.' . $dbcon, array(
                    'driver'    => 'mysql',
                    'host'      => $tenant->dbhost,
                    'database'  => $tenant->dbname,
                    'username'  => $tenant->dbusername,
                    'password'  => $tenant->dbpassword,
                    'charset'   => 'utf8',
                    'collation' => 'utf8_unicode_ci',
                    'prefix'    => '',
                    'strict'    => false,

                ));

                \Config::set('database.default', $dbcon);

                Artisan::call('migrate', ['--database' => $dbcon]);

                if ($this->input->getOption('seed') == true) {
                    Artisan::call('db:seed', ['--database' => $dbcon]);
                    $seeds = '+ seeds';
                }

                if (\DB::connection()->getDatabaseName()) {
                    $this->info('Migration ' . $seeds . ' successfully done - ' . \DB::connection()->getDatabaseName());
                }
            }

        } catch (\Exception $e) {
            echo $e->getMessage();
            \Log::error($e->getMessage());
            App::abort(404);

        }
    }
}
