@extends('tamu.layouts.app')

@section('title', 'Survei Kepuasan - Pilih Nama')

@section('content')
    <div class="max-w-xl mx-auto py-10 px-4">
        <div class="bg-white rounded-2xl shadow-sm p-6 md:p-8">
            <h1 class="text-2xl font-bold text-gray-900 mb-1">Survei Kepuasan Layanan</h1>
            <p class="text-sm text-gray-500 mb-6">Pilih nama Anda untuk melanjutkan ke survei.</p>

            @if (session('error'))
                <div class="mb-6 rounded-lg bg-red-100 border border-red-300 text-red-700 p-4 text-sm">
                    {{ session('error') }}
                </div>
            @endif

            @if ($tamus->isEmpty())
                <div class="rounded-lg bg-yellow-50 border border-yellow-200 text-yellow-800 p-4 text-sm">
                    Belum ada tamu yang mengisi Buku Tamu hari ini. Silakan isi
                    <a href="{{ route('tamu.form') }}" class="font-semibold underline">Buku Tamu</a> terlebih dahulu.
                </div>
            @else
                <form action="{{ route('survei.pilih.store') }}" method="POST">
                    @csrf
                    <label for="id_tamu" class="block text-sm font-semibold text-gray-700 mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <select id="id_tamu" name="id_tamu" required
                        class="w-full rounded-xl border px-4 py-3 outline-none focus:ring-2 focus:ring-[#173860] @error('id_tamu') border-red-400 @else border-gray-300 @enderror">
                        <option value="">-- Pilih nama --</option>
                        @foreach ($tamus as $t)
                            @php $terisi = in_array($t->id_tamu, $sudahMengisi); @endphp
                            <option value="{{ $t->id_tamu }}" @disabled($terisi) @selected(old('id_tamu') == $t->id_tamu)>
                                {{ $t->nama_lengkap }}{{ $t->nama_perusahaan ? ' - ' . $t->nama_perusahaan : '' }}{{ $terisi ? ' (sudah mengisi)' : '' }}
                            </option>
                        @endforeach
                    </select>
                    @error('id_tamu')
                        <p class="mt-1 text-xs text-red-500">{{ $message }}</p>
                    @enderror

                    <div class="flex justify-end pt-6 mt-6 border-t border-gray-100">
                        <button type="submit"
                            class="px-6 py-2.5 rounded-xl bg-[#173860] hover:bg-[#102a48] text-white font-semibold text-sm transition">
                            Selanjutnya &rarr;
                        </button>
                    </div>
                </form>
            @endif
        </div>
    </div>
@endsection