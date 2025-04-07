
# ICPL-web-app-for-SGCL

This laravel project is a web application for Sundarban Gas Company Limited. This will provide service for User/Admin auth, Manage the activities and run on [localhost:8000](http://localhost:8000)

| Configarations    | Version |
| ---      | ---       |
| Laravel | 11.x         |
| php | 8.3         |


### Install

Clone Git Repo

    git clone https://github.com/ipsita-soft/SGCL-APP.git

Install composer

    composer install

Or update composer

    composer update

create .env file

    cp .env.example .env

Generate Key

    php artisan key:generate

Migrate Database

    php artisan migrate

Seed Database

    php artisan db:seed

JWT Secret Token

    php artisan jwt:secret

To access your Laravel Application visit

    localhost:8000

### Project Rules For Dev

If The Model Used For Individual CRUD Then Use This Command Below

    php artisan make:model ExampleModel -a


0. Every Time Write Proper Seeder
1. Use Soft Delete
2. Use Cascade On Delete For Foreign Key
3. Use Index For Foreign Key
4. Use Storage Location For Uploaded Files & Don't Use Public Folder
5. Use MeaningFul Name For Class,Folder,Model,Table,RouteName,Relation Name
6. Use Dependency Injection/IOC
7. Use Service Based Class [ Service Pattern ]
8. Write Scope For Reusable Query
9. Write TestCase For Every End Point
10. Use Custom Request Class For Validation
11. Write Custom Rule For Reusable Rule/Validation
12. Use Resource/Collection For Api Response
13. List Response Should Be Paginated
14. Email Should Be Markdown & Queued
15. Use $gurded In Model
16. Eger Loading & Select For Query Efficiency
17. Dont Push Main Branch Without Permission
18. Write Meaningful Commit
19. Every DB Action should be used DB::beginTransaction();


## Contributors

We'd like to thank the following individuals for their contributions to this project:

- [Arun Kumar](https://github.com/arunkroy) - Initiated this project.

- [Md. Siddiqul Islam labib](https://github.com/csesiddiqul) - Software Engineer this project.
- [Apurbo Roy](https://github.com/apurbocse) - Software Engineer In This Project.


