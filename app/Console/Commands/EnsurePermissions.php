<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EnsurePermissions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'permissions:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates read/write permissions for all existing models';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        // Read/Write/Create/Delete for all existing models,
        $models = $this->getFileNames(app_path());
        $this->ensureGroups($models);

        // Additional Permissions
        $this->addAdditionalPermissions();


        // Create a Super-Admin Role and assign all Permissions
        $role = Role::query()->firstOrCreate(['name' => 'super-admin', 'guard_name' => 'web']);
        $role->givePermissionTo(Permission::all());

        return 0;
    }

    public function addAdditionalPermissions()
    {
        $this->ensureSinglePermission("standard", "view standard_dashboard");
        $this->ensureSinglePermission("standard", "view mourner_profile");
        $this->ensureSinglePermission("standard", "view supporter_profile");
        $this->ensureSinglePermission("standard", "view user_actions");
        $this->ensureSinglePermission("standard", "view partner");

        $this->ensureSinglePermission("b2_b_code", "update usage b2_b_code");

        $this->ensureSinglePermission("b2_b", "view b2b_dashboard");
        $this->ensureSinglePermission("b2_b", "view company_intro");
        $this->ensureSinglePermission("b2_b", "view funeral-company_intro");
    }

    public function ensureGroups($classes)
    {
        foreach ($classes as $class) {
            $group = ltrim(rtrim(Str::snake($class), '_'), '_');

            $this->ensureSinglePermission($group, "create {$group}");
            $this->ensureSinglePermission($group, "update {$group}");
            $this->ensureSinglePermission($group, "view {$group}");
            $this->ensureSinglePermission($group, "delete {$group}");
        }
    }

    /**
     * Will either find or create a permission by a given slug
     *
     * @param string $group
     * @param string $name
     * @param string $guard
     * @return Model
     */
    public function ensureSinglePermission(string $group, string $name, string $guard = 'web'): Model
    {
        return Permission::query()
            ->where('guard_name', $guard)
            ->where('name', $name)
            ->where('group', $group)
            ->firstOrCreate(
                [
                    'guard_name' => $guard,
                    'name' => $name,
                    'group' => $group
                ]
            );
    }

    /**
     * Returns an array of all existing models by scanning the Models dir
     * @param $path
     * @return array
     */
    public function getFileNames($path): array
    {
        $out = [];
        $results = scandir($path);
        foreach ($results as $result) {
            if ($result === '.' or $result === '..') continue;
            $filename = $path . '/' . $result;
            $file_ext = pathinfo($filename, PATHINFO_EXTENSION);
            if (!is_dir($filename) && $file_ext === 'php') {
                $modelPath = explode('/', substr($filename, 0, -4));
                $out[] = array_pop($modelPath);
            }
        }
        return $out;
    }
}