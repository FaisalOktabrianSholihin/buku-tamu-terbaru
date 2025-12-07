<x-filament::modal id="belumValidasiSatpam" alignment="center" :close-by-click-outside="false">
    <x-slot name="heading">
        ⚠️ Validasi Satpam Belum Dilakukan
    </x-slot>

    <p class="text-gray-600">
        Tamu ini belum divalidasi satpam. Silakan lakukan validasi satpam terlebih dahulu.
    </p>

    <x-slot name="footer">
        <x-filament::button color="danger" wire:click="$dispatch('close-modal', { id: 'belumValidasiSatpam' })">
            Tutup
        </x-filament::button>
    </x-slot>
</x-filament::modal>
