<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartonTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $cartonTypes = [
            // 02 — Slotted Boxes
            ['standard_code' => '0200', 'name' => 'Half-Slotted Container (HSC)'],
            ['standard_code' => '0201', 'name' => 'Regular Slotted Container (RSC)'],
            ['standard_code' => '0202', 'name' => 'RSC with Overlap Slotted Bottom'],
            ['standard_code' => '0203', 'name' => 'Full Overlap Slotted Container (FOL)'],
            ['standard_code' => '0204', 'name' => 'Overlap Slotted Container'],
            ['standard_code' => '0205', 'name' => 'Center Special Slotted Container'],
            ['standard_code' => '0206', 'name' => 'Center Special Overlap Slotted Container'],
            ['standard_code' => '0207', 'name' => 'Reverse Tuck End Slotted Box'],
            ['standard_code' => '0208', 'name' => 'Partial Overlap Slotted Container'],
            ['standard_code' => '0209', 'name' => 'Double Cover Box'],

            // 03 — Telescope Boxes
            ['standard_code' => '0300', 'name' => 'Telescope Box (generic)'],
            ['standard_code' => '0301', 'name' => 'Full Telescope Design Style Box'],
            ['standard_code' => '0302', 'name' => 'Double Cover Box with Telescopic Lid'],
            ['standard_code' => '0303', 'name' => 'Telescopic Boxes, variations'],

            // 04 — Folder / Wrap Boxes
            ['standard_code' => '0400', 'name' => 'Folder Type Box (generic)'],
            ['standard_code' => '0401', 'name' => 'One-Piece Folder (OPF)'],
            ['standard_code' => '0403', 'name' => 'Five-Panel Folder (FPF)'],
            ['standard_code' => '0404', 'name' => 'Wrap-Around Blank'],
            ['standard_code' => '0405', 'name' => 'Flat-Container Tube'],
            ['standard_code' => '0406', 'name' => 'Roll-End Tray'],
            ['standard_code' => '0409', 'name' => 'Folder with Connected Lid'],

            // 05 — Slide Boxes
            ['standard_code' => '0500', 'name' => 'Slide Box (generic)'],
            ['standard_code' => '0501', 'name' => 'Sleeve + Tray Style'],
            ['standard_code' => '0502', 'name' => 'Sleeve + Sleeve (sliding)'],
            ['standard_code' => '0503', 'name' => 'Multi-part Slide Boxes'],

            // 06 — Rigid (Set-Up) Boxes
            ['standard_code' => '0600', 'name' => 'Rigid Box (generic)'],
            ['standard_code' => '0601', 'name' => 'Pre-glued Lid Box'],
            ['standard_code' => '0602', 'name' => 'Pre-glued Base Box'],
            ['standard_code' => '0603', 'name' => 'Pre-formed Folded Box'],

            // 07 — Ready-Glued Boxes
            ['standard_code' => '0700', 'name' => 'Ready-Glued Box (generic)'],
            ['standard_code' => '0701', 'name' => 'Automatic Bottom with RSC Top'],
            ['standard_code' => '0703', 'name' => 'Crash-Lock Bottom Box'],
            ['standard_code' => '0711', 'name' => 'Self-Erecting Tray'],
            ['standard_code' => '0713', 'name' => 'Self-Locking Tray'],

            // 09 — Interior Fitments
            ['standard_code' => '0900', 'name' => 'Interior Fitment (generic)'],
            ['standard_code' => '0901', 'name' => 'Pads'],
            ['standard_code' => '0902', 'name' => 'Dividers / Partitions'],
            ['standard_code' => '0904', 'name' => 'Die-Cut Cushion Inserts'],
            ['standard_code' => '0905', 'name' => 'Cross Pieces'],
        ];

        foreach ($cartonTypes as $type) {
            DB::table('carton_types')->updateOrInsert(
                ['standard_code' => $type['standard_code']],
                [
                    'name' => $type['name'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
