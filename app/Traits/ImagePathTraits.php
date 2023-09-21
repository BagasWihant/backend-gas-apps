<?php

namespace App\Traits;

use App\Enums\TypeImageEnum;

trait ImagePathTraits{
    public function imagePathProduk($table,$fileName){

        $pathMap = [
            0 => 'bukan_toko',
            1 =>'fashion',
            6 => 'kebutuhan_pokok',
            7 => 'buah_sayur',
            8 => 'makan_minum',
            9 => 'bumbu',
            10 =>'mandi',
            11 =>  'kosmetik',

        ];
        return url('image/produk/' . $pathMap[$table] . '/'. $fileName);

    }
}
