<?php

namespace App\Jobs;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class CreateTenantAdmin implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    protected $tenant;
    protected $userData;

    /**
     * Create a new job instance.
     */
    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        //sleep(20);
        $this->tenant->run(function ($tenant) {
            $user = User::create(
                $tenant->only(['name', 'email', 'password'])
            );

            $this->tenant->update([
                'name' => null,
                'email' => null,
                'password' => null,
                'ready' => true,
            ]);
        });
    }
}
