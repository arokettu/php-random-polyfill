<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests;

use Arokettu\Random\Tests\DevEngines\SingleByte;
use Arokettu\Random\Tests\DevEngines\Xorshift32;
use Arokettu\Random\Tests\DevEngines\Zeros;
use Exception;
use PHPUnit\Framework\TestCase;
use Random\Randomizer;
use RuntimeException;
use Throwable;

class RandomizerWithCustomEngineTest extends TestCase
{
    public function testGetInt(): void
    {
        $testMatrix = [
            -59034 => [
                14063, -58251, 62020, -3429, 2777, -50963, 64694, -46787, -61324, 24390, -5558, -24966, -27594, 21701,
                4191, 29374, 16313, -6289, 64364, -4021, -31667, 16372, -33181, -2135, -44517, 53591, -31748, 16536,
                17574, 32022, 29263, 38314, -17059, -45807, -59906, -64757, -55214, -32776, 55435, 12710, -23048,
                -46106, 21980, -29646, -51981, 28499, -20446, 10850, 49034, 17484, 43681, 23113, 11518, -2590, 12780,
                60503, 26338, 2442, 3915, 28538, -54374, 12928, -34972, -42714, 20856, 22520, 2915, -36536, 16384,
                -14372, -16055, 64476, -20821, -17197, -3304, -2003, -20666, -17684, -22131, -28378, -35699, 34763,
                29900, 62634, -3254, -43915, 13813, -26265, -58887, 47004, 43987, -54624, -44090, 59519, -23416, -51434,
                -54591, -9771, 45215, 22361,
            ],
            17483 => [
                8687, 15303, -59266, 38270, 36949, 21599, 31714, -63191, 58144, 46923, 29995, 1974, 50802, -62291,
                -27486, -9281, -30764, -24016, -42539, -2714, 43471, -56264, 24424, -29377, -62082, -30275, -37607,
                -40162, -21505, -64229, 57114, 59519, -42924, -24230, -16081, 58549, -55210, -16281, 56637, -33836,
                31109, 12001, -48812, -61913, 44123, -11826, -45206, -19992, -29210, -15956, -9672, -5480, 33292,
                -51897, -9256, 53974, -24344, 58153, -25664, 30939, -17179, 1718, 62958, 51671, 33425, -5393, -8473,
                17648, 49001, -31951, 54384, -11329, 56726, -54475, 13172, -46102, -60621, -22838, -51078, -8688, 33550,
                -7795, -2352, 13718, -53500, -60709, -25917, -16285, 23433, 28627, -27023, 9317, -5732, 44678, 23714,
                -42856, -7591, 28809, 46029, 18642,
            ],
            -7694 => [
                41554, 29741, 5836, -25298, 37974, 22111, 63301, 6819, -31429, 5636, 55239, -43133, 25581, -13761,
                28353, -57568, -2172, -8327, -19402, 7044, 60009, -48390, 28233, 8846, -41499, -64129, 58111, -24276,
                63182, 16881, -50862, 54897, -57232, 21320, 13285, -22765, -40788, 44501, -11699, -60893, 8602, -4099,
                -10173, 1100, -21012, 58392, 2461, -47571, -60204, -1667, 30953, -38489, -13897, -54506, 24839, -47445,
                38742, -57556, -52624, 11152, -10296, 19324, 38434, -36877, -23424, 49368, 55366, 52304, -47565, -47153,
                463, 11083, 14389, 42094, -8822, 10802, -20461, -54300, -22303, 449, 40470, 25104, -59711, -62360,
                -13691, 63440, 28380, 48897, -42367, 2225, -32817, -26946, -31553, -37565, -22913, -30747, -57577,
                44150, 41183, 27983,
            ],
            -63067 => [
                32448, -52077, -10625, -42542, 40464, -11214, -26614, -31527, 37400, -33117, -13832, 3581, -40613,
                48130, 52456, -11533, 1451, 58560, -57728, 39860, 53304, -11100, 13727, -25542, -58925, 1497, -37237,
                -58864, 54587, -62206, -56671, 57999, 15753, -51691, 30346, -42635, -22081, -10944, -28516, 40070,
                -56731, 24727, -24570, 21996, -39363, -61886, -45627, -26152, -56716, -25500, -32134, -16159, -15351,
                49519, 7522, -2534, -43882, -28342, -63957, -35146, -56617, -22415, -6786, -63059, -13132, -38114,
                -54078, 11049, -7946, -65404, 31230, -43632, -53555, 2680, -8752, 56391, -34536, 6021, -14953, 18342,
                49903, 50450, 59612, 16679, 28265, -60391, 17391, -7689, -52456, -18299, 19427, -28776, -36341, -36991,
                -6718, -28936, 6566, 61273, -32211, -22828,
            ],
            43492 => [
                -46022, 9477, -24997, -39109, 31126, 19891, -12970, -2635, 20812, -59739, 44235, 57536, -16728, 54774,
                52076, -23119, 6898, 1270, 18889, 47191, -63958, 10915, 19255, -14713, -60089, -52975, 51675, 23053,
                -64207, 26096, 64687, -24113, 2417, -58002, 13431, 40215, 2136, 50148, -19484, -25169, 45407, -58676,
                -9836, 46862, -33758, -61333, -35449, 62086, -36156, -38311, -3763, -24293, 36778, 43292, -61708, 50195,
                -62563, 23698, -28564, 65286, 17292, 46638, 17698, 53498, 13215, 28040, -23640, 24597, 63218, -64188,
                38749, -23655, 20007, 11266, -42051, -2964, 2983, -16064, -25875, 7361, 50353, -4133, -52717, -30815,
                39374, 2196, 41187, 57377, 45439, 40456, -46984, -54491, -57793, -64532, -22947, 38416, 1325, -53445,
                11508, -13630,
            ],
        ];

        foreach ($testMatrix as $seed => $nums) {
            $rnd = new Randomizer(new Xorshift32($seed));

            for ($i = 0; $i < 100; $i++) {
                $num = $rnd->getInt(-65536, 65535);
                self::assertEquals($nums[$i], $num, "Seed: $seed Index: $i");
            }
        }
    }

