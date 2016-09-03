<?php

use Illuminate\Database\Seeder;

class LinksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data=[[
                'link_name' => "考研狗",
                'link_title' => "国内最好的考研社区",
                'link_url' => "http://www.kaoyandoge.com",
                'link_order' =>1,
        ],
        [
            'link_name' => "考研论坛",
            'link_title' => "考研狗",
            'link_url' => "http://www.kaoyandoge.com",
            'link_order' =>2,
        ]
        ];
        DB::table('links')->insert($data);
    }
}
