<?php

namespace Modules\V021\Http\Controllers\Marketplace;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Marketplace\Keranjang;
use App\Models\Produk\Bumbu\ProdukBumbuMain;
use App\Models\Produk\Mandi\ProdukMandiMain;
use Modules\V021\Http\Repositories\KeranjangRepo;
use App\Models\Produk\Kosmetik\ProdukKosmetikMain;
use App\Models\Produk\BuahSayur\ProdukBuahSayurMain;
use App\Models\Produk\MakanMinum\ProdukMakanMinumMain;
use App\Models\Produk\KebutuhanPokok\ProdukKebutuhanPokokMain;
use App\Models\Produk\UserBasic\ProdukUserMain;
use App\Models\User;

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

        $item = Keranjang::select(['seller_id','produk_id', 'table_id', 'var_id', 'harga', 'qty', 'is_user'])->where('user_id', $idMarket)->get();
        $temp = [];
        foreach ($item as $item) {
            if ($item->is_user == 1) {
                $produk = ProdukUserMain::where('produk_id', $item->produk_id)->select(['produk_id','name'])->first();
                $img = $produk->images()->select('img')->first();
                $variasi = $produk->variasi()->select(['var_1', 'var_2', 'stok', 'harga'])->find($item->var_id);

                $idSeller = $item->seller()->first();
                $store = User::select('name')->find($idSeller->user_id_main)->name;
            } else {
                $idSeller = $item->seller()->first();
                $store = $idSeller->store()->first()->store_name;

                $tableID = $item->table_id;

                switch ($tableID) {

                    case 6:
                        $produk = ProdukKebutuhanPokokMain::where('produk_id', $item->produk_id)->select(['produk_id','name'])->first();
                        $img = $produk->images()->select('img')->first();
                        $variasi = $produk->variasi()->select(['var_1', 'var_2', 'stok', 'harga'])->find($item->var_id);
                        break;

                    case 7:
                        $produk = ProdukBuahSayurMain::where('produk_id', $item->produk_id)->select(['produk_id','name'])->first();
                        $img = $produk->images()->select('img')->first();
                        $variasi = $produk->variasi()->select(['var_1', 'var_2', 'stok', 'harga'])->find($item->var_id);


                        break;

                    case 8:
                        $produk = ProdukMakanMinumMain::where('produk_id', $item->produk_id)->select(['produk_id','name'])->first();
                        $img = $produk->images()->select('img')->first();
                        $variasi = $produk->variasi()->select(['var_1', 'var_2', 'stok', 'harga'])->find($item->var_id);

                        break;

                    case 9:
                        $produk = ProdukBumbuMain::where('produk_id', $item->produk_id)->select(['produk_id','name'])->first();
                        $img = $produk->images()->select('img')->first();
                        $variasi = $produk->variasi()->select(['var_1', 'var_2', 'stok', 'harga'])->find($item->var_id);

                        break;

                    case 10:
                        $produk = ProdukMandiMain::where('produk_id', $item->produk_id)->select(['produk_id','name'])->first();
                        $img = $produk->images()->select('img')->first();
                        $variasi = $produk->variasi()->select(['var_1', 'var_2', 'stok', 'harga'])->find($item->var_id);
                        break;

                    case 11:
                        $produk = ProdukKosmetikMain::where('produk_id', $item->produk_id)->select(['produk_id','name'])->first();
                        $img = $produk->images()->select('img')->first();
                        $variasi = $produk->variasi()->select(['var_1', 'var_2', 'stok', 'harga'])->find($item->var_id);
                        break;

                    default:
                        return [false, 'Kategori produk yang dipilih tidak ada'];
                        break;
                }
            }

            $item['var_1'] = $variasi->var_1;
            $item['var_2'] = $variasi->var_2;
            $item['harga'] = $variasi->harga;
            $item['stok'] = $variasi->stok;
            $item['img'] = $img->img;
            $item['store'] = $store;
            $temp[] = $item ;
        }
        $collect = collect($temp)->groupBy('store');
        return response()->json($collect);
    }

    public function show($keranjang)
    {
        dd($keranjang);
    }

    public function store(Request $request)
    {
        return $this->repo->store($request);

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
