<?php

namespace App\Http\Middleware;

use Closure;
use App\Tenant;

class ResolveTenant
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
                try {
            $pieces    = explode('.', $request->getHost());
            $subdomain = $request->header('subdomain');

            $tenant = Tenant::where('subdomain', '=', $subdomain)->firstOrFail();
            // DB connection setup

            \Config::set('database.connections.tenantdb', array(
                'driver'    => 'mysql',
                'host'      => $tenant->dbhost,
                'database'  => $tenant->dbname,
                'username'  => $tenant->dbusername,
                'password'  => $tenant->dbpassword,
                'charset'   => 'utf8',
                'collation' => 'utf8_unicode_ci',
                'prefix'    => '',
                'modes' => [],
            ));

            // //set s3 bucket

            // \Config::set('filesystems.disks.s3', array(
            //     'driver' => 's3',
            //     'key'    => $tenant->s3_key,
            //     'secret' => $tenant->s3_secret,
            //     'region' => $tenant->s3_region,
            //     'bucket' => $tenant->s3_bucket,
            // ));

            \Config::set('database.default', 'tenantdb');

        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            abort(404);
        }

        return $next($request);
    }
}
