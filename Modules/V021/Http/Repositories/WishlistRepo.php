<?php

namespace Modules\V021\Http\Repositories;

use App\Models\Marketplace\Wishlist;
use App\Traits\ImagePathTraits;
use App\Traits\ProdukDetailTrait;

class WishlistRepo
{

    use ProdukDetailTrait;
    use ImagePathTraits;
    public function store($data)
    {
        $user_id = $data['user']->idMarket->user_id_market;
        $produk = $this->ProdukDetail($data['table'], $data['produk_id']);

        $img = $produk->images()->select('img')->first();

        try {
            $cek = Wishlist::where(['produk_id'=>$data['produk_id'], 'user_id'=>$user_id])->first();
            if($cek) return response()->badRequest('Produk sudah ada di keranjang');
            Wishlist::create([
                'produk_id' => $data['produk_id'],
                'user_id' => $user_id,
                'table' => $data['table'],
                'img' => $img['img']
            ]);

            return response()->ok('Produk Berhasil ditambahkan ke Wishlist');
        } catch (\Throwable $th) {
            return env('APP_DEBUG') ? response()->badRequest($th->getMessage(), $th->getTrace()) : response()->badRequest('Kesalahan sistem');
        }
    }

    public function listProduk($id)
    {
        $wl = Wishlist::where('user_id', $id)->select(['produk_id', 'table', 'img'])->paginate(25);
        $dt = [];
        foreach ($wl->items() as $key) {
            $produk = $this->ProdukDetail($key->table, $key->produk_id, ['produk_id', 'name', 'rating']);
            $data = [
                'produk_id' => $produk->produk_id,
                'name' => $produk->name,
                'rating' => $produk->rating,
                'img' => $this->imagePathProduk($key->table, $key->img),
                'stok' => '',
                'terjual' => '',
            ];
            $dt[] = $data;
        }
        if ($dt) return $dt;

        return response()->ok('Tidak ada Produk di Wishlist');
    }

    public function deleteItem($id,$user_id)
    {

        $wl = Wishlist::where(['produk_id'=> $id, 'user_id'=>$user_id])->delete();
        if ($wl) return response()->ok('Terhapus dari Wishlist');

        return response()->badRequest('Produk Tidak ada');
    }
}
