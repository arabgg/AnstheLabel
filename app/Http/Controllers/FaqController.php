<?php

namespace App\Http\Controllers;

use App\Models\FaqModel;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index(Request $request)
    {
        $searchQuery = $request->input('search');
        $sort = $request->input('sort');

        $query = FaqModel::select('faq_id', 'pertanyaan', 'jawaban');

        if ($searchQuery) {
            $query->where(function ($q) use ($searchQuery) {
                $q->where('pertanyaan', 'like', "%{$searchQuery}%")
                    ->orWhere('jawaban', 'like', "%{$searchQuery}%");
            });
        }

        if ($sort === 'terbaru') {
            $query->orderBy('created_at', 'desc');
        } elseif ($sort === 'terlama') {
            $query->orderBy('created_at', 'asc');
        } else {
            // default order
            $query->orderBy('faq_id', 'desc');
        }

        // paginate (10 per page) dan pertahankan query string (search + sort)
        $faqs = $query->paginate(10)->withQueryString();

        return view('admin.faq.index', compact('faqs', 'searchQuery'));
    }

    public function create()
    {
        return view('admin.faq.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pertanyaan' => 'required|string|max:255',
            'jawaban'    => 'required|string',
        ]);

        $faq = FaqModel::create([
            'pertanyaan' => $request->pertanyaan,
            'jawaban'    => $request->jawaban,
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'FAQ berhasil ditambahkan',
                'data'    => $faq,
            ]);
        }

        return redirect()->route('faq.index')
            ->with('success', 'FAQ berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $faq = FaqModel::findOrFail($id);
        if (request()->ajax()) {
            return view('admin.faq.show', compact('faq'));
        }
        return redirect()->route('faq.index');
    }

    public function edit(string $id)
    {
        $faq = FaqModel::findOrFail($id);
        return view('admin.faq.edit', compact('faq'));
    }

    public function update(Request $request, string $id)
    {
        $faq = FaqModel::findOrFail($id);

        $request->validate([
            'pertanyaan' => 'required|string|max:255',
            'jawaban'    => 'required|string',
        ]);

        $faq = FaqModel::findOrFail($id);
        $faq->pertanyaan = $request->pertanyaan;
        $faq->jawaban = $request->jawaban;
        $faq->save();

        return response()->json([
            'success' => true,
            'message' => 'FAQ berhasil diperbarui.',
            'data'    => $faq,
        ]);
    }

    public function destroy(string $id)
    {
        $faq = FaqModel::findOrFail($id);
        $faq->delete();

        return response()->json([
            'success' => true,
            'message' => 'FAQ berhasil dihapus.',
            'id'      => $id,
        ]);
    }
}