    public function testGetIntWrongRange(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage(
            'Random\Randomizer::getInt(): Argument #2 ($max) must be greater than or equal to argument #1 ($min)'
        );

        $rnd = new Randomizer(new Xorshift32(123));
        $rnd->getInt(1, -1);
    }

    public function testNextInt(): void
    {
        $testMatrix = [
            -8234 => [
                39015338, 753332377, 743241107, 757924220, 760244301, 727761985, 251087637, 355132207, 548267656,
                333577846, 701113425, 88104129, 366288413, 885004230, 824395658, 596735763, 588461290, 876655501,
                605114405, 212402895,
            ],
            -47519 => [
                951019900, 631018556, 343603585, 571689140, 733903862, 1020631894, 945081785, 496911432, 939431603,
                3567831, 196902897, 202110912, 747383193, 509392278, 354573777, 227815725, 1031871061, 129831712,
                519776332, 94634773,
            ],
            34864 => [
                418073449, 21632762, 826122544, 644862989, 19080490, 357210841, 69506033, 487433052, 94423567, 12954511,
                54291429, 106644597, 879329535, 179976772, 866462493, 236359614, 600394142, 363397267, 47588238,
                308018029,
            ],
            14567 => [
                843307812, 768522583, 644661381, 838043867, 502660431, 702382145, 593508827, 791684406, 424210269,
                281734300, 645282864, 536196552, 14191790, 954582296, 144470603, 477439233, 578171931, 979343937,
                736126874, 91727475,
            ],
            -44941 => [
                367736309, 434509759, 959364295, 947726348, 854221026, 540269737, 796577322, 558836693, 42250303,
                82445758, 266679680, 66262122, 246865525, 980872681, 514858908, 892577437, 1038142413, 934693297,
                780974273, 184948091,
            ],
        ];

        foreach ($testMatrix as $seed => $nums) {
            $rnd = new Randomizer(new Xorshift32($seed));

            for ($i = 0; $i < 20; $i++) {
                $num = $rnd->nextInt();
                self::assertEquals($nums[$i], $num, "Seed: $seed Index: $i");
            }
        }
    }

