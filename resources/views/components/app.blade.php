@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const selectMarketing = document.getElementById('marketing_id');

    selectMarketing.addEventListener('change', function () {
        const marketingId = this.value;
        if (!marketingId) return;

        fetch(`/api/get-marketing/${marketingId}`)
            .then(response => response.json())
            .then(data => {
                if (data) {
                    document.getElementById('pekerjaan').value = data.nama_pekerjaan || '';
                    document.getElementById('klien').value = data.nama_klien || '';
                    document.getElementById('lokasi').value = data.lokasi || '';
                    document.getElementById('tahap_pengerjaan').value = 'Pemilahan';
                    document.getElementById('tgl_mulai').value = data.tgl_mulai || '';
                    document.getElementById('tgl_selesai').value = data.tgl_selesai || '';
                    document.getElementById('nilai_proyek').value = data.nilai_akhir_proyek || '';
                    document.getElementById('link_rab').value = data.link_rab || '';
                    document.getElementById('jenis_arsip').value = data.jenis_pekerjaan || '';
                    document.getElementById('volume_arsip').value = data.total_volume || '';
                    document.getElementById('no_telp_pm').value = data.telepon || '';
                    document.getElementById('status').value = 'Behind Schedule';
                    document.getElementById('project_manager').value = data.user_id || '';
                }
            })
            .catch(error => console.error('Error fetching marketing data:', error));
    });
});
</script>
@endpush
