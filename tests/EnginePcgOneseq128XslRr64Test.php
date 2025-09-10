<?php

/**
 * @copyright 2022 Anton Smirnov
 * @license BSD-3-Clause https://spdx.org/licenses/BSD-3-Clause.html
 */

declare(strict_types=1);

namespace Arokettu\Random\Tests;

use Exception;
use PHPUnit\Framework\TestCase;
use Random\Engine\PcgOneseq128XslRr64;
use RuntimeException;
use Throwable;

final class EnginePcgOneseq128XslRr64Test extends TestCase
{
    public function testSeedInt(): void
    {
        $seq = [
            0 => [
                'f1f895e696010701', '93449fc540c83e70', 'fa443a4b915449e5', '5e28b904f20f1396', '1ab2ce35f5de9f7d',
                'a019122ed4ee6f66', '6f32c82157681f98', 'da4dab6e0d7180ad', '29a037b080c402e2', 'e207d9edea90335d',
                'aab8c639fbbe5607', 'a3624d63a64bb41f', '426642623642208d', 'b49fa3670191ea34', 'b60a0da8430b0193',
                '8a56fc988ab03d66', 'ae6f9535130a0b72', 'bad3e1313e48352c', '9d40376377399f42', '44736838e6996db4',
                'eecaed6ab9705310', '71ff1c812fe99939', 'c9cf91b5bcf830d2', 'a5de7bbab23dce0d', 'af99ec1ec9522fcf',
                '398a998b4ac2c72b', '9ca199d5b0f18abd', 'f55960c6ab45bc56', '1e7f0f17dc460a17', 'ad5fb87752af5dc2',
                'dbea48c9e0c229e6', 'ed42559196a72017', '5199904faa0cfb22', '3dd8ac75410f0c7e', '0c862aff37abfcd9',
                'd1ba5420fb8022ab', '999efa376fa0e858', 'c72865b0302aa5c3', 'bdc13fa173f77501', '40e8004b58fc1c73',
                'cb698064b2c74c40', '83f7b7b05391c25b', 'd199c98ca3101377', '16a9710a2f576a76', '4883c44ffb50f490',
                '0d1a7b1c3eea80f0', '446ad607451a4715', '69dff3785ae5587d', '9cc96a5794a03001', '871d4bd0b29c6646',
                '40181920ed5bab17', '3effad60d277b195', '074ceeb624b65f02', '5fa9540133e45db3', '794ce267ff0f51e8',
                'd3d26ed7bc3c2c13', '0439095a14cce735', '793b58815f5b5b9f', '33629633a549e73e', 'cda8edcd8658f84a',
                'aaf33ecb0e38a50c', '92311d66b74e674f', '3377cd00ad9aa288', 'c6fa5f04ca27b670', '3d6287a83eb41259',
                '21f22cf6c69fdc95', '5b905c2aa1816092', '51e6fc7dcdd4579c', '5dbbe323bf2cab85', '523102639f66cdc5',
                '8e895dad0fbe6730', '053db54c446fb512', 'fc34340c645a2ebc', 'e13f61e4f0bf8092', '3e7428c594908198',
                '33df29d8981c9d99', '4292c82d012af89f', '94bec839ed8391f9', '551c42cd6191f5f0', '8dc4f6c23057703c',
                '618a27e9c685ad66', 'd0d528a4e428342a', '0d9404fd687d2079', '3084dc2e402b7fea', '3bf657c89a416ba0',
                '1c7ec4fbe6d01dcb', 'a4a6ad009222554f', '7f929c35e6b54796', 'e5efc797918fbf30', 'd084c39c521995a7',
                '9764ad39f3c422bb', '7541d1592f78b9d7', '1801ecf2ff12ff0d', '7c3a340583ad31a3', '62087ef1e3d7da48',
                '65963cfdb36f4c32', 'c4df33790e35e4f0', 'b6038b0bf3a2cd7c', '40de059017c6afa0', '303a3b066dda65ee',
            ],
            -2102720976 => [
                'c47625a3abcc5d25', '52a1a815fcacf12e', 'fb9ebac885d86bfb', '09ace4721d054e21', '6ee5d925afba2a93',
                '27c7c123c8fec3eb', '624e395487b08b56', '4ec83093201614c4', 'a649b2346ae83a2c', 'f0924f1986588380',
                'efb5b2b92bf1ff8c', '77febc25f6a82e9d', '93b43c7653ffca12', 'da927b243bca375d', '8df74e940805a69d',
                '1ec2214d8480af3e', '6636840f59ac65ee', 'ad6cedad9a13b87a', '0efb92d3258fa34f', '1c952346e59cbde4',
                '6438206e5539413f', 'd66b99e47437b0ad', 'c80e864d741bcfde', 'cbcb13cfdb4e585a', '28264e0a59849672',
                'e4ec6cec7f33f1b4', 'f6318991e6917b80', '60074fed1acedfeb', 'e0c082edc31dc911', '4d4dad0e5f0d4664',
                'c3af5855bc562410', '38dc46e79f08486d', 'badb1e7e57105f02', '423d8d78f72947f9', 'd751c82bf8716489',
                '66ede63f8256018d', 'fa81fff6a8d4de4b', 'e3e31f59b7f191ee', '17a1cea2de7933dd', 'cae78dbda9dee2f1',
                'fe21b325116c46d1', 'da7f1534b4db2f88', 'd9e2652f6744a99b', 'ca465cf99f3060fa', '92c308743ca7e61f',
                '325b0c60d5c60353', '2a1da98535842dde', '57b4e91932e63647', 'd51806042213ac03', 'bc4355ecc1c84ce1',
                'd677b55e8a46159c', '832d7ca10d84eaaa', '9a37b9509af053cc', '128c6f8456d9fbc2', 'd2680866dec79c74',
                '7a87eb28ed0c6242', 'f73221b687ac33d9', 'fa150ab3b02bf11e', '4c4fbe1118400b63', '3e587da9141ddd1d',
                'c5e49306d8a9c703', 'db09e9f531360a78', '8004e2a5c40028cd', 'd822ce4c7aa761ed', 'cc7ffe27b1d57853',
                '1f421aa4d407955c', 'eef3daac845c68f8', '2e7db9237e13350f', '3d9935b80889182e', '031fee530c793e9c',
                'daf5057088f774ed', 'bcceb10eb07294bb', 'd06a4c0deb603532', '5fab43ca1022bf47', '19d6ab2b01ccb3a0',
                '15180e2d0d2123ea', '2214e7cfe7dfac8b', '152b4807c485d918', '165d9782ac90b007', '3934a49d35ec1dc4',
                '3105f89914f6f6e1', '3431cc3a4dfa2516', '29a8cef8d11cb986', '9607f05206b0e31e', '15843d75eefa2f60',
                '0ed725e3d6cdde4a', '25e3025a65de70aa', '581cb084a0d613bc', '7317348c06d6550e', '9e1b6453711eaeb5',
                '8c67376c4e5d5647', '9bf1b7d830c3bcb5', '7e49c0a52e89aa60', 'bb6e20e82ff98701', '45cc2144f81bd70d',
                '2d314a38461386f3', 'eb1cb38d17995b30', '0b6fd2197b2b5e97', '369e77181fffef05', '627dbc44203e798f',
            ],
        ];

        foreach ($seq as $seed => $bytesExpected) {
            $engine = new PcgOneseq128XslRr64($seed);

            for ($i = 0; $i < 100; $i++) {
                $bytes = \bin2hex($engine->generate());
                self::assertEquals($bytesExpected[$i], $bytes, "Seed: $seed; Index: $i");
            }
        }
    }

