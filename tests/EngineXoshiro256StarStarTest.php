<?php

declare(strict_types=1);

namespace Arokettu\Random\Tests;

use PHPUnit\Framework\TestCase;
use Random\Engine\Xoshiro256StarStar;

class EngineXoshiro256StarStarTest extends TestCase
{
    public function testSeedInt(): void
    {
        $seq = [
            0 => [
                'b4f275cb365fec99', '2a455649781f6ebf', 'e0e633499d845f1a', '2c2d2d26f194a56a', '592e841f4aada5bb',
                'cacaebd97583efff', '984cf5d2ee0d166c', '3f0ac38f64ad2089', '319753a70b2c03db', '3d9a743e5a473aeb',
                '542a3fa43f99421d', 'b54ba126f51b3611', '9c8e3daba5074f1b', '7fdb86697f25a3a7', '9cfc5d6095aafd7e',
                'b8aa8ea7c097de4b', '6c661835c4ea55b4', '900673066cbf4d30', '8c798a597677be8c', 'e527d7fc7fdfcb0e',
                '70e23f535721f54f', '2e2f24875b47617e', '246831a9688c5552', '76114792c500bda0', '9e3bc6a0a3839bfc',
                'ef888b0a0f6c784d', '38232ff6c47324a5', 'd9d625db3700dce9', 'c39450d2a9ebe5fc', '514bd6e21ee6dbe3',
                'df72122b432ef623', '13492c343a44c74a', '91198c65a9f11cc3', '1d8b91ceff970c29', '6a63902ee05544f5',
                '3ff3b88b754577f5', 'd923281285b6e1e5', 'ec9780fde0cd162c', '3619bca544bcde3c', 'bd2d3c72faba3368',
                '9ed3d3a14b6cfab6', 'c3edc256b632b9e5', '9f5c6121610bcf09', 'd536c67fd5254e21', '37256e8021173dcf',
                '02dc5d33c66f79cf', '22039b48868b3c35', 'aab64725826548fc', '8c3f8bee843dc9e8', 'd6f223a32021b4d1',
                'b236ff47d2113aa7', '8ca5bb58692342ae', '3acfff2a9e6722b6', 'f445f66000ab3bcc', 'a7daf0785ce4012e',
                '8a94be165f6c5608', '40f6e18721acbe73', '6e5d1b2c753d908e', '1d51d7941068345b', 'c1f5472038adeb70',
                'cce9d44814cae5ea', 'd51b635b77622d3d', 'c37d4f5bbc2eb78c', '800a69ea39299c09', '4bc806301a06d3f9',
                '5f4a734d392c87d0', '2a5c0db72f8c76bf', '5972c5e4277e5a9b', '049448a050b0008e', 'f3409dfe4545ae72',
                '5acf50cb8dccbd02', '64d67ce09f23207b', '138f13eaa837b037', 'b065cde9f9512fa5', '7a5581d52fb7e05e',
                'a76f2cc99abb7e52', 'da552989fc034fb6', '45909a52eb4f2c97', '95a761246b14eea5', '079bbba54910efaa',
                'e9cfb68af3f5bc00', 'ddac3815fd986301', 'e2ca1573fd44078a', 'a65766de391ebfa4', 'efe20d9d44475268',
                'ddb14f368c5c8da4', 'b5652b1ffbaabc7f', '48ecdd557f96f096', '1a8a784afe3cd430', '11c73880ed5bb1f7',
                '2a1a26b96d2ab88d', '2e5b0ea0776aeb94', 'a421d2bf0d49193e', '546b0a5d5be14496', 'a7a1ecda3fca35e2',
                '9ec7a71e00e0b82d', '75fc74761dfb821d', '4d4005483b9b0365', '1b7975e9c39f3ad7', '9c21ba1f022db73c',
            ],
            2076265327 => [
                '2987db0c90b49cc2', 'a3fd5a8b114ee1a9', '01e0ce6861a67e63', '810a904e2354ea9b', '57f9f0104fb4dcd3',
                'bb68ae76e6d9aa27', 'eea7325becc0f0d8', '313a5c0cefb33b2d', '0fa0e9093291e429', '82082044bd97fe21',
                'ee068d1c06d2b998', '5a621e91f70f0d2c', '9cd11238a1226a7b', '0ef17cd12574c8c9', '851e46c363b70c94',
                '766b7ba65682f02b', 'b7e69bfd55ebaed2', 'b9722ecc0ce3533e', 'a8fbf3f32b9a2c8b', '85c7f141c69e5016',
                '43f8c42550008f45', '0fb3aadc4725e706', 'b307ca05a1309f11', '7c9004e77bfcec3f', 'b80af9361aaec3d4',
                'c06bfdd4ba0bc066', '32e1c10617d9a5a9', 'a725a9f50489b8d8', 'dfa2ae3a3f0f2ac5', '9a900d86827979e6',
                '91f7b5510ce17251', 'fa122e1030813e2e', '1f2b958ef855b259', '9d7ca207d47f72dd', '2985d651fdd38443',
                '1a27c2c4c07c3e30', '2d9cc1252f1ac2a9', 'fbb2753321759698', 'cf8598b6573470a3', '2ae9d60554ce6258',
                '7051d56c2a0428f9', 'c5efd77a66b53eaa', 'ebf57b04bc616c34', '08a8cc88baf39882', 'd0c8fd14e9d57bed',
                '076e89bb559fc16e', 'd3bbefc6a16c8247', 'fb6d42eb4840264f', '00cc9c750d29db06', '4d6485b33d7ba7d4',
                '8f745bd8cc76fecc', 'b543a5416c782ee2', 'eaf7aede302d9646', 'eb2f29561ac9a853', 'b0a466f29b7cfc9a',
                '78b8a3ed76f705b6', 'efe1adf1013acdd4', 'b9b8b17e4872ee58', '2bebf538b505c4a2', '669d3aba1eae5f45',
                '290e9ef1d7089352', '9763c247a07dc0fe', '35da9e02d705146a', '50b3130d1edda2b6', 'a541b3ecf915aa8d',
                'a476f4c07facc2e9', 'ddf976f2df51e500', 'afb664ff8d7fa240', '1fdf8bd48257f2c4', 'ddb254194402ae6d',
                '3ed5b5e8b6b0577c', 'dbd02299b749ab42', '7309defc0152c9b9', 'c9714f37a73ac87a', 'f0d14f9e8f57504e',
                'f158d31ec8717930', 'd93c068c1b78eb18', 'f8fe5d694a3abf3c', '4694ea44e21ed14f', 'ba29f2d9c0dcb817',
                '4adf586fb5e15781', '88d443a2c2cbc309', '3c09d91612fd949b', 'ba77297a8f010335', '20b307c20fa17b33',
                '5822102d9743a0ba', '19f16d3d00649ada', '7ea93db49549e132', 'b6b765699718de12', '0df01a6d6c6ba355',
                'da13c77ced09ca25', 'd4bc7a62a01e96cf', 'e1a7152bfefa913b', 'f2758876ffb1b1cd', '9a4d0c4550e96e6a',
                'c54e4e92993bf2cb', 'da402550954d8816', '38b6bc436b950608', '6f4d8c406c5c2ab1', 'd8f1d094682dd37c',
            ],
        ];

        foreach ($seq as $seed => $bytesExpected) {
            $engine = new Xoshiro256StarStar($seed);

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
            -7554633497861286132 => [
                '4eabd9e48b9b35a4', '92ce43bbcf1d1ccd', '82cafd3d8de767be', '4f7db910e0389ac8', '1aa585de9d756e08',
                'fbed6de2e17b606d', '04cfc6946bb2b8a6', 'ce617ce31fc2d2d1', '256b02708488213d', '78acde993e6fd6af',
                '14ac86c0644e4204', '522af1be30ce44d3', '51b2074543f5ddae', '9cecd78e2ec150eb', '1188a742976eda71',
                '712db863cdbbebfa', '4bb1b906da35c6e4', '28ef1ba7798bf5b3', '5e49ed5a88cbebf7', 'dee202e8a479bd19',
                'e9dfa51ce96ae17c', '09d702dfc3f58258', 'a85868658f2b849b', 'e034b35600549329', 'cbd9c06751ab0295',
                'efba26807f7649c7', 'fd153984028049ff', '1f3ab30bc5f3cc34', '53ca5eb6e01e9cf7', '74a2f2e5a2601fe8',
                '67acaadf658fa470', '7c83bf39c074af02', '7ac915462510c856', '8661d04beab14cd7', 'f5ecec92009c168c',
                '976e4eaed05a55f7', 'b516e2f9b4a8951b', '819df3fdfae35a77', '8753a5bce2532c37', '5c919b2f0d716431',
                '0ad15f404f0c8e37', 'b253801e5bc2e538', 'edbb3b12586ea3e5', '1b8c812f9fca7941', '21fc0b35aac7db9c',
                '646f008cbdfa4f6b', '18d8e83066f82a0e', 'd4d3af46da8c3081', '040659518fbe8cc8', 'c24561b16fef2f63',
                '57de93c33ed1245d', 'd5758923da569a77', 'd9d6579e96fc663f', 'abef797d70f3a20b', '947f7de9010140a1',
                '6cc6425162a818c9', 'cd7b24c3371686cd', 'c407ef77adcb5117', '94b5d5ee30c82e9e', '332f0aaf7b57ec69',
                '49b5952024dccd22', 'cacca55eae6821c7', 'e65d081cced0979d', 'f9d60bd11cd40a12', '58be23e2f888fd6d',
                '5984124d856c9d77', 'deb7b15b08c38d67', 'cbe95f14392540de', '5b9311bf0e5457d4', '918b516175a2985d',
                '950916a850608bc5', '26c53bd48d572ad5', '4f3d74afaf46747e', '100e74616044d3fa', 'd10704ea67fb2b53',
                '3386b99a1b6eb3e8', '2a1e98280f14f593', '8aa1200663c61e02', '5589d603418c3e7a', '426c627dff082c02',
                '6e3bde4e0d05189e', '49335038df881b65', '4d3bcb76c6b7ccd4', '8b3a408c46d9e68d', '23c0834ec8d80bb0',
                '1793b53d0ec83d96', '2c254c05027b0201', '7edb1e722b4d918d', 'd8d8d788257abee8', '92157dd7cfc2ed46',
                '4a2735ba2a6969d0', '9c395d41e4ad2c76', 'ec8c7b6f30fc4923', 'f1ad2bc79d62d4ce', 'd678ea5765335321',
                '4a24cc7f25eb8e46', 'f1fab12c387a3d8e', '2a43223e2d31ac73', '3e40124545cf2e19', 'beabfca5c0b5ccd7',
            ],
        ];

        foreach ($seq as $seed => $bytesExpected) {
            $engine = new Xoshiro256StarStar($seed);

            for ($i = 0; $i < 100; $i++) {
                $bytes = \bin2hex($engine->generate());
                self::assertEquals($bytesExpected[$i], $bytes, "Seed: $seed; Index: $i");
            }
        }
    }

