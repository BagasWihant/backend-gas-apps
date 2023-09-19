<?php

namespace Modules\V021\Http\Repositories;

use App\Models\Store\Store;
use App\Models\Store\StoreDetail;
use Illuminate\Support\Facades\DB;

class StoreRepo
{

    public function create($allowed)
    {

        try {
            DB::connection('mysql_market')->beginTransaction();
            $type = $allowed['pict_profil']->getClientOriginalExtension();
            $name = $allowed['data']['store_id'] . ".$type";
            $allowed['pict_profil']->move($allowed['data']['pathPictStore'], $name);

            $store = Store::create(
                [
                    'user_id_market' => $allowed['data']['user']->idMarket->user_id_market,
                    'store_id' => $allowed['data']['store_id'],
                    'store_name' => $allowed['name'],
                    'store_lokasi' => isset($allowed['lokasi']) ? $allowed['lokasi'] : null,
                    'store_type' => $allowed['tipe'],
                    'store_kategori' => $allowed['kategori'],
                    'foto_profil' => $name,
                ]
            );

            $typeKTP = $allowed['pict_ktp']->getClientOriginalExtension();
            $nameKTP = $allowed['data']['store_id'] . "_KTP.$typeKTP";
            $allowed['pict_ktp']->move($allowed['data']['pathDetailStore'], $nameKTP);

            $typeSelf = $allowed['pict_self']->getClientOriginalExtension();
            $nameSelf = $allowed['data']['store_id'] . "_Selfie.$typeSelf";
            $allowed['pict_self']->move($allowed['data']['pathDetailStore'], $nameSelf);

            $storeDetail = StoreDetail::create([
                'store_id' => $allowed['data']['store_id'],
                'alamat' => $allowed['alamat'],
                'phone' => $allowed['phone'],
                'email' => $allowed['email'],
                'lon' => $allowed['lon'],
                'lat' => $allowed['lat'],
                'ktp_img' => $nameKTP,
                'self_img' => $nameSelf,
            ]);

            DB::connection('mysql_market')->commit();
            return env('APP_DEBUG') ? response()->created('Store ' . $allowed['name'] . ' Berhasil didaftarkan',$store->toArray()) : response()->created('Store ' . $allowed['name'] . ' Berhasil didaftarkan');
        } catch (\Throwable $th) {
            DB::connection('mysql_market')->rollBack();
            return env('APP_DEBUG') ? response()->badRequest($th->getMessage()) : response()->badRequest();
        }
    }
}
