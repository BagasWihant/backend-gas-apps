<?php

namespace Modules\V021\Http\Controllers\User;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Resources\Respons;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Support\Renderable;

class ProfileController extends Controller
{

    public function getData()
    {
        $user = Auth::user();

        $coll = collect([
            'name' => $user->name,
            'mail' => $user->email,
            'phone' => $user->phone,
            'jk' => $user->jenis_kelamin,
            'tgl' => $user->tgl_lahir,
        ]);

        return new Respons(true, '', $coll);
    }

    public function update(Request $req)
    {
        $user = Auth::user();
        $input = $req->only('name', 'mail', 'phone', 'jk', 'tgl');
        $validator = Validator::make($input, [
            'mail' => 'required|email:filter,spoof,dns|unique:users,email,' . $user->id
        ]);
        if ($validator->fails()) return new Respons(false, 'Validation Failed', $validator->errors());

        try {
            $updating = User::where('id', $user->id)->where('email', $user->email)->first();
            if ($input['mail']) $updating->email = $input['mail'];
            if ($input['name']) $updating->name = $input['name'];
            if ($input['phone']) $updating->phone = $input['phone'];
            if ($input['jk']) $updating->jenis_kelamin = Str::upper($input['jk']);
            if ($input['tgl']) $updating->tgl_lahir = $input['tgl'];
            $updating->save();

            return new Respons(true, 'Update sukses');
        } catch (\Throwable $th) {

            $debug = config('app.debug');
            if ($debug) return new Respons(false, 'Update Gagal', $th);
            return new Respons(false, 'Update Gagal');
        }
    }

    public function changeFoto(Request $req)
    {
        $userID = $req->user()->id;

        $valid = Validator::make($req->only('img'),[
            'img' => 'required|image|mimes:jpg,png,jpeg|max:2048'
        ]);
        if ($valid->fails()) return new Respons(false, 'Validation Failed', $valid->errors());

        $path =  public_path('image/user_profile');
        if (!File::exists($path)) File::makeDirectory($path, 0755, false, true);

        // if (File::exists(public_path($this->category->image))) {
        //     File::delete(public_path($this->category->image));
        // }
        try {
            DB::beginTransaction();
            $ext = $req->img->getClientOriginalExtension();
            $name = $userID."_profile.$ext";
            $req->img->move($path,$name);

            User::find($userID)->update(['photo' => $name]);
            DB::commit();
            return new Respons(false,'Sukses Ganti foto');
        } catch (\Throwable $th) {
            DB::rollBack();
            return new Respons(false,'Ganti foto gagal',$th->getMessage());
            //throw $th;
        }
    }
}