    public function testSeedString(): void
    {
        $seq = [
            "73f4d0a15245034cc38c1bd07c183d1d12dd064e704f403609fad28600b362fc" => [
                '1126df6b4af8a6de', '12bd0e939b489d94', '49bb459bfee59763', '3271bce56e699294', '32304f581f1fdb12',
                '3e7350c29f62498b', '4536edd88fd8401e', 'b181997f5037976c', '7d92b2c20fa5235a', 'f6b5165e57ac2fd1',
                '51f2588cc11a8e05', 'a2838b42d8c83154', 'bcb19cdd14f59f2f', '8d7094e3d42981d3', '8f1ea30d63a7eb17',
                '1a4f053a366f2ad0', 'e9946316cb21cf68', 'a50284ed40fbb5b5', '51bb8b72de01cce4', 'd8156b8ceb812b99',
                '63874bbe0c66cbbd', '53aea15001173559', '16cf9bae73877a00', '1240fc02f1505c57', 'd8eb76b41757c0d4',
                '3b391ece2bbc3485', 'fc42a5d709aa323d', '277151f2808f3260', 'ec2fe942386f639c', 'f69bb0f0b7ea0320',
                '4cb79a0624899244', '75a99f169f0f057f', 'c867f45670a28571', 'ded15aa388599bca', '8981da032bf4cf19',
                '10896dde40f44ddb', '5de461be9100b438', '47ee4fe4fe93fd87', '012a63d8d16570fb', 'ebc32e2cffbd006b',
                '2038b17951af67ad', 'b1b3812b779bad1d', 'fc7d846e7025a67e', '3b16fc62092a2d35', 'c75a06e4a1ae2c15',
                '35360af3eab4fe60', 'e77028f926fdb638', 'dd343bcd6e6645ef', '2b4d1c87b70862bb', '944c339eca2fc959',
                '0b23ab09d287f977', 'd8bd53008d4de9cc', '16b05bbf430998d1', 'ca7d61c3b2eb5dbd', '787d3f6cd282963b',
                '00eaaf1a34475b4c', 'aff692fb0a3c1b78', 'aaf3fcb65781c2ed', 'b68ca765b87c704b', '90f2d133c74d59d0',
                'e54ecf264843741f', '8bd2e90348663565', '6d5558352d3dea95', '5756f4311bafb32a', '36d340bf469818d4',
                '6617944b8a1e58db', '7fcf24e6ca86677d', 'bf53dab4d911ab5f', '1b4b1ada8d748e60', '2d2fb27446e13f19',
                'acd162f84cba5d62', '485f51c4b486f99a', '6fcf9a7d5b897db5', '4b2cdc0d5d68bbc4', '704a39db0491779a',
                '29bc4f735a2b34ee', 'b51e2cddefbacf7a', '9dd0dc38cbca113c', 'b2d7bd9e6be3dea0', 'a33158c601130962',
                '7bb6321f917130a7', '70e5a01ad922ec86', 'd70ba8e5d63cf91a', 'ac249bd00c62d5bb', '30d7c6a3858790c8',
                'dfc19189919aefa2', '7dc95806c65ac60c', '26483fd938fb3147', '3615de58f642bc90', 'af09cb4d69b50676',
                'a80c93c570d8f181', '5aaa572794ea107d', '78c7863d6e33e3fc', 'de3fbb5c3dc6c60e', '2a9a29e5725e1980',
                '3db6c75aaf6ab471', '112bf947010a718e', 'a659fd152ff0c341', 'f0bb80f8eab44c6f', '60c4f4639093af26',
            ],
            "ffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffffff" => [
                'f7edffffffffffff', 'f7edffffffffffff', '770400d3ffffffff', '80e9ff2c00000000', '80e9ffffff590000',
                '97eaffffff5930fd', '000000d359004cb3', '17c1345e16a6cf4e', '97ba020000a6cf9a', 'e02c0b53162d3057',
                '3874d855fcb0ffb3', '9da1d8dcffd2ffa5', '9bb902537667ae5c', '121c72aba8722102', '537d510d15f58fdf',
                'c64c23d9c254531e', 'ca1adc794b77584a', 'b74f6bb3b95f43dc', '098af8e7e4cbb82b', 'd03952b8d300aaae',
                '1fa6547ce3d510dd', '9d936dd9dcee4df7', '02818cc3cf941a28', '5793b37be1da3308', '2a4e538ae583973a',
                '4c9cdc6c7a233fd8', 'c06a55e16ed2b586', '6d10406b056e6df1', '919182ff443491d7', 'aa5a232ce20aacbd',
                '4d190c626f3f6428', 'b2c6b742623defb3', '808ce4d6842b32f0', '541664e9276ae652', 'e0fad895b85c0dea',
                '0edb49a538234dc0', 'cab89be24a33ceda', 'e75ba3064cf9e482', '418584db84172808', '82bef1af93939cf5',
                '80b162c17b8a5dea', '724eef1ed721e3f6', '9e6f483a09c7c853', '69f402bc78069a2a', '69b7d3d22dbcb39b',
                'bb88b2f6e57aa37b', '3c152bb8957053c7', '1fee206ca7587a04', 'b37f41bfcacb793f', 'b363da3b63ee5a5a',
                '8e02c3f4bd57f070', '7593b68171813d02', 'f4131c3c3f86b469', '34cc183feda58b6b', '4c7faf92124e0ecf',
                '0232bc1289eb525d', 'f2a31f791719f58b', '4e76a36ea8064d9a', '076d72f1dc30df10', 'df2b952f3cf655bd',
                '156db62905b594fe', '4d82fe787648cd4f', '1b37dbc61b7d72b7', '5bcbd87d7ac202e7', 'bfb6c258151c7324',
                'fc60dba7d7d16c15', '64a51a85c4bbf92a', 'd37db2bf77446eda', '458745cea9944cc6', '38ced34a5d28f3de',
                '139226ff7db6aa33', '4102e598863586dd', 'c7ea0832b0b70eef', 'be9b9e4eb59aaae4', '6abb4684511c7600',
                '6bd5f142dfe8ff89', '166fc1d15f78d9fc', '094051b65eca9ea4', 'a9ca9c6c77e0a9d8', '86dea7ba42a4203b',
                '83961ef788213a1b', 'ebf5b3d1fc759129', 'd44d9b45a1d0f4a7', 'f3f3136ba072a6d7', '006a1b89e4784444',
                '00498d61ec0cfa7a', '9bd4da37b649f1d4', '3704c9988d8c5609', 'e3b256ea06a7d588', '7d696b383cf6202a',
                '7d5b6cbddaafbdf5', '686c0f2f88dc1409', '842fce9960c7c492', '19efdc6d018cbfca', '8542a66571b0c20a',
                '18902a04f9c284ff', '267ea85bfad4e162', '1b4136ec6c38e397', '03c8d431a4563c33', 'f3d659a81558cb67',
            ],
            "0000000000000000000000000000000000000000000000000000000000000001" => [ // all zeros won't work
                '0000000000000000', '0000000000000000', '1200000000000080', '1200000000d00280', '1200005a00d00280',
                '4002005a000000a0', '5b02005a00b46821', '000000002db46801', 'd0a205002d006809', 'a2a0055a00845d9c',
                'a2a005408be6c435', '9603d0428b135d80', 'de0370488b66732d', '3c0b0740d895e6bc', '8700a06d8ee47eb4',
                'f3950168012d40b8', 'e18fbbc2a102cb55', '7b0bd70261776f93', '847d8cb0d5b9c2e6', 'd6ffe79c8809f920',
                '338340f7830da2db', 'bd3571f198c5f1f2', '6eb3330ea5323bd1', '49a25f008b60a05e', 'a75b8025d1609eb0',
                '146dae6c3d733eca', '8e1a84279e47ade1', '1ce7046c22f5766b', 'a5d7088dfa0c41c9', 'b4fe1df6b93dfa9e',
                '1ae92ae075a128f4', 'c1c5658669c35392', 'f47f9e71a617efa9', '4b76a0f57b4952d3', 'd16e55798352a2fc',
                '7b84af14f12d2088', '2a31a21b582f9b90', '57761ea15842475e', 'f7ab09eaaccf7b17', 'a35a691f85f9975a',
                'af2ceed2db74c4ce', 'b44dd991db8c382a', '261fa3434cd0782b', 'fb52d65c1cc8172f', '1e2b2e6355f254aa',
                '4829b66143c21e7d', 'a78ae5f05b5087dc', 'cf9c5c2853192f38', '0205a58f87d0570c', '150d8155cd683836',
                'bda66a1e5e47b1e9', '37531634fc89c4c1', '23b42d33be6e63c3', '30f620a1a2550ed5', 'c0b338a0cd27c494',
                '70a4fcf24ef602b5', 'f34ed100fbd08019', '5c1fa169b7e31a77', '3b9f711de4208641', '65ce1a6da1c2c708',
                '8fd90f58f2056bbe', '3a635a11090b275f', '0fe918c2543f5966', 'ca26d7837564a8da', '87eef00675ce63bc',
                '235dd7541008761b', 'f662e0c02ef61ddd', 'eda1c8de165065fb', '786fdb63f0e444ce', '1c27ddb26c5bc2ff',
                'd4e448ce295aa7bd', '45adec3b534907b8', 'af13b4f3fa3c3e07', 'b6c5348f6cdcbb07', 'b76dfe951ba845cf',
                'c48cbc9a2f5a383a', 'e619eb6c63609f35', 'dc396fc70979d610', '00198fc439a4b20f', '96b2b875104f6dbd',
                '3b8ff0532bc7176b', '5a9d05810444f4ce', '4b7d74fb5bb5f10a', 'c76fd5e9b7403686', 'c209ef0b5ce31fe1',
                'f686c41d13035ad9', '7ea9308d9a01fa35', '5096fc837c61b58e', '7611b817ca4b0896', 'ef452e2a55e9a110',
                'd3d8ebba4816b79b', '56ef315acfb5f70d', '3d9212d1813ef0f2', 'c2df410b0e0f03db', '1bdc83a42fbca006',
                'a8e0d02151fd6358', 'a35b37fc652637ea', '2e6400d8f30cb3dc', '5edefcacade5d1a3', '8b360b83c36c1935',
            ],
        ];

        foreach ($seq as $seed => $bytesExpected) {
            $engine = new Xoshiro256StarStar(\hex2bin($seed));

            for ($i = 0; $i < 100; $i++) {
                $bytes = \bin2hex($engine->generate());
                self::assertEquals($bytesExpected[$i], $bytes, "Seed: $seed; Index: $i");
            }
        }
    }

