<?php

namespace Database\Seeders;

use App\Models\Cast_member;
use App\Models\CastMember;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CastMemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        CastMember::factory(10)->create();
    }
}
