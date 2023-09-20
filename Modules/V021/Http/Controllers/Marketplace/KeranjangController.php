<?php

namespace Modules\V021\Http\Controllers\Marketplace;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\V021\Http\Repositories\KeranjangRepo;

class KeranjangController extends Controller
{
    private $repo;
    public function __construct(KeranjangRepo $repo)
    {
        $this->repo = $repo;
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $idMarket = $user->idMarket->user_id_market;

        return $this->repo->listProduk($idMarket);
    }

    public function show($keranjang)
    {
        dd($keranjang);
    }

    public function store(Request $req)
    {
        $allowed = $req->only(
            'produk_id',
            'seller_id',
            'table',
            'var_id',
            'harga',
            'qty',
            'catatan',
        );
        $validator = Validator::make($allowed, [
            'produk_id' => 'required',
            'seller_id' => 'required',
            'table' => 'required',
            'var_id' => 'required',
            'harga' => 'required',
            'qty' => 'required',
        ]);

        if ($validator->fails()) return response()->badRequest('Validasi Gagal',$validator->errors());

        $allowed['user'] = $req->user();
        return $this->repo->store($allowed);

    }


    public function update(Request $request, $id)
    {
        //
    }



    public function destroy($id)
    {
        //
    }
}
