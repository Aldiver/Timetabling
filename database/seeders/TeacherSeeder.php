<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Teacher;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $teachers = [
            [
                'full_name' => 'DIAZ MARY GRACE',
                'last_name' => 'DIAZ',
                'first_name' => 'MARY GRACE',
                'middle_name' => 'R.',
                'specialization' => 'TLE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 1,'department_id' => 7]
            ],
            [
                'full_name' => 'ABEJERO JANET',
                'last_name' => 'ABEJERO',
                'first_name' => 'JANET',
                'middle_name' => 'V.',
                'specialization' => 'TLE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 1,'department_id' => 7]
            ],
            [
                'full_name' => 'PALTINGCA ANGIELYN',
                'last_name' => 'PALTINGCA',
                'first_name' => 'ANGIELYN',
                'middle_name' => 'T.',
                'specialization' => 'TLE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 1,'department_id' => 7]
            ],
            [
                'full_name' => 'TORIBIO CHRISTY',
                'last_name' => 'TORIBIO',
                'first_name' => 'CHRISTY',
                'middle_name' => 'L.',
                'specialization' => 'TLE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 1,'department_id' => 7]
            ],
            [
                'full_name' => 'HANSEN CRISTIA MAE',
                'last_name' => 'HANSEN',
                'first_name' => 'CRISTIA MAE',
                'middle_name' => 'E.',
                'specialization' => 'TLE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 1,'department_id' => 7]
            ],
            [
                'full_name' => 'GATAB JOEL',
                'last_name' => 'GATAB',
                'first_name' => 'JOEL',
                'middle_name' => 'B.',
                'specialization' => 'TLE',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 1,'department_id' => 7]
            ],
            [
                'full_name' => 'DEPOSITARIO MA. MAGDALENA',
                'last_name' => 'DEPOSITARIO',
                'first_name' => 'MA. MAGDALENA',
                'middle_name' => 'M.',
                'specialization' => 'TLE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 7]
            ],
            [
                'full_name' => 'TRAYA MARISSA',
                'last_name' => 'TRAYA',
                'first_name' => 'MARISSA',
                'middle_name' => 'B.',
                'specialization' => 'TLE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 7]
            ],
            [
                'full_name' => 'CHIONG MELYN',
                'last_name' => 'CHIONG',
                'first_name' => 'MELYN',
                'middle_name' => 'S.',
                'specialization' => 'TLE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 7]
            ],
            [
                'full_name' => 'APUADO EMY LOU',
                'last_name' => 'APUADO',
                'first_name' => 'EMY LOU',
                'middle_name' => 'J.',
                'specialization' => 'TLE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 7]
            ],
            [
                'full_name' => 'DELOS SANTOS OSCAR JR.',
                'last_name' => 'DELOS SANTOS',
                'first_name' => 'OSCAR JR.',
                'middle_name' => 'J.',
                'specialization' => 'TLE',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 2,'department_id' => 7]
            ],
            [
                'full_name' => 'TUALA NANCY',
                'last_name' => 'TUALA',
                'first_name' => 'NANCY',
                'middle_name' => 'T.',
                'specialization' => 'TLE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 7]
            ],
            [
                'full_name' => 'MALLAREZ JOFFREY',
                'last_name' => 'MALLAREZ',
                'first_name' => 'JOFFREY',
                'middle_name' => 'A.',
                'specialization' => 'TLE',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 2,'department_id' => 7]
            ],
            [
                'full_name' => 'HANSEN ANDREUS',
                'last_name' => 'HANSEN',
                'first_name' => 'ANDREUS',
                'middle_name' => 'J.',
                'specialization' => 'TLE',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 3,'department_id' => 7]
            ],
            [
                'full_name' => 'CALUYO NEILITA',
                'last_name' => 'CALUYO',
                'first_name' => 'NEILITA',
                'middle_name' => 'C.',
                'specialization' => 'TLE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 3,'department_id' => 7]
            ],
            [
                'full_name' => 'LUNA KELLY',
                'last_name' => 'LUNA',
                'first_name' => 'KELLY',
                'middle_name' => 'A.',
                'specialization' => 'TLE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 3,'department_id' => 7]
            ],
            [
                'full_name' => 'BORROMEO CIRIACA',
                'last_name' => 'BORROMEO',
                'first_name' => 'CIRIACA',
                'middle_name' => 'F.',
                'specialization' => 'TLE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 3,'department_id' => 7]
            ],
            [
                'full_name' => 'HICAR ERLYN',
                'last_name' => 'HICAR',
                'first_name' => 'ERLYN',
                'middle_name' => 'A.',
                'specialization' => 'TLE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 3,'department_id' => 7]
            ],
            [
                'full_name' => 'GASPAR MARITA',
                'last_name' => 'GASPAR',
                'first_name' => 'MARITA',
                'middle_name' => 'T.',
                'specialization' => 'TLE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 3,'department_id' => 7]
            ],
            [
                'full_name' => 'BONTIA REMEDIOS',
                'last_name' => 'BONTIA',
                'first_name' => 'REMEDIOS',
                'middle_name' => 'J.',
                'specialization' => 'TLE',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 3,'department_id' => 7]
            ],
            [
                'full_name' => 'MARQUEZ GILBERT',
                'last_name' => 'MARQUEZ',
                'first_name' => 'GILBERT',
                'middle_name' => 'G.',
                'specialization' => 'TLE',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 3,'department_id' => 7]
            ],
            [
                'full_name' => 'CLEMENTE ROBIN',
                'last_name' => 'CLEMENTE',
                'first_name' => 'ROBIN',
                'middle_name' => 'A.',
                'specialization' => 'TLE',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 3,'department_id' => 7]
            ],
            [
                'full_name' => 'TAHIL JULSALI',
                'last_name' => 'TAHIL',
                'first_name' => 'JULSALI',
                'middle_name' => 'J.',
                'specialization' => 'TLE',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 3,'department_id' => 7]
            ],
            [
                'full_name' => 'YTING CELSO',
                'last_name' => 'YTING',
                'first_name' => 'CELSO',
                'middle_name' => 'C.',
                'specialization' => 'TLE',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 3,'department_id' => 7]
            ],
            [
                'full_name' => 'QUILATON ARIEL',
                'last_name' => 'QUILATON',
                'first_name' => 'ARIEL',
                'middle_name' => 'P.',
                'specialization' => 'TLE',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 3,'department_id' => 7]
            ],
            [
                'full_name' => 'FORGOSA SALVADOR',
                'last_name' => 'FORGOSA',
                'first_name' => 'SALVADOR',
                'middle_name' => 'G.',
                'specialization' => 'TLE',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 4,'department_id' => 7]
            ],
            [
                'full_name' => 'MAGSUCANG ROWENA',
                'last_name' => 'MAGSUCANG',
                'first_name' => 'ROWENA',
                'middle_name' => 'R.',
                'specialization' => 'TLE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 7]
            ],
            [
                'full_name' => 'MAGNO LINALYN',
                'last_name' => 'MAGNO',
                'first_name' => 'LINALYN',
                'middle_name' => 'DR.',
                'specialization' => 'TLE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 7]
            ],
            [
                'full_name' => 'IGNACIO AMME',
                'last_name' => 'IGNACIO',
                'first_name' => 'AMME',
                'middle_name' => 'F.',
                'specialization' => 'TLE',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 4,'department_id' => 7]
            ],
            [
                'full_name' => 'MIRA SANDRA',
                'last_name' => 'MIRA',
                'first_name' => 'SANDRA',
                'middle_name' => 'J.',
                'specialization' => 'TLE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 7]
            ],
            [
                'full_name' => 'SANSON BERNADETH',
                'last_name' => 'SANSON',
                'first_name' => 'BERNADETH',
                'middle_name' => 'H.',
                'specialization' => 'TLE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 7]
            ],
            [
                'full_name' => 'AMAHOY EVELYN',
                'last_name' => 'AMAHOY',
                'first_name' => 'EVELYN',
                'middle_name' => 'P.',
                'specialization' => 'TLE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 7]
            ],
            [
                'full_name' => 'GREGORIO REGIN',
                'last_name' => 'GREGORIO',
                'first_name' => 'REGIN',
                'middle_name' => 'A.',
                'specialization' => 'TLE',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 4,'department_id' => 7]
            ],
            [
                'full_name' => 'BANAT NELMAR',
                'last_name' => 'BANAT',
                'first_name' => 'NELMAR',
                'middle_name' => 'C.',
                'specialization' => 'TLE',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 4,'department_id' => 7]
            ],
            [
                'full_name' => 'VENDIOLA SANDY',
                'last_name' => 'VENDIOLA',
                'first_name' => 'SANDY',
                'middle_name' => '',
                'specialization' => 'TLE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 7]
            ],
            [
                'full_name' => 'CALUYO BENY',
                'last_name' => 'CALUYO',
                'first_name' => 'BENY',
                'middle_name' => 'V.',
                'specialization' => 'TLE',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 4,'department_id' => 7]
            ],
            [
                'full_name' => 'MODILLAS RAYMOND',
                'last_name' => 'MODILLAS',
                'first_name' => 'RAYMOND',
                'middle_name' => 'P.',
                'specialization' => 'TLE',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 4,'department_id' => 7]
            ],
            [
                'full_name' => 'TRAYA AVELINO JR.',
                'last_name' => 'TRAYA',
                'first_name' => 'AVELINO JR.',
                'middle_name' => 'D.',
                'specialization' => 'TLE',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 4,'department_id' => 7]
            ],
            [
                'full_name' => 'BARING NOEL',
                'last_name' => 'BARING',
                'first_name' => 'NOEL',
                'middle_name' => 'W.',
                'specialization' => 'TLE',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 4,'department_id' => 7]
            ],
            [
                'full_name' => 'PANIZA STEPHANIE',
                'last_name' => 'PANIZA',
                'first_name' => 'STEPHANIE',
                'middle_name' => 'V.',
                'specialization' => 'ESP',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 1,'department_id' => 8]
            ],
            [
                'full_name' => 'AUTENTICO ANGELI RYLL',
                'last_name' => 'AUTENTICO',
                'first_name' => 'ANGELI RYLL',
                'middle_name' => 'C.',
                'specialization' => 'ESP',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 1,'department_id' => 8]
            ],
            [
                'full_name' => 'SEMACIO KARL',
                'last_name' => 'SEMACIO',
                'first_name' => 'KARL',
                'middle_name' => 'A.',
                'specialization' => 'ESP',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 1,'department_id' => 8]
            ],
            [
                'full_name' => 'BARREDO EARLI CRIS',
                'last_name' => 'BARREDO',
                'first_name' => 'EARLI CRIS',
                'middle_name' => 'G.',
                'specialization' => 'ESP',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 1,'department_id' => 8]
            ],
            [
                'full_name' => 'NALZARO MA. ELOISA',
                'last_name' => 'NALZARO',
                'first_name' => 'MA. ELOISA',
                'middle_name' => 'P.',
                'specialization' => 'ESP',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 8]
            ],
            [
                'full_name' => 'TAMAYO RUTHZEL',
                'last_name' => 'TAMAYO',
                'first_name' => 'RUTHZEL',
                'middle_name' => 'F.',
                'specialization' => 'ESP',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 8]
            ],
            [
                'full_name' => 'MALANA SHERWINA',
                'last_name' => 'MALANA',
                'first_name' => 'SHERWINA',
                'middle_name' => 'E.',
                'specialization' => 'ESP',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 8]
            ],
            [
                'full_name' => 'SALVADOR MARINA',
                'last_name' => 'SALVADOR',
                'first_name' => 'MARINA',
                'middle_name' => 'B.',
                'specialization' => 'ESP',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 3,'department_id' => 8]
            ],
            [
                'full_name' => 'RAFOLS VIVIAN',
                'last_name' => 'RAFOLS',
                'first_name' => 'VIVIAN',
                'middle_name' => 'F.',
                'specialization' => 'ESP',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 3,'department_id' => 8]
            ],
            [
                'full_name' => 'AMSID JOSEPHINE',
                'last_name' => 'AMSID',
                'first_name' => 'JOSEPHINE',
                'middle_name' => 'C.',
                'specialization' => 'ESP',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 3,'department_id' => 8]
            ],
            [
                'full_name' => 'MELO VALORREE',
                'last_name' => 'MELO',
                'first_name' => 'VALORREE',
                'middle_name' => 'M.',
                'specialization' => 'ESP',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 3,'department_id' => 8]
            ],
            [
                'full_name' => 'SILAO BERNADETTE',
                'last_name' => 'SILAO',
                'first_name' => 'BERNADETTE',
                'middle_name' => 'R.',
                'specialization' => 'ESP',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 8]
            ],
            [
                'full_name' => 'ARANAL REV. EMIL',
                'last_name' => 'ARANAL',
                'first_name' => 'REV. EMIL',
                'middle_name' => 'F.',
                'specialization' => 'ESP',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 4,'department_id' => 8]
            ],
            [
                'full_name' => 'AMSID VICTOR',
                'last_name' => 'AMSID',
                'first_name' => 'VICTOR',
                'middle_name' => 'O.',
                'specialization' => 'ESP',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 4,'department_id' => 8]
            ],
            [
                'full_name' => 'TIANES CARLBEN',
                'last_name' => 'TIANES',
                'first_name' => 'CARLBEN',
                'middle_name' => 'T.',
                'specialization' => 'ESP',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 4,'department_id' => 8]
            ],
            [
                'full_name' => 'LAGUARDIA AZUSENA',
                'last_name' => 'LAGUARDIA',
                'first_name' => 'AZUSENA',
                'middle_name' => 'D.',
                'specialization' => 'ARPAN',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 1,'department_id' => 6]
            ],
            [
                'full_name' => 'REYES TERESITA',
                'last_name' => 'REYES',
                'first_name' => 'TERESITA',
                'middle_name' => 'S.',
                'specialization' => 'ARPAN',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 1,'department_id' => 6]
            ],
            [
                'full_name' => 'CAÑEGA SHIELA',
                'last_name' => 'CAÑEGA',
                'first_name' => 'SHIELA',
                'middle_name' => 'D.',
                'specialization' => 'ARPAN',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 1,'department_id' => 6]
            ],
            [
                'full_name' => 'VICENTE MARY JANE',
                'last_name' => 'VICENTE',
                'first_name' => 'MARY JANE',
                'middle_name' => 'F.',
                'specialization' => 'ARPAN',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 1,'department_id' => 6]
            ],
            [
                'full_name' => 'TORIBIO MERRIAM',
                'last_name' => 'TORIBIO',
                'first_name' => 'MERRIAM',
                'middle_name' => 'A.',
                'specialization' => 'ARPAN',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 1,'department_id' => 6]
            ],
            [
                'full_name' => 'TOGONON ANDREW',
                'last_name' => 'TOGONON',
                'first_name' => 'ANDREW',
                'middle_name' => 'C.',
                'specialization' => 'ARPAN',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 1,'department_id' => 6]
            ],
            [
                'full_name' => 'CASABA VIVIAN',
                'last_name' => 'CASABA',
                'first_name' => 'VIVIAN',
                'middle_name' => 'I.',
                'specialization' => 'ARPAN',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 6]
            ],
            [
                'full_name' => 'BUCOY LORENA',
                'last_name' => 'BUCOY',
                'first_name' => 'LORENA',
                'middle_name' => 'L.',
                'specialization' => 'ARPAN',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 6]
            ],
            [
                'full_name' => 'MENDOZA ANNAMAE',
                'last_name' => 'MENDOZA',
                'first_name' => 'ANNAMAE',
                'middle_name' => 'D.',
                'specialization' => 'ARPAN',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 6]
            ],
            [
                'full_name' => 'MACASINAG AMY',
                'last_name' => 'MACASINAG',
                'first_name' => 'AMY',
                'middle_name' => 'M.',
                'specialization' => 'ARPAN',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 6]
            ],
            [
                'full_name' => 'SORIA LEONILO',
                'last_name' => 'SORIA',
                'first_name' => 'LEONILO',
                'middle_name' => 'A.',
                'specialization' => 'ARPAN',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 2,'department_id' => 6]
            ],
            [
                'full_name' => 'ENCARNADA GARRY',
                'last_name' => 'ENCARNADA',
                'first_name' => 'GARRY',
                'middle_name' => 'G.',
                'specialization' => 'ARPAN',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 3,'department_id' => 6]
            ],
            [
                'full_name' => 'CABILES DAISY',
                'last_name' => 'CABILES',
                'first_name' => 'DAISY',
                'middle_name' => 'M.',
                'specialization' => 'ARPAN',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 3,'department_id' => 6]
            ],
            [
                'full_name' => 'LLANDA GUIADEE',
                'last_name' => 'LLANDA',
                'first_name' => 'GUIADEE',
                'middle_name' => 'A.',
                'specialization' => 'ARPAN',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 3,'department_id' => 6]
            ],
            [
                'full_name' => 'POLLISCO LUCIE',
                'last_name' => 'POLLISCO',
                'first_name' => 'LUCIE',
                'middle_name' => 'M.',
                'specialization' => 'ARPAN',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 3,'department_id' => 6]
            ],
            [
                'full_name' => 'PALTINGCA RIZALITO',
                'last_name' => 'PALTINGCA',
                'first_name' => 'RIZALITO',
                'middle_name' => 'L.',
                'specialization' => 'ARPAN',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 3,'department_id' => 6]
            ],
            [
                'full_name' => 'NALZARO JESSE',
                'last_name' => 'NALZARO',
                'first_name' => 'JESSE',
                'middle_name' => 'J.',
                'specialization' => 'ARPAN',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 4,'department_id' => 6]
            ],
            [
                'full_name' => 'ANGELES CATHERINE',
                'last_name' => 'ANGELES',
                'first_name' => 'CATHERINE',
                'middle_name' => 'U.',
                'specialization' => 'ARPAN',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 6]
            ],
            [
                'full_name' => 'ANTONIO BERNADETTE',
                'last_name' => 'ANTONIO',
                'first_name' => 'BERNADETTE',
                'middle_name' => 'A.',
                'specialization' => 'ARPAN',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 6]
            ],
            [
                'full_name' => 'ROMERO IRENE',
                'last_name' => 'ROMERO',
                'first_name' => 'IRENE',
                'middle_name' => '',
                'specialization' => 'ARPAN',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 6]
            ],
            [
                'full_name' => 'SINORO idENFORT',
                'last_name' => 'SINORO',
                'first_name' => 'idENFORT',
                'middle_name' => 'C.',
                'specialization' => 'ARPAN',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 4,'department_id' => 6]
            ],
            [
                'full_name' => 'ECOY MARIA CORAZON',
                'last_name' => 'ECOY',
                'first_name' => 'MARIA CORAZON',
                'middle_name' => 'S.',
                'specialization' => 'ARPAN',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 6]
            ],
            [
                'full_name' => 'CANDA JOYCE',
                'last_name' => 'CANDA',
                'first_name' => 'JOYCE',
                'middle_name' => 'D.',
                'specialization' => 'MAPEH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 1,'department_id' => 5]
            ],
            [
                'full_name' => 'BONGON ELAINE',
                'last_name' => 'BONGON',
                'first_name' => 'ELAINE',
                'middle_name' => 'C.',
                'specialization' => 'MAPEH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 1,'department_id' => 5]
            ],
            [
                'full_name' => 'CALUYO KEARZTIE THERESE',
                'last_name' => 'CALUYO',
                'first_name' => 'KEARZTIE THERESE',
                'middle_name' => 'C.',
                'specialization' => 'MAPEH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 1,'department_id' => 5]
            ],
            [
                'full_name' => 'DELOS REYES JENNY LYN',
                'last_name' => 'DELOS REYES',
                'first_name' => 'JENNY LYN',
                'middle_name' => 'G.',
                'specialization' => 'MAPEH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 1,'department_id' => 5]
            ],
            [
                'full_name' => 'TORRES AMALIA',
                'last_name' => 'TORRES',
                'first_name' => 'AMALIA',
                'middle_name' => 'T.',
                'specialization' => 'MAPEH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 1,'department_id' => 5]
            ],
            [
                'full_name' => 'TAN KATHERINE',
                'last_name' => 'TAN',
                'first_name' => 'KATHERINE',
                'middle_name' => 'S.',
                'specialization' => 'MAPEH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 5]
            ],
            [
                'full_name' => 'BOLIMA JANICE',
                'last_name' => 'BOLIMA',
                'first_name' => 'JANICE',
                'middle_name' => 'B.',
                'specialization' => 'MAPEH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 5]
            ],
            [
                'full_name' => 'REMILLETE MARIA SUSANA',
                'last_name' => 'REMILLETE',
                'first_name' => 'MARIA SUSANA',
                'middle_name' => 'S.',
                'specialization' => 'MAPEH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 5]
            ],
            [
                'full_name' => 'SAQUIBAL EVELYN',
                'last_name' => 'SAQUIBAL',
                'first_name' => 'EVELYN',
                'middle_name' => 'D.',
                'specialization' => 'MAPEH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 5]
            ],
            [
                'full_name' => 'SOLIS NICK ODILON',
                'last_name' => 'SOLIS',
                'first_name' => 'NICK ODILON',
                'middle_name' => 'D.',
                'specialization' => 'MAPEH',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 2,'department_id' => 5]
            ],
            [
                'full_name' => 'BUE FRANICEL',
                'last_name' => 'BUE',
                'first_name' => 'FRANICEL',
                'middle_name' => 'T.',
                'specialization' => 'MAPEH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 5]
            ],
            [
                'full_name' => 'MANUEL GENARO',
                'last_name' => 'MANUEL',
                'first_name' => 'GENARO',
                'middle_name' => 'A.',
                'specialization' => 'MAPEH',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 3,'department_id' => 5]
            ],
            [
                'full_name' => 'ABAD JOEVELYN',
                'last_name' => 'ABAD',
                'first_name' => 'JOEVELYN',
                'middle_name' => 'T.',
                'specialization' => 'MAPEH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 3,'department_id' => 5]
            ],
            [
                'full_name' => 'LANDINGIN MARIA JEANETTE',
                'last_name' => 'LANDINGIN',
                'first_name' => 'MARIA JEANETTE',
                'middle_name' => 'B.',
                'specialization' => 'MAPEH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 3,'department_id' => 5]
            ],
            [
                'full_name' => 'CABASAG JACQUELINE',
                'last_name' => 'CABASAG',
                'first_name' => 'JACQUELINE',
                'middle_name' => 'D.',
                'specialization' => 'MAPEH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 3,'department_id' => 5]
            ],
            [
                'full_name' => 'PADA JOSE',
                'last_name' => 'PADA',
                'first_name' => 'JOSE',
                'middle_name' => 'L.',
                'specialization' => 'MAPEH',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 3,'department_id' => 5]
            ],
            [
                'full_name' => 'FRANCISCO RANDY',
                'last_name' => 'FRANCISCO',
                'first_name' => 'RANDY',
                'middle_name' => 'R.',
                'specialization' => 'MAPEH',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 3,'department_id' => 5]
            ],
            [
                'full_name' => 'TOLENTINO MARK ANTHONY',
                'last_name' => 'TOLENTINO',
                'first_name' => 'MARK ANTHONY',
                'middle_name' => 'G.',
                'specialization' => 'MAPEH',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 4,'department_id' => 5]
            ],
            [
                'full_name' => 'SOLATORIO MARLON',
                'last_name' => 'SOLATORIO',
                'first_name' => 'MARLON',
                'middle_name' => 'G.',
                'specialization' => 'MAPEH',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 4,'department_id' => 5]
            ],
            [
                'full_name' => 'MARZAN RACHEL',
                'last_name' => 'MARZAN',
                'first_name' => 'RACHEL',
                'middle_name' => 'A.',
                'specialization' => 'MAPEH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 5]
            ],
            [
                'full_name' => 'RANA PILAR',
                'last_name' => 'RANA',
                'first_name' => 'PILAR',
                'middle_name' => 'L.',
                'specialization' => 'MAPEH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 5]
            ],
            [
                'full_name' => 'SHARIF AMRAN',
                'last_name' => 'SHARIF',
                'first_name' => 'AMRAN',
                'middle_name' => 'L.',
                'specialization' => 'MAPEH',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 4,'department_id' => 5]
            ],
            [
                'full_name' => 'QUILALA WHEANN',
                'last_name' => 'QUILALA',
                'first_name' => 'WHEANN',
                'middle_name' => 'L.',
                'specialization' => 'MAPEH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 5]
            ],
            [
                'full_name' => 'MANUEL LAILA',
                'last_name' => 'MANUEL',
                'first_name' => 'LAILA',
                'middle_name' => 'G.',
                'specialization' => 'FIL',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 1,'department_id' => 4]
            ],
            [
                'full_name' => 'AZUELO ROSA PILAR',
                'last_name' => 'AZUELO',
                'first_name' => 'ROSA PILAR',
                'middle_name' => 'S.',
                'specialization' => 'FIL',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 1,'department_id' => 4]
            ],
            [
                'full_name' => 'AHADAIN MARLENE',
                'last_name' => 'AHADAIN',
                'first_name' => 'MARLENE',
                'middle_name' => 'J.',
                'specialization' => 'FIL',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 1,'department_id' => 4]
            ],
            [
                'full_name' => 'CAZAR RONALYN',
                'last_name' => 'CAZAR',
                'first_name' => 'RONALYN',
                'middle_name' => 'G.',
                'specialization' => 'FIL',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 1,'department_id' => 4]
            ],
            [
                'full_name' => 'ELPEDES MELVIC',
                'last_name' => 'ELPEDES',
                'first_name' => 'MELVIC',
                'middle_name' => 'C.',
                'specialization' => 'FIL',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 2,'department_id' => 4]
            ],
            [
                'full_name' => 'GRAPA EDIANE',
                'last_name' => 'GRAPA',
                'first_name' => 'EDIANE',
                'middle_name' => 'A.',
                'specialization' => 'FIL',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 4]
            ],
            [
                'full_name' => 'SOLIS JOY',
                'last_name' => 'SOLIS',
                'first_name' => 'JOY',
                'middle_name' => 'F.',
                'specialization' => 'FIL',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 4]
            ],
            [
                'full_name' => 'ROBIN ROZELLA JALLET',
                'last_name' => 'ROBIN',
                'first_name' => 'ROZELLA JALLET',
                'middle_name' => 'O.',
                'specialization' => 'FIL',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 4]
            ],
            [
                'full_name' => 'GUINTO CLARIBEL',
                'last_name' => 'GUINTO',
                'first_name' => 'CLARIBEL',
                'middle_name' => 'C.',
                'specialization' => 'FIL',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 4]
            ],
            [
                'full_name' => 'BUSTAMANTE MA. LEAH',
                'last_name' => 'BUSTAMANTE',
                'first_name' => 'MA. LEAH',
                'middle_name' => 'R.',
                'specialization' => 'FIL',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 3,'department_id' => 4]
            ],
            [
                'full_name' => 'MANUEL JILDA',
                'last_name' => 'MANUEL',
                'first_name' => 'JILDA',
                'middle_name' => 'M.',
                'specialization' => 'FIL',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 3,'department_id' => 4]
            ],
            [
                'full_name' => 'MARQUEZ CONSTANCIA',
                'last_name' => 'MARQUEZ',
                'first_name' => 'CONSTANCIA',
                'middle_name' => 'G.',
                'specialization' => 'FIL',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 3,'department_id' => 4]
            ],
            [
                'full_name' => 'CARTAGENAS MICHELLE',
                'last_name' => 'CARTAGENAS',
                'first_name' => 'MICHELLE',
                'middle_name' => 'I.',
                'specialization' => 'FIL',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 3,'department_id' => 4]
            ],
            [
                'full_name' => 'VILLAESCUSA VIOLETA',
                'last_name' => 'VILLAESCUSA',
                'first_name' => 'VIOLETA',
                'middle_name' => 'G.',
                'specialization' => 'FIL',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 3,'department_id' => 4]
            ],
            [
                'full_name' => 'ACEDO MARICHU',
                'last_name' => 'ACEDO',
                'first_name' => 'MARICHU',
                'middle_name' => 'A.',
                'specialization' => 'FIL',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 4]
            ],
            [
                'full_name' => 'ANNI FRADZKAN',
                'last_name' => 'ANNI',
                'first_name' => 'FRADZKAN',
                'middle_name' => 'A.',
                'specialization' => 'FIL',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 4,'department_id' => 4]
            ],
            [
                'full_name' => 'CALUNOD JUDELYN',
                'last_name' => 'CALUNOD',
                'first_name' => 'JUDELYN',
                'middle_name' => 'C.',
                'specialization' => 'FIL',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 4]
            ],
            [
                'full_name' => 'DELIMA EMILY',
                'last_name' => 'DELIMA',
                'first_name' => 'EMILY',
                'middle_name' => 'E.',
                'specialization' => 'FIL',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 4]
            ],
            [
                'full_name' => 'FILOTEO ELSIE',
                'last_name' => 'FILOTEO',
                'first_name' => 'ELSIE',
                'middle_name' => 'A.',
                'specialization' => 'FIL',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 4]
            ],
            [
                'full_name' => 'SILVA HAZEL',
                'last_name' => 'SILVA',
                'first_name' => 'HAZEL',
                'middle_name' => 'V.',
                'specialization' => 'FIL',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 4]
            ],
            [
                'full_name' => 'MONTEALEGRE FEBERTO',
                'last_name' => 'MONTEALEGRE',
                'first_name' => 'FEBERTO',
                'middle_name' => 'L.',
                'specialization' => 'MATH',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 1,'department_id' => 3]
            ],
            [
                'full_name' => 'BALDOMERO ABDURAUF',
                'last_name' => 'BALDOMERO',
                'first_name' => 'ABDURAUF',
                'middle_name' => 'J.',
                'specialization' => 'MATH',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 1,'department_id' => 3]
            ],
            [
                'full_name' => 'PAUSAL JULIET',
                'last_name' => 'PAUSAL',
                'first_name' => 'JULIET',
                'middle_name' => 'L.',
                'specialization' => 'MATH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 1,'department_id' => 3]
            ],
            [
                'full_name' => 'CRUZ ASUNCION',
                'last_name' => 'CRUZ',
                'first_name' => 'ASUNCION',
                'middle_name' => 'B.',
                'specialization' => 'MATH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 1,'department_id' => 3]
            ],
            [
                'full_name' => 'MARTIN MA. EDNA',
                'last_name' => 'MARTIN',
                'first_name' => 'MA. EDNA',
                'middle_name' => 'G.',
                'specialization' => 'MATH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 1,'department_id' => 3]
            ],
            [
                'full_name' => 'LUTIAN CORAZON',
                'last_name' => 'LUTIAN',
                'first_name' => 'CORAZON',
                'middle_name' => 'M.',
                'specialization' => 'MATH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 3]
            ],
            [
                'full_name' => 'JASIM GIZELLE NARCITTA',
                'last_name' => 'JASIM',
                'first_name' => 'GIZELLE NARCITTA',
                'middle_name' => 'S.',
                'specialization' => 'MATH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 3]
            ],
            [
                'full_name' => 'CALUYO MERRY JOY',
                'last_name' => 'CALUYO',
                'first_name' => 'MERRY JOY',
                'middle_name' => 'L.',
                'specialization' => 'MATH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 3]
            ],
            [
                'full_name' => 'ANTONIO JOHNNY',
                'last_name' => 'ANTONIO',
                'first_name' => 'JOHNNY',
                'middle_name' => 'C.',
                'specialization' => 'MATH',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 2,'department_id' => 3]
            ],
            [
                'full_name' => 'FERNANDEZ JING-JING',
                'last_name' => 'FERNANDEZ',
                'first_name' => 'JING-JING',
                'middle_name' => 'M.',
                'specialization' => 'MATH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 3]
            ],
            [
                'full_name' => 'CALUYO GENARO, JR.',
                'last_name' => 'CALUYO',
                'first_name' => 'GENARO, JR.',
                'middle_name' => 'V.',
                'specialization' => 'MATH',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 3,'department_id' => 3]
            ],
            [
                'full_name' => 'TINDOC EVELYN',
                'last_name' => 'TINDOC',
                'first_name' => 'EVELYN',
                'middle_name' => 'E.',
                'specialization' => 'MATH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 3,'department_id' => 3]
            ],
            [
                'full_name' => 'ANTONIO AIDA',
                'last_name' => 'ANTONIO',
                'first_name' => 'AIDA',
                'middle_name' => 'F.',
                'specialization' => 'MATH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 3,'department_id' => 3]
            ],
            [
                'full_name' => 'VELASCO JOSIELYN',
                'last_name' => 'VELASCO',
                'first_name' => 'JOSIELYN',
                'middle_name' => 'M.',
                'specialization' => 'MATH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 3,'department_id' => 3]
            ],
            [
                'full_name' => 'PIÑERO ZORINA',
                'last_name' => 'PIÑERO',
                'first_name' => 'ZORINA',
                'middle_name' => 'F.',
                'specialization' => 'MATH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 3,'department_id' => 3]
            ],
            [
                'full_name' => 'BORRES ANTHONY',
                'last_name' => 'BORRES',
                'first_name' => 'ANTHONY',
                'middle_name' => 'E.',
                'specialization' => 'MATH',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 3,'department_id' => 3]
            ],
            [
                'full_name' => 'BUHAYAN CRIS',
                'last_name' => 'BUHAYAN',
                'first_name' => 'CRIS',
                'middle_name' => 'A.',
                'specialization' => 'MATH',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 4,'department_id' => 3]
            ],
            [
                'full_name' => 'PONCE OSITA',
                'last_name' => 'PONCE',
                'first_name' => 'OSITA',
                'middle_name' => 'A.',
                'specialization' => 'MATH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 3]
            ],
            [
                'full_name' => 'MACROHON MELVIN',
                'last_name' => 'MACROHON',
                'first_name' => 'MELVIN',
                'middle_name' => 'C.',
                'specialization' => 'MATH',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 4,'department_id' => 3]
            ],
            [
                'full_name' => 'Taide LADY MAY',
                'last_name' => 'Taide',
                'first_name' => 'LADY MAY',
                'middle_name' => 'C.',
                'specialization' => 'MATH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 3]
            ],
            [
                'full_name' => 'PERLADA GERWIN',
                'last_name' => 'PERLADA',
                'first_name' => 'GERWIN',
                'middle_name' => 'O.',
                'specialization' => 'MATH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 3]
            ],
            [
                'full_name' => 'BESIN ROQUE',
                'last_name' => 'BESIN',
                'first_name' => 'ROQUE',
                'middle_name' => 'L.',
                'specialization' => 'MATH',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 4,'department_id' => 3]
            ],
            [
                'full_name' => 'MOLI JESSICA',
                'last_name' => 'MOLI',
                'first_name' => 'JESSICA',
                'middle_name' => 'D.',
                'specialization' => 'MATH',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 4,'department_id' => 3]
            ],
            [
                'full_name' => 'GACETA JANE AUBREY',
                'last_name' => 'GACETA',
                'first_name' => 'JANE AUBREY',
                'middle_name' => 'D.',
                'specialization' => 'SCIENCE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 1,'department_id' => 2]
            ],
            [
                'full_name' => 'PAGOTAISIDRO MILDRED',
                'last_name' => 'PAGOTAISIDRO',
                'first_name' => 'MILDRED',
                'middle_name' => 'T.',
                'specialization' => 'SCIENCE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 1,'department_id' => 2]
            ],
            [
                'full_name' => 'WAHID AISA',
                'last_name' => 'WAHID',
                'first_name' => 'AISA',
                'middle_name' => 'A.',
                'specialization' => 'SCIENCE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 1,'department_id' => 2]
            ],
            [
                'full_name' => 'ALEJANDRO NERISSA',
                'last_name' => 'ALEJANDRO',
                'first_name' => 'NERISSA',
                'middle_name' => 'B.',
                'specialization' => 'SCIENCE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 1,'department_id' => 2]
            ],
            [
                'full_name' => 'DUMAGUING IRENE GRACE',
                'last_name' => 'DUMAGUING',
                'first_name' => 'IRENE GRACE',
                'middle_name' => 'E.',
                'specialization' => 'SCIENCE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 1,'department_id' => 2]
            ],
            [
                'full_name' => 'REMILLETE MA. LEONORA',
                'last_name' => 'REMILLETE',
                'first_name' => 'MA. LEONORA',
                'middle_name' => 'S.',
                'specialization' => 'SCIENCE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 1,'department_id' => 2]
            ],
            [
                'full_name' => 'TRUMATA LAMBERTO',
                'last_name' => 'TRUMATA',
                'first_name' => 'LAMBERTO',
                'middle_name' => 'C.',
                'specialization' => 'SCIENCE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 2]
            ],
            [
                'full_name' => 'MONGCOPA EDWIN',
                'last_name' => 'MONGCOPA',
                'first_name' => 'EDWIN',
                'middle_name' => 'A.',
                'specialization' => 'SCIENCE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 2]
            ],
            [
                'full_name' => 'ALVAREZ MARILYN',
                'last_name' => 'ALVAREZ',
                'first_name' => 'MARILYN',
                'middle_name' => 'D.',
                'specialization' => 'SCIENCE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 2]
            ],
            [
                'full_name' => 'SALCEDO ALANA',
                'last_name' => 'SALCEDO',
                'first_name' => 'ALANA',
                'middle_name' => 'P.',
                'specialization' => 'SCIENCE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 2]
            ],
            [
                'full_name' => 'BALANLAYOS DOROTHY',
                'last_name' => 'BALANLAYOS',
                'first_name' => 'DOROTHY',
                'middle_name' => 'M.',
                'specialization' => 'SCIENCE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 2]
            ],
            [
                'full_name' => 'SUAN MARIFE',
                'last_name' => 'SUAN',
                'first_name' => 'MARIFE',
                'middle_name' => 'C.',
                'specialization' => 'SCIENCE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 3,'department_id' => 2]
            ],
            [
                'full_name' => 'SENON MARILYN',
                'last_name' => 'SENON',
                'first_name' => 'MARILYN',
                'middle_name' => 'M.',
                'specialization' => 'SCIENCE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 3,'department_id' => 2]
            ],
            [
                'full_name' => 'TIO JULIET',
                'last_name' => 'TIO',
                'first_name' => 'JULIET',
                'middle_name' => 'S.',
                'specialization' => 'SCIENCE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 3,'department_id' => 2]
            ],
            [
                'full_name' => 'POLARON AIRA',
                'last_name' => 'POLARON',
                'first_name' => 'AIRA',
                'middle_name' => 'P.',
                'specialization' => 'SCIENCE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 3,'department_id' => 2]
            ],
            [
                'full_name' => 'WAHID AILEEN',
                'last_name' => 'WAHID',
                'first_name' => 'AILEEN',
                'middle_name' => 'D.',
                'specialization' => 'SCIENCE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 3,'department_id' => 2]
            ],
            [
                'full_name' => 'JAUDDIN MARYAM',
                'last_name' => 'JAUDDIN',
                'first_name' => 'MARYAM',
                'middle_name' => 'T.',
                'specialization' => 'SCIENCE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 3,'department_id' => 2]
            ],
            [
                'full_name' => 'PELEGRINO VIVIAN',
                'last_name' => 'PELEGRINO',
                'first_name' => 'VIVIAN',
                'middle_name' => 'B.',
                'specialization' => 'SCIENCE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 2]
            ],
            [
                'full_name' => 'TRUMATA CLARINDA',
                'last_name' => 'TRUMATA',
                'first_name' => 'CLARINDA',
                'middle_name' => 'E.',
                'specialization' => 'SCIENCE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 2]
            ],
            [
                'full_name' => 'GUEVARA LILIAN',
                'last_name' => 'GUEVARA',
                'first_name' => 'LILIAN',
                'middle_name' => 'S.',
                'specialization' => 'SCIENCE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 2]
            ],
            [
                'full_name' => 'MAGNAYE ALDRIAN DHEEN',
                'last_name' => 'MAGNAYE',
                'first_name' => 'ALDRIAN DHEEN',
                'middle_name' => 'M.',
                'specialization' => 'SCIENCE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 2]
            ],
            [
                'full_name' => 'ARCILLAS ARIESA',
                'last_name' => 'ARCILLAS',
                'first_name' => 'ARIESA',
                'middle_name' => 'N.',
                'specialization' => 'SCIENCE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 2]
            ],
            [
                'full_name' => 'REYES ALDIBEL',
                'last_name' => 'REYES',
                'first_name' => 'ALDIBEL',
                'middle_name' => 'B.',
                'specialization' => 'SCIENCE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 2]
            ],
            [
                'full_name' => 'TIMOSA ROXANNE',
                'last_name' => 'TIMOSA',
                'first_name' => 'ROXANNE',
                'middle_name' => 'C.',
                'specialization' => 'SCIENCE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 2]
            ],
            [
                'full_name' => 'TIO JESUS',
                'last_name' => 'TIO',
                'first_name' => 'JESUS',
                'middle_name' => 'S.',
                'specialization' => 'SCIENCE',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 2]
            ],
            [
                'full_name' => 'PASION MA. SHERILL',
                'last_name' => 'PASION',
                'first_name' => 'MA. SHERILL',
                'middle_name' => 'M.',
                'specialization' => 'ENGLISH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 1,'department_id' => 1]
            ],
            [
                'full_name' => 'VILOS FE',
                'last_name' => 'VILOS',
                'first_name' => 'FE',
                'middle_name' => 'Q.',
                'specialization' => 'ENGLISH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 1,'department_id' => 1]
            ],
            [
                'full_name' => 'ARQUIZA MA. LEILANIE',
                'last_name' => 'ARQUIZA',
                'first_name' => 'MA. LEILANIE',
                'middle_name' => 'S.',
                'specialization' => 'ENGLISH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 1,'department_id' => 1]
            ],
            [
                'full_name' => 'FERNANDEZ MARIA RAMONA ROSENAH',
                'last_name' => 'FERNANDEZ',
                'first_name' => 'MARIA RAMONA ROSENAH',
                'middle_name' => 'H.',
                'specialization' => 'ENGLISH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 1,'department_id' => 1]
            ],
            [
                'full_name' => 'IJIRANI PRINCIPON',
                'last_name' => 'IJIRANI',
                'first_name' => 'PRINCIPON',
                'middle_name' => 'P.',
                'specialization' => 'ENGLISH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 1,'department_id' => 1]
            ],
            [
                'full_name' => 'MANGUBAT MARIVIC',
                'last_name' => 'MANGUBAT',
                'first_name' => 'MARIVIC',
                'middle_name' => 'D.',
                'specialization' => 'ENGLISH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 1,'department_id' => 1]
            ],
            [
                'full_name' => 'SALVADOR ABIGAIL',
                'last_name' => 'SALVADOR',
                'first_name' => 'ABIGAIL',
                'middle_name' => 'M.',
                'specialization' => 'ENGLISH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 1]
            ],
            [
                'full_name' => 'MONTANO NOEMI EOUS',
                'last_name' => 'MONTANO',
                'first_name' => 'NOEMI EOUS',
                'middle_name' => 'B.',
                'specialization' => 'ENGLISH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 1]
            ],
            [
                'full_name' => 'BALDESAMO HERMOSA',
                'last_name' => 'BALDESAMO',
                'first_name' => 'HERMOSA',
                'middle_name' => 'O.',
                'specialization' => 'ENGLISH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 1]
            ],
            [
                'full_name' => 'TADJUL PUTRIE MAZ-WARA',
                'last_name' => 'TADJUL',
                'first_name' => 'PUTRIE MAZ-WARA',
                'middle_name' => 'A.',
                'specialization' => 'ENGLISH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 1]
            ],
            [
                'full_name' => 'FERRER MICHELLE',
                'last_name' => 'FERRER',
                'first_name' => 'MICHELLE',
                'middle_name' => 'JF.',
                'specialization' => 'ENGLISH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 1]
            ],
            [
                'full_name' => 'DE MESA JOSEPHINE',
                'last_name' => 'DE MESA',
                'first_name' => 'JOSEPHINE',
                'middle_name' => 'T.',
                'specialization' => 'ENGLISH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 1]
            ],
            [
                'full_name' => 'QUITAYEN BONNA JANE',
                'last_name' => 'QUITAYEN',
                'first_name' => 'BONNA JANE',
                'middle_name' => 'J.',
                'specialization' => 'ENGLISH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 1]
            ],
            [
                'full_name' => 'BUCOY MA. ELEANOR',
                'last_name' => 'BUCOY',
                'first_name' => 'MA. ELEANOR',
                'middle_name' => 'B.',
                'specialization' => 'ENGLISH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 2,'department_id' => 1]
            ],
            [
                'full_name' => 'MEDALLO ETHEL',
                'last_name' => 'MEDALLO',
                'first_name' => 'ETHEL',
                'middle_name' => 'A.',
                'specialization' => 'ENGLISH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 3,'department_id' => 1]
            ],
            [
                'full_name' => 'TUBO MARY JANE',
                'last_name' => 'TUBO',
                'first_name' => 'MARY JANE',
                'middle_name' => 'P.',
                'specialization' => 'ENGLISH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 3,'department_id' => 1]
            ],
            [
                'full_name' => 'AQUINO JUDITH',
                'last_name' => 'AQUINO',
                'first_name' => 'JUDITH',
                'middle_name' => 'B.',
                'specialization' => 'ENGLISH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 3,'department_id' => 1]
            ],
            [
                'full_name' => 'REYES EDNA',
                'last_name' => 'REYES',
                'first_name' => 'EDNA',
                'middle_name' => 'G.',
                'specialization' => 'ENGLISH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 3,'department_id' => 1]
            ],
            [
                'full_name' => 'MAWANI OMAR',
                'last_name' => 'MAWANI',
                'first_name' => 'OMAR',
                'middle_name' => 'N.',
                'specialization' => 'ENGLISH',
                'gender' => 'Male',
                'gradelevels' =>  ['id' => 3,'department_id' => 1]
            ],
            [
                'full_name' => 'DINAPO MYRNA',
                'last_name' => 'DINAPO',
                'first_name' => 'MYRNA',
                'middle_name' => 'P.',
                'specialization' => 'ENGLISH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 3,'department_id' => 1]
            ],
            [
                'full_name' => 'DELA CRUZ CAROLINA',
                'last_name' => 'DELA CRUZ',
                'first_name' => 'CAROLINA',
                'middle_name' => 'D.',
                'specialization' => 'ENGLISH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 3,'department_id' => 1]
            ],
            [
                'full_name' => 'SANTIAGO SHERYLL',
                'last_name' => 'SANTIAGO',
                'first_name' => 'SHERYLL',
                'middle_name' => 'J.',
                'specialization' => 'ENGLISH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 1]
            ],
            [
                'full_name' => 'KAWAGUCHI JONATHAN',
                'last_name' => 'KAWAGUCHI',
                'first_name' => 'JONATHAN',
                'middle_name' => 'J.',
                'specialization' => 'ENGLISH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 1]
            ],
            [
                'full_name' => 'BALAGA MARIA VICTORIA',
                'last_name' => 'BALAGA',
                'first_name' => 'MARIA VICTORIA',
                'middle_name' => 'V.',
                'specialization' => 'ENGLISH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 1]
            ],
            [
                'full_name' => 'PATAJO EVELYN',
                'last_name' => 'PATAJO',
                'first_name' => 'EVELYN',
                'middle_name' => 'S.',
                'specialization' => 'ENGLISH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 1]
            ],
            [
                'full_name' => 'VILLACIN MA. ROBINA',
                'last_name' => 'VILLACIN',
                'first_name' => 'MA. ROBINA',
                'middle_name' => 'M.',
                'specialization' => 'ENGLISH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 1]
            ],
            [
                'full_name' => 'LIM NORALYN',
                'last_name' => 'LIM',
                'first_name' => 'NORALYN',
                'middle_name' => 'M.',
                'specialization' => 'ENGLISH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 1]
            ],
            [
                'full_name' => 'SANCHEZ GLENDA',
                'last_name' => 'SANCHEZ',
                'first_name' => 'GLENDA',
                'middle_name' => 'N.',
                'specialization' => 'ENGLISH',
                'gender' => 'Female',
                'gradelevels' =>  ['id' => 4,'department_id' => 1]
            ]
        ];

        foreach ($teachers as $teacherData) {
            $teacher = Teacher::create([
                'full_name' => $teacherData['full_name'],
                'last_name' => $teacherData['last_name'],
                'first_name' => $teacherData['first_name'],
                'middle_name' => $teacherData['middle_name'],
                'specialization' => $teacherData['specialization'],
                'gender' => $teacherData['gender']
            ]);

            $gradelevels = [
                $teacherData['gradelevels']['id'] => ['department_id' => $teacherData['gradelevels']['department_id']]
            ];

            $teacher->gradelevel()->sync($gradelevels);
        }
    }
}
