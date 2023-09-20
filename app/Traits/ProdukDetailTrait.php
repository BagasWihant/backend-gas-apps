<?php

namespace App\Traits;

use App\Models\Produk\Bumbu\ProdukBumbuMain;
use App\Models\Produk\Mandi\ProdukMandiMain;
use App\Models\Produk\UserBasic\ProdukUserMain;
use App\Models\Produk\Fashion\ProdukFashionMain;
use App\Models\Produk\Kosmetik\ProdukKosmetikMain;
use App\Models\Produk\BuahSayur\ProdukBuahSayurMain;
use App\Models\Produk\MakanMinum\ProdukMakanMinumMain;
use App\Models\Produk\KebutuhanPokok\ProdukKebutuhanPokokMain;


trait ProdukDetailTrait
{
    public function ProdukDetail($tableID, $is_user = null, $produkId)
    {
        $tableIDValue = $is_user ? 0 : $tableID;
        $tableMap = [
            0 => ProdukUserMain::class,
            1 => ProdukFashionMain::class,
            6 => ProdukKebutuhanPokokMain::class,
            7 => ProdukBuahSayurMain::class,
            8 => ProdukMakanMinumMain::class,
            9 => ProdukBumbuMain::class,
            10 => ProdukMandiMain::class,
            11 => ProdukKosmetikMain::class,
        ];

        if (isset($tableMap[$tableIDValue])) {
            $modelClass = $tableMap[$tableIDValue];
            $produk = $modelClass::where('produk_id', $produkId)->first();

            return $produk;
        }
    }
}
