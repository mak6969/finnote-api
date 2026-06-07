<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class WebController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        $totalIncome = $user->transactions()->where('type', 'income')->sum('amount');
        $totalExpense = $user->transactions()->where('type', 'expense')->sum('amount');
        $balance = $totalIncome - $totalExpense;

        $recentTransactions = $user->transactions()
            ->with('category')
            ->latest('transaction_date')
            ->take(8)
            ->get();

        $categories = $user->categories;

        return view('dashboard', compact('totalIncome', 'totalExpense', 'balance', 'recentTransactions', 'categories'));
    }

    public function transactions()
    {
        $user = Auth::user();
        $transactions = $user->transactions()
            ->with('category')
            ->latest('transaction_date')
            ->get();
        $categories = $user->categories;
        return view('transactions', compact('transactions', 'categories'));
    }

    public function categories()
    {
        $categories = Auth::user()->categories;
        return view('categories', compact('categories'));
    }

    public function storeTransaction(Request $request)
    {
        $request->validate([
            'category_id'      => 'required|exists:categories,id',
            'type'             => 'required|in:income,expense',
            'amount'           => 'required|numeric|min:0',
            'description'      => 'nullable|string',
            'transaction_date' => 'required|date',
        ]);

        Auth::user()->transactions()->create($request->all());

        return redirect()->back()->with('success', 'Transaksi berhasil ditambahkan!');
    }

    public function destroyTransaction(\App\Models\Transaction $transaction)
    {
        if ($transaction->user_id !== Auth::id()) {
            abort(403);
        }

        $transaction->delete();

        return redirect()->back()->with('success', 'Transaksi berhasil dihapus!');
    }

    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
        ]);

        Auth::user()->categories()->create($request->only(['name', 'type']));

        return redirect()->back()->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function destroyCategory(\App\Models\Category $category)
    {
        if ($category->user_id !== Auth::id()) {
            abort(403);
        }

        $category->transactions()->delete();
        $category->delete();

        return redirect()->back()->with('success', 'Kategori dan transaksi terkait berhasil dihapus!');
    }

    public function showRegisterForm()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email ini sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal harus 6 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user, true);

        return redirect()->route('dashboard')->with('success', 'Registrasi berhasil dan Anda telah masuk!');
    }
}