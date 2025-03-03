<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Core\Models\Platform;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    public $coupons = [
        ['sn' => 'BE202502250', 'pin' => '2ECRT5381MB787', 'value' => '5'],
        ['sn' => 'BE202502251', 'pin' => '2ECEK16CPS161B', 'value' => '5'],
        ['sn' => 'BE202502252', 'pin' => '2ECTMG5344R1R8', 'value' => '5'],
        ['sn' => 'BE202502253', 'pin' => '2ECDHV2GSV2EM7', 'value' => '5'],
        ['sn' => 'BE202502254', 'pin' => '2ECHJAA42Q8AEJ', 'value' => '5'],
        ['sn' => 'BE202502255', 'pin' => '2ECE6QJQUJUUVT', 'value' => '5'],
        ['sn' => 'BE202502256', 'pin' => '2ECPMB2N8GN28N', 'value' => '5'],
        ['sn' => 'BE202502257', 'pin' => '2ECU1BN43AS7MR', 'value' => '5'],
        ['sn' => 'BE202502258', 'pin' => '2EC54U17MBE144', 'value' => '5'],
        ['sn' => 'BE202502259', 'pin' => '2EC1UEEBQHMHEH', 'value' => '5'],
        ['sn' => 'BE202502210', 'pin' => '2ECTN6D1CFQJQE', 'value' => '10'],
        ['sn' => 'BE202502211', 'pin' => '2ECDBUV5RDM5FT', 'value' => '10'],
        ['sn' => 'BE202502212', 'pin' => '2ECF3J367RFEPH', 'value' => '10'],
        ['sn' => 'BE202502213', 'pin' => '2ECMVC75FVJ1V7', 'value' => '10'],
        ['sn' => 'BE202502214', 'pin' => '2ECTH8P7JC32G8', 'value' => '10'],
        ['sn' => 'BE202502215', 'pin' => '2ECKKVF41BRSQH', 'value' => '10'],
        ['sn' => 'BE202502216', 'pin' => '2ECTH7V11FD4AM', 'value' => '10'],
        ['sn' => 'BE202502217', 'pin' => '2EC7CN7TCVJV61', 'value' => '20'],
        ['sn' => 'BE202502218', 'pin' => '2ECGDTS8K28R1D', 'value' => '20'],
        ['sn' => 'BE202502219', 'pin' => '2ECE8EJ1U2RPMP', 'value' => '20'],
        ['sn' => 'BE202502220', 'pin' => '2ECG7JRDKH5TRF', 'value' => '20'],
        ['sn' => 'BE202502221', 'pin' => '2EC2QP36TEBT13', 'value' => '20'],
        ['sn' => 'BE202502222', 'pin' => '2ECCSNKJ6HK6N7', 'value' => '20'],
        ['sn' => 'BE202502223', 'pin' => '2ECF4SECBA4E7C', 'value' => '20'],
        ['sn' => 'BE202502224', 'pin' => '2ECTKGDRA5AUDS', 'value' => '20'],
        ['sn' => 'BE202502225', 'pin' => '2EC1J22MK8RKCJ', 'value' => '20'],
        ['sn' => 'BE202502226', 'pin' => '2ECTP2RSQAVNQ4', 'value' => '20'],
        ['sn' => 'BE202502227', 'pin' => '2ECVC8U36E272J', 'value' => '50'],
        ['sn' => 'BE202502228', 'pin' => '2ECS77REKMB3KN', 'value' => '50'],
        ['sn' => 'BE202502229', 'pin' => '2EC41UQ44FENB5', 'value' => '50'],
        ['sn' => 'BE202502230', 'pin' => '2ECVV4528C2EVS', 'value' => '50'],
        ['sn' => 'BE202502231', 'pin' => '2ECKGFFVSE1CQ7', 'value' => '50'],
        ['sn' => 'BE202502232', 'pin' => '2ECHHFKGDFHRUP', 'value' => '50'],
        ['sn' => 'BE202502233', 'pin' => '2EC4MKS5KJ2STE', 'value' => '50'],
        ['sn' => 'BE202502234', 'pin' => '2ECR381MKV2G4N', 'value' => '50'],
        ['sn' => 'BE202502235', 'pin' => '2ECBEBGPMFETEV', 'value' => '50'],
        ['sn' => 'BE202502236', 'pin' => '2ECQ1D61RF1PKB', 'value' => '50'],
        ['sn' => 'BE202502237', 'pin' => '2ECMRK3Q1V1682', 'value' => '50'],
        ['sn' => 'BE202502238', 'pin' => '2ECD1KJJ14KR8J', 'value' => '50'],
        ['sn' => 'BE202502239', 'pin' => '2ECB114FMD6E3M', 'value' => '50'],
        ['sn' => 'BE202502240', 'pin' => '2ECDTC3GES8J35', 'value' => '50'],
        ['sn' => 'BE202502241', 'pin' => '2ECS4NFDBK6RSQ', 'value' => '50'],
        ['sn' => 'BE202502242', 'pin' => '2ECS3R2E3VKDRB', 'value' => '50'],
        ['sn' => 'BE202502243', 'pin' => '2ECGG2H3S1PPHC', 'value' => '5'],
        ['sn' => 'BE202502244', 'pin' => '2EC7S28P7AF3PN', 'value' => '100'],
        ['sn' => 'BE202502245', 'pin' => '2ECUT65CG8DP2H', 'value' => '100'],
        ['sn' => 'BE202502246', 'pin' => '2ECHKAQN7S2KF3', 'value' => '100'],
        ['sn' => 'BE202502247', 'pin' => '2ECDSD6P4TA5HM', 'value' => '100'],
        ['sn' => 'BE202502248', 'pin' => '2ECN2TCA5RJMUQ', 'value' => '100'],
        ['sn' => 'BE202502249', 'pin' => '2EC3H3QESPGR1S', 'value' => '100']
    ];


    public function run()
    {
        $platforms = Platform::all();

        foreach ($platforms as $platform) {
            foreach ($this->coupons as $coupon) {
                $coupon['platform_id'] = $platform->id;
                $coupon['sn'] = $platform->id . '00' . $coupon['sn'];
                $coupon['pin'] = $platform->id . '00' . $coupon['pin'];
                Coupon::create($coupon);
            }
        }
    }
}