    public function testSeedInt64(): void
    {
        if (\PHP_INT_SIZE < 8) {
            $this->markTestSkipped('64 bit only');
        }

        $seq = [
            6159709666678653989 => [
                'ca8a8c7bb93e0b81', 'cc6c6ef5fa7aec03', '35791f3041d77f7e', '676023804391f22e', 'fb902cdd86b0886b',
                '68729efc3cc7847a', '43eceb67e23ba9bd', '91c1b012980dda73', 'ebc1844b088ed3d9', '527b5eac2245f4d8',
                '52fa3bd4424986ec', '72e2e3601498cb61', '219bf7599969384f', '0e40d9473056f247', 'becd298dc440294f',
                '2a4c6b500379c2ba', '6142d25fc7ae309d', '471050f0521c74f1', 'b0274a2c402906c9', '3889aaf5031fb040',
                'cf7f4a3a037a55fe', '63a73cca620cda17', '50649339ffdc83dc', '39e5b557ed1ad5a9', '1b275086b475057d',
                'ca51235303fc9a2c', 'e43c8c4d5b06b844', 'fc6c47a144329b09', 'e19fb19daa159bf2', 'b7966edcc22a4dd4',
                'fe81e16081d3a5ff', '6971d3440d2200e8', '92c432d6cca15876', 'fa4a0b6bed014e45', '6e4b788cd32d83b0',
                '6a5dcb471f9ba5c0', '1b81ed6e76257ec2', 'a52e598e7b414d61', '87b972aba3a3fd6a', 'ce8cb3f5fcbfb3b5',
                '6e76ab59d307862b', '90ab09c75b40eae2', 'b90ee469ae20d11e', '04482a1e81d92abc', '39588b79b7fca55f',
                'c9634692317bcfef', '2a4fd2b59e8ba21a', 'e0e20ef159baee6c', '077752f442a1d2a6', '6657b1d99af27cde',
                'ac0fdf174a2b59af', 'faee2f272cc542d8', 'f47250f198f64281', 'e24767ad7f6af4be', '03d17f63536a0e5f',
                'eeeaa589df68ed71', '55b02227b97ca881', 'bc47d4bf176d24ab', '96094e0d226fcbb3', '0b8615647863564d',
                '9ef6a557ac5e4fbc', 'dea50e981f860c99', 'a2ff7e94fb122f4b', '1998743d9e3de9d3', '9e2a500d5cb9f6e9',
                'a9ae7a94cc731955', '43a5ce10dc0db559', '4994f40c0df99efc', '0521bd0326803fa6', '61510f2aeeae9e54',
                '659c6c4cfc6ced55', '2785462ff9311426', 'd1a030a396e48874', '1022da6a4bf2b33b', '4d57354cbae9e1fb',
                '3298d5724dc2d9fa', '7b138aa564bf841e', '52bae0a219894bea', '759fc4098b97ffa7', 'dc3c7d3bc1673dc1',
                'f65f3e69f0bb4422', '24bfd309a039b7c6', '6eea03dc927bd995', '2a1c69e5b12e992b', '87b49f9957be8b7d',
                'dd08c4bf1a4cfeae', 'd0a116507062e8c8', '615359b2de204d6c', '0d0f2726a7575ee5', '20be1c71e14a9561',
                'c8e68b464252d40c', '4199ba471ebd5f30', '1e57862424607537', '33832e0b1b7a6ea7', '73c3c7a3e59c79c6',
                '8f09ab2a475fefaf', 'e4e7c071f9543040', '6e544b954ced3182', '3b571839f6401e86', '30e680db070c851d',
            ],
        ];

        foreach ($seq as $seed => $bytesExpected) {
            $engine = new PcgOneseq128XslRr64($seed);

            for ($i = 0; $i < 100; $i++) {
                $bytes = \bin2hex($engine->generate());
                self::assertEquals($bytesExpected[$i], $bytes, "Seed: $seed; Index: $i");
            }
        }
    }

