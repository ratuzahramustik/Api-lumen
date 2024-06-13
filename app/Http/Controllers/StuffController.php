<?php

namespace App\Http\Controllers;

use App\Helpers\ApiFormatter;
use App\Models\Stuff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class StuffController extends Controller
{
    public function index()
    {
        $stuff = Stuff::with('stock')->get();

        return ApiFormatter::sendResponse(200,true, 'Lihat Semua Barang', $stuff);

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
                'name' => 'required',
                'category' => 'required',
            ]);
            $stuff = Stuff::create([
                'name' => $request->input ('name'),
                'category' => $request->input ('category'),
            ]);
            return ApiFormatter::sendResponse(201, true, 'barang berhasil disimpan!', $stuff);
        } catch (\Throwable $th) {
            if ($th->validator->errors()) {
                return ApiFormatter::sendResponse(400, false, 'terdapat kesalahan input silakan coba lagi!', $th->validator->errors());
            }else {
                return ApiFormatter::sendResponse(400, false, 'terdapat kesalahan input silakan coba laagi!', $th->getMessage());
            }
        }

        // $validator = Validator::make($request->all(), [
        //     'name' => 'required',
        //     'category' => 'required',
        // ]);

        // if ($validator->fails()) {

        //     return response()->json([
        //         'success' => false,
        //         'message' => 'semua kolom wajib diisi',
        //         'data' => $validator->errors()
        //     ],400);
        // } else {

        //     $stuff = Stuff::create([
        //         'name' => $request->input ('name'),
        //         'category' => $request->input ('category'),
        //     ]);

        //     if ($stuff) {
        //         return response()->json([
        //             'succes' => true,
        //             'message' => 'barang berhasil disimpan',
        //             'data' => $stuff
        //         ],201);
        //     } else {
        //         return response()->json([
        //             'success' => false,
        //             'message' => 'barang gagal disimpan',
        //         ], 400);
        //     }
        // }
    }



    public function show($id)
    {
        try {
            $stuff = Stuff::with('stock')->findOrFail($id);

            return ApiFormatter::sendResponse(200, true, "lihat barang dengan id $id", $stuff);

        } catch (\Throwable $th) {
            return ApiFormatter::sendResponse(404, false, "Data dengan id $id tidak ditemukan");
        }
    }



    public function update(Request $request, $id)
    {
        try {
                $stuff = stuff::findOrFail($id);
                $name = ($request->name) ? $request->name : $stuff->name;
                $category = ($request->category) ? $request-> category : $stuff-> category;

                $stuff->update([
                    'name' => $name,
                    'category' => $category
                ]);

                return ApiFormatter::sendResponse(200, true, "Berhasil ubah data dengan id $id");
        } catch (\Throwable $th) {
            return ApiFormatter::sendResponse(404, false, "Proses gagal silahkan coba lagi!", $th->getMessage());

        }
    }

    public function destroy($id)
    {
        try {
            $stuff = Stuff::findOrFail($id);

            $stuff->delete();

            return ApiFormatter::sendResponse(200, true, "Berhasil hapus data barang dengan id $id", ['id' => $id]);
        }catch (\Throwable $th) {
            return ApiFormatter::sendResponse(404, false, "Proses gagal silahkan coba lagi!", $th->getMessage());
        }
    }

    public function deleted()
    {
        try {
            $stuffs = Stuff::onlyTrashed()->get();

            return ApiFormatter::sendResponse(200, true, "lihat data barang yang dihapus", $stuffs);
        }catch (\Throwable $th) {
            //throw $th
            return ApiFormatter::sendResponse(404, false, "Proses gagal! silahkan coba lagi!", $th->getMessage());
        }
    }


    public function restore($id)
    {
        try {
            $stuff = Stuff::onlyTrashed()->where('id', $id);

            $stuff->restore();

            return ApiFormatter::sendResponse(200, true, "berhasil mengembalikan data yang telah dihapus!", ['id' => $id]);
          }catch (\Throwable $th) {
            //throw $th
            return ApiFormatter::sendResponse(404, false, "proses gagal! silahkan coba lagi!", $th->getMessage());
          }
    }

    public function restoreAll()
    {
        try{
            $stuffs = Stuff::onlyTrashed();

            $stuffs->restore();

            return ApiFormatter::sendResponse(200, true, "berhasil mengembalikan semua data yang telah dihapus!");
        }catch (\Throwable $th) {
            return ApiFormatter::sendResponse(404, false, "Proses gagal! silahkan coba lagi", $th->getMessage());
        }
    }


    public function permanentDelete($id)
    {
         try{
            $stuffs = Stuff::onlyTrashed()->where('id', $id)->forceDelete();

            return ApiFormatter::sendResponse(200, true, "berhasil hapus permanen data yang telah dihapus!", ['id' => $id]);
         }catch (\Throwable $th) {
            return ApiFormatter::sendResponse(404, false, "proses gagal! silahkan coba lagi", $th->getMessage());
         }
    }

    public function permanentDeleteAll()
    {
        try{
            $stuffs = Stuff::onlyTrashed();

            $stuffs -> forceDelete();

            return ApiFormatter::sendResponse(200, true, "Berhasil hapus permanen semua data yang telah dihapus!");
        }catch (\Throwable $th) {
            return ApiFormatter::sendResponse(404, false, "Proses gagal! silahkan coba lagi!", $th->getMessage());

        }
    }
}

