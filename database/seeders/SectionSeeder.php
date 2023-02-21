<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $sections = [
            [
                'name'=> 'G7-AFFLUENCE',
                'bldg_letter'=> 'CB-H ',
                'room_number'=> '201',
                'gradelevel_id'=> '1'
            ],
            [
                'name'=> 'G7-BENEVOLENCE',
                'bldg_letter'=> 'CB-H ',
                'room_number'=> '202',
                'gradelevel_id'=> '1'
            ],
            [
                'name'=> 'G7-CHARITY',
                'bldg_letter'=> 'CB-H ',
                'room_number'=> '203',
                'gradelevel_id'=> '1'
            ],
            [
                'name'=> 'G7-DILIGENCE',
                'bldg_letter'=> 'CB-H ',
                'room_number'=> '204',
                'gradelevel_id'=> '1'
            ],
            [
                'name'=> 'G7-EMPATHY',
                'bldg_letter'=> 'CB-H ',
                'room_number'=> '205',
                'gradelevel_id'=> '1'
            ],
            [
                'name'=> 'G7-FAITH',
                'bldg_letter'=> 'CB-I ',
                'room_number'=> '101',
                'gradelevel_id'=> '1'
            ],
            [
                'name'=> 'G7-GENEROSITY',
                'bldg_letter'=> 'CB-I ',
                'room_number'=> '102',
                'gradelevel_id'=> '1'
            ],
            [
                'name'=> 'G7-HONESTY',
                'bldg_letter'=> 'CB-I ',
                'room_number'=> '103',
                'gradelevel_id'=> '1'
            ],
            [
                'name'=> 'G7-INTEGRITY',
                'bldg_letter'=> 'CB-I ',
                'room_number'=> '104',
                'gradelevel_id'=> '1'
            ],
            [
                'name'=> 'G7-JUSTICE',
                'bldg_letter'=> 'CB-I ',
                'room_number'=> '105',
                'gradelevel_id'=> '1'
            ],
            [
                'name'=> 'G7-KINDNESS',
                'bldg_letter'=> 'CB-I ',
                'room_number'=> '201',
                'gradelevel_id'=> '1'
            ],
            [
                'name'=> 'G7-LOYALTY',
                'bldg_letter'=> 'CB-I ',
                'room_number'=> '202',
                'gradelevel_id'=> '1'
            ],
            [
                'name'=> 'G7-MODESTY',
                'bldg_letter'=> 'CB-I ',
                'room_number'=> '203',
                'gradelevel_id'=> '1'
            ],
            [
                'name'=> 'G7-NEATNESS',
                'bldg_letter'=> 'CB-I ',
                'room_number'=> '204',
                'gradelevel_id'=> '1'
            ],
            [
                'name'=> 'G7-OPTIMISM',
                'bldg_letter'=> 'CB-I ',
                'room_number'=> '205',
                'gradelevel_id'=> '1'
            ],
            [
                'name'=> 'G7-PEACE',
                'bldg_letter'=> 'CB-J ',
                'room_number'=> '202',
                'gradelevel_id'=> '1'
            ],
            [
                'name'=> 'G7-QUALITY',
                'bldg_letter'=> 'CB-J ',
                'room_number'=> '203',
                'gradelevel_id'=> '1'
            ],
            [
                'name'=> 'G7-RESILIENCY',
                'bldg_letter'=> 'CB-J ',
                'room_number'=> '204',
                'gradelevel_id'=> '1'
            ],
            [
                'name'=> 'G7-SERENITY',
                'bldg_letter'=> 'CB-J ',
                'room_number'=> '205',
                'gradelevel_id'=> '1'
            ],
            [
                'name'=> 'G8-AMETHYST',
                'bldg_letter'=> 'CB-F ',
                'room_number'=> '104',
                'gradelevel_id'=> '2'
            ],
            [
                'name'=> 'G8-BERYL',
                'bldg_letter'=> 'CB-F ',
                'room_number'=> '105',
                'gradelevel_id'=> '2'
            ],
            [
                'name'=> 'G8-CITRINE',
                'bldg_letter'=> 'CB-F ',
                'room_number'=> '201',
                'gradelevel_id'=> '2'
            ],
            [
                'name'=> 'G8-DIAMOND',
                'bldg_letter'=> 'CB-F ',
                'room_number'=> '202',
                'gradelevel_id'=> '2'
            ],
            [
                'name'=> 'G8-EMERALD',
                'bldg_letter'=> 'CB-F ',
                'room_number'=> '203',
                'gradelevel_id'=> '2'
            ],
            [
                'name'=> 'G8-FLUORITE',
                'bldg_letter'=> 'CB-F ',
                'room_number'=> '204',
                'gradelevel_id'=> '2'
            ],
            [
                'name'=> 'G8-GARNET',
                'bldg_letter'=> 'CB-F ',
                'room_number'=> '205',
                'gradelevel_id'=> '2'
            ],
            [
                'name'=> 'G8-HEMATITE',
                'bldg_letter'=> 'CB-G ',
                'room_number'=> '101',
                'gradelevel_id'=> '2'
            ],
            [
                'name'=> 'G8-IOLITE',
                'bldg_letter'=> 'CB-G ',
                'room_number'=> '102',
                'gradelevel_id'=> '2'
            ],
            [
                'name'=> 'G8-JADE',
                'bldg_letter'=> 'CB-G ',
                'room_number'=> '103',
                'gradelevel_id'=> '2'
            ],
            [
                'name'=> 'G8-KYANITE',
                'bldg_letter'=> 'CB-G ',
                'room_number'=> '104',
                'gradelevel_id'=> '2'
            ],
            [
                'name'=> 'G8-LAZULITE',
                'bldg_letter'=> 'CB-G ',
                'room_number'=> '201',
                'gradelevel_id'=> '2'
            ],
            [
                'name'=> 'G8-MOONSTONE',
                'bldg_letter'=> 'CB-G ',
                'room_number'=> '202',
                'gradelevel_id'=> '2'
            ],
            [
                'name'=> 'G8-NEPHRITE',
                'bldg_letter'=> 'CB-G ',
                'room_number'=> '203',
                'gradelevel_id'=> '2'
            ],
            [
                'name'=> 'G8-OPAL',
                'bldg_letter'=> 'CB-G ',
                'room_number'=> '204',
                'gradelevel_id'=> '2'
            ],
            [
                'name'=> 'G8-PEARL',
                'bldg_letter'=> 'CB-G ',
                'room_number'=> '205',
                'gradelevel_id'=> '2'
            ],
            [
                'name'=> 'G8-QUARTZ',
                'bldg_letter'=> 'CB-H ',
                'room_number'=> '101',
                'gradelevel_id'=> '2'
            ],
            [
                'name'=> 'G8-RUBY',
                'bldg_letter'=> 'CB-H ',
                'room_number'=> '102',
                'gradelevel_id'=> '2'
            ],
            [
                'name'=> 'G8-SAPPHIRE',
                'bldg_letter'=> 'CB-H ',
                'room_number'=> '103',
                'gradelevel_id'=> '2'
            ],
            [
                'name'=> 'G8-TOPAZ',
                'bldg_letter'=> 'CB-H ',
                'room_number'=> '104',
                'gradelevel_id'=> '2'
            ],
            [
                'name'=> 'G9-ALMOND',
                'bldg_letter'=> 'CB-C ',
                'room_number'=> '204',
                'gradelevel_id'=> '3'
            ],
            [
                'name'=> 'G9-BREADNUT',
                'bldg_letter'=> 'CB-C ',
                'room_number'=> '205',
                'gradelevel_id'=> '3'
            ],
            [
                'name'=> 'G9-CYPRESS',
                'bldg_letter'=> 'CB-D ',
                'room_number'=> '101',
                'gradelevel_id'=> '3'
            ],
            [
                'name'=> 'G9-DAO',
                'bldg_letter'=> 'CB-D ',
                'room_number'=> '102',
                'gradelevel_id'=> '3'
            ],
            [
                'name'=> 'G9-EUCALYPTUS',
                'bldg_letter'=> 'CB-D ',
                'room_number'=> '103',
                'gradelevel_id'=> '3'
            ],
            [
                'name'=> 'G9-FOXGLOVE',
                'bldg_letter'=> 'CB-D ',
                'room_number'=> '104',
                'gradelevel_id'=> '3'
            ],
            [
                'name'=> 'G9-GUIJO',
                'bldg_letter'=> 'CB-D ',
                'room_number'=> '201',
                'gradelevel_id'=> '3'
            ],
            [
                'name'=> 'G9-HEMLOCK',
                'bldg_letter'=> 'CB-D ',
                'room_number'=> '202',
                'gradelevel_id'=> '3'
            ],
            [
                'name'=> 'G9-IRONWOOD',
                'bldg_letter'=> 'CB-D ',
                'room_number'=> '203',
                'gradelevel_id'=> '3'
            ],
            [
                'name'=> 'G9-JUNIPER',
                'bldg_letter'=> 'CB-D ',
                'room_number'=> '204',
                'gradelevel_id'=> '3'
            ],
            [
                'name'=> 'G9-KENTUCKY',
                'bldg_letter'=> 'CB-D',
                'room_number'=> '205',
                'gradelevel_id'=> '3'
            ],
            [
                'name'=> 'G9-LANCEWOOD',
                'bldg_letter'=> 'CB-E ',
                'room_number'=> '101',
                'gradelevel_id'=> '3'
            ],
            [
                'name'=> 'G9-MOLAVE',
                'bldg_letter'=> 'CB-E ',
                'room_number'=> '102',
                'gradelevel_id'=> '3'
            ],
            [
                'name'=> 'G9-NARRA',
                'bldg_letter'=> 'CB-E ',
                'room_number'=> '103',
                'gradelevel_id'=> '3'
            ],
            [
                'name'=> 'G9-OLIVE',
                'bldg_letter'=> 'CB-E ',
                'room_number'=> '104',
                'gradelevel_id'=> '3'
            ],
            [
                'name'=> 'G9-PINE',
                'bldg_letter'=> 'CB-E ',
                'room_number'=> '201',
                'gradelevel_id'=> '3'
            ],
            [
                'name'=> 'G9-QUIVER',
                'bldg_letter'=> 'CB-E ',
                'room_number'=> '202',
                'gradelevel_id'=> '3'
            ],
            [
                'name'=> 'G9-REDWOOD',
                'bldg_letter'=> 'CB-E ',
                'room_number'=> '203',
                'gradelevel_id'=> '3'
            ],
            [
                'name'=> 'G9-SPINDLE',
                'bldg_letter'=> 'CB-E ',
                'room_number'=> '204',
                'gradelevel_id'=> '3'
            ],
            [
                'name'=> 'G9-TALISAY',
                'bldg_letter'=> 'CB-E ',
                'room_number'=> '205',
                'gradelevel_id'=> '3'
            ],
            [
                'name'=> 'G9-ULMUS',
                'bldg_letter'=> 'CB-F ',
                'room_number'=> '101',
                'gradelevel_id'=> '3'
            ],
            [
                'name'=> 'G9-VITEX',
                'bldg_letter'=> 'CB-F ',
                'room_number'=> '102',
                'gradelevel_id'=> '3'
            ],
            [
                'name'=> 'G9-WALNUT',
                'bldg_letter'=> 'CB-F ',
                'room_number'=> '103',
                'gradelevel_id'=> '3'
            ],
            [
                'name'=> 'G10-ARCHIMEDEZ',
                'bldg_letter'=> 'CB-A ',
                'room_number'=> '101',
                'gradelevel_id'=> '4'
            ],
            [
                'name'=> 'G10-BABBAGE',
                'bldg_letter'=> 'CB-A ',
                'room_number'=> '102',
                'gradelevel_id'=> '4'
            ],
            [
                'name'=> 'G10-CELSIUS',
                'bldg_letter'=> 'CB-A ',
                'room_number'=> '103',
                'gradelevel_id'=> '4'
            ],
            [
                'name'=> 'G10-DESCARTES',
                'bldg_letter'=> 'CB-A ',
                'room_number'=> '104',
                'gradelevel_id'=> '4'
            ],
            [
                'name'=> 'G10-EINSTEIN',
                'bldg_letter'=> 'CB-A ',
                'room_number'=> '201',
                'gradelevel_id'=> '4'
            ],
            [
                'name'=> 'G10-FERMAT',
                'bldg_letter'=> 'CB-A ',
                'room_number'=> '202',
                'gradelevel_id'=> '4'
            ],
            [
                'name'=> 'G10-GAUSS',
                'bldg_letter'=> 'CB-A ',
                'room_number'=> '203',
                'gradelevel_id'=> '4'
            ],
            [
                'name'=> 'G10-HARVEY',
                'bldg_letter'=> 'CB-A ',
                'room_number'=> '204',
                'gradelevel_id'=> '4'
            ],
            [
                'name'=> 'G10-ISING',
                'bldg_letter'=> 'CB-A ',
                'room_number'=> '205',
                'gradelevel_id'=> '4'
            ],
            [
                'name'=> 'G10-JOULE',
                'bldg_letter'=> 'CB-B ',
                'room_number'=> '101',
                'gradelevel_id'=> '4'
            ],
            [
                'name'=> 'G10-KEPLER',
                'bldg_letter'=> 'CB-B ',
                'room_number'=> '102',
                'gradelevel_id'=> '4'
            ],
            [
                'name'=> 'G10-LAWRENCE',
                'bldg_letter'=> 'CB-B ',
                'room_number'=> '103',
                'gradelevel_id'=> '4'
            ],
            [
                'name'=> 'G10-MENDEL',
                'bldg_letter'=> 'CB-B ',
                'room_number'=> '104',
                'gradelevel_id'=> '4'
            ],
            [
                'name'=> 'G10-NEWTON',
                'bldg_letter'=> 'CB-B ',
                'room_number'=> '201',
                'gradelevel_id'=> '4'
            ],
            [
                'name'=> 'G10-OHM',
                'bldg_letter'=> 'CB-B ',
                'room_number'=> '202',
                'gradelevel_id'=> '4'
            ],
            [
                'name'=> 'G10-PASTEUR',
                'bldg_letter'=> 'CB-B ',
                'room_number'=> '203',
                'gradelevel_id'=> '4'
            ],
            [
                'name'=> 'G10-QUIMBY',
                'bldg_letter'=> 'CB-B ',
                'room_number'=> '204',
                'gradelevel_id'=> '4'
            ],
            [
                'name'=> 'G10-RIEMANN',
                'bldg_letter'=> 'CB-B ',
                'room_number'=> '205',
                'gradelevel_id'=> '4'
            ],
            [
                'name'=> 'G10-STENO',
                'bldg_letter'=> 'CB-C ',
                'room_number'=> '101',
                'gradelevel_id'=> '4'
            ],
            [
                'name'=> 'G10-TORRICELLI',
                'bldg_letter'=> 'CB-C ',
                'room_number'=> '102',
                'gradelevel_id'=> '4'
            ],
            [
                'name'=> 'G10-URSELL',
                'bldg_letter'=> 'CB-C ',
                'room_number'=> '103',
                'gradelevel_id'=> '4'
            ],
            [
                'name'=> 'G10-VENN',
                'bldg_letter'=> 'CB-C ',
                'room_number'=> '104',
                'gradelevel_id'=> '4'
            ],
            [
                'name'=> 'G10-WRIGHT',
                'bldg_letter'=> 'CB-C ',
                'room_number'=> '201',
                'gradelevel_id'=> '4'
            ],
            [
                'name'=> 'G10-XENOCRATES',
                'bldg_letter'=> 'CB-C ',
                'room_number'=> '202',
                'gradelevel_id'=> '4'
            ],
            [
                'name'=> 'G10-YOUNG',
                'bldg_letter'=> 'CB-C ',
                'room_number'=> '203',
                'gradelevel_id'=> '4'
            ]
        ];

        foreach ($sections as $section) {
            $existing = DB::table('sections')->where('name', $section['name'])->first();

            if (!$existing) {
                DB::table('sections')->insert($section);
            }
        }
    }
}
