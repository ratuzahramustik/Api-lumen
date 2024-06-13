<?php

namespace App\Http\Controllers;

use App\Models\Stuff;
use App\Models\StuffStock;
use App\Models\InboundStuff;
use Illuminate\Http\Request;
use App\Helpers\ApiFormatter;
use App\Http\Controllers\Controller;


class InboundStuffController extends Controller
{
    public function index()
    {
        $InboundStuff = InboundStuff::with('Stuff')->get();
        $Stuff = Stuff::get();
        $Stock = StuffStock::get();

        $data = ["barang" => $Stuff, 'stock' => $Stock];

        // return response()->json([
        //     'success' => true,
        //     'message' => 'lihat stock barang',
        //     'data' => [
        //         'barang' => $stuff,
        //         'stock barang' => $stuffStock
        //     ]
        //     ]);

        return ApiFormatter::sendResponse(200, true, 'lihat stock barang', [$InboundStuff]);
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
        // try {
        //     $this-> validate($request, [
        //         'stuff_id' => 'required',
        //         'total' => 'required',
        //         'date' => 'required|date_format:d-m-Y',
        //         'proff_file' => 'required'

        //     ]);
        //     $InboundStuff = InboundStuff::Create([
        //         'stuff_id' => $request->input('stuff_id'),
        //         'total' => $request->input('total'),
        //         'date' => $request->input('date'),
        //         'proff_file' => $request->input('proff_file'),


        //     ]);
        //     return ApiFormatter::sendResponse(201, true, 'stock barang berhasil disimpan!', $InboundStuff );
        // } catch (\Throwable $th) {
        //     if ($th->validator->errors()) {
        //         return ApiFormatter::sendResponse(400, false, 'semua kolom wajib diisi', $th->validator->errors());
        //     }else {
        //         return ApiFormatter::sendResponse(400, false, 'stock barang gagal disimpan!', $th->validator->errors());
        //     }
        // }

        try {
            $this->validate($request, [
                'stuff_id' => 'required',
                'proff_file' => 'required|file|max:2048', // Max size 2MB
            ]);
            $file = $request->file('proff_file');

            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $file->move('files', $fileName);
            $InboundStuff = InboundStuff::updateOrCreate([
                'stuff_id' => $request->input('stuff_id'),
            ], [
                'total' => $request->input('total'),
                'date' => $request->input('date'),
                'proff_file' => $fileName,
            ]);

            $Stock = StuffStock::where('stuff_id', $request->input('stuff_id'))->first();

            $total_Stock = (int)$Stock->total_avaible + (int)$request->input('total');

            $Stock->update([
                'total_avaible' => (int)$total_Stock
            ]);
            //ini yang dari kak aca
            if ($InboundStuff && $Stock) {
                return ApiFormatter::sendResponse(201, true, 'Inbound barang berhasil disimpan');
            } else {
                return ApiFormatter::sendResponse(400, false, 'Inbound barang gagal disimpan');
            }
            //ini yang nyoba dari naya
            return ApiFormatter::sendResponse(201, true, 'Inbound barang berhasil disimpan', [$InboundStuff , 'file_path' => 'files/' . $fileName]);
        } catch (\Throwable $th) {
            //throw $th;
            if ($th->validator->errors()) {
                return ApiFormatter::sendResponse(400, false, 'Terdapat kesalahan input silakan coba lagi!', $th->validator->errors());
            } else {
                return ApiFormatter::sendResponse(400, false, 'Terdapat kesalahan input silakan coba lagi!', $th->getMessage());
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
            $InboundStuff = InboundStuff::with('stuff')->findOrFail($id);

            return ApiFormatter::sendResponse(200, true, "lihat barang dengan id $id", $InboundStuff);
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

            $InboundStuff = InboundStuff::with('stuff')->findOrFail($id);
            $Stuff_id= ($request->Stuff_id) ? $request->Stuff_id : $InboundStuff->Stuff_id;
            $total = ($request->total) ? $request->total : $InboundStuff->total;
            $date = ($request->date) ? $request->date : $InboundStuff->date;
            $proff_file = ($request->proff_file) ? $request->proff_file : $InboundStuff->proff_file;

            $InboundStuff->update([
                'Stuff_id' => $Stuff_id,
                'total' => $total,
                'date' => $date,
                'proff_file' => $proff_file,
            ]);
            $file = $request->file('proff_file');

            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            $file->move('files', $fileName);

            return ApiFormatter::sendResponse(200, true, "berhasil ubah data stock dengan id $id", [$InboundStuff , 'file_path' => 'files/' . $fileName]);
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
            $Inbound = InboundStuff::findOrFail($id);

            $Inbound->delete();

            return ApiFormatter::sendResponse(200, true, 'Berhasil hapus data dengan id $id', [ 'id' => $id,]);
        } catch (\Throwable $th) {
            //throw $th;
            return ApiFormatter::sendResponse(404, false, "proses gagal silahkan coba lagi", $th->getMessage()); 
        }
    }

    public function trash()
    {
        try{
            $data= InboundStuff::onlyTrashed()->get();

            return Apiformater::sendResponse(200, 'success', $data);
        }catch(\Exception $err){
            return Apiformater::sendResponse(400, 'bad request', $err->getMessage());
        }
    }

    public function deleted()
    {
        try {
            $InboundStuff = InboundStuff::onlyTrashed()->get();

            return ApiFormatter::sendResponse(200, true, "lihat data stock barang yang dihapus", $InboundStuff);
        }catch (\Throwable $th) {
            return ApiFormatter::sendResponse(404, false, "proses gagal silahkan coba lagi!", $th->getMessage());
        }
    }

    public function restore(InboundStuff $inboundStuff, $id)
    {
        try {
            $checkProses = InboundStuff::onlyTrashed()->where('id', $id)->restore();
    
            if ($checkProses) {
                $restoredData = InboundStuff::find($id);
                $totalRestored = $restoredData->total;
    
                $stuffId = $restoredData->stuff_id;
    
                $stuffStock = StuffStock::where('stuff_id', $stuffId)->first();
                
                if ($stuffStock) {
                    $stuffStock->total_available += $totalRestored;
    
                    $stuffStock->save();
                }
    
                return Apiformater::sendResponse(200, 'success', $restoredData);
            } else {
                return Apiformater::sendResponse(400, 'bad request', 'Gagal mengembalikan data!');
            }
        } catch (\Exception $err) {
            return Apiformater::sendResponse(400, 'bad request', $err->getMessage());
        }
    }
    // public function restore($id)
    // {
    //     try {
    //         $Inbound = InboundStuff::onlyTrashed()->findOrFail($id);
    //         $has_inbound = InboundStuff::where('stuff_id', $Inbound->stuff_id)->get();

    //         if ($has_inbound->count() == 1) {
    //             $message = "Data stok sudah ada, tidak boleh ada duplikat data stok untuk satu barang silakan update data stok dengan id stok $Inbound->stuff_id";
    //         } else {
    //             $Inbound->restore();
    //             $message = "Berhasil mengembalikan data yang telah di hapus!";
    //         }

    //         return ApiFormatter::sendResponse(200, true, $message, ['id' => $id, 'stuff_id' => $Inbound->stuff_id]);

    //     } catch (\Throwable $th) {
    //         //throw $th;
    //         return ApiFormatter::sendResponse(404, false, "Proses gagal! Silakan coba lagi", $th->getMessage());
    //     }
    // }


    // public function restoreAll()
    // {
    //     try {
    //         $InboundStuff= InboundStuff::onlyTrashed()->restore();

    //         return ApiFormatter::sendResponse(200, true,
    //         "Berhasil mengembalikkan semua data yang telah dihapus!");

    //     } catch (\Throwable $th) {
    //         return ApiFormatter::sendResponse(404, false,
    //         "Proses gagal! silahkan coba lagi!", $th->getMessage());
    //     }
    // }

    public function permanentDelete($id)
    {
        try {
            $InboundStuff = InboundStuff::onlyTrashed()->where('id', $id)->first();

            unlink(base path('public/proof/'.$getInboundStuff->proof_file));

            $checkProses = InboundStuff::where('id', $id)->forceDelete();

            return ApiFormatter::sendResponse(200, true, "Berhasil hapus permanen data yang telah di hapus", ['id' => $id]);

        } catch (\Throwable $th) {
            return ApiFormatter::sendResponse(404, false,"Proses gagal! silahkan coba lagi!", $th->getMessage());
        }
    }

    public function permanentDeleteAll()
    {
        try {
            $InboundStuff = InboundStuff::onlyTrashed()->forceDelete();

            return ApiFormatter::sendResponse(200, true, "Berhasil hapus permanen data yang telah di hapus!");
        } catch (\Throwable $th) {
            return ApiFormatter::sendResponse(404, false,"Proses gagal! silahkan coba lagi!", $th->getMessage());
        }
    }

    public function upload(Request $request)
    {
        try {
            // Validate the request
            $this->validate($request, [
                'proff_file' => 'required|file|max:2048', // Max size 2MB
            ]);
            // Get the file from the request
            $file = $request->file('proff_file');

            // Generate a unique name for the file
            $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();

            // Move the file to the storage directory
            $file->move('files', $fileName);

            // Return a response with the file path
            return response()->json(['file_path' => 'files/' . $fileName]);
        } catch (\Throwable $th) {
            //throw $th;
            return ApiFormatter::sendResponse(400, false, 'Terdapat kesalahan input silakan coba lagi!', $th->getMessage());
        }
    }

    private function deleteAssociatedFile(InboundStuff $InboundStuff)
    {
        $publicPath = $_SERVER['DOCUMENT_ROOT'] . '/public/proof';

    

         $filePath = public_path('proof/'.$InboundStuff->proof_file);
 
        if (file_exists($filePath)) {

            unlink(base_path($filePath));
        }
    }
}

