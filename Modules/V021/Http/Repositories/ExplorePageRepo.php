<?php

namespace Modules\V021\Http\Repositories;

use App\Traits\ProdukDetailTrait;
use Illuminate\Support\Facades\DB;
use App\Models\Produk\ProdukMaster;

class ExplorePageRepo
{
    use ProdukDetailTrait;

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
        $tableID = ProdukMaster::where('produk_id', $id)->select('table')->first();

        if ($tableID) {
            $produk = $this->ProdukDetail($tableID->table,$id);
            if($produk){

                $image = $produk->images()->select('img')->get();
                $variasi = $produk->variasi()->select(['var_1', 'var_2', 'harga', 'stok', 'id AS kode'])->get();
                $produk['table'] =$tableID->table;
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
