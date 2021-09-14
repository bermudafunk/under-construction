'use strict';
window.addEventListener('DOMContentLoaded', _ => {
    // Instantiates the variable that holds the media library frame.
    let metaImageFrame;

    // Runs when the media button is clicked.
    let upload_buttons = document.querySelectorAll( '.under-construction-upload' );
    if(upload_buttons) {
        for(let upload_button of upload_buttons) {
            upload_button.addEventListener('click', e => {
                e.preventDefault();
                let btn = e.target;
                let field = document.getElementById(btn.getAttribute('data-field'));
                let selected_label = document.getElementById(btn.getAttribute('data-field') + '-selected');
                let type = btn.getAttribute('data-type');

                metaImageFrame = wp.media.frames.metaImageFrame = wp.media({
                    title: mannheim_under_construction_admin.title,
                    button: {text: mannheim_under_construction_admin.button},
                    multiple: false,
                    library: {
                        orderby: "date",
                        query: true,
                        post_mime_type: type
                    },
                });

                metaImageFrame.on('select', function () {
                    // Grabs the attachment selection and creates a JSON representation of the model.
                    let media_attachment = metaImageFrame.state().get('selection').first().toJSON();
                    field.setAttribute('value', media_attachment.id);
                    selected_label.innerText = media_attachment.filename;
                    fetch(media_attachment.url).then(r => {
                        if(r.ok){
                            r.arrayBuffer().then(processTrack, console.error);
                        }
                    }, console.error);
                });

                // Opens the media library frame.
                metaImageFrame.open();
            });
        }
    }


    // Inspired by https://gist.github.com/maxjvh/e4f6c9ec0fdea9450fd9303dd088b96d

    let svg;
    let svg_width;
    let svg_height;
    let svg_progress;
    let svg_remaining;
    setTimeout(_ => {
        svg = document.querySelector('.mannheim-under-construction-waveform svg');
        if(svg) {
            svg_width = svg.getAttribute('width');
            svg_height = svg.getAttribute('height');
            svg_progress = svg.querySelector('#progress');
            svg_remaining = svg.querySelector('#remaining');
            svg.setAttribute('viewBox', `0 0 ${svg_width} ${svg_height}`);
            svg_progress.setAttribute('height', svg_height);
            svg_progress.setAttribute('width', '0');
            svg_remaining.setAttribute('height', svg_height);
            svg_remaining.setAttribute('width', svg_width);
        }
    }, 1000);

    const smoothing = 2;
    const avg = values => values.reduce((sum, value) => sum + value, 0) / values.length;
    const max = values => values.reduce((max, value) => Math.max(max, value), 0);
    const audioContext = new window.AudioContext();

    function getWaveformData(audioBuffer, dataPoints) {
        let channels = [];
        for(let i = 0; i < audioBuffer.numberOfChannels; ++i) {
            channels[i] = audioBuffer.getChannelData(i);
        }

        const values = new Float32Array(dataPoints);
        const dataWindow = Math.round(channels[0].length / dataPoints);
        for (let i = 0, y = 0, buffer = []; i < channels[0].length; ++i) {
            let summedValue = 0;
            for(let channel of channels){
                summedValue += Math.abs(channel[i]);
            }
            summedValue = summedValue / audioBuffer.numberOfChannels;
            buffer.push(summedValue);
            if (buffer.length === dataWindow) {
                values[y++] = avg(buffer);
                buffer = [];
            }
        }
        return values;
    }

    function getSVGPath(waveformData) {
        const maxValue = max(waveformData);

        let path = `M 0 ${svg_height} `;
        for (let i = 0; i < waveformData.length; ++i) {
            path += `L ${i * smoothing} ${(1 - waveformData[i] / maxValue) * svg_height} `;
        }
        path += `V ${svg_height} H 0 Z`;

        return path;
    }

    function processTrack(buffer) {
        console.time('decodeAudioData');
        return audioContext.decodeAudioData(buffer)
            .then(audioBuffer => {
                console.timeEnd('decodeAudioData');

                console.time('getWaveformData');
                const waveformData = getWaveformData(audioBuffer, svg_width / smoothing);
                console.timeEnd('getWaveformData');

                const svgPath = getSVGPath(waveformData, svg_height, smoothing);
                svg.querySelector('path').setAttribute('d', svgPath);
                document.querySelector('#mannheim_under_construction_waveform').setAttribute('value', svgPath);
            })
            .catch(console.error);
    }

    setTimeout(setup_map, 1000);
});

function setup_map(){
    const start_location = [49.4933, 8.4681];
    let map_element = document.getElementById('select-map-location');
    if(map_element === null){
        return;
    }
    let map = L.map(map_element, {center: start_location, zoom: 13, worldCopyJump: true});
    L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
        subdomains: 'abcd',
        maxZoom: 19
    }).addTo(map);
    const audio_icon = L.icon({iconUrl: mannheim_under_construction_admin.audio_icon_url, iconSize: [31, 47]});
    let marker = L.marker(start_location, {icon: audio_icon, title: mannheim_under_construction_admin.desired_location, alt: mannheim_under_construction_admin.desired_location}).addTo(map);
    let selected_location_lat = document.getElementById('mannheim_under_construction_location_lat');
    let selected_location_lng = document.getElementById('mannheim_under_construction_location_lng');
    if(selected_location_lat.value) {
        marker.setLatLng([selected_location_lat.value, selected_location_lng.value]);
    } else {
        selected_location_lat.value = start_location[0];
        selected_location_lng.value = start_location[1];
    }
    map.on('click', function(e) {
        marker.setLatLng(e.latlng);
        selected_location_lat.value = e.latlng.lat;
        selected_location_lng.value = e.latlng.lng;
    });
}
