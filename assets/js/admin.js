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
        return audioContext.decodeAudioData(buffer)
            .then(audioBuffer => {
                const waveformData = getWaveformData(audioBuffer, svg_width / smoothing);

                const svgPath = getSVGPath(waveformData, svg_height, smoothing);
                svg.querySelector('path').setAttribute('d', svgPath);
                document.querySelector('#mannheim_under_construction_waveform').setAttribute('value', svgPath);
            })
            .catch(console.error);
    }

    setTimeout(setup_map, 1000);
    if(mannheim_under_construction_admin.is_walk) {
        let station_count = 0;
        let intro_count = 0;
        let stations_selects = document.getElementById('select-walk-stations');
        let intro_selects = document.getElementById('select-walk-intros');
        if(!stations_selects || !intro_selects){
            return;
        }
        let audio_options = '<option value=""> - </option>';
        for (let audio of mannheim_under_construction_admin.walk.audios) {
            audio_options += '<option value="' + audio.id + '">' + audio.title + '</option>';
        }
        //station select
        for (let station of mannheim_under_construction_admin.walk.stations) {
            let row = document.createElement('tr');
            row.innerHTML = '<td><input name="mannheim_under_construction_stations['+station_count+'][title]" type="text" value="' + station.title + '"></td>';
            row.innerHTML += '<td><select name="mannheim_under_construction_stations['+station_count+'][audio_id]">' + audio_options + '</select></td>';
            let selected = row.querySelector('select option[value="'+station.audio_id+'"]');
            if(selected) {
                selected.setAttribute('selected', 'selected');
            }
            stations_selects.appendChild(row);
            ++station_count;
        }
        let row = document.createElement('tr');
        row.innerHTML = '<td><input name="mannheim_under_construction_intros['+station_count+'][title]" type="text" value="Station ' + (station_count + 1) + '"></td>';
        row.innerHTML += '<td><select name="mannheim_under_construction_stations['+station_count+'][audio_id]">' + audio_options + '</select></td>';
        stations_selects.appendChild(row);
        ++station_count;
        let last_station_select_field = stations_selects.querySelector('tr:last-of-type select');
        function stations_last_select_change(e) {
            e.target.removeEventListener('change', stations_last_select_change);
            let row = document.createElement('tr');
            row.innerHTML = '<td><input name="mannheim_under_construction_stations['+station_count+'][title]" type="text" value="Station ' + (station_count + 1) + '"></td>';
            row.innerHTML += '<td><select name="mannheim_under_construction_stations['+station_count+'][audio_id]">' + audio_options + '</select></td>';
            stations_selects.appendChild(row);
            ++station_count;
            last_station_select_field = stations_selects.querySelector('tr:last-of-type select');
            last_station_select_field.addEventListener('change', stations_last_select_change);
        }
        last_station_select_field.addEventListener('change', stations_last_select_change);

        //intro select
        for (let intro of mannheim_under_construction_admin.walk.intros) {
            let row = document.createElement('tr');
            row.innerHTML = '<td><input name="mannheim_under_construction_intros['+intro_count+'][title]" type="text" value="' + intro.title + '"></td>';
            row.innerHTML += '<td><select name="mannheim_under_construction_intros['+intro_count+'][audio_id]">' + audio_options + '</select></td>';
            let selected = row.querySelector('select option[value="'+intro.audio_id+'"]');
            if(selected) {
                selected.setAttribute('selected', 'selected');
            }
            intro_selects.appendChild(row);
            ++intro_count;
        }
        row = document.createElement('tr');
        row.innerHTML = '<td><input name="mannheim_under_construction_intros['+intro_count+'][title]" type="text" value="Intro ' + (intro_count + 1) + '"></td>';
        row.innerHTML += '<td><select name="mannheim_under_construction_intros['+intro_count+'][audio_id]">' + audio_options + '</select></td>';
        intro_selects.appendChild(row);
        ++intro_count;
        let last_intro_select_field = intro_selects.querySelector('tr:last-of-type select');
        function intro_last_select_change(e) {
            e.target.removeEventListener('change', intro_last_select_change);
            let row = document.createElement('tr');
            row.innerHTML = '<td><input name="mannheim_under_construction_intros['+intro_count+'][title]" type="text" value="Intro ' + (intro_count + 1) + '"></td>';
            row.innerHTML += '<td><select name="mannheim_under_construction_intros['+intro_count+'][audio_id]">' + audio_options + '</select></td>';
            intro_selects.appendChild(row);
            ++intro_count;
            last_intro_select_field = intro_selects.querySelector('tr:last-of-type select');
            last_intro_select_field.addEventListener('change', intro_last_select_change);
        }
        last_intro_select_field.addEventListener('change', intro_last_select_change);
    }
});

function setup_map(){
    const start_location = [49.4933, 8.4681];
    let map_element = document.getElementById('select-map-location');
    if(map_element === null){
        return;
    }
    let map = L.map(map_element, {center: start_location, zoom: 13, worldCopyJump: true});
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener">OpenStreetMap</a> contributors'
    }).addTo(map);
    const audio_icon = L.icon({iconUrl: mannheim_under_construction_admin.audio_icon_url, iconSize: [47, 47]});
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
