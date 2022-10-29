'use strict';
window.addEventListener('DOMContentLoaded', function(){
    let map_wrapper = document.querySelector('.mannheim-under-construction-map-wrapper');
    if(map_wrapper) {
        const start_location = [49.4933, 8.4681];
        const audio_icon = L.icon({iconUrl: mannheim_under_construction.audio_icon_url, iconSize: [47, 47]});
        const walk_icon = L.icon({iconUrl: mannheim_under_construction.walk_icon_url, iconSize: [47, 47]});
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
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="noopener noreferrer">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions" target="_blank" rel="noopener noreferrer">CARTO</a>',
            subdomains: 'abcd',
            maxZoom: 19
        }).addTo(map);
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
            body.classList.remove('sidebar-fullscreen');
            body.classList.remove('sidebar-open');
        });
        sidebar_right.on('closing', _ => {
            body.classList.remove('sidebar-fullscreen');
            body.classList.remove('sidebar-open');
        });
        sidebar_right.on('opening', _ => {
            sidebar_left.close();
            update_bg();
            body.classList.add('sidebar-open');
        });
        let audio_stations = [];
        if (mannheim_under_construction.map_data) {
            let station_markers = L.markerClusterGroup();
            for (let location of mannheim_under_construction.map_data) {
                audio_stations[location.id] = location;
                if(location.hidden){
                    continue;
                }
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
                station_markers.addLayer(marker);
            }
            map.addLayer(station_markers);
        }
        let walks = [];
        if (mannheim_under_construction.walk_data) {
            let walk_markers = L.markerClusterGroup();
            for (let walk of mannheim_under_construction.walk_data) {
                walks[walk.id] = walk;
                let marker = L.marker([walk.lat, walk.lng], {title: walk.title, alt: walk.title, icon: walk_icon, data_id: walk.id});
                marker.addEventListener('click', e => {
                    load_walk(e.target.options.data_id);
                });
                marker.addEventListener('mouseover', e => {
                    e.target._icon.setAttribute('src', mannheim_under_construction.walk_icon_url_bw);
                });
                marker.addEventListener('mouseout', e => {
                    e.target._icon.setAttribute('src', mannheim_under_construction.walk_icon_url);
                });
                walk_markers.addLayer(marker);
            }
            map.addLayer(walk_markers);
        }
        let body = document.querySelector('body');
        let player = document.querySelector('#audio_player');
        let player_new = document.querySelector('#audio_player_new');
        let play_pause_buttons = document.querySelectorAll('.play_pause_button');
        let back_buttons = document.querySelectorAll('.back-button, button.close-button');
        let black_white_switcher = document.querySelector('#black_white_switcher');
        let font_size_button = document.querySelector('#font_size_switcher');
        let onboarding = document.querySelector('.mannheim-under-construction-onboarding');
        let popup = document.querySelector('.mannheim-under-construction-popup');
        let search_form = document.querySelector('#search form');
        let search_form_filters = document.querySelectorAll('#search .filter-box select');
        let search_form_open_status = document.querySelectorAll('#search .filter-box input[type="checkbox"]');
        let search_form_button = document.querySelector('.mannheim-under-construction-search button');
        let search_sidebar_fulltext = document.querySelector('#search-fulltext');
        let search_sidebar_tags = document.querySelector('#search-tags');
        let seek_backwards = document.querySelectorAll('.seek_backwards');
        let seek_forwards = document.querySelectorAll('.seek_forwards');
        let walk_prevs = document.querySelectorAll('#walk .prev-track');
        let walk_nexts = document.querySelectorAll('#walk .next-track');
        let waveform = document.querySelector('#play .waveform svg');
        let waveform_progress = waveform.querySelector('#progress');
        let waveform_update_interval = null;
        let open_under_construction_info = document.querySelectorAll('.open-under-construction-info');
        let open_under_construction_more_info = document.querySelectorAll('.under-construction-more-info');
        let search_extend = document.querySelector('#search-extend');
        let sidebar_left_dom = document.querySelector('.leaflet-sidebar-left');
        let sidebar_right_dom = document.querySelector('.leaflet-sidebar-right');
        let play_tab_button = document.querySelector('#play_tab_button');
        let content_walk_stations = document.querySelector('#walk .content-walk-stations');
        let walk_intro = document.querySelector('#walk .walk-intro');
        let walk_intro_player = walk_intro.querySelector('.content-player');

        let current_walk = 0;
        let current_walk_station = 0;
        for(let opener of open_under_construction_info){
            opener.addEventListener('click', _ => {
                sidebar_right.open('#info');
            });
        }
        for(let opener of open_under_construction_more_info){
            opener.addEventListener('click', _ => {
                if(body.classList.contains('sidebar-fullscreen')){
                    body.classList.remove('sidebar-fullscreen');
                } else {
                    body.classList.add('sidebar-fullscreen');
                }
            });
        }
        search_extend.addEventListener('click', _ => {
            body.classList.add('sidebar-fullscreen');
        });
        black_white_switcher.addEventListener('click', _ => {
            body.classList.toggle('black-white');
            update_bg();
        });
        for(let back_button of back_buttons) {
            back_button.addEventListener('click', _ => {
                if (body.classList.contains('sidebar-fullscreen')) {
                    body.classList.remove('sidebar-fullscreen');
                } else if(!sidebar_left_dom.classList.contains('collapsed')) {
                    sidebar_left.close();
                } else if(body.classList.contains('sidebar-fullscreen')){
                    body.classList.remove('sidebar-fullscreen');
                } else if(!sidebar_right_dom.classList.contains('collapsed')) {
                    sidebar_right.close();
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
        for(let play_pause_button of play_pause_buttons) {
            play_pause_button.addEventListener('click', play_pause_handler);
        }
        walk_intro_player.querySelector('.play_pause_button').removeEventListener('click', play_pause_handler);
        walk_intro_player.querySelector('.play_pause_button').addEventListener('click', _ => {
            if(current_walk.intros[0]){
                play_pause_handler_with_id(current_walk.intros[0].audio_id);
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
        for(let seek of seek_forwards) {
            seek.addEventListener('click', seek_forwards_handler);
        }
        for(let seek of seek_backwards) {
            seek.addEventListener('click', seek_backwards_handler);
        }
        for(let walk_prev of walk_prevs) {
            walk_prev.addEventListener('click', _ => {
                load_walk_station(current_walk_station - 1);
            });
        }
        for(let walk_next of walk_nexts) {
            walk_next.addEventListener('click', _ => {
                load_walk_station(current_walk_station + 1);
            });
        }
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
            show_search_results();
        });
        search_form_button.addEventListener('click', e => {
            e.preventDefault();
            show_search_results();
        });
        for(let filter of search_form_filters) {
            filter.addEventListener('change', e => {
                e.preventDefault();
                show_search_results();
            });
        }
        for(let open_status of search_form_open_status) {
            open_status.addEventListener('change', e => {
                if(!e.target.checked){
                    let options = e.target.parentElement.querySelectorAll('select option:checked');
                    if(options.length > 0){
                        for(let option of options){
                            option.selected = false;
                        }
                        show_search_results();
                    }
                }
            });
        }

        L.DomEvent.disableScrollPropagation(onboarding);
        L.DomEvent.disableClickPropagation(onboarding);
        if(popup) {
            L.DomEvent.disableScrollPropagation(popup);
            L.DomEvent.disableClickPropagation(popup);
            let close_button = popup.querySelector('button.close-button');
            close_button.addEventListener('click', _ => {
                popup.style.display = 'none';
            });
            popup.addEventListener('click', e => {
                if(e.target === popup) {
                    popup.style.display = 'none';
                }
            });
        }

        load_audio(mannheim_under_construction.initial_audio, mannheim_under_construction.load_initial_only);
        if(mannheim_under_construction.initial_walk) {
            load_walk(mannheim_under_construction.initial_walk);
        }
        function play_pause_handler(){
            if (player.innerHTML !== player_new.innerHTML) {
                player.pause();
                player.innerHTML = player_new.innerHTML;
                player.load();
            }
            if (player.paused) {
                player.play();
            } else {
                player.pause();
            }
        }
        function play_pause_handler_with_id(audio_id){
            let audio_station = audio_stations[audio_id];
            audio_player_new.innerHTML = '';
            if(audio_station.ogg) {
                audio_player_new.innerHTML += '<source src="' + audio_station.ogg + '" type="' + audio_station.ogg_mime + '">';
            }
            if(audio_station.aac) {
                audio_player_new.innerHTML += '<source src="' + audio_station.aac + '" type="' + audio_station.aac_mime + '">';
            }
            play_pause_handler();
        }

        function seek_forwards_handler() {
            if (player.duration > player.currentTime + 15) {
                player.currentTime += 15;
            } else {
                player.currentTime = player.duration;
            }
            if (player.innerHTML === player_new.innerHTML) {
                updateAudioPosition();
            }
        }

        function seek_backwards_handler() {
            if (0 < player.currentTime - 15) {
                player.currentTime -= 15;
            } else {
                player.currentTime = 0;
            }
            if (player.innerHTML === player_new.innerHTML) {
                updateAudioPosition();
            }
        }

        function show_search_results(){
            body.classList.add('wait');
            body.classList.add('sidebar-fullscreen');
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
        function load_audio(audio_id, load_only = false){
            if(audio_stations[audio_id]){
                play_tab_button.setAttribute('href', '#play');
                let audio_station = audio_stations[audio_id];
                document.querySelector('#play .content-location').innerHTML = audio_station.location;
                document.querySelector('#play .content-title').innerHTML = audio_station.title;
                document.querySelector('#play .content-description').innerHTML = audio_station.description;
                document.querySelector('#play .content-credits').innerHTML = audio_station.credits;
                let content_length = document.querySelector('#play .content-length .length');
                content_length.innerHTML = audio_station.length;
                content_length.setAttribute('aria-label', audio_station.length_readable);
                let tags_html = '';
                for(let tag of audio_station.tags) {
                    tags_html += '<div class="tag" data-tagid="' + tag + '">#' + mannheim_under_construction.tag_data[tag] + '</div>';
                }
                document.querySelector('#play .content-tags').innerHTML = tags_html;
                audio_player_new.innerHTML = '';
                if(audio_station.ogg) {
                    audio_player_new.innerHTML += '<source src="' + audio_station.ogg + '" type="' + audio_station.ogg_mime + '">';
                }
                if(audio_station.aac) {
                    audio_player_new.innerHTML += '<source src="' + audio_station.aac + '" type="' + audio_station.aac_mime + '">';
                }
                waveform.querySelector('path').setAttribute('d', audio_station.waveform);
                if(player.innerHTML !== player_new.innerHTML){
                    if(waveform_update_interval) {
                        clearInterval(waveform_update_interval);
                        waveform_update_interval = null;
                    }
                    waveform_progress.setAttribute('width', '0');
                }else if(!waveform_update_interval && !player.paused){
                    waveform_update_interval = setInterval(function(){
                        requestAnimationFrame(updateAudioPosition);
                    }, 100);
                }
                if(!load_only) {
                    sidebar_right.close();
                    body.classList.remove('sidebar-fullscreen');
                    sidebar_left.open('#play');
                    map.setView([audio_station.lat, audio_station.lng]);
                }
            }
        }

        function load_walk(walk_id){
            if(walks[walk_id]){
                play_tab_button.setAttribute('href', '#walk');
                current_walk = walks[walk_id];
                if(current_walk.intros[0]){
                    load_walk_station(-1);
                }
                let details_wrapper = walk_intro.querySelector('.intro-station-details-wrapper');
                let content_player = walk_intro.querySelector('.content-player');
                walk_intro.querySelector('span.next-track').innerHTML = current_walk.stations[0].title;
                details_wrapper.innerHTML = '';
                let first = true;
                for(let intro of current_walk.intros){
                    let audio_station = audio_stations[intro.audio_id];
                    if(first){
                        walk_intro.querySelector('.intro-station-description').innerHTML = audio_station.description;
                        first = false;
                    } else {
                        details_wrapper.innerHTML += '<details data-><summary>' + intro.title + '</summary><div class="content-player" data-audio-id="' + intro.audio_id + '">' + content_player.innerHTML + '</div>' + audio_station.description + '</details>';
                    }
                }
                let content_players = details_wrapper.querySelectorAll('.content-player');
                if(content_players) {
                    for (let content_player of content_players) {
                        content_player.querySelector('.play_pause_button').addEventListener('click', _ => {
                            play_pause_handler_with_id(parseInt(content_player.getAttribute('data-audio-id')));
                        });
                        content_player.querySelector('.seek_backwards').addEventListener('click', seek_backwards_handler);
                        content_player.querySelector('.seek_forwards').addEventListener('click', seek_forwards_handler);
                    }
                }
                sidebar_right.close();
                body.classList.remove('sidebar-fullscreen');
                sidebar_left.open('#walk');
                map.setView([current_walk.lat, current_walk.lng]);
            }
        }

        function load_walk_station(station_id){
            if(current_walk.stations[station_id]){
                content_walk_stations.style.display = '';
                walk_intro.style.display = 'none';
                current_walk_station = station_id;
                let station = current_walk.stations[station_id];
                let audio_station = audio_stations[station.audio_id];
                content_walk_stations.querySelector('.content-station-title').innerHTML = station.title;
                content_walk_stations.querySelector('.content-title').innerHTML = audio_station.title;
                content_walk_stations.querySelector('.content-location').innerHTML = audio_station.location;
                content_walk_stations.querySelector('.content-location-2').innerHTML = audio_station.location_2;
                content_walk_stations.querySelector('.content-description').innerHTML = audio_station.description;
                let image_container = content_walk_stations.querySelector('.content-image');
                if(audio_station.thumbnail) {
                    image_container.innerHTML = '<img src="' + audio_station.thumbnail + '" loading="lazy">';
                } else {
                    image_container.innerHTML = '';
                }
                let content_length = content_walk_stations.querySelector('.content-length .length');
                content_length.innerHTML = audio_station.length;
                content_length.setAttribute('aria-label', audio_station.length_readable);
                audio_player_new.innerHTML = '';
                if(audio_station.ogg) {
                    audio_player_new.innerHTML += '<source src="' + audio_station.ogg + '" type="' + audio_station.ogg_mime + '">';
                }
                if(audio_station.aac) {
                    audio_player_new.innerHTML += '<source src="' + audio_station.aac + '" type="' + audio_station.aac_mime + '">';
                }
                content_walk_stations.querySelector('.track-swipe-bar span.prev-track').innerHTML = ('' + station_id).padStart(2, '0') + ' / ' + ('' + current_walk.stations.length).padStart(2, '0');
                for(let walk_prev of walk_prevs){
                    walk_prev.style.display = '';
                }
                if(current_walk.stations.length <= station_id + 1) {
                    content_walk_stations.querySelector('.track-swipe-bar span.next-track').innerHTML = '';
                    for (let walk_next of walk_nexts) {
                        walk_next.style.display = 'none';
                    }
                } else if(current_walk.stations.length <= station_id + 2) {
                    content_walk_stations.querySelector('.track-swipe-bar span.next-track').innerHTML = 'Bonus';
                    for(let walk_next of walk_nexts){
                        walk_next.style.display = '';
                    }
                } else {
                    content_walk_stations.querySelector('.track-swipe-bar span.next-track').innerHTML = ('' + (station_id + 2 )).padStart(2, '0') + ' / ' + ('' + current_walk.stations.length).padStart(2, '0');
                    for(let walk_next of walk_nexts){
                        walk_next.style.display = '';
                    }
                }
                if(waveform_update_interval) {
                    clearInterval(waveform_update_interval);
                    waveform_update_interval = null;
                }
                waveform_progress.setAttribute('width', '0');
            } else if(station_id === -1){
                current_walk_station = station_id;
                content_walk_stations.style.display = 'none';
                walk_intro.style.display = '';
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
                    sidebar_left.open('#search');
                    body.classList.add('sidebar-fullscreen');
                    target_tag.nextElementSibling.classList.add('active');
                    target_tag.scrollIntoView();
                }
            }
        });
        document.querySelector('#help_menu').addEventListener('click', _ => {
            onboarding.querySelector('#onboarding-start-button').innerText = mannheim_under_construction.back;
            sidebar_right.close();
            onboarding.classList.add('active');
        });
        document.querySelector('#imprint_menu').addEventListener('click', _ => {
            body.classList.remove('sidebar-fullscreen');
            sidebar_right.open('#imprint');
        });
        document.querySelector('#privacy_menu').addEventListener('click', _ => {
            body.classList.remove('sidebar-fullscreen');
            sidebar_right.open('#privacy');
        });
    }
});
