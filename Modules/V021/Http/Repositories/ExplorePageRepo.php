<?php

namespace Modules\V021\Http\Repositories;

use App\Models\Produk\BuahSayur\ProdukBuahSayurMain;
use App\Models\Produk\Bumbu\ProdukBumbuMain;
use App\Models\Produk\Fashion\ProdukFashionMain;
use App\Models\Produk\KebutuhanPokok\ProdukKebutuhanPokokMain;
use App\Models\Produk\Kosmetik\ProdukKosmetikMain;
use App\Models\Produk\MakanMinum\ProdukMakanMinumMain;
use App\Models\Produk\Mandi\ProdukMandiMain;
use App\Models\Produk\ProdukMaster;
use App\Models\Produk\UserBasic\ProdukUserMain;
use Illuminate\Support\Facades\DB;


class ExplorePageRepo
{
    public function temukanProduk($data)
    {
        $binding = $data['binding'];
        $filter = $data['filter'];
        $where = 'MATCH(name) AGAINST(? IN BOOLEAN MODE)';


        if (!empty($filter)) {
            $binding[] = $filter;
            $where .= " AND MATCH(key_filter) AGAINST(? IN BOOLEAN MODE)";
        }

        $commonColumns = ['produk_id', 'name', 'img', 'rating', 'harga'];
        try {

            $query = DB::connection('mysql_market')
                ->table('produk_masters')
                ->select($commonColumns)
                ->whereRaw($where, $binding);

            if (isset($key['sort'])) {
                $sort = $key['sort'];
                if ($sort == 'hgdesc') $query->orderBy('harga', 'desc');
                elseif ($sort == 'hgasc') $query->orderBy('harga', 'asc');
                elseif ($sort == 'dtasc') $query->orderBy('created_at', 'asc');
                elseif ($sort == 'lrs') $query->orderBy('terjual', 'desc');
            }

            $results = $query->paginate(25);
            $res['data'] = $results->items();
            $res['maxPage'] = $results->lastPage();
            $res['currentPage'] = $results->currentPage();
            return response($res);
        } catch (\Throwable $th) {
            $res = env('APP_DEBUG') ? response()->badRequest($th->getMessage()) : response()->badRequest();
            return $res;
        }
    }

    // Detail Produk
    public function detail($id)
    {
        $tableID = ProdukMaster::where('produk_id', $id)->select(['is_user', 'table'])->first();

        if ($tableID) {

            $tableIDValue = ($tableID->is_user) ? 0 : $tableID->table;
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
                $produk = $modelClass::where('produk_id', $id)->first();
                $image = $produk->images()->select('img')->get();
                $variasi = $produk->variasi()->select(['var_1', 'var_2', 'harga', 'stok', 'id AS kode'])->get();
                $data['is_user'] = $tableID->is_user ? 1 : 0 ;
                $data['image'] = $image;
                $data['variasi'] = $variasi;
                $data['produk'] = $produk;

                return response($data);
            }

            return response()->badRequest('Kategori Tidak Ada');
        } else {
            return response()->badRequest('Produk Tidak DItemukan');
        }
    }
}
