<?php

namespace Modules\V021\Http\Repositories;

use App\Http\Resources\dataSend;
use App\Models\Marketplace\Keranjang;

class KeranjangRepo
{
    public function store($data)
    {
        // SAMPAI BUAT KERANJANG
        $user = $data->user();

        $idMarket = $user->idMarket->user_id_market;

        $checkItem = Keranjang::select('produk_id')->where([
            'user_id' => $idMarket,
            'produk_id' => $data->produk_id,
            'var_id' => $data->var_id
        ])->first();
        if (!$checkItem->produk_id) {

            Keranjang::create([
                'user_id' => $idMarket,
                'seller_id' => $data->seller_id,
                'produk_id' => $data->produk_id,
                'table_id' => $data->kategori,
                'is_user' => $data->is_user,
                'var_id' => $data->var_id,
                'harga' => $data->harga,
                'catatan' => isset($data->catatan) ? $data->catatan : '',
                'qty' => isset($data->qty) ? $data->qty : 1,
            ]);

            return response()->json(['message'=>'Ditambahkan ke Cart']);
        }
        return response()->json(['message'=>'Sudah ada di keranjang']);
    }
}
