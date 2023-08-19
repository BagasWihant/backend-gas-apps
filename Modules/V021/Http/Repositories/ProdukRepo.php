<?php

namespace Modules\V021\Http\Repositories;

use Illuminate\Support\Facades\File;
use App\Models\Produk\Fashion\ProdukFashionMain;
use App\Models\Produk\Fashion\ProdukFashionImage;
use App\Models\Produk\Fashion\ProdukFashionVariasi;

class ProdukRepo
{

    public function createProdukFashion($data)
    {

        // BUAT MAIN PRODUK
        ProdukFashionMain::create([
            'produk_id' => $data['produk_id'],
            'name' => $data['nama'],
            'desc' => $data['deskripsi'],
            'kategori' => $data['kategori'],
            'gender' => $data['gender'],
            'kondisi' => $data['kondisi'],
            'variasi' => $data['varian'],
            'berat' => $data['berat'][0] . '.' . $data['berat'][1],
        ]);

        // BUAT PRODUK VARIAN
        foreach ($data['produk'] as $produk) {
            $varian = explode(',', $produk['variant']);
            ProdukFashionVariasi::create([
                'produk_id' => $data['produk_id'],
                'var_1' => $varian[0],
                'var_2' => $varian[1],
                'harga' => $produk['harga'],
                'stok' => $produk['stok'],
            ]);
        }

        // SIMPAN IMG PRODUK

        $urut = 0;
        $path = public_path('image/produk/fashion');
        if (!File::exists($path)) File::makeDirectory($path, 0755, false, true);

        foreach ($data['foto'] as $foto) {
            $ext = $foto->getClientOriginalExtension();
            $fileName = $data['produk_id'] . '_' . $urut . '.' . $ext;
            $foto->move($path, $fileName);

            ProdukFashionImage::create([
                'produk_id' => $data['produk_id'],
                'img' => $fileName
            ]);
            $urut++;
        }
    }
}