    public function testSeedString(): void
    {
        $seq = [
            "af1afd8706ed0f3a15768207df4d758b" => [
                '0852e4b12552dbcb', '4ddc71b6f15330d6', '3f4e3ffcdcfb946d', '48a5fdd11ab064bd', '96d70baf1f3a26fc',
                '36649355a2ddae2f', '22a7a23da6d78a07', 'a2fef5ad28302384', 'a96e4e20b4494e43', 'edb2486be64ec9ab',
                'b469ad6509a363cb', 'dc513a4792973bed', '6daffd23010a4947', 'd3b060c4d6187b12', '9fd7ecd90f02407b',
                'bde103901bfe3c4c', '42c2f65a184f26a7', '14bd281ff8430cc7', '1dd3ba5c95c3ae88', '4a5c48aaf063b90d',
                '0979cbf128162a68', '13351f818f384f84', '09bbe85c0c0eeaae', '0d8008597fb64c99', '61e09eff67d1b2b6',
                '3ac15b78c5934987', '1d92860ce6199be4', '9bf53cc5f37f0ed3', '57dd78e54c2b1b3a', '35fc9dc1593d19fe',
                '9f428831ee79642f', '58c95d877cebcbd6', 'ac7dd8acd79497e5', 'e67cea03ce804cdd', '23f390e0351d3349',
                '599ff97b5e8f5b81', 'be262afec12e9fc7', 'a310ab09ef578bff', 'f93e99e18dbdd20f', 'fffa55edf6b5e44e',
                '111c631951c5486a', 'fc5277dfc2210167', 'e0e56dcbfb77a3d4', 'd636898602d86e3d', '7b8b5a50c9438fa0',
                'fedc38a42d25aba6', 'fea5b1d4ae3422ac', '7e3be0810ea3628c', 'eb97681148da00f0', '7676ade59610179e',
                '903b61f26efd0765', '10835adc477a1fa0', '9ca3808642f1b9c4', '625ac25df194aeb0', '965e5492a1a038a3',
                'c7fad5a466a00410', 'e24d5f98cad4abf0', '93789d83adfb4639', 'c0159e8d5aaec353', 'e1466a7edbd4f35d',
                '49bff22c3e78891b', '85e2d53a1fbf83a3', '502446186a8dad60', 'c97620a8a8c10d66', '97674634dcec4df6',
                'aeb4558a510a24e8', '48be4f66760a1c5a', '9a5192ff5bb8f23f', 'c1c763c6d3c6ed46', '1ab182afbaa86ddc',
                'b762898c4b13228a', '097c8450cb069558', 'bf6e228d3c30b233', 'fc14dea793bc0c01', '6efaebb1dbb75ebe',
                '269f45873a94ef24', '05dbcb90a8206030', '400a793e722e9a2c', '464e41d473c18774', '85e52dd3c06b1980',
                '8639392b4a5bf78d', '1b48edb0196743a4', 'e4e728de2abd351f', 'b0a0e67fab8018ab', 'a9e5b7e4a31db47d',
                '6cccdb30f77413ff', '9cc1322b80b00049', '37513427b617a7ff', '1d312ce3f991ad35', '069fdf36b61bf661',
                '237dd68b34bd48e8', '17a135cab882fe82', 'efe909b1fa228723', '294e51ec75a73d5e', '2844ab848ea1910d',
                '61f758ae3c0f784e', '0cd538e8ec0ab2cd', 'afabebbff6cdd66e', '7da54abc2f7d0c6e', '2f5f17a7f658bc58',
            ],
            "ffffffffffffffffffffffffffffffff" => [
                '96aa3f7b26dc71e8', 'ecc7246556a8ce1a', '10ef413c3df97250', '1895d77ffd48d8e5', 'fafb51a6ab92cacd',
                '507004c727842c5b', 'aa50d3c8dd4da643', 'eacc83594dc04e35', 'bcbaabb017ab0d72', '712a6dc52c671811',
                '72a3f8a932d37427', 'f9e66f7eee51416c', '82752dac1e414b1d', '9a661b4e6fed4de6', '637353e5a5ee7c95',
                '91a2e19e40a878a1', '49d9532992c81b06', '2dff68858ad74f6a', '5886b33c5639b51b', 'f1df680db084db08',
                'e65b10c25e7c8880', '9f57f18e70d84838', '4a84ddd0c2e4af03', '1799bf9ffb5f7c32', '581ff4eb5bf3a37a',
                'f618aa0cc587ac9a', '07e584d9ec2ef8cd', 'a8f02389e2af037f', '564e374bfddd380e', 'acb6ddc504cb2bfa',
                'bc625f7937cb14e2', '722caadb3c225308', 'dec8826bd8f5d35c', '7a7be1c830fc805b', '85c60007f900e628',
                '3b803f98ced50cc6', '799cf9f67783c0d2', 'a3b5fadcb9ddfe8b', '6e91bbc39c362cf2', '2438cc8362625e31',
                '92606406987adf53', 'dca51a9cd7964dcd', '15cfffc587c7a88b', '75d16c95ea3be8c2', 'cdfafe4f5c486700',
                '98c5f1c5748c1427', '3473eb5bae501b8d', 'a88da50f398c2959', '42c88f844f93e02e', '2bed4a10018ed1c3',
                'fdc5ffa7ee3b4a5d', '43944b403f84c651', 'dff34a8fcd5bb5d8', '09525e6b2b3128ad', 'ce5b14072aff92a3',
                'ffa9001b10178cdc', '10f35fe0464ad48f', '5309c12caf3649d1', '63225dbceb531838', '01f94412fdebf476',
                'f0c48d27a995df80', '9d7d7e3a96366b9f', '14beb12d6b4fa337', 'ed578b8fcdcaddd8', 'd4f7c188f8a527cf',
                'ef2ac8d2130fca32', '4b28f0a5218cd899', '5e4823ad3817745c', '7c715ffd7c129368', 'f547bdafdf6c7b05',
                'eea5b1a545592483', 'af5a7828470da591', '6eda8e385311c83f', '63870af216b32628', '7b8c2c44f7d85626',
                '8760f958b28a6f8a', '89b3a8c54d59c1ad', 'bca74e7dc4158104', '430e6b314b0630ab', 'e27fbeff972ef9af',
                '4561b2d5c18c999e', '1b8e275cd583c698', 'e6c6447c9a45b494', 'e8c14777a1f39787', '54025f0a49baab38',
                'af68d9a6c81e2889', '072b0382506dee12', '8a7fe9bb1da79dfd', '37cadd8f81e348da', 'e4feddd8e25de4e6',
                'fcd609b7a946645e', '778e9c2cf122e014', '1d485da1888480bb', '607ea054b60f3fdc', '40382c15bb223322',
                'e775af49d9d1dc30', '800de40416a8f28c', 'cc46d2fe4c709dbc', 'a407ada3b23c55d4', 'fa4c135ccafeca0b',
            ],
            "00000000000000000000000000000000" => [
                'f1f895e696010701', '93449fc540c83e70', 'fa443a4b915449e5', '5e28b904f20f1396', '1ab2ce35f5de9f7d',
                'a019122ed4ee6f66', '6f32c82157681f98', 'da4dab6e0d7180ad', '29a037b080c402e2', 'e207d9edea90335d',
                'aab8c639fbbe5607', 'a3624d63a64bb41f', '426642623642208d', 'b49fa3670191ea34', 'b60a0da8430b0193',
                '8a56fc988ab03d66', 'ae6f9535130a0b72', 'bad3e1313e48352c', '9d40376377399f42', '44736838e6996db4',
                'eecaed6ab9705310', '71ff1c812fe99939', 'c9cf91b5bcf830d2', 'a5de7bbab23dce0d', 'af99ec1ec9522fcf',
                '398a998b4ac2c72b', '9ca199d5b0f18abd', 'f55960c6ab45bc56', '1e7f0f17dc460a17', 'ad5fb87752af5dc2',
                'dbea48c9e0c229e6', 'ed42559196a72017', '5199904faa0cfb22', '3dd8ac75410f0c7e', '0c862aff37abfcd9',
                'd1ba5420fb8022ab', '999efa376fa0e858', 'c72865b0302aa5c3', 'bdc13fa173f77501', '40e8004b58fc1c73',
                'cb698064b2c74c40', '83f7b7b05391c25b', 'd199c98ca3101377', '16a9710a2f576a76', '4883c44ffb50f490',
                '0d1a7b1c3eea80f0', '446ad607451a4715', '69dff3785ae5587d', '9cc96a5794a03001', '871d4bd0b29c6646',
                '40181920ed5bab17', '3effad60d277b195', '074ceeb624b65f02', '5fa9540133e45db3', '794ce267ff0f51e8',
                'd3d26ed7bc3c2c13', '0439095a14cce735', '793b58815f5b5b9f', '33629633a549e73e', 'cda8edcd8658f84a',
                'aaf33ecb0e38a50c', '92311d66b74e674f', '3377cd00ad9aa288', 'c6fa5f04ca27b670', '3d6287a83eb41259',
                '21f22cf6c69fdc95', '5b905c2aa1816092', '51e6fc7dcdd4579c', '5dbbe323bf2cab85', '523102639f66cdc5',
                '8e895dad0fbe6730', '053db54c446fb512', 'fc34340c645a2ebc', 'e13f61e4f0bf8092', '3e7428c594908198',
                '33df29d8981c9d99', '4292c82d012af89f', '94bec839ed8391f9', '551c42cd6191f5f0', '8dc4f6c23057703c',
                '618a27e9c685ad66', 'd0d528a4e428342a', '0d9404fd687d2079', '3084dc2e402b7fea', '3bf657c89a416ba0',
                '1c7ec4fbe6d01dcb', 'a4a6ad009222554f', '7f929c35e6b54796', 'e5efc797918fbf30', 'd084c39c521995a7',
                '9764ad39f3c422bb', '7541d1592f78b9d7', '1801ecf2ff12ff0d', '7c3a340583ad31a3', '62087ef1e3d7da48',
                '65963cfdb36f4c32', 'c4df33790e35e4f0', 'b6038b0bf3a2cd7c', '40de059017c6afa0', '303a3b066dda65ee',
            ],
        ];

        foreach ($seq as $seed => $bytesExpected) {
            $engine = new PcgOneseq128XslRr64(\hex2bin($seed));

            for ($i = 0; $i < 100; $i++) {
                $bytes = \bin2hex($engine->generate());
                self::assertEquals($bytesExpected[$i], $bytes, "Seed: $seed; Index: $i");
            }
        }
    }

