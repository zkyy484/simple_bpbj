<div x-show="openEdit" x-cloak class="fixed inset-0 z-[999] flex items-center justify-center p-4">

    <div x-show="openEdit" x-transition.opacity @click="openEdit = false"
        class="absolute inset-0 bg-black/50 backdrop-blur-sm"></div>

    <div x-show="openEdit" x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-90" @click.outside="openEdit = false"
        class="relative bg-white rounded-2xl shadow-2xl w-full max-w-2xl overflow-hidden max-h-[90vh] overflow-y-auto">

        <div class="bg-white px-6 py-5 flex items-center justify-between border-b border-gray-200 sticky top-0">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 text-amber-600" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-gray-900 leading-tight">Edit Akun</h2>
            </div>
            <button type="button" @click="openEdit = false" aria-label="Tutup modal"
                class="w-8 h-8 rounded-full bg-red-500 hover:bg-red-600 text-white flex items-center justify-center transition-colors shadow-sm">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <form action="{{ route('super.akun.update') }}" method="POST">
            @csrf
            @method('PUT')
            <input type="hidden" name="form_type" value="edit">
            <input type="hidden" name="id_user" x-model="selectedUser.id">

            <div class="p-6 space-y-4">
                @if ($errors->any())
                    <div class="p-3 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm space-y-1">
                        @foreach ($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" x-model="selectedUser.nama_lengkap"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">NIP</label>
                        <input type="text" name="nip" x-model="selectedUser.nip"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Email</label>
                        <input type="email" name="email" x-model="selectedUser.email"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon</label>
                        <input type="text" name="no_telepon" x-model="selectedUser.no_telepon"
                            class="w-full rounded-xl border border-gray-300 px-4 py-3 outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500">
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Sub Bagian</label>
                    <select name="id_sub_bagian" x-model="selectedUser.id_sub_bagian"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 cursor-pointer">

                        <option value="" disabled>Pilih Sub Bagian</option>

                        @foreach ($subBagians as $sub)
                            @if ($sub->status == 'aktif')
                                <option value="{{ $sub->id_sub_bagian }}">
                                    {{ $sub->nama_sub_bagian }}
                                </option>
                            @endif
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat</label>
                    <textarea name="alamat" rows="3" x-model="selectedUser.alamat"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 resize-none"></textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Hak Akses (Role)</label>
                    <select name="role" x-model="selectedUser.role"
                        class="w-full rounded-xl border border-gray-300 px-4 py-3 outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 cursor-pointer">
                        <option value="admin_fo">Admin FO</option>
                        <option value="pegawai">Pegawai</option>
                    </select>
                </div>
            </div>

            <div class="bg-gray-50 border-t border-gray-200 px-6 py-4 flex justify-end gap-3 sticky bottom-0">
                <button type="button" @click="openEdit = false"
                    class="px-5 py-2.5 rounded-xl border border-gray-300 hover:bg-gray-100 text-gray-700 font-semibold transition">
                    Batal
                </button>
                <button type="submit"
                    class="px-6 py-2.5 rounded-xl bg-amber-500 hover:bg-amber-600 text-white font-semibold transition flex items-center gap-2">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    Perbarui Data
                </button>
            </div>
        </form>
    </div>
</div>