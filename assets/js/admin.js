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
                });

                // Opens the media library frame.
                metaImageFrame.open();
            });
        }
        let accordions = document.getElementById('select-accordions');
        let accordion_count = 0;
        if(accordions){
            for (let accordion of mannheim_under_construction_admin.accordions) {
                let row = document.createElement('tr');
                row.innerHTML = '<td><input name="mannheim_under_construction_accordions['+accordion_count+'][title]" type="text" value="' + accordion.title + '"></td>';
                row.innerHTML += '<td><textarea name="mannheim_under_construction_accordions['+accordion_count+'][description]">' + accordion.description + '</textarea></td>';
                accordions.appendChild(row);
                ++accordion_count;
            }
            let row = document.createElement('tr');
            row.innerHTML = '<td><input name="mannheim_under_construction_accordions['+accordion_count+'][title]" type="text"></td>';
            row.innerHTML += '<td><textarea name="mannheim_under_construction_accordions['+accordion_count+'][description]"></textarea></td>';
            accordions.appendChild(row);
            ++accordion_count;
            let last_accordion_textarea = accordions.querySelector('tr:last-of-type textarea');
            function accordions_last_change(e) {
                e.target.removeEventListener('change', accordions_last_change);
                let row = document.createElement('tr');
                row.innerHTML = '<td><input name="mannheim_under_construction_accordions['+accordion_count+'][title]" type="text"></td>';
                row.innerHTML += '<td><textarea name="mannheim_under_construction_accordions['+accordion_count+'][description]"></textarea></td>';
                accordions.appendChild(row);
                ++accordion_count;
                last_accordion_textarea = accordions.querySelector('tr:last-of-type textarea');
                last_accordion_textarea.addEventListener('change', accordions_last_change);
            }
            last_accordion_textarea.addEventListener('change', accordions_last_change);
        }
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
        row.innerHTML = '<td><input name="mannheim_under_construction_stations['+station_count+'][title]" type="text" value="Station ' + (station_count + 1) + '"></td>';
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
