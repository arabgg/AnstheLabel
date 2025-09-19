<?php

namespace App\Http\Controllers;

use App\Models\FaqModel;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    public function index()
    {
        $faqs = FaqModel::select('faq_id', 'pertanyaan', 'jawaban')->get();
        return view('admin.faq.index', compact('faqs'));
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

        FaqModel::create($request->only('pertanyaan', 'jawaban'));

        return redirect()->route('faq.index')
                         ->with('success', 'FAQ berhasil ditambahkan.');
    }

    public function show(string $id)
    {
        $faq = FaqModel::findOrFail($id);
        return view('admin.faq.show', compact('faq'));
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

        $faq->update($request->only('pertanyaan', 'jawaban'));

        return redirect()->route('faq.index')
                         ->with('success', 'FAQ berhasil diperbarui.');
    }

    public function destroy(string $id)
    {
        $faq = FaqModel::findOrFail($id);
        $faq->delete();

        return redirect()->route('faq.index')
                         ->with('success', 'FAQ berhasil dihapus.');
    }
}
