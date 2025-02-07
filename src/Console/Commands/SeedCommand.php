<?php

namespace Iqionly\MenuManagement\Console\Commands;

use Illuminate\Console\Command;
use Iqionly\MenuManagement\Models\MenuManagementListMenu;
use PHPUnit\TextUI\XmlConfiguration\MigrationException;

class SeedCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'menu-management:seed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Seed the menu management database tables';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Seeding the menu management database tables...');

        $countExistsData = MenuManagementListMenu::count();

        $table = new MenuManagementListMenu();

        if($countExistsData > 0) {
            throw new MigrationException('Data already exists in table ' . $table->getTable());
        }

        $now = date('Y-m-d H:i:s');

        $default_menus = [
            [
                'id' => 1,
                'parent_id' => null,
                'depth' => 0,
                'priority' => 1,
                'name' => 'Home',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 2,
                'parent_id' => null,
                'depth' => 0,
                'priority' => 2,
                'name' => 'Dashboard',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 3,
                'parent_id' => null,
                'depth' => 0,
                'priority' => 3,
                'name' => 'Orders',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 4,
                'parent_id' => null,
                'depth' => 0,
                'priority' => 4,
                'name' => 'Products',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 5,
                'parent_id' => null,
                'depth' => 0,
                'priority' => 5,
                'name' => 'Customers',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 6,
                'parent_id' => 2,
                'depth' => 1,
                'priority' => 6,
                'name' => 'Sales',
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'id' => 7,
                'parent_id' => 6,
                'depth' => 2,
                'priority' => 7,
                'name' => 'Administration',
                'created_at' => $now,
                'updated_at' => $now
            ]
        ];

        MenuManagementListMenu::insert($default_menus);

        $this->info('Menu management tables have been seeded successfully.');
    }
}