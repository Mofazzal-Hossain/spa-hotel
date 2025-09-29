<?php

// Don't load directly
defined('ABSPATH') || exit;


?>
<div id="hotel-map-location" class="tf-hotel-location-section spa-single-section">
    <h4 class="tf-section-title"><?php esc_html_e("Location", "spa-hotel-toolkit"); ?></h4>
    <?php if (!defined('TF_PRO')) { ?>
        <?php
        if ($address && $tf_openstreet_map != "default" && (empty($address_latitude) || empty($address_longitude))) { ?>
            <iframe src="https://maps.google.com/maps?q=<?php echo esc_attr($address); ?>&output=embed" width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        <?php } elseif ($address && $tf_openstreet_map == "default" && !empty($address_latitude) && !empty($address_longitude)) {
        ?>
            <div id="hotel-location"></div>
            <script>
                const map = L.map('hotel-location').setView([<?php echo esc_html($address_latitude); ?>, <?php echo esc_html($address_longitude); ?>], <?php echo esc_html($address_zoom); ?>);

                const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 20,
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);

                const marker = L.marker([<?php echo esc_html($address_latitude); ?>, <?php echo esc_html($address_longitude); ?>], {
                        alt: '<?php echo esc_html($address); ?>'
                    }).addTo(map)
                    .bindPopup('<?php echo esc_html($address); ?>');
            </script>
        <?php } else { ?>
            <iframe src="https://maps.google.com/maps?q=<?php echo esc_html($address); ?>&output=embed" width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        <?php } ?>
    <?php } else { ?>
        <?php
        if ($address && $tf_openstreet_map != "default" && (empty($address_latitude) || empty($address_longitude))) { ?>
            <iframe src="https://maps.google.com/maps?q=<?php echo esc_html($address); ?>&output=embed" width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        <?php } elseif ($address && $tf_openstreet_map == "default" && !empty($address_latitude) && !empty($address_longitude)) {
        ?>
            <div id="hotel-location"></div>
            <script>
                const map = L.map('hotel-location').setView([<?php echo esc_html($address_latitude); ?>, <?php echo esc_html($address_longitude); ?>], <?php echo esc_html($address_zoom); ?>);

                const tiles = L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    maxZoom: 20,
                    attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
                }).addTo(map);

                const marker = L.marker([<?php echo esc_html($address_latitude); ?>, <?php echo esc_html($address_longitude); ?>], {
                        alt: '<?php echo esc_html($address); ?>'
                    }).addTo(map)
                    .bindPopup('<?php echo esc_html($address); ?>');
            </script>
        <?php } else { ?>
            <iframe src="https://maps.google.com/maps?q=<?php echo esc_html($address); ?>&output=embed" width="100%" height="250" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
        <?php } ?>
    <?php } ?>
</div>
