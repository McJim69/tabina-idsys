<?php if (isset($_SESSION['user'])): ?>
<script>
jQuery(document).ready(function() {
    let city_mun = <?php echo json_encode($_SESSION['city_mun'] ?? ''); ?>;
    let barangay = <?php echo json_encode($_SESSION['barangay'] ?? ''); ?>;
    let purok = <?php echo json_encode($_SESSION['purok'] ?? ''); ?>;
    
    if (city_mun) {
        let citySelect = document.getElementById('city_mun');
        if (citySelect) {
            citySelect.value = city_mun;
            // Fetch barangay
            fetch('get_options.php?type=barangay&city_mun=' + encodeURIComponent(city_mun))
              .then(res => res.json())
              .then(data => {
                let barangaySelect = document.getElementById('barangay');
                if (barangaySelect) {
                    barangaySelect.innerHTML = '<option value="">Barangay</option>';
                    data.forEach(item => {
                      barangaySelect.innerHTML += '<option value="'+item+'" '+(item === barangay ? 'selected' : '')+'>'+item+'</option>';
                    });
                }
                if (barangay) {
                    // Fetch purok
                    fetch('get_options.php?type=purok&barangay=' + encodeURIComponent(barangay))
                      .then(res => res.json())
                      .then(data => {
                        let purokSelect = document.getElementById('purok');
                        if (purokSelect) {
                            purokSelect.innerHTML = '<option value="">Purok</option>';
                            data.forEach(item => {
                              purokSelect.innerHTML += '<option value="'+item+'" '+(item === purok ? 'selected' : '')+'>'+item+'</option>';
                            });
                        }
                      });
                }
              });
        }
    }
});
</script>
<?php endif; ?>
