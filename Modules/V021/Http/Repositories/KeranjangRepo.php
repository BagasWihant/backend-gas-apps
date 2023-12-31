<?php

namespace Modules\V021\Http\Repositories;

use App\Models\Marketplace\Keranjang;
use App\Models\Produk\Bumbu\ProdukBumbuMain;
use App\Models\Produk\Mandi\ProdukMandiMain;
use App\Models\Produk\Kosmetik\ProdukKosmetikMain;
use App\Models\Produk\BuahSayur\ProdukBuahSayurMain;
use App\Models\Produk\Fashion\ProdukFashionMain;
use App\Models\Produk\MakanMinum\ProdukMakanMinumMain;
use App\Models\Produk\KebutuhanPokok\ProdukKebutuhanPokokMain;
use App\Models\Produk\UserBasic\ProdukUserMain;
use App\Models\User;
use App\Traits\ImagePathTraits;

class KeranjangRepo
{
    use ImagePathTraits;
    public function store($data)
    {
        // SAMPAI BUAT KERANJANG
        $user = $data['user'];

        $idMarket = $user->idMarket->user_id_market;

        $checkItem = Keranjang::select('produk_id')->where([
            'user_id' => $idMarket,
            'produk_id' => $data['produk_id'],
            'var_id' => $data['var_id']
        ])->first();
        if (!$checkItem) {

            Keranjang::create([
                'user_id' => $idMarket,
                'seller_id' => $data['seller_id'],
                'produk_id' => $data['produk_id'],
                'table_id' => $data['table'],
                'var_id' => $data['var_id'],
                'harga' => $data['harga'],
                'catatan' => isset($data['catatan']) ? $data['catatan'] : '',
                'qty' => isset($data['qty']) ? $data['qty'] : 1,
            ]);

            return response()->ok('Berhasil menambahkan ke keranjang');
        }

        return response()->ok('Produk Sudah di keranjang');
    }


    public function listProduk($id)
    {

        $item = Keranjang::select(['seller_id', 'produk_id', 'table_id', 'var_id', 'harga', 'qty'])->where('user_id', $id)->orderBy('created_at', 'desc')->paginate(15);
        $storeProducts = [];

        try {
            foreach ($item as $item) {

                $idSeller = $item->seller()->first();
                $photoPath = '';
                if ($item->table_id == 0) {
                    $store = User::select(['name', 'photo'])->find($idSeller->user_id_main);
                    if ($store) $photoPath = $this->imagePathUser($store->photo);
                    $is_store = 0;
                } else {
                    $store = $idSeller->store()->select(['store_name as name', 'foto_profil as photo'])->first();
                    if ($store) $photoPath = $this->imagePathStore($store->photo);
                    $is_store = 1;
                }

                $tableID = $item->table_id;

                switch ($tableID) {
                    case 0:
                        $modelName = ProdukUserMain::class;
                        break;
                    case 1:
                    case 2:
                    case 3:
                    case 4:
                    case 5:
                        $modelName = ProdukFashionMain::class;
                        break;
                    case 6:
                        $modelName = ProdukKebutuhanPokokMain::class;
                        break;
                    case 7:
                        $modelName = ProdukBuahSayurMain::class;
                        break;
                    case 8:
                        $modelName = ProdukMakanMinumMain::class;
                        break;
                    case 9:
                        $modelName = ProdukBumbuMain::class;
                        break;
                    case 10:
                        $modelName = ProdukMandiMain::class;
                        break;
                    case 11:
                        $modelName = ProdukKosmetikMain::class;
                        break;
                    default:
                        return response()->json(['message' => 'Kategori produk yang dipilih tidak ada']);
                }

                if (!isset($storeProducts[$store->name])) $storeProducts[$store->name] = ['name' => $store->name, 'photo' => $photoPath, 'seller' => $item->seller_id, 'store'=>$is_store , 'data' => []];

                $produk = $modelName::where('produk_id', $item->produk_id)->select(['produk_id', 'name'])->first();
                $img = $produk->images()->select('img')->first();

                if ($img) {
                    $img->img = $this->imagePathProduk($item->table_id, $img->img);
                }

                $variasi = $produk->variasi()->select(['var_1', 'var_2', 'stok', 'harga'])->find($item->var_id);

                $itemData = [
                    'name' => $produk->name,
                    'idProduct' => $produk->produk_id,
                    'idVar' => $item->var_id,
                    'qty' => $item->qty,
                    'var_1' => $variasi ? $variasi->var_1 : null,
                    'var_2' => $variasi ? $variasi->var_2 : null,
                    'harga' => $variasi ? $variasi->harga : null,
                    'stok' => $variasi ? $variasi->stok : null,
                    'img' => $img ? $img->img : null,
                ];

                $storeProducts[$store->name]['data'][] = $itemData;
            }

            $data = array_values($storeProducts);
            $res = count($data) > 0 ? response()->json($data) : response()->ok('Belum ada Produk');
            return $res;
        } catch (\Throwable $th) {
            return env('APP_DEBUG') ? response()->internalServerError($th->getMessage(), $th->getTrace()) : response()->internalServerError();
        }
    }

    public function updateQty($id,$int=null){
        $keranjang = Keranjang::where('produk_id',$id)->select(['id','produk_id','qty'])->first();

        if($int) $keranjang->qty = $int;
        else $keranjang->qty ++;

        $keranjang->save();
    }

    public function deleteItem($id){
        $keranjang = Keranjang::where('produk_id',$id)->select(['id','produk_id'])->first();
        if($keranjang) {
            $keranjang->delete();
            return response()->ok('Produk terhapus');
        }
        return response()->badRequest('Produk Tidak ada di keranjang');
    }
}