    public function testGetBytes(): void
    {
        $testMatrix = [
            33664 => [
                'b8', '2364', '748348', '8704e752', '3c4a887cbb', '18d09059f1cb', 'b818ee1ac4bcdf', '87b8a1393fa49a58',
                '1104b50fca42d149ce', '152a004034be176ddc58', '6a68755616af7a149ab491', 'fb05584488b60f58079d0a75',
                '12a4e773a013d96bb6d90b7c28', '351812445fcc83653b690c1dee24', 'a55c0d4bc951a073a5edb04ac7a288',
                'cf36da598ef0787f7a07494c09546878', 'dde2c02f1039f040d945913dcc866730f3',
                '2190763598f0220900bb0a31958e3b2bc17f', 'f930182eba9be72832388e29b6a1813fcde95b',
                '7c762b0cce267c3e1c881a6e90e53b5c544fb731',
            ],
            -43196 => [
                '2f', 'fbaa', '59a4bc', '8247c011', '5a68313fa7', 'c293161dd57a', '2048b3109beb59', '4ee37c3744eeb349',
                '59fb153ba6f5a50bee', '9ab64843b756e0662c95', 'ad282230f6f4c1537949bf', '91a89f5d37063e39ab83e346',
                'e0e44964cad96c426019e373e7', '8f0b294dc36943673e931a458048', '035c0c09e5950b53fe825317785e82',
                '1e63e2649e5cb7388c14b237bd6d5651', '6dbad90047351445b9672234b3017a6235',
                '17ef1e5749477d55e3691f4fb2064066d9ca', '58777c0df3a4797100261b794d35be26860de3',
                'cf492a6906816422a45ccf7a2955213a0bd00b00',
            ],
            63589 => [
                '83', '7811', 'd97547', 'c74d7146', '43defa169a', '732c4f397320', '42708d7c003926', 'e36bc5655fd89c5f',
                '94587456c732b871d7', '54ece172c13702223c92', 'c0ded91640c0380dd89129', '71bb4023a6adfe31e3af016d',
                '3d9041475e59875d08c33c6366', '91d67660a393396e256df6558ce0', 'a1b7730d22e6720bd9326721a719ba',
                '60c45712add0a531d5dbbe7d577e497e', '5664f561c9df8f62329da8379529d72507',
                '9f1dfa105b53733ab7711b53016b9c181f7e', '44144613c328196823a62c6d37a0f744af51da',
                'd8f81f799a809b461ea0936716539d61c96b0934',
            ],
            51298 => [
                '64', '2c30', '2e6a37', 'f7e1221c', 'd91e9843f8', '17d7e2537741', '83ad3a44866dd2', 'd7fd3141d270ef2f',
                '2815577ff161b123f6', '8abe51418a7c9826ce29', 'c407a82fec9d4245f3c41d', '721af0418d1e71157d1eb462',
                'c0560a4e89c1981e7db9955320', '8d077359ccd2d5094a2a05426c64', '697c102bc78a3437d147e36bfdad35',
                '38c0ce64dc15f22578697d0ef1621d49', '507a2d4d03278e2eb434a027cf21e20762',
                '97485b41d32a7e21e160ab60fa915f1a8a5c', 'fc44481277fb2e40378e4277d5d30b764dc85f',
                '26e99c113ac0bd1b07ade854c3d94568dd9fcf3c',
            ],
            -59300 => [
                'e6', '9b69', '343eba', '4ab8ef01', '19a43e2aa7', '1849bf1c1609', '4e9b2961ae3f4e', '13ffc16ac2055571',
                'b4cb406ccf54f8539f', 'afed8d53d36c3f29a1fe', '2bd04b350c669c266297e3', '4502f4727bf82f250b18361e',
                '9084dd7bd787bb221790681a62', '36033f7e5a4f6055ff02ef4dc770', '9cf9616315a1185608217e054735bf',
                'ccbaed0ed6e4300f638def66823e3f7f', '552c0105101ef831ade67a753a1d451cab',
                '4403301be8534970b2a0bb1c6448177d498f', '8c1dd937b8607a19ce0bc4781015034e406b81',
                '3457c52885125946a1903965d7fd9e32e543a149',
            ],
        ];

        foreach ($testMatrix as $seed => $strings) {
            $rnd = new Randomizer(new Xorshift32($seed));

            for ($i = 0; $i < 20; $i++) {
                $num = \bin2hex($rnd->getBytes($i + 1));
                self::assertEquals($strings[$i], $num, "Seed: $seed Index: $i");
            }
        }
    }

