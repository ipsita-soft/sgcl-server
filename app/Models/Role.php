<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Role extends Model
{
    use HasFactory;
    use SoftDeletes;
    protected $guarded = [];

    public function permissionsList()
    {
        return [
            [
                "name"=>"HRM",
                "slug" => "hrm",
                "comment" => "",
                "permissions"=>[
                    [
                        "name"=>"Department",
                        "slug" => "department",
                        "comment" => "",
                        "permissions"=>[
                            [
                                "name" => "Show",
                                "slug" => "department.show",
                                "comment" => ""
                            ],
                            [
                                "name" => "Create",
                                "slug" => "department.create",
                                "comment" => ""
                            ],
                            [
                                "name" => "Edit",
                                "slug" => "department.edit",
                                "comment" => ""
                            ],
                            [
                                "name" => "Delete",
                                "slug" => "department.delete",
                                "comment" => ""
                            ],
                        ]
                    ],
                    [
                        "name"=>"designation",
                        "slug" => "designation",
                        "comment" => "",
                        "permissions"=>[
                            [
                                "name" => "Show",
                                "slug" => "designation.show",
                                "comment" => ""
                            ],
                            [
                                "name" => "Create",
                                "slug" => "designation.create",
                                "comment" => ""
                            ],
                            [
                                "name" => "Edit",
                                "slug" => "designation.edit",
                                "comment" => ""
                            ],
                            [
                                "name" => "Delete",
                                "slug" => "designation.delete",
                                "comment" => ""
                            ],
                        ]
                    ],
                    // Add more modules as needed
                ]
            ],
            [
                "name" => "profile",
                "slug" => "profile",
                "comment" => "Permissions related to user profiles",
                "permissions" => [
                    [
                        "name" => "Show",
                        "slug" => "profile.show",
                        "comment" => "Permission to view user profiles",
                        "permissions" => [
                            [
                                "name" => "basic_info",
                                "slug" => "profile.show.basic_info",
                                "comment" => "Permission to view basic profile information",
                                "permissions" => [
                                    [
                                        "name" => "personal_details",
                                        "slug" => "profile.show.basic_info.personal_details",
                                        "comment" => "Permission to view personal details"
                                    ],
                                    [
                                        "name" => "contact_details",
                                        "slug" => "profile.show.basic_info.contact_details",
                                        "comment" => "Permission to view contact details"
                                    ],
                                ]
                            ],
                            [
                                "name" => "photo",
                                "slug" => "profile.show.photo",
                                "comment" => "Permission to view profile photos"
                            ],
                        ]
                    ],
                    [
                        "name" => "Edit",
                        "slug" => "profile.edit",
                        "comment" => "Permission to update user profiles",
                        "permissions" => [
                            [
                                "name" => "basic_info",
                                "slug" => "profile.edit.basic_info",
                                "comment" => "Permission to update basic profile information"
                            ],
                            [
                                "name" => "photo",
                                "slug" => "profile.edit.photo",
                                "comment" => "Permission to update profile photos"
                            ],
                        ]
                    ],
                ]
            ],

            [
                "name" => "dashboard.show",
                "slug" => "dashboard",
                "comment" => "dashboard related Info",
            ],

            [
                "name" => "user-payments-request.view",
                "slug" => "user-payments-request",
                "comment" => "user payments request related Info",

            ],

            [
                "name" => "admin-payments-request.view",
                "slug" => "user-payments-request",
                "comment" => "admin payments  related Info",

            ],
            [
                "name" => "message.view",
                "slug" => "message",
                "comment" => "message  related Info",

            ],
            [
                "name" => "all-data.view",
                "slug" => "message",
                "comment" => "message  related Info",

            ],
            [
                "name" => "application-from.create",
                "slug" => "application-from",
                "comment" => "message  related Info",

            ],






        ];
    }

    public function getPermissionSlugs()
    {
        $permissions = $this->permissionsList();
        $slugs = [];

        foreach ($permissions as $module) {
            $slugs[] = $module['slug'];

            if (isset($module['permissions'])) {
                $slugs = array_merge($slugs, $this->getNestedPermissionSlugs($module['permissions']));
            }
        }

        return $slugs;
    }

    private function getNestedPermissionSlugs($permissions)
    {
        $slugs = [];

        foreach ($permissions as $permission) {
            $slugs[] = $permission['slug'];

            if (isset($permission['permissions'])) {
                $slugs = array_merge($slugs, $this->getNestedPermissionSlugs($permission['permissions']));
            }
        }

        return $slugs;
    }

}
