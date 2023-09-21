<?php

namespace App\Enums;

enum TypeImageEnum:string {
    case store = 'store_profile';
    case profile = 'user_profile';
    case storeDetail = 'store_detail_pict';
    case produk = 'produk';
}
