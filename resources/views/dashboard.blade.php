@extends('layouts.app')

@section('content')
<div class="container">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row g-4 mb-4">
        <!-- Income Card -->
        <div class="col-md-4">
            <div class="card shadow border-0 card-income bg-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Pemasukan</h6>
                            <h3 class="text-success mb-0">Rp {{ number_format($totalIncome) }}</h3>
                        </div>
                        <div class="bg-success-subtle text-success rounded p-3">
                            <i class="fas fa-arrow-down fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Expense Card -->
        <div class="col-md-4">
            <div class="card shadow border-0 card-expense bg-white">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Total Pengeluaran</h6>
                            <h3 class="text-danger mb-0">Rp {{ number_format($totalExpense) }}</h3>
                        </div>
                        <div class="bg-danger-subtle text-danger rounded p-3">
                            <i class="fas fa-arrow-up fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Balance Card -->
        <div class="col-md-4">
            <div class="card shadow border-0 bg-white" style="border-left: 5px solid #0d6efd;">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-muted mb-1">Saldo Akhir</h6>
                            <h3 class="text-primary mb-0">Rp {{ number_format($balance) }}</h3>
                        </div>
                        <div class="bg-primary-subtle text-primary rounded p-3">
                            <i class="fas fa-wallet fa-2x"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Transactions Table -->
    <div class="card shadow border-0 bg-white">
        <div class="card-header bg-white py-3 border-0 d-flex justify-content-between align-items-center">
            <h5 class="mb-0 font-weight-bold">Transaksi Terbaru</h5>
            <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addTransactionModal">
                <i class="fas fa-plus me-1"></i> Tambah Transaksi
            </button>
        </div>
        <div class="card-body p-0">
            <table class="table table-striped table-hover mb-0">
                <thead>
                    <tr>
                        <th class="ps-4">Tanggal</th>
                        <th>Kategori</th>
                        <th>Deskripsi</th>
                        <th class="text-end pe-4">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions ?? [] as $t)
                    <tr>
                        <td class="ps-4">{{ \Carbon\Carbon::parse($t->transaction_date)->format('d M Y') }}</td>
                        <td>{{ $t->category->name ?? '-' }}</td>
                        <td>{{ $t->description ?? '-' }}</td>
                        <td class="text-end pe-4 {{ $t->type == 'income' ? 'text-success' : 'text-danger' }}">
                            Rp {{ number_format($t->amount) }}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-4">Belum ada transaksi</td></tr>
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