    public function testGetBytesTooLow(): void
    {
        $this->expectException(\ValueError::class);
        $this->expectExceptionMessage('Random\Randomizer::getBytes(): Argument #1 ($length) must be greater than 0');

        $rnd = new Randomizer(new Xorshift32(123));
        $rnd->getBytes(0);
    }

    public function testShuffleArray(): void
    {
        $array = \range(1, 100);

        $testMatrix = [
            -4850 => [
                66, 45, 4, 87, 99, 96, 37, 67, 61, 25, 53, 9, 86, 74, 89, 16, 95, 39, 73, 7, 13, 24, 31, 22, 47, 54, 71,
                56, 62, 92, 65, 51, 35, 75, 50, 8, 15, 79, 68, 27, 21, 44, 60, 36, 12, 29, 30, 40, 2, 28, 38, 100, 1,
                26, 97, 93, 17, 23, 64, 57, 81, 76, 5, 41, 84, 91, 80, 6, 98, 14, 20, 46, 10, 3, 18, 52, 70, 77, 82, 42,
                32, 72, 19, 55, 69, 43, 49, 78, 59, 33, 88, 63, 48, 34, 85, 83, 90, 11, 94, 58,
            ],
            -35518 => [
                41, 78, 10, 79, 36, 12, 60, 58, 64, 33, 53, 39, 97, 96, 77, 35, 74, 52, 46, 32, 23, 67, 68, 80, 17, 75,
                11, 49, 31, 95, 9, 70, 51, 27, 28, 83, 85, 94, 14, 56, 43, 61, 82, 87, 29, 21, 99, 66, 54, 19, 47, 69,
                37, 48, 1, 25, 98, 30, 73, 86, 20, 93, 26, 42, 7, 8, 44, 88, 91, 63, 84, 38, 92, 90, 40, 6, 62, 16, 57,
                2, 34, 76, 100, 65, 72, 71, 89, 81, 55, 15, 22, 59, 18, 5, 24, 3, 13, 4, 45, 50,
            ],
            41380 => [
                13, 87, 16, 96, 25, 33, 26, 40, 41, 27, 70, 9, 82, 44, 73, 54, 52, 90, 22, 12, 63, 6, 34, 74, 24, 2, 20,
                57, 77, 95, 29, 48, 36, 86, 59, 51, 81, 79, 92, 97, 75, 3, 64, 76, 10, 49, 43, 68, 45, 84, 46, 56, 32,
                35, 8, 30, 62, 71, 19, 14, 89, 83, 85, 72, 47, 18, 98, 11, 53, 1, 28, 37, 42, 58, 88, 66, 50, 60, 94, 5,
                67, 4, 65, 69, 55, 78, 7, 15, 38, 93, 21, 80, 99, 91, 17, 61, 23, 31, 100, 39,
            ],
            -6629 => [
                83, 4, 75, 1, 9, 87, 55, 96, 28, 45, 22, 41, 89, 21, 2, 74, 26, 76, 98, 32, 14, 8, 72, 88, 79, 64, 61,
                99, 19, 63, 40, 31, 86, 38, 65, 93, 59, 62, 49, 66, 6, 57, 16, 73, 69, 24, 85, 47, 46, 52, 33, 78, 20,
                90, 70, 58, 67, 80, 91, 17, 35, 95, 13, 50, 42, 27, 43, 56, 30, 51, 60, 5, 97, 12, 29, 68, 7, 37, 82,
                18, 3, 44, 71, 81, 10, 100, 11, 25, 53, 92, 48, 36, 34, 15, 23, 77, 39, 84, 54, 94,
            ],
            61842 => [
                26, 3, 94, 40, 78, 35, 16, 33, 60, 44, 12, 32, 59, 20, 73, 69, 43, 51, 2, 89, 27, 24, 81, 97, 67, 77,
                95, 100, 84, 41, 17, 23, 55, 1, 38, 14, 90, 92, 45, 74, 87, 64, 80, 21, 34, 5, 22, 54, 31, 57, 11, 25,
                39, 52, 49, 58, 53, 30, 79, 10, 28, 8, 42, 70, 46, 68, 6, 82, 63, 50, 71, 7, 88, 36, 99, 29, 9, 91, 76,
                65, 15, 47, 18, 19, 86, 37, 66, 62, 61, 83, 56, 93, 4, 72, 13, 98, 48, 85, 75, 96,
            ],
        ];

        foreach ($testMatrix as $seed => $shuffledExpected) {
            $rnd = new Randomizer(new Xorshift32($seed));

            $shuffled = $rnd->shuffleArray($array);
            self::assertEquals($shuffledExpected, $shuffled, "Seed: $seed");
        }
    }