    public function testJump(): void
    {
        $engine = new Xoshiro256StarStar(123456);

        self::assertEquals('c4cb8684e9e8d520', \bin2hex($engine->generate()));
        self::assertEquals('7d731154119f2626', \bin2hex($engine->generate()));
        self::assertEquals('d68c804f79efddb7', \bin2hex($engine->generate()));

        $engine->jump();

        self::assertEquals('e02b9c3c698a50be', \bin2hex($engine->generate()));
        self::assertEquals('a47af196a01f0697', \bin2hex($engine->generate()));
        self::assertEquals('cb4baa5e2923ab4f', \bin2hex($engine->generate()));

        $engine->jumpLong();

        self::assertEquals('c5a7ed51bdd8f289', \bin2hex($engine->generate()));
        self::assertEquals('fe1c26e9363fa406', \bin2hex($engine->generate()));
        self::assertEquals('b9345e05be4b96ea', \bin2hex($engine->generate()));
    }

    public function testSerialize(): void
    {
        $engine1 = new Xoshiro256StarStar();

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
            'O:32:"Random\Engine\Xoshiro256StarStar":2:{i:0;a:0:{}i:1;a:4:{i:0;s:16:"ef30ee3b093b3bbd";i:1;s:16:"4da7' .
            'adf2fac619de";i:2;s:16:"9e6b4c55d1c15380";i:3;s:16:"326ebe04d6f1b44f";}}';

        $engine1 = new Xoshiro256StarStar(123456);
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

        \serialize(new Xoshiro256StarStar());
    }
}