    public function testJump(): void
    {
        $engine = new PcgOneseq128XslRr64(123456);

        self::assertEquals('5541cb1034d23cbe', \bin2hex($engine->generate()));
        self::assertEquals('41d1f22928582772', \bin2hex($engine->generate()));
        self::assertEquals('1763bf76310a3e44', \bin2hex($engine->generate()));

        $engine->jump(0);

        self::assertEquals('688456c10d3a6d63', \bin2hex($engine->generate()));
        self::assertEquals('4e50c51b77ee1e75', \bin2hex($engine->generate()));
        self::assertEquals('a43ff17b5a2d94a4', \bin2hex($engine->generate()));

        $engine->jump(127);

        self::assertEquals('640a138e2e137c2f', \bin2hex($engine->generate()));
        self::assertEquals('9cbbb1317b3e3bf6', \bin2hex($engine->generate()));
        self::assertEquals('9f0466861d538c75', \bin2hex($engine->generate()));

        $engine->jump(256);

        self::assertEquals('d20987c39c791f24', \bin2hex($engine->generate()));
        self::assertEquals('a635504550a7a8ab', \bin2hex($engine->generate()));
        self::assertEquals('07f2a1f42e889d98', \bin2hex($engine->generate()));
    }

    public function testSerialize(): void
    {
        $engine1 = new PcgOneseq128XslRr64();

        $engine1->generate();
        $engine1->generate();

        $engine2 = \unserialize(@\serialize($engine1));

        self::assertEquals($engine1->generate(), $engine2->generate());
    }