    public function testShuffleString(): void
    {
        $string = \implode(\range('a', 'z')) . \implode(\range('0', 9)) . \implode(\range('A', 'Z'));

        $testMatrix = [
            -35487 => 'udtp5IXlnm27hEP84V61bkezgcNfZDRU9TFAswyY0WLCjiQrqaOKM3JvHBxoSG',
            39925  => 'gbJxlvu3cNRVAmaKnI2wDhjMpOf45UdQTFL0yi8oCqHEsBSkWZrPG91zXet67Y',
            -27577 => 'VmYg1DaueX9hdoc4znPArL38sURFSqGZxjWINpCOytiBEHf2KT57Jb6Qlwk0vM',
            -38362 => 'E3y8R4ir2g7HNZukwem1sPVa9qMbIOW6dJclQUx0jSKXCBoTGvYDFAh5tnzfLp',
            -54215 => 'XTt83fqGgLHbUMSaP5h6pA9ksi4d1EuKZnIxzVrQW2j7yODNcwBRYCeom0vFlJ',
        ];

        foreach ($testMatrix as $seed => $shuffledExpected) {
            $rnd = new Randomizer(new Xorshift32($seed));

            $shuffled = $rnd->shuffleBytes($string);
            self::assertEquals($shuffledExpected, $shuffled, "Seed: $seed");
        }
    }

    public function testShuffleEmpty(): void
    {
        $rnd = new Randomizer(new Xorshift32(123));

        self::assertEquals('', $rnd->shuffleBytes(''));
        self::assertEquals([], $rnd->shuffleArray([]));
    }

