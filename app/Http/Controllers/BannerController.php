<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\BannerModel;
use Spatie\ImageOptimizer\OptimizerChainFactory;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function index(Request $request)
    {
        $searchQuery = $request->input('search', '');

        $banners = BannerModel::select('banner_id', 'nama_banner', 'foto_banner', 'deskripsi', 'status')
            ->when(!empty($searchQuery), function ($q) use ($searchQuery) {
                $q->where('nama_banner', 'like', "%{$searchQuery}%");
            })
            ->orderBy('banner_id', 'asc')
            ->paginate(4)
            ->through(function ($banner) {
                $banner->is_video = strtolower($banner->nama_banner) === 'transaksi';
                return $banner;
            });

        return view('admin.banner.index', compact('banners', 'searchQuery'));
    }

    public function show($id)
    {
        $banner = BannerModel::select('banner_id', 'nama_banner', 'foto_banner', 'deskripsi', 'created_at')
            ->findOrFail($id);

        $banner->is_video = strtolower($banner->nama_banner) === 'transaksi';

        if (request()->ajax()) {
            return view('admin.banner.show', compact('banner'));
        }

        return redirect()->route('banner.index');
    }

    public function edit($id)
    {
        $banner = BannerModel::select('banner_id', 'foto_banner', 'deskripsi', 'status')
            ->findOrFail($id);

        return view('admin.banner.edit', compact('banner'));
    }

    public function update(Request $request, $id)
    {
        $optimizerChain = OptimizerChainFactory::create();

        $request->validate([
            'foto_banner' => 'nullable|file|mimes:jpg,jpeg,png,avif,mp4|max:5240',
            'deskripsi' => 'nullable|string|max:255',
            'status' => 'nullable|in:0,1'
        ]);

        $banner = BannerModel::select('banner_id', 'foto_banner', 'deskripsi', 'status')
            ->findOrFail($id);

        if ($request->hasFile('foto_banner')) {
            // Hapus file lama jika ada
            if ($banner->foto_banner && Storage::exists('public/banner/' . $banner->foto_banner)) {
                Storage::delete('public/banner/' . $banner->foto_banner);
            }

            $file = $request->file('foto_banner');
            $filename =  $file->hashName();
            $file->storeAs('public/banner', $filename);
            $banner->foto_banner = $filename;
            $optimizerChain->optimize(storage_path('app/public/banner/' . $filename));
        }

        $banner->deskripsi = $request->deskripsi;
        $banner->status = $request->status;
        
        $banner->save();

        return response()->json([
            'success' => true,
            'message' => 'Banner berhasil diperbarui',
            'data' => $banner
        ]);
    }
}
