<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateTransactionRequest;
use App\Http\Requests\UpdateTransactionRequest;
use Illuminate\Http\Request;
use App\Models\Transaction;
use Exception;

class TransactionController extends Controller
{



    public function fetch(Request $request)
    {
        try {
            $id = $request->input('id_peminjaman');

            if ($id) {
                $transaction = Transaction::find($id);

                if ($transaction) {
                    return response()->json(
                        [
                            'id_peminjaman' => $transaction->id_peminjaman,
                            'nama_mobil' => $transaction->nama_mobil,
                            'tanggal_peminjaman' => $transaction->tanggal_peminjaman,
                            'tanggal_pengembalian' => $transaction->tanggal_pengembalian,
                            'status_peminjaman' => $transaction->status_peminjaman,
                        ]
                    );
                }

                throw new Exception('Transaction not found');
            }

            $transactions = Transaction::all();

            foreach ($transactions as $transaction) {
                $data[] = [
                    'id_peminjaman' => $transaction->id_peminjaman,
                    'nama_mobil' => $transaction->nama_mobil,
                    'tanggal_peminjaman' => $transaction->tanggal_peminjaman,
                    'tanggal_pengembalian' => $transaction->tanggal_pengembalian,
                    'status_peminjaman' => $transaction->status_peminjaman,
                ];
            }

            return response()->json($data);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }


    public function create(CreateTransactionRequest $request)
    {

        function hasExist($nama_mobil, $tanggal_peminjaman, $status_peminjaman)
        {
            $transaction = Transaction::where(
                [
                    'nama_mobil' => $nama_mobil,
                    'tanggal_peminjaman' => $tanggal_peminjaman,
                    'status_peminjaman' => $status_peminjaman,
                ]
            );

            return $transaction->count() > 0 ? true : false;
        }

        try {
            $isExist = hasExist($request->nama_mobil, $request->tanggal_peminjaman, $request->status_peminjaman);

            if ($isExist) {
                throw new Exception('Transaction has Exist');
            }

            $transaction = Transaction::create([
                'nama_mobil' => $request->nama_mobil,
                'tanggal_peminjaman' => $request->tanggal_peminjaman,
                'tanggal_pengembalian' => $request->tanggal_pengembalian,
                'status_peminjaman' => $request->status_peminjaman,
            ]);

            if ($transaction) {
                return response()->json(
                    [
                        'id_peminjaman' => $transaction->id_peminjaman,
                        'nama_mobil' => $transaction->nama_mobil,
                        'tanggal_peminjaman' => $transaction->tanggal_peminjaman,
                        'tanggal_pengembalian' => $transaction->tanggal_pengembalian,
                        'status_peminjaman' => $transaction->status_peminjaman,
                    ]
                );
            } else {
                throw new Exception('Transaction not created');
            }
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function update(UpdateTransactionRequest $request, $id)
    {
        try {
            $transaction = Transaction::find($id);
            if (!$transaction) {
                throw new Exception('Transaction not found');
            }


            $isExpire = Transaction::where('id_peminjaman', $id)->whereDate('tanggal_pengembalian', '<', $transaction->tanggal_peminjaman);
            if ($isExpire->count() > 0) {
                throw new Exception('Update transaction error');
            }

            $transaction->update(
                [
                    'nama_mobil' => $request->nama_mobil,
                    'tanggal_peminjaman' => $request->tanggal_peminjaman,
                    'tanggal_pengembalian' => $request->tanggal_pengembalian,
                    'status_peminjaman' => $request->status_peminjaman,
                ]
            );

            return response()->json(
                [
                    'id_peminjaman' => $transaction->id_peminjaman,
                    'nama_mobil' => $transaction->nama_mobil,
                    'tanggal_peminjaman' => $transaction->tanggal_peminjaman,
                    'status_peminjaman' => $transaction->status_peminjaman,
                ]
            );
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }

    public function destroy($id)
    {
        try {
            $transaction = Transaction::find($id);

            if (!$transaction) {
                throw new Exception('Transaction not found');
            }
            $transaction->delete();

            return response()->json([
                'message' => 'Data berhasil dihapus'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ]);
        }
    }
}