    public function testPickKeys(): void
    {
        // try to be accurate at least on packed arrays
        $array = \array_flip(\array_merge(\range('a', 'z'), \range('0', 9), \range('A', 'Z')));

        $testMatrix = [
            63250 => [
                ['j'],
                ['m', '7'],
                ['p', '2', 'I'],
                ['s', '0', 'G', 'Q'],
                ['x', 'G', 'R', 'T', 'Z'],
                ['b', 'g', 'j', '2', '6', '7'],
                ['b', 'g', 'l', '0', '5', 'G', 'X'],
                ['i', 'k', 'q', 'u', '9', 'E', 'H', 'Y'],
                ['c', 'h', 't', 'z', '3', '9', 'C', 'R', 'T'],
                ['i', 's', '5', 'K', 'M', 'P', 'R', 'T', 'U', 'X'],
                ['i', 'n', 'w', '0', '4', '7', 'C', 'I', 'M', 'N', 'U'],
                ['a', 'e', 'k', 'm', 's', 'u', 'z', '4', '6', 'A', 'C', 'V'],
                ['e', 'k', 'l', 'o', 'q', '0', '1', '5', '8', 'C', 'G', 'L', 'M'],
                ['f', 'h', 'j', 'k', 'o', 'w', '0', '8', 'F', 'J', 'O', 'R', 'U', 'Z'],
                ['b', 'd', 'g', 'h', 's', 'v', '4', '8', 'F', 'H', 'P', 'Q', 'R', 'U', 'V'],
                ['b', 'c', 'd', 'f', 'j', 'o', 'p', 't', 'x', '0', '1', '3', '5', '8', 'J', 'X'],
                ['c', 'e', 'f', 'k', 'n', 'r', 'v', 'z', '0', '2', '4', '7', '8', 'T', 'U', 'Y', 'Z'],
                ['d', 'g', 'j', 'l', 'o', 's', 'w', 'y', 'z', '2', '3', '6', 'C', 'F', 'R', 'U', 'X', 'Z'],
                ['e', 'h', 'l', 'm', 'p', 't', 'z', '1', '9', 'F', 'L', 'O', 'R', 'S', 'T', 'U', 'V', 'X', 'Y'],
                ['a', 'd', 'h', 'q', 't', 'u', 'v', 'w', '0', '1', '4', '5', '7', 'D', 'J', 'M', 'P', 'Q', 'R', 'V'],
            ],
            19413 => [
                ['a'],
                ['f', 'H'],
                ['G', 'H', 'P'],
                ['k', 'm', 'o', '1'],
                ['a', '0', '8', 'Q', 'Y'],
                ['d', 't', '7', 'L', 'Y', 'Z'],
                ['r', 'y', 'z', '0', 'F', 'I', 'Y'],
                ['a', 'm', 'A', 'H', 'J', 'P', 'S', 'W'],
                ['a', 'f', 'k', 'r', 'w', 'z', '9', 'O', 'Z'],
                ['c', 'm', 'o', 'q', 't', '7', '8', 'L', 'S', 'Y'],
                ['c', 'r', 's', 'y', '6', 'F', 'H', 'I', 'O', 'R', 'X'],
                ['b', 'h', 'n', 's', 'z', '8', 'C', 'E', 'M', 'O', 'U', 'V'],
                ['j', 'm', 'x', 'y', 'z', 'E', 'H', 'I', 'M', 'O', 'Q', 'R', 'W'],
                ['a', 'g', 'i', 't', 'y', '1', '3', '7', 'D', 'H', 'O', 'S', 'U', 'Y'],
                ['b', 'e', 'f', 'k', 'l', 'm', 'q', 'r', 's', 'z', '0', '2', '4', 'O', 'Y'],
                ['b', 'd', 'l', 'm', 'r', 's', 'v', 'w', '9', 'C', 'D', 'F', 'S', 'T', 'X', 'Y'],
                ['a', 'g', 'h', 'o', '1', '5', '8', 'B', 'C', 'D', 'F', 'G', 'K', 'T', 'V', 'W', 'Y'],
                ['b', 'j', 'p', 's', 'w', 'y', '3', '6', 'A', 'C', 'E', 'H', 'J', 'U', 'V', 'W', 'X', 'Y'],
                ['c', 'd', 'e', 'f', 'j', 'n', 'p', 'r', 't', 'u', 'v', '4', '8', 'C', 'M', 'P', 'Q', 'T', 'W'],
                ['b', 'd', 'e', 'g', 'k', 't', 'z', '1', '8', '9', 'A', 'B', 'D', 'G', 'I', 'O', 'Q', 'S', 'U', 'X'],
            ],
            -3227 => [
                ['O'],
                ['F', 'K'],
                ['E', 'V', 'W'],
                ['d', 'k', 'm', 'C'],
                ['p', 'v', 'C', 'D', 'X'],
                ['k', 'w', '5', '6', 'M', 'P'],
                ['b', 'l', 'm', 'u', '0', 'P', 'X'],
                ['c', 'y', '0', '1', '8', 'O', 'Q', 'R'],
                ['b', 'e', 'i', '2', '3', 'C', 'H', 'S', 'T'],
                ['l', 'o', 's', 'z', '2', 'G', 'H', 'N', 'Q', 'U'],
                ['o', 'p', 'q', 't', 'u', 'x', 'y', '0', '2', 'S', 'T'],
                ['s', 't', 'u', 'v', 'y', 'z', '3', '6', 'J', 'R', 'W', 'Z'],
                ['b', 'f', 'q', 'y', '2', '3', '4', '7', '8', 'C', 'J', 'Q', 'Z'],
                ['b', 'i', 'j', 's', 'z', '0', 'A', 'F', 'H', 'N', 'Q', 'S', 'V', 'X'],
                ['d', 'n', 'o', 's', 't', 'v', 'x', 'C', 'F', 'H', 'P', 'Q', 'V', 'X', 'Y'],
                ['g', 'h', 'i', 'j', 'n', 'o', 'q', 'r', 'u', 'y', '6', 'I', 'O', 'P', 'W', 'Y'],
                ['f', 'h', 'n', 't', 'u', 'w', 'z', '1', '7', 'D', 'G', 'I', 'O', 'P', 'U', 'V', 'Y'],
                ['b', 'e', 'i', 'o', 'p', 'u', 'z', '1', '4', '5', '6', 'F', 'G', 'I', 'Q', 'S', 'W', 'Y'],
                ['h', 'p', 'u', 'v', 'w', '1', '2', '6', '7', 'B', 'C', 'D', 'G', 'I', 'M', 'N', 'Q', 'T', 'X'],
                ['a', 'd', 'e', 'f', 'i', 'n', 'q', 's', 'w', 'x', 'y', 'z', '7', 'K', 'M', 'O', 'Q', 'R', 'W', 'X'],
            ],
            6752 => [
                ['U'],
                ['f', 'h'],
                ['a', 'l', 't'],
                ['o', 'u', 'A', 'V'],
                ['y', '1', 'I', 'N', 'R'],
                ['7', '8', 'G', 'S', 'U', 'X'],
                ['c', 'i', 'k', 'v', 'Q', 'R', 'S'],
                ['f', 'q', 'J', 'O', 'Q', 'R', 'T', 'Y'],
                ['f', 'm', 'p', 's', 'w', '4', '9', 'E', 'H'],
                ['g', 'l', 'n', 'q', 'y', '2', 'A', 'C', 'G', 'P'],
                ['h', 'j', 'q', '5', '6', '9', 'C', 'I', 'K', 'L', 'N'],
                ['a', 'c', 'j', 'k', 'o', 'r', 'z', 'D', 'F', 'S', 'U', 'V'],
                ['d', 'p', 'q', 't', 'v', 'x', 'y', 'z', '4', '5', 'C', 'J', 'Y'],
                ['b', 'c', 'd', 'f', 'l', 'y', '2', '5', 'C', 'E', 'G', 'K', 'L', 'T'],
                ['a', 'b', 'p', 'q', 'v', 'z', '1', '3', '8', '9', 'L', 'S', 'X', 'Y', 'Z'],
                ['c', 'f', 'h', 'j', 'n', 's', 't', 'v', 'y', 'z', '2', '3', '8', 'A', 'O', 'S'],
                ['c', 'f', 'h', 'j', 'n', 'w', '3', '7', '8', 'B', 'E', 'M', 'P', 'S', 'V', 'W', 'Z'],
                ['j', 'k', 'q', 's', 't', 'u', 'v', '0', '5', '6', 'B', 'D', 'E', 'H', 'K', 'M', 'N', 'V'],
                ['e', 'f', 'l', 'p', 'q', 's', 't', 'z', '3', '5', '9', 'A', 'D', 'K', 'M', 'N', 'O', 'Q', 'V'],
                ['a', 'd', 'n', 'p', 'r', 's', 'v', 'y', 'z', '4', '5', '6', '9', 'A', 'C', 'E', 'F', 'L', 'T', 'Y'],
            ],
            -41584 => [
                ['C'],
                ['k', 'W'],
                ['2', '8', 'J'],
                ['a', 'b', 'e', 'E'],
                ['u', 'w', '5', 'H', 'K'],
                ['i', 'u', 'v', '6', 'D', 'Y'],
                ['c', 'g', 'i', 't', '3', '4', 'D'],
                ['b', 'c', 'd', 'j', 'l', '1', '3', '9'],
                ['o', 'p', 's', '3', 'G', 'H', 'L', 'Q', 'V'],
                ['m', 'q', 's', '5', '7', 'J', 'O', 'T', 'U', 'Y'],
                ['c', 'h', 'k', 'o', 'r', '1', 'A', 'L', 'M', 'O', 'Z'],
                ['c', 'f', 'g', 't', 'u', '5', '8', 'F', 'J', 'L', 'N', 'W'],
                ['d', 'r', 'w', '0', '6', '8', 'B', 'E', 'J', 'K', 'M', 'U', 'Y'],
                ['a', 'b', 'e', 'q', '2', '8', 'E', 'F', 'L', 'R', 'S', 'T', 'W', 'Z'],
                ['b', 'l', 's', 'u', 'y', '6', 'C', 'E', 'F', 'H', 'K', 'L', 'V', 'Y', 'Z'],
                ['e', 'g', 'n', 'r', 's', 'w', '1', '2', '6', '7', 'A', 'F', 'G', 'J', 'T', 'U'],
                ['b', 'c', 'g', 'k', 'q', 'r', 'u', 'v', '1', '3', '7', 'B', 'G', 'I', 'K', 'Y', 'Z'],
                ['b', 'e', 'f', 'g', 'i', 'm', 'q', 'v', 'z', '5', 'G', 'H', 'J', 'K', 'M', 'N', 'T', 'X'],
                ['f', 'g', 'h', 'l', 'o', 's', 't', 'u', 'v', 'x', '0', '7', 'C', 'H', 'J', 'K', 'N', 'T', 'Z'],
                ['a', 'h', 'i', 'j', 'l', 'n', 'p', 'u', 'v', 'z', '1', '4', '6', 'D', 'H', 'I', 'J', 'O', 'T', 'Y'],
            ],
        ];

        foreach ($testMatrix as $seed => $keysExpected) {
            $rnd = new Randomizer(new Xorshift32($seed));

            for ($i = 0; $i < 20; $i++) {
                $keys = @$rnd->pickArrayKeys($array, $i + 1);
                self::assertEquals($keysExpected[$i], $keys, "Seed: $seed Index: $i");
            }
        }
    }