    public function testSerializeKnown(): void
    {
        if (\PHP_VERSION_ID < 70400) {
            $this->markTestSkipped('Only 7.4+ is compatible');
        }

        // seed = 123456, 3 generates
        $serialized =
            'O:33:"Random\Engine\PcgOneseq128XslRr64":2:{i:0;a:0:{}i:1;a:2:{i:0;s:16:"48443d943ea2ee8f";i:1;s:16:"c31' .
            '5cdb584ba153a";}}';

        $engine1 = new PcgOneseq128XslRr64(123456);
        $engine1->generate();
        $engine1->generate();
        $engine1->generate();

        self::assertEquals($serialized, \serialize($engine1));

        $engine2 = \unserialize($serialized);

        self::assertEquals($engine1->generate(), $engine2->generate());
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

        \serialize(new PcgOneseq128XslRr64());
    }

    public function testUnserializeWrongArrayLength(): void
    {
        if (\PHP_VERSION_ID < 70400) {
            $this->markTestSkipped('Only 7.4+ is compatible');
        }

        // seed = 123456, 3 generates
        $serialized =
            'O:33:"Random\Engine\PcgOneseq128XslRr64":2:{i:0;a:0:{}i:1;a:1:{i:0;s:16:"48443d943ea2ee8f";}}';

        try {
            \unserialize($serialized);
        } catch (Throwable $e) {
            self::assertEquals(
                'Invalid serialization data for Random\Engine\PcgOneseq128XslRr64 object',
                $e->getMessage()
            );
            self::assertEquals(Exception::class, \get_class($e));
            self::assertEquals(0, $e->getCode());
            self::assertNull($e->getPrevious());

            return;
        }

        throw new RuntimeException('Throwable expected'); // do not use expectException to test getPrevious()
    }
}
