<?php

namespace Modules\V021\Http\Controllers\Store;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Resources\Respons;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;

use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Support\Renderable;
use Modules\V021\Http\Repositories\StoreRepo;

class StoreController extends Controller
{
    private $storeRepo;
    public function __construct(StoreRepo $storeRepo)
    {
        $this->storeRepo = $storeRepo;
    }

    public function createStore(Request $req)
    {
        $allowed = $req->only(
            'name',
            'email',
            'phone',
            'tipe',
            'kategori',
            'kab',
            'kec',
            'alamat',
            'alamat2',
            'lon',
            'lat',
            'pict_profil',
            'pict_ktp',
            'pict_self',
            'lokasi'
        );
        $validator = Validator::make($allowed, [
            'name' => 'required',
            'email' => 'required|email:filter,spoof,dns|unique:mysql_market.store_details,email',
            'phone' => 'required',
            'tipe' => 'required',
            'kategori' => 'required',
            'kab' => 'required',
            'kec' => 'required',
            'alamat' => 'required',
            'pict_profil' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'pict_ktp' => 'required|image|mimes:jpg,png,jpeg|max:2048',
            'pict_self' => 'required|image|mimes:jpg,png,jpeg|max:2048',
        ]);
        if ($validator->fails()) return response()->badRequest('Validasi Gagal',$validator->errors());

        $timestamp = Carbon::now()->timestamp;
        $rand = rand(1000, 9999);
        $store_id = $rand . substr($timestamp, 2);

        $pathPictStore = public_path('image/store_profile');
        if (!File::exists($pathPictStore)) File::makeDirectory($pathPictStore, 0755, false, true);

        $pathDetailStore = public_path('image/store_detail_pict');
        if (!File::exists($pathDetailStore)) File::makeDirectory($pathDetailStore, 0755, false, true);

        $allowed['data'] = collect([
            "store_id" => $store_id,
            "pathPictStore" => $pathPictStore,
            "pathDetailStore" => $pathDetailStore,
            "user" => $req->user()
        ]);

        return $this->storeRepo->create($allowed);
        // if(!$proses[0]) return new Respons(false,'Gagal mendaftarkan Toko');

        // return new Respons(true,'Berhasil mendaftarkan Toko');

    }

}