    public function testSerialize(): void
    {
        $rnd1 = new Randomizer(new Xorshift32(\random_int(0, \PHP_INT_MAX)));

        $rnd1->nextInt();
        $rnd1->nextInt();

        $rnd2 = \unserialize(@\serialize($rnd1));

        self::assertEquals($rnd1->nextInt(), $rnd2->nextInt());
    }

    public function testSerializeKnown(): void
    {
        if (\PHP_VERSION_ID < 70400) {
            $this->markTestSkipped('Only 7.4+ is compatible');
        }

        // seed = 123456, 3 generates
        $serialized =
            "O:17:\"Random\\Randomizer\":1:{i:0;a:1:{s:6:\"engine\";O:43:\"Arokettu\\Random\\Tests\\DevEngines\\Singl" .
            "eByte\":1:{s:48:\"\0Arokettu\\Random\\Tests\\DevEngines\\SingleByte\0chr\";i:3;}}}";

        $rnd1 = new Randomizer(new SingleByte());
        $rnd1->nextInt();
        $rnd1->nextInt();
        $rnd1->nextInt();

        self::assertEquals($serialized, \serialize($rnd1));

        $rnd2 = \unserialize($serialized);

        self::assertEquals($rnd1->nextInt(), $rnd2->nextInt());
    }

