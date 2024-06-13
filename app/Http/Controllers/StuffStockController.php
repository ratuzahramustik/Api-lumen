<?php

namespace App\Http\Controllers;

use App\Helpers\ApiFormatter;
use App\Models\Stuff;
use App\Models\StuffStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class StuffStockController extends Controller
{
    public function index()
    {
        $stuffStock = StuffStock::with('stuff')->get();
        $stuff = Stuff::get();
        $stock = StuffStock::get();

        $data = ["barang" => $stuff, 'stock' => $stock];

        // return response()->json([
        //     'success' => true,
        //     'message' => 'lihat stock barang',
        //     'data' => [
        //         'barang' => $stuff,
        //         'stock barang' => $stuffStock
        //     ]
        //     ]);

        return ApiFormatter::sendResponse(200, true, 'lihat stock barang', [$stuff, $stuffStock]);
    }

    public function store(Request $request)
    {
        // $validator = validator::make($request->all(), [
        //     'stuff_id' => 'required',
        //     'total_avaible' => 'required',
        //     'total_defec' => 'required'
        // ]);

        // if ($validator->fails()) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'semua kolom wajib diisi',
        //         'data' => $validator->errors()
        //     ],400);
        // } else {
        //     $stock = StuffStock::updateOrCreate([
        //         'stuff_id' => $request->input('stuff_id')
        //     ], [
        //         'total_avaible' => $request->input('total_avaible'),
        //         'total_defec' => $request->input('total_defec'),
        //     ]);

        //     if ($stock) {
        //         return response()->json([
        //             'success' =>true,
        //             'message' => 'stock barang berhasil disimpan!',
        //             'data' => $stock
        //         ], 201);
        //     } else {
        //         return response()->json([
        //             'success' => false,
        //             'message' => 'Stock barang gagal disimpan'
        //         ], 400);
        //     }
        // }
        try {
            $this-> validate($request, [
                'stuff_id' => 'required',
                'total_avaible' => 'required',
                'total_defec' => 'required',
            ]);
            $stock = StuffStock::updateOrcreate([
                'stuff_id' => $request->input('stuff_id'),
            ]);
            return ApiFormatter::sendResponse(201, true, 'stock barang berhasil disimpan!', $stock );
        } catch (\Throwable $th) {
            if ($th->validator->errors()) {
                return ApiFormatter::sendResponse(400, false, 'semua kolom wajib diisi', $th->validator->errors());
            }else {
                return ApiFormatter::sendResponse(400, false, 'stock barang gagal disimpan!', $th->validator->errors());
            }
        }
    }

    public function show($id)
    {
        // try {
        //     $stock = StuffStock::with('stuff')->find($id);

        //     return response()->json([
        //         'success' => true,
        //         'message' => "lihat barang dengan id $id",
        //         'data' => $stock
        //     ],200);
        // } catch (\Throwable $th) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => "data dengan id $id tidak ditemukan",
        //     ], 404);
        // }
        try {
            $stock = StuffStock::with('stuff')->findOrFail($id);

            return ApiFormatter::sendResponse(200, true, "lihat barang dengan id $id", $stock);
        } catch (\Throwable $th) {

            return ApiFormatter::sendResponse(404, false, "data dengan id $id tidak ditemukan");
        }
    }

    public function update(Request $request, $id)
    {
        // try {
        //     $stock= StuffStock::with('stuff')->find($id);

        //     $total_avaible = ($request->total_avaible);
        //     $request->total_avaible;
        //     $total_defec = ($request->total_defec) ? $request->total_defec : $stock->total_defec;

        //     if ($stock) {
        //         $stock->update([
        //             'total_avaible' => $total_avaible,
        //             'total_defec' => $total_defec,
        //         ]);

        //         return response()->json([
        //             'success' => true,
        //             'message' => "berhasil ubah data stock dengan id $id",
        //             'data' => $stock
        //         ], 200);
        //     } else {
        //         return response()->json([
        //             'success' => false,
        //             'message' => "proses gagal"
        //         ], 404);
        //     }
        // } catch (\Throwable $th) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => " proses gagal data dengan id $id tidak ditemukan"
        //     ], 404);
        // }

        try {

            $stock = StuffStock::with('stuff')->findOrFail($id);
            $total_avaible = ($request->total_avaible) ? $request->total_avaible : $stock->total_avaible;
            $total_defec = ($request->total_defec) ? $request->total_defec : $stock->total_defec;

            $stock->update([
                'total_avaible' => $total_avaible,
                'total_defec' => $total_defec,
            ]);
            return ApiFormatter::sendResponse(200, true, "berhasil ubah data stock dengan id $id", $stock);
        } catch (\Throwable $th) {
            return ApiFormatter::sendResponse(404, false,"proses gagal data dengan id $id tidak ditemukan" );
        }
    }

    public function destroy($id)
    {
        // try {
        //     $stock = StuffStock::findOrFail($id);

        //     $stock->delete();

        //     return response()->json([
        //         'success' => true,
        //         'message' => "berhasil hapus data dengan id $id",
        //         'data' => [ 'id' => $id,
        //         ]
        //     ], 200);
        // } catch (\Throwable $th) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => "proses gagal data dengan id $id tidak ditemukan"
        //     ], 404);
        // }

        try {
            $stock = StuffStock::FindorFail($id);

            $stock->delete();
            return ApiFormatter::sendResponse(200, true, "berhasil hapus data dengan id $id", ['id' => $id]);
        } catch (\Throwable $th) {
            return ApiFormatter::sendResponse(404, false, "proses gagal data dengan id $id tidak ditemukan!");
        }
    }

    public function deleted()
    {
        try {
            $stocks = StuffStock::onlyTrashed()->get();

            return ApiFormatter::sendResponse(200, true, "lihat data stock barang yang dihapus", $stocks);
        }catch (\Throwable $th) {
            return ApiFormatter::sendResponse(404, false, "proses gagal silahkan coba lagi!", $th->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            $stock = StuffStock::onlyTrashed()->findOrFail($id);
            $has_stock = StuffStock::where('stuff_id', $stock->stuff_id)->get();

            if ($has_stock->count() == 1 ) {
                $message = "Data stock sudah ada, tidak boleh ada duplikat data stock untuk satu barang silahkan update data stock dengan id stock $stock->stuff_id";
            }else {
                $stock->restore();
                $message = "Berhasil mengembalikan data yang telah dihapus!";
            }
            return ApiFormatter::sendResponse(200, true, $message, ['id' => $id, 'stuff_id'=> $stock->stuff_id]);

        } catch (\Throwable $th) {
            return ApiFormatter::sendResponse(404, false, "Proses gagal! silahkan coba lagi!", $th->getMessage());
        }
    }
    public function restoreAll()
    {
        try {
            $stocks= StuffStock::onlyTrashed()->restore();

            return ApiFormatter::sendResponse(200, true,
            "Berhasil mengembalikkan semua data yang telah dihapus!");
        } catch (\Throwable $th) {
            return ApiFormatter::sendResponse(404, false,
            "Proses gagal! silahkan coba lagi!", $th->getMessage());
        }
    }

    public function permanentDelete($id)
    {
        try {
            $stock = StuffStock::onlyTrashed()->where('id', $id)->forceDelete();

            return ApiFormatter::sendResponse(200, true,
            "Berhasil hapus permanen data yang telah di hapus", ['id' => $id]);
        } catch (\Throwable $th) {
            return ApiFormatter::sendResponse(404, false,
            "Proses gagal! silahkan coba lagi!", $th->getMessage());
        }
    }

    public function permanentDeleteAll()
    {
        try {
            $stocks = StuffStock::onlyTrashed()->forceDelete();

            return ApiFormatter::sendResponse(200, true,
            "Berhasil hapus permanen data yang telah di hapus!");
        } catch (\Throwable $th) {
            return ApiFormatter::sendResponse(404, false,
            "Proses gagal! silahkan coba lagi!", $th->getMessage());
        }
    }
}

