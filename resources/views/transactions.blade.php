@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Transaksi</h2>
        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
            <i class="fas fa-plus me-1"></i> Tambah Transaksi
        </button>
    </div>

    <div class="card shadow border-0 bg-white">
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Tanggal</th>
                        <th>Kategori</th>
                        <th>Deskripsi</th>
                        <th class="text-end">Jumlah</th>
                        <th class="text-center" style="width: 100px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($transactions ?? [] as $t)
                    <tr>
                        <td class="ps-4">{{ \Carbon\Carbon::parse($t->transaction_date)->format('d M Y') }}</td>
                        <td>{{ $t->category->name ?? '-' }}</td>
                        <td>{{ $t->description ?? '-' }}</td>
                        <td class="text-end {{ $t->type == 'income' ? 'text-success' : 'text-danger' }}">
                            Rp {{ number_format($t->amount) }}
                        </td>
                        <td class="text-center">
                            <form action="{{ route('transactions.destroy', $t->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-4">Belum ada transaksi</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal Tambah Transaksi -->
<div class="modal fade" id="addTransactionModal" tabindex="-1" aria-labelledby="addTransactionModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('transactions.store') }}" method="POST">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTransactionModalLabel">Tambah Transaksi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Kategori</label>
                        <select name="category_id" id="category_id" class="form-select" required>
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }} ({{ $cat->type == 'income' ? 'Pemasukan' : 'Pengeluaran' }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Tipe</label>
                        <select name="type" id="type" class="form-select" required>
                            <option value="income">Pemasukan</option>
                            <option value="expense">Pengeluaran</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Jumlah (Rupiah)</label>
                        <input type="number" name="amount" id="amount" class="form-control" min="0" step="any" placeholder="Contoh: 50000" required>
                    </div>
                    <div class="mb-3">
                        <label for="transaction_date" class="form-label">Tanggal</label>
                        <input type="date" name="transaction_date" id="transaction_date" class="form-control" value="{{ date('Y-m-d') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Deskripsi</label>
                        <textarea name="description" id="description" class="form-control" rows="3" placeholder="Deskripsi transaksi..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection