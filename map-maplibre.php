<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Event Map with Styled Popups</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="fonts/inter.css">

    <!-- MapLibre GL CSS and JS -->
    <link href="https://unpkg.com/maplibre-gl@3.3.1/dist/maplibre-gl.css" rel="stylesheet" />
    <script src="https://unpkg.com/maplibre-gl@3.3.1/dist/maplibre-gl.js"></script>

    <style>
        html, body {
            margin: 0;
            padding: 0;
            height: 100%;
            font-family: 'Inter';
        }

        #map {
            width: 100%;
            height: 100%;
        }

        /* Hide popup background, border, and shadow */
        .mapboxgl-popup-content {
            background: none !important;
            box-shadow: none !important;
            padding: 0 !important;
            border-radius: 0 !important;
        }

        /* Hide popup close button */
        .mapboxgl-popup-close-button {
            display: none !important;
        }

        /* Hide the small triangle tip */
        .mapboxgl-popup-tip {
            display: none !important;
        }

        .popup-content strong {
            font-size: 1.4em;
            line-height: 1.1em;
            color: #333333;
            display: block;
            margin-bottom: 4px;
        }

        .popup-content p {
            margin: 4px 0;
        }

        .popup-content button {
            margin-top: 8px;
            padding: 6px 12px;
            background-color: #fff;
            color: #406E37;
            border: 1px solid #A0D896;
            border-radius: 5px;
            font-size: 1em;
            cursor: pointer;
            transition: 0.3s;
        }

        .popup-content button:hover {
            border-color: #333333;
            color: #333333;
        }

        .popup-wrapper {
            background: rgba(255, 255, 255, 0.2);
            color: #333;
            font-family: Arial, sans-serif;
            font-size: 1.2em;
            max-width: 260px;
        }

        /* Make popup background transparent */
        .mapboxgl-popup-content {
            background: transparent !important;
            box-shadow: none !important;
            padding: 0 !important;
            border: none !important;
        }

        /* Hide the popup tip (arrow) */
        .mapboxgl-popup-tip {
            display: none !important;
        }

        /* Hide the close "×" button */
        .mapboxgl-popup-close-button {
            display: none !important;
        }
    </style>
</head>
<body>

<div id="map"></div>

<script type="module">
    const map = new maplibregl.Map({
        container: 'map',
        style: {
            version: 8,
            glyphs: 'https://demotiles.maplibre.org/font/{fontstack}/{range}.pbf',
            sources: {
                osm: {
                    type: 'raster',
                    tiles: ['https://tiles.oklabflensburg.de/sgm/{z}/{x}/{y}.png'],
                    tileSize: 256,
                    attribution: 'MapLibre | © OpenStreetMap contributors'
                }
            },
            layers: [{
                id: 'osm-layer',
                type: 'raster',
                source: 'osm',
                paint: {
                    'raster-brightness-min': 0.5,
                    'raster-brightness-max': 1
                }
            }]
        },
        center: [9.5, 54.3],
        zoom: 8
    });

    map.addControl(new maplibregl.NavigationControl());

    let currentPopup = null;

    <?php include("config.php"); ?>
    const apiUrl = <?php echo json_encode($apiBaseUrl . "/query?mode=venue-map"); ?>;

    fetch (apiUrl)
        .then(response => response.json())
        .then(data => {
            const events = data.events || [];

            const geojson = {
                type: "FeatureCollection",
                features: events
                    .filter(e => e.venue_lat && e.venue_lon)
                    .map(e => ({
                        type: "Feature",
                        geometry: {
                            type: "Point",
                            coordinates: [e.venue_lon, e.venue_lat]
                        },
                        properties: {
                            venue_name: e.venue_name,
                            venue_city: e.venue_city,
                            venue_type_list: e.venue_type_list,
                            venue_url: e.venue_url

                        }
                    }))
            };

            map.on('load', () => {
                map.addSource('events', {
                    type: 'geojson',
                    data: geojson,
                    cluster: true,
                    clusterMaxZoom: 17,
                    clusterRadius: 40
                });

                map.addLayer({
                    id: 'clusters',
                    type: 'circle',
                    source: 'events',
                    filter: ['has', 'point_count'],
                    paint: {
                        'circle-color': '#ffffff',
                        'circle-radius': [
                            'step',
                            ['get', 'point_count'],
                            24,
                            16,
                            32,
                            48,
                            40
                        ],
                        'circle-stroke-width': 3,
                        'circle-stroke-color': '#A0D896'
                    }
                });

                map.addLayer({
                    id: 'cluster-count',
                    type: 'symbol',
                    source: 'events',
                    filter: ['has', 'point_count'],
                    layout: {
                        'text-field': '{point_count_abbreviated}',
                        'text-size': 14
                    },
                    paint: {
                        'text-color': '#000000'
                    }
                });

                map.loadImage('marker.png', (error, image) => {
                    if (error) throw error;
                    map.addImage('custom-marker', image);

                    map.addLayer({
                        id: 'unclustered-point',
                        type: 'symbol',
                        source: 'events',
                        filter: ['!', ['has', 'point_count']],
                        layout: {
                            'icon-image': 'custom-marker',
                            'icon-size': 0.8,
                            'icon-anchor': 'bottom',
                            'icon-allow-overlap': true,
                            'icon-ignore-placement': true
                        }
                    });
                });

                map.on('click', 'unclustered-point', (e) => {
                    const props = e.features[0].properties;

                    // Close previous popup if open
                    if (currentPopup) {
                        currentPopup.remove();
                        currentPopup = null;
                    }

                    const html = `
                        <div class="popup-wrapper">
                            <div class="popup-content">
                                <strong>
                                    ${props.venue_url
                                            ? `<a href="${props.venue_url}" target="_blank" style="color: #333333; text-decoration: none;">
                                            ${props.venue_name}
                                        </a>`
                                            : props.venue_name
                                        }
                                </strong>
                                <p>${props.venue_city}</p>
                                ${props.venue_type_list ? `<p>${props.venue_type_list}</p>` : ''}
                                ${props.venue_url ? `<button onclick="window.open('${props.venue_url}', '_blank')">Details</button>` : ''}
                            </div>
                        </div>
                    `;

                    currentPopup = new maplibregl.Popup({ closeButton: true })
                        .setLngLat(e.lngLat)
                        .setHTML(html)
                        .addTo(map);
                });

                // Clicking elsewhere closes popup
                map.on('click', (e) => {
                    const features = map.queryRenderedFeatures(e.point, {
                        layers: ['unclustered-point', 'clusters']
                    });
                    if (features.length === 0 && currentPopup) {
                        currentPopup.remove();
                        currentPopup = null;
                    }
                });

                map.on('click', 'clusters', (e) => {
                    const features = map.queryRenderedFeatures(e.point, {
                        layers: ['clusters']
                    });
                    const clusterId = features[0].properties.cluster_id;
                    map.getSource('events').getClusterExpansionZoom(
                        clusterId,
                        (err, zoom) => {
                            if (err) return;
                            map.easeTo({
                                center: features[0].geometry.coordinates,
                                zoom: zoom
                            });
                        }
                    );
                });

                map.on('mouseenter', 'unclustered-point', () => {
                    map.getCanvas().style.cursor = 'pointer';
                });
                map.on('mouseleave', 'unclustered-point', () => {
                    map.getCanvas().style.cursor = '';
                });
                map.on('mouseenter', 'clusters', () => {
                    map.getCanvas().style.cursor = 'pointer';
                });
                map.on('mouseleave', 'clusters', () => {
                    map.getCanvas().style.cursor = '';
                });
            });
        })
        .catch(error => {
            console.error('Error loading events:', error);
        });
</script>

</body>
</html>