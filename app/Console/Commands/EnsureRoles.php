<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class EnsureRoles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'roles:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates user roles';


    /**
     * The Groups of Permission to be assigned.
     *
     * @var array
     */
    public $groupPermission = [
        'general' => [
            'user',
            'profile_question_answer',
            'profile_visibility',
            'referrer',
            'profile_motto',
            'profile_progress',
            'user_profile_setting',
        ],
        'standard' => [
            'chat_connection',
            'chat_message',
            'group',
            'group_category',
            'group_user',
            'impression',
            'post',
            'post_comment',
            'profile_message_alert',
            'report',
            'tooltip',
            'user_block_list_item',
            'user_friend',
            'user_friend_request',
            'user_watch_list_item',
        ],
        'b2b' => [
            'b2_b_code',
            'b2_b_coupon',
            'b2_b_discount',
            'b2_b_partner',
            'b2_b_partner_redirect',
            'b2_b_user',
        ],
        'mourner' => [
            'matching_user_answer',
            'matching_user_partner_ranking'
        ]
    ];

    /**
     * The Single Permissions to be assigned.
     *
     * @var array
     */
    public $singlePermission = [
        'general' => [
            'view faq_question',
            'view matching_answer,',
            'view matching_answer_type',
            'view matching_answer_validation',
            'view matching_cluster_dimensions_definition',
            'view matching_cluster_ranking_definition',
            'view matching_question',
            'view matching_question_answer_condition',
            'view matching_question_step_content',
            'view profile_question',
            'update usage b2_b_code'
        ],
        'standard' => [
            'view standard_dashboard'
        ],
        'mourner' => [
            'view partner',
            'view user_actions',
            'view mourner_profile'
        ],
        'supporter' => [
            'view supporter_profile'
        ],
        'b2b' => [
            'view b2b_dashboard'
        ],
        'company' => [
            'view company_intro'
        ],
        'funeral-company' => [
            'view funeral-company_intro'
        ]
    ];


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {

        // Create the Standards Roles
        $roleMourner = $this->ensureSingleRole('mourner');
        $this->givePermissionTo($roleMourner);

        $roleSupporter = $this->ensureSingleRole('supporter');
        $this->givePermissionTo($roleSupporter);

        // Create the Companies Roles
        $roleCompany = $this->ensureSingleRole('company');
        $this->givePermissionTo($roleCompany);

        $roleFuneralCompany = $this->ensureSingleRole('funeral-company');
        $this->givePermissionTo($roleFuneralCompany);

        return 0;
    }


    public function ensureSingleRole(string $name, string $guard = 'web'): Model
    {
        return Role::query()->firstOrCreate(['name' => $name, 'guard_name' => $guard]);
    }

    public function givePermissionTo(Model $role)
    {
        $commonGroups = [
            'standard' => ['mourner', 'supporter'],
            'b2b' => ['company', 'funeral-company']
        ];


        $groups = $this->mergePermission($this->groupPermission, $role->name, $this->groupPermission['general']);
        $singles = $this->mergePermission($this->singlePermission, $role->name, $this->groupPermission['general']);


        foreach ($commonGroups as $key => $values) {
            if (in_array($role->name, $values)) {
                $groups = $this->mergePermission($this->groupPermission, $key, $groups);
                $singles = $this->mergePermission($this->singlePermission, $key, $singles);
            }
        }

        $this->ensureGroupPermission($role, $groups);
        $this->ensureSinglePermission($role, $singles);

    }

    public function mergePermission(array $parent, string $key, array $base = []): array
    {
        if (array_key_exists($key, $parent)) {
            return array_merge($base, $parent[$key]);
        }
        return $base;
    }

    public function ensureGroupPermission(Model $role, array $groups = [])
    {
        $permissions = Permission::query()
            ->whereIn('group', $groups)
            ->get();

        $role->givePermissionTo($permissions);
    }

    public function ensureSinglePermission(Model $role, array $names = [])
    {
        $permissions = Permission::query()
            ->whereIn('name', $names)
            ->get();

        $role->givePermissionTo($permissions);
    }

}