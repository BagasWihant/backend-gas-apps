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
    public function ProdukDetail($tableID, $produkId, array $column = [])
    {
        $tableIDValue = $tableID;
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
            if ($column == null) $produk = $modelClass::where('produk_id', $produkId)->first();
            else $produk = $modelClass::where('produk_id',$produkId)->select($column)->first();
            return $produk;
        }
    }
}
