'use strict';
window.addEventListener('DOMContentLoaded', function(){
    let maps = document.getElementsByClassName('mannheim-under-construction-map-wrapper');
    if(maps) {
        const start_location = [49.4933, 8.4681];
        const audio_icon = L.icon({iconUrl: mannheim_under_construction.audio_icon_url, iconSize: [31, 47]});
        for (let map_wrapper of maps) {
            let map = L.map(map_wrapper.querySelector('.mannheim-under-construction-map'), {
                center: start_location,
                zoom: 13,
                worldCopyJump: true,
                zoomControl: false,
            });
            L.control.zoom({
                zoomInText: '+',
                zoomOutText: '−',
                zoomInTitle: mannheim_under_construction.zoom_in_title,
                zoomOutTitle: mannheim_under_construction.zoom_out_title,
                position: 'topleft',
            }).addTo(map);
            let tile_layer_light = L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
                attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener noreferrer">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions" target="_blank" rel="noopener noreferrer">CARTO</a>',
                subdomains: 'abcd',
                maxZoom: 19
            }).addTo(map);
            let tile_layer_dark = L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_smooth_dark/{z}/{x}/{y}{r}.png', {
                maxZoom: 20,
                attribution: '&copy; <a href="https://stadiamaps.com/" target="_blank" rel="noopener noreferrer">Stadia Maps</a>, &copy; <a href="https://openmaptiles.org/" target="_blank" rel="noopener noreferrer">OpenMapTiles</a> &copy; <a href="http://openstreetmap.org" target="_blank" rel="noopener noreferrer">OpenStreetMap</a> contributors'
            });
            let sidebar_left = L.control.sidebar({
                position: 'left',
                container: 'left_sidebar',
            }).addTo(map);
            let sidebar_right = L.control.sidebar({
                position: 'right',
                container: 'right_sidebar',
            }).addTo(map);
            sidebar_left.on('opening', _ => {
                sidebar_right.close();
                update_bg();
                body.classList.add('sidebar-open');
            });
            sidebar_left.on('closing', _ => {
                search_sidebar_fulltext.classList.remove('active');
                search_sidebar_tags.classList.remove('active');
                body.classList.remove('search-results-open');
                body.classList.remove('sidebar-open');
            });
            sidebar_right.on('closing', _ => {
                sidebar_right_dom.classList.remove('fully-expanded');
                body.classList.remove('deepdive-open');
                body.classList.remove('sidebar-open');
            });
            sidebar_right.on('opening', _ => {
                search_sidebar_fulltext.classList.remove('active');
                search_sidebar_tags.classList.remove('active');
                sidebar_left.close();
                update_bg();
                body.classList.add('sidebar-open');
            });
            let audio_stations = [];
            let audio_station_tags = JSON.parse(map_wrapper.querySelector('.tags-info').innerText);
            let locations = JSON.parse(map_wrapper.querySelector('.location-info').innerText);
            if (locations) {
                let markers = L.markerClusterGroup();
                for (let location of locations) {
                    audio_stations[location.id] = location;
                    let marker = L.marker([location.lat, location.lng], {title: location.title, alt: location.title, icon: audio_icon, data_id: location.id});
                    marker.addEventListener('click', e => {
                        load_audio(e.target.options.data_id);
                    });
                    marker.addEventListener('mouseover', e => {
                        e.target._icon.setAttribute('src', mannheim_under_construction.audio_icon_url_bw);
                    });
                    marker.addEventListener('mouseout', e => {
                        e.target._icon.setAttribute('src', mannheim_under_construction.audio_icon_url);
                    });
                    markers.addLayer(marker);
                }
                map.addLayer(markers);
            }
            let body = document.querySelector('body');
            let player = document.querySelector('#audio_player');
            let player_new = document.querySelector('#audio_player_new');
            let play_pause_button = document.querySelector('.play_pause_button');
            let back_buttons = document.querySelectorAll('#back_button, button.close-button');
            let black_white_switcher = document.querySelector('#black_white_switcher');
            let font_size_button = document.querySelector('#font_size_switcher');
            let onboarding = document.querySelector('.mannheim-under-construction-onboarding');
            let search_form = document.querySelector('.mannheim-under-construction-search');
            let search_form_input = document.querySelector('.mannheim-under-construction-search input');
            let search_form_button = document.querySelector('.mannheim-under-construction-search button');
            let search_sidebar_fulltext = document.querySelector('#search-fulltext');
            let search_sidebar_tags = document.querySelector('#search-tags');
            let seek_backwards = document.querySelector('#seek_backwards');
            let seek_forwards = document.querySelector('#seek_forwards');
            let waveform = document.querySelector('#play .waveform svg');
            let waveform_progress = waveform.querySelector('#progress');
            let waveform_update_interval = null;
            let open_under_construction_info = document.querySelectorAll('.open-under-construction-info');
            let open_under_construction_more_info = document.querySelectorAll('.under-construction-more-info');
            let sidebar_right_dom = document.querySelector('.leaflet-sidebar-right');
            for(let opener of open_under_construction_info){
                opener.addEventListener('click', _ => {
                    sidebar_right.open('#info');
                });
            }
            for(let opener of open_under_construction_more_info){
                opener.addEventListener('click', _ => {
                    sidebar_right_dom.classList.add('fully-expanded');
                    body.classList.add('deepdive-open');
                    sidebar_right.open('#info');
                });
            }
            black_white_switcher.addEventListener('click', _ => {
                if(body.classList.contains('black-white')){
                    black_white_switcher.querySelector('circle').setAttribute('fill', '#FFFFFF');
                    tile_layer_dark.removeFrom(map);
                    tile_layer_light.addTo(map);
                } else {
                    black_white_switcher.querySelector('circle').setAttribute('fill', '#F2FF5B');
                    tile_layer_light.removeFrom(map);
                    tile_layer_dark.addTo(map);
                }
                body.classList.toggle('black-white');
            });
            for(let back_button of back_buttons) {
                back_button.addEventListener('click', _ => {
                    sidebar_right.close();
                    if (search_sidebar_tags.classList.contains('active')) {
                        search_sidebar_tags.classList.remove('active');
                        body.classList.remove('search-results-open');
                    } else if (search_sidebar_fulltext.classList.contains('active')) {
                        search_sidebar_fulltext.classList.remove('active');
                        body.classList.remove('search-results-open');
                    } else {
                        sidebar_left.close();
                    }
                });
            }
            let font_size = 2;
            font_size_button.addEventListener('click', _ => {
                ++font_size;
                if(font_size > 3){
                    font_size = 1;
                }
                body.classList.remove('small-font');
                body.classList.remove('large-font');
                if(font_size === 1){
                    body.classList.add('small-font');
                }
                if(font_size === 3){
                    body.classList.add('large-font');
                }
            });
            player.addEventListener('play', _ => {
                if(player.innerHTML === player_new.innerHTML){
                    waveform_update_interval = setInterval(function () {
                        requestAnimationFrame(updateAudioPosition);
                    }, 100);
                }
            });
            player.addEventListener('pause', _ => {
                if(waveform_update_interval){
                    clearInterval(waveform_update_interval);
                    waveform_update_interval = null;
                }
            });
            play_pause_button.addEventListener('click', _ => {
                if(player.innerHTML !== player_new.innerHTML) {
                    player.pause();
                    player.innerHTML = player_new.innerHTML;
                    player.load();
                }
                if (player.paused) {
                    player.play();
                } else {
                    player.pause();
                }
            });
            waveform.addEventListener('click', e => {
                const position = e.offsetX / waveform.getBoundingClientRect().width;
                if(player.innerHTML !== player_new.innerHTML) {
                    player.pause();
                    player.innerHTML = player_new.innerHTML;
                    player.load();
                    player.addEventListener('durationchange', update_time);
                    function update_time(){
                        player.currentTime = position * player.duration;
                        player.play();
                        player.removeEventListener('durationchange', update_time);
                    }
                } else {
                    if(player.duration) {
                        player.currentTime = position * player.duration;
                        player.play();
                    }
                }
            });
            seek_forwards.addEventListener('click', _ => {
                if(player.duration > player.currentTime + 15){
                    player.currentTime += 15;
                } else {
                    player.currentTime = player.duration;
                }
            });
            seek_backwards.addEventListener('click', _ => {
                if(0 < player.currentTime - 15){
                    player.currentTime -= 15;
                } else {
                    player.currentTime = 0;
                }
            });
            onboarding.addEventListener('click', _ => {
                onboarding.classList.remove('active');
            });
            onboarding.addEventListener('keydown', e => {
                if(['Esc', 'Escape', 'Enter', ' '].includes(e.key)) {
                    onboarding.classList.remove('active');
                }
            });
            search_form.addEventListener('submit', e => {
                e.preventDefault();
                show_search_results(search_form_input.value);
            });
            search_form_input.addEventListener('click', e => {
                if(e.clientX > 410 && window.innerWidth >= 1200 ){
                    show_search_results(search_form_input.value);
                } else if(e.clientX > 340 && window.innerWidth >= 992 && window.innerWidth < 1200 ){
                    show_search_results(search_form_input.value);
                } else if(e.clientX > 250 && window.innerWidth >= 768 && window.innerWidth < 992 ){
                    show_search_results(search_form_input.value);
                } else if(e.clientX > window.innerWidth - 80 ){
                    show_search_results(search_form_input.value);
                }
            });
            search_form_button.addEventListener('click', e => {
                e.preventDefault();
                search_sidebar_fulltext.classList.remove('active');
                search_sidebar_tags.classList.add('active');
                body.classList.add('search-results-open');
            });

            L.DomEvent.disableScrollPropagation(search_sidebar_fulltext);
            L.DomEvent.disableClickPropagation(search_sidebar_fulltext);
            L.DomEvent.disableScrollPropagation(search_sidebar_tags);
            L.DomEvent.disableClickPropagation(search_sidebar_tags);
            L.DomEvent.disableScrollPropagation(onboarding);
            L.DomEvent.disableClickPropagation(onboarding);
            function show_search_results(s){
                search_sidebar_tags.classList.remove('active');
                search_sidebar_fulltext.classList.add('active');
                body.classList.add('wait');
                body.classList.add('search-results-open');
                let form_data = new FormData(search_form);
                form_data.append('action', 'audio_search');
                fetch(mannheim_under_construction.ajax_url, {
                    body: form_data,
                    method: 'POST',
                }).then(response => {
                    if(response.ok){
                        response.json().then(r => {
                            search_sidebar_fulltext.querySelector('.message').innerHTML = r.data.message;
                            search_sidebar_fulltext.querySelector('.audios').innerHTML = r.data.audios_html;
                            let results = search_sidebar_fulltext.querySelectorAll('.audios li');
                            for(let result of results){
                                result.addEventListener('click', e => {
                                    load_audio(e.target.getAttribute('data-id'));
                                });
                            }
                        }, _ => {
                            search_sidebar_fulltext.querySelector('.message').innerHTML = mannheim_under_construction.search_error_message;
                        });
                    }
                    body.classList.remove('wait');
                }, _ => {
                    body.classList.remove('wait');
                });
            }
            let tags = search_sidebar_tags.querySelectorAll('.tag');
            for(let tag of tags){
                tag.addEventListener('click', e => {
                    e.target.nextElementSibling.classList.toggle('active');
                });
            }
            let tag_results = search_sidebar_tags.querySelectorAll('.tag-result li');
            for(let tag_result of tag_results){
                tag_result.addEventListener('click', e => {
                    load_audio(e.target.getAttribute('data-id'));
                });
            }
            function load_audio(audio_id){
                if(audio_stations[audio_id]){
                    let audio_station = audio_stations[audio_id];
                    document.querySelector('#play .content-title').innerHTML = audio_station.title;
                    document.querySelector('#play .content-description').innerHTML = audio_station.description;
                    let content_length = document.querySelector('#play .content-length');
                    content_length.innerHTML = audio_station.length;
                    content_length.setAttribute('aria-label', audio_station.length_readable);
                    let tags_html = '';
                    for(let tag of audio_station.tags) {
                        tags_html += '<div class="tag" data-tagid="' + tag + '">#' + audio_station_tags[tag] + '</div>';
                    }
                    document.querySelector('#play .content-tags').innerHTML = tags_html;
                    document.querySelector('#audio_player_new').innerHTML = '';
                    if(audio_station.ogg) {
                        document.querySelector('#audio_player_new').innerHTML += '<source src="' + audio_station.ogg + '" type="' + audio_station.ogg_mime + '">';
                    }
                    if(audio_station.aac) {
                        document.querySelector('#audio_player_new').innerHTML += '<source src="' + audio_station.aac + '" type="' + audio_station.aac_mime + '">';
                    }
                    waveform.querySelector('path').setAttribute('d', audio_station.waveform);
                    if(player.innerHTML !== player_new.innerHTML){
                        if(waveform_update_interval) {
                            clearInterval(waveform_update_interval);
                            waveform_update_interval = null;
                        }
                        waveform_progress.setAttribute('width', 0);
                    }else if(!waveform_update_interval && !player.paused){
                        waveform_update_interval = setInterval(function(){
                            requestAnimationFrame(updateAudioPosition);
                        }, 100);
                    }
                    sidebar_right.close();
                    search_sidebar_fulltext.classList.remove('active');
                    search_sidebar_tags.classList.remove('active');
                    body.classList.remove('search-results-open');
                    sidebar_left.open('#play');
                    map.setView([audio_station.lat, audio_station.lng]);
                }
            }

            function updateAudioPosition() {
                const {currentTime, duration} = player;
                const physicalPosition = currentTime / duration * 500;
                if (physicalPosition) {
                    waveform_progress.setAttribute('width', physicalPosition);
                }
            }

            function update_bg(){
                let backgrounds;
                if(body.classList.contains('black-white')){
                    backgrounds = mannheim_under_construction.dark_backgrounds;
                } else {
                    backgrounds = mannheim_under_construction.light_backgrounds;
                }
                document.documentElement.style.setProperty('--sidebar-background', 'url(' + backgrounds[Math.floor(Math.random() * backgrounds.length)] + ')');
            }

            document.querySelector('#play .content-tags').addEventListener('click', e => {
                let tag_id = e.target.getAttribute('data-tagid');
                if(tag_id) {
                    let target_tag = search_sidebar_tags.querySelector('.tag[data-tagid="' + tag_id + '"]');
                    if(target_tag) {
                        search_sidebar_tags.classList.add('active');
                        body.classList.add('search-results-open');
                        target_tag.nextElementSibling.classList.add('active');
                        target_tag.scrollIntoView();
                    }
                }
            });
            document.querySelector('#help_menu').addEventListener('click', _ => {
                onboarding.querySelector('#onboarding-start-button').innerText = mannheim_under_construction.back;
                onboarding.classList.add('active');
            });
            document.querySelector('#imprint_menu').addEventListener('click', _ => {
                sidebar_right_dom.classList.remove('fully-expanded');
                body.classList.remove('deepdive-open');
                sidebar_right.open('#imprint');
            });
            document.querySelector('#privacy_menu').addEventListener('click', _ => {
                sidebar_right_dom.classList.remove('fully-expanded');
                body.classList.remove('deepdive-open');
                sidebar_right.open('#privacy');
            });
        }
    }
});
