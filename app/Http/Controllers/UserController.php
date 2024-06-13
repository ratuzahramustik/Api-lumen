<?php

namespace App\Http\Controllers;

use App\Helpers\ApiFormatter;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    public function index()
    {
        $User = user::All();

        return ApiFormatter::sendResponse(200,true, 'Lihat Semua Barang', $User);

        // return response()->json([
        //     'success' => true,
        //     'message' => 'lihat semua barang',
        //     'data' => $stuff
        // ], 200);
    }

    public function store(Request $request)
    {

        try {
            $this->validate($request, [
                'username' => 'required|min:4|unique:users,username',
                'email' => 'required|unique:users,email',
                'password' => 'required',
                'role' => 'required'
            ]);
            $User = User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => Hash::make ($request->password),
                'role' => $request->input ('role'),
            ]);


            if ($User) { // Memeriksa apakah $prosesData adalah instance model yang valid
                return ApiFormatter::sendResponse(200, 'success', $User);
            } else {
                return ApiFormatter::sendResponse(400, 'bad_request', 'Gagal menambahkan data, silahkan coba lagi !');
            }
        } catch (\Exception $err){
            return ApiFormatter::sendResponse(400, 'bad_request', $err->getMessage());
        }


    }



    // public function show($id)
    // {
    //     try {
    //         $User = user::findOrFail($id);

    //         return ApiFormatter::sendResponse(200, true, "lihat barang dengan id $id", $User);

    //     } catch (\Throwable $th) {
    //         return ApiFormatter::sendResponse(404, false, "Data dengan id $id tidak ditemukan");
    //     }
    // }



    // public function update(Request $request, $id)
    // {
    //     try {
    //             $User = user::findOrFail($id);
    //             $Username= ($request->Username) ? $request->Username : $User->Username;
    //             $email = ($request->email) ? $request-> email : $User-> email;
    //             $password = ($request->password) ? $request-> password : $User-> password;
    //             $role = ($request->role) ? $request-> role : $User-> role;


    //             $User->update([
    //                 'Username' => $Username,
    //                 'email' => $email,
    //                 'password' => $password,
    //                 'role'=> $role
    //             ]);

    //             return ApiFormatter::sendResponse(200, true, "Berhasil ubah data dengan id $id");
    //     } catch (\Throwable $th) {
    //         return ApiFormatter::sendResponse(404, false, "Proses gagal silahkan coba lagi!", $th->getMessage());

    //     }
    // }

    // public function destroy($id)
    // {
    //     try {
    //         $User = User::findOrFail($id);

    //         $User->delete();

    //         return ApiFormatter::sendResponse(200, true, "Berhasil hapus data user dengan id $id", ['id' => $id]);
    //     }catch (\Throwable $th) {
    //         return ApiFormatter::sendResponse(404, false, "Proses gagal silahkan coba lagi!", $th->getMessage());
    //     }
    // }

    // public function deleted()
    // {
    //     try {
    //         $Users = User::onlyTrashed()->get();

    //         return ApiFormatter::sendResponse(200, true, "lihat data barang yang dihapus", $Users);
    //     }catch (\Throwable $th) {
    //         //throw $th
    //         return ApiFormatter::sendResponse(404, false, "Proses gagal! silahkan coba lagi!", $th->getMessage());
    //     }
    // }


    // public function restore($id)
    // {
    //     try {
    //         $User = User::onlyTrashed()->where('id', $id);

    //         $User->restore();

    //         return ApiFormatter::sendResponse(200, true, "berhasil mengembalikan data yang telah dihapus!", ['id' => $id]);
    //       }catch (\Throwable $th) {
    //         //throw $th
    //         return ApiFormatter::sendResponse(404, false, "proses gagal! silahkan coba lagi!", $th->getMessage());
    //       }
    // }

    // public function restoreAll()
    // {
    //     try{
    //         $Users = User::onlyTrashed();

    //         $Users->restore();

    //         return ApiFormatter::sendResponse(200, true, "berhasil mengembalikan semua data yang telah dihapus!");
    //     }catch (\Throwable $th) {
    //         return ApiFormatter::sendResponse(404, false, "Proses gagal! silahkan coba lagi", $th->getMessage());
    //     }
    // }


    // public function permanentDelete($id)
    // {
    //      try{
    //         $Users = User::onlyTrashed()->where('id', $id)->forceDelete();

    //         return ApiFormatter::sendResponse(200, true, "berhasil hapus permanen data yang telah dihapus!", ['id' => $id]);
    //      }catch (\Throwable $th) {
    //         return ApiFormatter::sendResponse(404, false, "proses gagal! silahkan coba lagi", $th->getMessage());
    //      }
    // }

    // public function permanentDeleteAll()
    // {
    //     try{
    //         $Users = User::onlyTrashed();

    //         $Users -> forceDelete();

    //         return ApiFormatter::sendResponse(200, true, "Berhasil hapus permanen semua data yang telah dihapus!");
    //     }catch (\Throwable $th) {
    //         return ApiFormatter::sendResponse(404, false, "Proses gagal! silahkan coba lagi!", $th->getMessage());

    //     }
    // }

    public function show($id)
    {
        try {
            $data = User::where('id', $id)->first();
            return ApiFormatter::sendResponse(200, 'success', $data);
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function update(Request $Request, $id)
{
    try {
        $this->validate($Request, [
            'username' => 'required|min:4|unique:users,username,' . $id,
            'email' => 'required|unique:users,email,' . $id,
            'password' => 'required|min:6',
            'role' => 'required'
        ]);

        $checkProses = User::where('id', $id)->update([
            'username' => $Request->username,
            'email' => $Request->email,
            'password' => hash::make($Request->password),
            'role' => $Request->role
        ]);

        if ($checkProses) {
            $data = User::where('id', $id)->first();

            return ApiFormatter::sendResponse(200, 'success', $data);
        }
    } catch (\Exception $err) {
        return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
    }
}

    public function destroy($id)
    {
        try {
            $checkproses = User::where('id', $id)->delete();

            if ($checkproses) {
                return
                    ApiFormatter::sendResponse(200, 'succes', 'berhasil hapus data User!');
            }
        } catch (\Exception $err) {
            return
                ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function trash()
    {
        try {
            $data = User::onlyTrashed()->get();

            return
                ApiFormatter::sendResponse(200, 'succes', $data);
        } catch (\Exception $err) {
            return
                ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            $checkRestore = User::onlyTrashed()->where('id',$id)->restore();

            if ($checkRestore) {
                $data = User::where('id', $id)->first();
                return ApiFormatter::sendResponse(200, 'succes', $data);
            }
        }catch (\Exception $err) {
            return ApiFormatter::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function permanentDelete($id)
    {
        try{
            $cekPermanentDelete = User::onlyTrashed()->where('id', $id)->forceDelete();

            if ($cekPermanentDelete) {
                return
                ApiFormatter::sendResponse(200, 'success','Berhasil menghapus data secara permanen' );
            }
        } catch (\Exception $err) {
            return
            ApiFormatter::sendResponse(400,'bad_request', $err->getMessage());
        }

    }

   
}