    public function testSerializeWarning(): void
    {
        if (\PHP_VERSION_ID >= 70400) {
            $this->expectNotToPerformAssertions();
        } elseif (\method_exists($this, 'expectWarning')) { // PHPUnit 8/9
            $this->expectWarning();
            $this->expectWarningMessage('Serialized object will be incompatible with PHP 8.2');
        } else {
            $this->markTestSkipped('PHPUnit is too old for this test');
        }

        \serialize(new Randomizer(new Zeros()));
    }

    public function testUnserializeWrongArrayLength(): void
    {
        if (\PHP_VERSION_ID < 70400) {
            $this->markTestSkipped('Only 7.4+ is compatible');
        }

        // seed = 123456, 3 generates
        $serialized =
            "O:17:\"Random\\Randomizer\":2:{i:0;a:1:{s:6:\"engine\";O:43:\"Arokettu\\Random\\Tests\\DevEngines\\Singl" .
            "eByte\":1:{s:48:\"\0Arokettu\\Random\\Tests\\DevEngines\\SingleByte\0chr\";i:3;}}i:1;i:123;}";

        try {
            \unserialize($serialized);
        } catch (Throwable $e) {
            self::assertEquals(Exception::class, \get_class($e));
            self::assertEquals(
                'Invalid serialization data for Random\Randomizer object',
                $e->getMessage()
            );
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }
}
