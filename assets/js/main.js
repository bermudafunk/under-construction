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
                let marker = L.marker([location.lat, location.lng], {title: location.title, alt: location.title, icon: audio_icon, data_id: location.id, keyboard: false});
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
            let walk_list = document.querySelector('.walk-list');
            for (let walk of mannheim_under_construction.walk_data) {
                walk_list.innerHTML += '<li data-walk-id="' + walk.id + '">' + walk.title + '</li>';
                walks[walk.id] = walk;
            }
            let walk_links = walk_list.querySelectorAll('li');
            for(let walk_link of walk_links){
                walk_link.addEventListener('click', e => {
                    load_walk(e.target.getAttribute('data-walk-id'));
                })
            }
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
        let timeline_update_interval = null;
        let timelines = document.querySelectorAll('.content-timeline');
        let open_under_construction_info = document.querySelectorAll('.open-under-construction-info');
        let open_under_construction_more_info = document.querySelectorAll('.under-construction-more-info');
        let search_extend = document.querySelector('#search-extend');
        let sidebar_left_dom = document.querySelector('.leaflet-sidebar-left');
        let sidebar_right_dom = document.querySelector('.leaflet-sidebar-right');
        let play_tab_button = document.querySelector('#play_tab_button');
        let play_tab = document.querySelector('#play');
        let play_tab_pause = play_tab.querySelector('.play_pause_button .pause');
        let play_tab_play = play_tab.querySelector('.play_pause_button .play');
        let play_track_swipe_bar = play_tab.querySelector('.track-swipe-bar');
        let play_track_swipe_bar_arrows = play_track_swipe_bar.querySelector('.swipe-bar-arrow-holder');
        let walk = document.querySelector('#walk');
        let walk_prevs = walk.querySelectorAll('.prev-track');
        let walk_nexts = walk.querySelectorAll('.next-track');
        let content_walk_stations = walk.querySelector('.content-walk-stations');
        let content_walk_pause = content_walk_stations.querySelector('.play_pause_button .pause');
        let content_walk_play = content_walk_stations.querySelector('.play_pause_button .play');
        let walk_select = walk.querySelector('.walk-select');
        let walk_end = walk.querySelector('.walk-end');
        let walk_intro = walk.querySelector('.walk-intro');
        let walk_intro_player = walk_intro.querySelector('.content-player');
        let explainers = walk.querySelectorAll('.onboarding-explainer-description');
        let current_audio = 0;
        let current_audio_update = 0;
        let current_walk = 0;
        let current_walk_station = 0;
        let current_walk_explainer = 0;
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
            if(!timeline_update_interval && player.innerHTML === player_new.innerHTML){
                timeline_update_interval = setInterval(function () {
                    requestAnimationFrame(updateAudioPosition);
                }, 100);
            }
        });
        player.addEventListener('pause', _ => {
            if(timeline_update_interval){
                clearInterval(timeline_update_interval);
                timeline_update_interval = null;
            }
        });
        for(let play_pause_button of play_pause_buttons) {
            play_pause_button.addEventListener('click', play_pause_handler);
        }
        walk_intro_player.querySelector('.play_pause_button').removeEventListener('click', play_pause_handler);
        walk_intro_player.querySelector('.play_pause_button').addEventListener('click', _ => {
            if(current_walk.intros[0]){
                play_pause_handler_with_id(current_walk.intros[0].audio_id);
                walk_intro_player.querySelector('.play_pause_button .play').style.display = player.paused ? '' : 'none';
                walk_intro_player.querySelector('.play_pause_button .pause').style.display = player.paused ? 'none' : '';
            }
        });

        for(let timeline of timelines) {
            timeline.addEventListener('click', e => {
                const position = e.offsetX / timeline.getBoundingClientRect().width;
                if (player.innerHTML !== player_new.innerHTML) {
                    player.addEventListener('durationchange', update_time);
                    play_pause_handler();
                    function update_time() {
                        player.currentTime = position * player.duration;
                        player.play().catch(_=>{});
                        player.removeEventListener('durationchange', update_time);
                    }
                } else {
                    if (player.duration) {
                        player.currentTime = position * player.duration;
                        player.play().catch(_=>{});
                    }
                }
            });
        }
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
        play_track_swipe_bar_arrows.addEventListener('click', _ => {
            load_audio_update(current_audio_update + 1);
        });
        let play_touch_x_down = 0;
        let play_touch_x_up = 0;
        play_tab.addEventListener('touchstart', e => {
            play_touch_x_down = e.touches[0].clientX;
            play_touch_x_up = e.touches[0].clientX;
            e.stopPropagation();
        }, {passive: true});
        play_tab.addEventListener('touchmove', e => {
            play_touch_x_up = e.touches[0].clientX;
            e.stopPropagation();
        }, {passive: true});
        play_tab.addEventListener('touchend', e => {
            if (Math.abs(play_touch_x_down - play_touch_x_up) > 40) {
                if (play_touch_x_down > play_touch_x_up) {
                    load_audio_update(current_audio_update + 1);
                } else {
                    load_audio_update(current_audio_update - 1);
                }
            }
            e.stopPropagation();
        }, {passive: true});
        play_tab.addEventListener('mousedown', e => {
            play_touch_x_down = e.clientX;
            e.stopPropagation();
        }, {passive: true});
        play_tab.addEventListener('mouseup', e => {
            play_touch_x_up = e.clientX;
            if (Math.abs(play_touch_x_down - play_touch_x_up) > 40) {
                if (play_touch_x_down > play_touch_x_up) {
                    load_audio_update(current_audio_update + 1);
                } else {
                    load_audio_update(current_audio_update - 1);
                }
            }
            e.stopPropagation();
        }, {passive: true});
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
        document.querySelector('#walk_button').addEventListener('click', _ => {
            load_walk_station(current_walk_station);
        });
        play_tab_button.addEventListener('click', _ => {
            load_audio(current_audio);
        });

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

        let walk_touch_x_down = 0;
        let walk_touch_x_up = 0;
        for(let screen of [content_walk_stations, walk_intro]) {
            screen.addEventListener('touchstart', e => {
                walk_touch_x_down = e.touches[0].clientX;
                walk_touch_x_up = e.touches[0].clientX;
            }, {passive: true});
            screen.addEventListener('touchmove', e => {
                walk_touch_x_up = e.touches[0].clientX;
            }, {passive: true});
            screen.addEventListener('touchend', _ => {
                if (Math.abs(walk_touch_x_down - walk_touch_x_up) > 40) {
                    if (walk_touch_x_down > walk_touch_x_up) {
                        load_walk_station(current_walk_station + 1);
                    } else {
                        load_walk_station(current_walk_station - 1);
                    }
                }
            }, {passive: true});
            screen.addEventListener('mousedown', e => {
                walk_touch_x_down = e.clientX;
            }, {passive: true});
            screen.addEventListener('mouseup', e => {
                walk_touch_x_up = e.clientX;
                if (Math.abs(walk_touch_x_down - walk_touch_x_up) > 40) {
                    if (walk_touch_x_down > walk_touch_x_up) {
                        load_walk_station(current_walk_station + 1);
                    } else {
                        load_walk_station(current_walk_station - 1);
                    }
                }
            }, {passive: true});
        }
        for(let explainer_wrapper of explainers) {
            explainer_wrapper.addEventListener('touchstart', e => {
                walk_touch_x_down = e.touches[0].clientX;
                walk_touch_x_up = e.touches[0].clientX;
                e.stopPropagation();
            }, {passive: true});
            explainer_wrapper.addEventListener('touchmove', e => {
                walk_touch_x_up = e.touches[0].clientX;
                e.stopPropagation();
            }, {passive: true});
            explainer_wrapper.addEventListener('touchend', e => {
                if (Math.abs(walk_touch_x_down - walk_touch_x_up) > 40) {
                    if (walk_touch_x_down > walk_touch_x_up) {
                        load_walk_explainer(current_walk_explainer + 1);
                    } else {
                        load_walk_explainer(current_walk_explainer - 1);
                    }
                }
                e.stopPropagation();
            }, {passive: true});
            explainer_wrapper.addEventListener('mousedown', e => {
                walk_touch_x_down = e.clientX;
                e.stopPropagation();
            }, {passive: true});
            explainer_wrapper.addEventListener('mouseup', e => {
                walk_touch_x_up = e.clientX;
                if (Math.abs(walk_touch_x_down - walk_touch_x_up) > 40) {
                    if (walk_touch_x_down > walk_touch_x_up) {
                        load_walk_explainer(current_walk_explainer + 1);
                    } else {
                        load_walk_explainer(current_walk_explainer - 1);
                    }
                }
                e.stopPropagation();
            }, {passive: true});
            let explainer_prev = explainer_wrapper.querySelector('.prev-slide');
            let explainer_next = explainer_wrapper.querySelector('.next-slide');
            explainer_next.addEventListener('click', _ => {
                load_walk_explainer(current_walk_explainer + 1);
            });
            explainer_prev.addEventListener('click', _ => {
                load_walk_explainer(current_walk_explainer - 1);
            });
        }

        load_audio(mannheim_under_construction.initial_audio, mannheim_under_construction.load_initial_only);
        if(mannheim_under_construction.initial_walk && mannheim_under_construction.initial_walk !== "0") {
            load_walk(mannheim_under_construction.initial_walk);
        } else {
            if(mannheim_under_construction.walk_data.length === 1){
                load_walk(mannheim_under_construction.walk_data[0].id, true);
                if(!mannheim_under_construction.load_initial_only){
                    load_audio(mannheim_under_construction.initial_audio, false);
                }
            }
        }
        function play_pause_handler(){
            if (player.innerHTML !== player_new.innerHTML) {
                player.pause();
                player.innerHTML = player_new.innerHTML;
                player.load();
            }
            let all_plays = document.querySelectorAll('.content-player .play');
            let all_pauses = document.querySelectorAll('.content-player .pause');
            if(all_plays) {
                for (let play of all_plays) {
                    play.style.display = '';
                }
            }
            if(all_pauses) {
                for (let pause of all_pauses) {
                    pause.style.display = 'none';
                }
            }
            if (player.paused) {
                player.play().catch(_=>{});
                content_walk_play.style.display = 'none';
                content_walk_pause.style.display = '';
                play_tab_play.style.display = 'none';
                play_tab_pause.style.display = '';
            } else {
                player.pause();
                content_walk_play.style.display = '';
                content_walk_pause.style.display = 'none';
                play_tab_play.style.display = '';
                play_tab_pause.style.display = 'none';
            }
        }
        function play_pause_handler_with_id(audio_id){
            let audio_station = audio_stations[audio_id];
            player_new.innerHTML = '';
            if(audio_station.ogg) {
                player_new.innerHTML += '<source src="' + audio_station.ogg + '" type="' + audio_station.ogg_mime + '">';
            }
            if(audio_station.aac) {
                player_new.innerHTML += '<source src="' + audio_station.aac + '" type="' + audio_station.aac_mime + '">';
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
            current_audio = audio_id;
            current_audio_update = 0;
            if(audio_stations[audio_id]){
                let audio_station = audio_stations[audio_id];
                play_tab.querySelector('.content-location').innerHTML = audio_station.location;
                play_tab.querySelector('.content-location-2').innerHTML = audio_station.location_2;
                play_tab.querySelector('.content-title').innerHTML = audio_station.title;
                play_tab.querySelector('.content-description').innerHTML = audio_station.description;
                play_tab.querySelector('.content-credits').innerHTML = audio_station.credits;
                let details_wrapper = play_tab.querySelector('.content-description-details');
                details_wrapper.innerHTML = '';
                for(let accordion of audio_station.accordions){
                    details_wrapper.innerHTML += '<details><summary><span>' + accordion.title + '</span><span class="arrow"></span></summary>' + accordion.description + '</details>';
                }
                let content_length = play_tab.querySelector('.content-length .length');
                content_length.innerHTML = audio_station.length;
                content_length.setAttribute('aria-label', audio_station.length_readable);
                let tags_html = '';
                for(let tag of audio_station.tags) {
                    tags_html += '<div class="tag" data-tagid="' + tag + '">#' + mannheim_under_construction.tag_data[tag] + '</div>';
                }
                play_tab.querySelector('.content-tags').innerHTML = tags_html;
                play_tab.querySelector('.content-production-date').innerHTML = audio_station.production_date;
                if(audio_station.updates.length > 0){
                    play_track_swipe_bar.querySelector('span.next-track').innerHTML = '01' + ' / ' + ('' + (audio_station.updates.length + 1)).padStart(2, '0');
                    play_track_swipe_bar_arrows.innerHTML = '';
                    play_track_swipe_bar_arrows.innerHTML += '<svg class="active next-track" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 60 60" xml:space="preserve"><path d="M31.83,51H20.728l9.761-21L20.728,9H31.83l9.764,21L31.83,51z"/></svg>';
                    for(let i=audio_station.updates.length; i>0; --i){
                        play_track_swipe_bar_arrows.innerHTML += '<svg class="next-track" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 60 60" xml:space="preserve"><path d="M31.83,51H20.728l9.761-21L20.728,9H31.83l9.764,21L31.83,51z"/></svg>';
                    }
                    play_track_swipe_bar.style.display = '';
                } else {
                    play_track_swipe_bar.style.display = 'none';
                }
                player_new.innerHTML = '';
                if(audio_station.ogg) {
                    player_new.innerHTML += '<source src="' + audio_station.ogg + '" type="' + audio_station.ogg_mime + '">';
                }
                if(audio_station.aac) {
                    player_new.innerHTML += '<source src="' + audio_station.aac + '" type="' + audio_station.aac_mime + '">';
                }
                if(player.innerHTML !== player_new.innerHTML){
                    if(timeline_update_interval) {
                        clearInterval(timeline_update_interval);
                        timeline_update_interval = null;
                    }
                    document.documentElement.style.setProperty('--audio-progress', 0);
                }else if(!timeline_update_interval && !player.paused){
                    timeline_update_interval = setInterval(function(){
                        requestAnimationFrame(updateAudioPosition);
                    }, 100);
                }
                if(player_new.innerHTML !== player.innerHTML || player.paused){
                    play_tab_play.style.display = '';
                    play_tab_pause.style.display = 'none';
                } else {
                    play_tab_play.style.display = 'none';
                    play_tab_pause.style.display = '';
                }
                if(!load_only) {
                    sidebar_right.close();
                    body.classList.remove('sidebar-fullscreen');
                    sidebar_left.open('#play');
                    map.setView([audio_station.lat, audio_station.lng]);
                }
            }
        }

        function load_audio_update(update_id){
            if(update_id === 0){
                load_audio(current_audio);
                return
            }
            if(audio_stations[current_audio].updates[update_id-1]){
                current_audio_update = update_id;
                let audio_station = audio_stations[current_audio].updates[update_id-1];
                if(audio_stations[current_audio].updates.length > 0){
                    play_track_swipe_bar.querySelector('span.next-track').innerHTML = ('' + (update_id + 1)).padStart(2, '0') + ' / ' + ('' + (audio_stations[current_audio].updates.length + 1)).padStart(2, '0');
                    play_track_swipe_bar_arrows.innerHTML = '';
                    for(let i=0; i<=audio_stations[current_audio].updates.length; ++i){
                        play_track_swipe_bar_arrows.innerHTML += '<svg class="'+(update_id === i ? 'active ' : '')+'next-track" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 60 60" xml:space="preserve"><path d="M31.83,51H20.728l9.761-21L20.728,9H31.83l9.764,21L31.83,51z"/></svg>';
                    }
                    play_track_swipe_bar.style.display = '';
                } else {
                    play_track_swipe_bar.style.display = 'none';
                }
                let content_length = play_tab.querySelector('.content-length .length');
                content_length.innerHTML = audio_station.length;
                content_length.setAttribute('aria-label', audio_station.length_readable);
                player_new.innerHTML = '';
                if(audio_station.ogg) {
                    player_new.innerHTML += '<source src="' + audio_station.ogg + '" type="' + audio_station.ogg_mime + '">';
                }
                if(audio_station.aac) {
                    player_new.innerHTML += '<source src="' + audio_station.aac + '" type="' + audio_station.aac_mime + '">';
                }
                if(player.innerHTML !== player_new.innerHTML){
                    if(timeline_update_interval) {
                        clearInterval(timeline_update_interval);
                        timeline_update_interval = null;
                    }
                    document.documentElement.style.setProperty('--audio-progress', 0);
                }else if(!timeline_update_interval && !player.paused){
                    timeline_update_interval = setInterval(function(){
                        requestAnimationFrame(updateAudioPosition);
                    }, 100);
                }
                if(player_new.innerHTML !== player.innerHTML || player.paused){
                    play_tab_play.style.display = '';
                    play_tab_pause.style.display = 'none';
                } else {
                    play_tab_play.style.display = 'none';
                    play_tab_pause.style.display = '';
                }
            }
        }


        function load_walk(walk_id, load_only = false){
            if(walks[walk_id]){
                current_walk = walks[walk_id];
                if(current_walk.intros[0]){
                    load_walk_station(-1);
                }
                let details_wrapper = walk_intro.querySelector('.intro-station-details-wrapper');
                let content_player = walk_intro.querySelector('.content-player');
                details_wrapper.innerHTML = '';
                let first = true;
                for(let intro of current_walk.intros){
                    let audio_station = audio_stations[intro.audio_id];
                    if(first){
                        walk_intro.querySelector('.intro-station-description').innerHTML = audio_station.description;
                        let image_container = walk_intro.querySelector('.content-image');
                        if(audio_station.thumbnail) {
                            image_container.innerHTML = '<img src="' + audio_station.thumbnail + '" loading="lazy">';
                        } else {
                            image_container.innerHTML = '';
                        }
                        walk_intro.querySelector('.content-title').innerHTML = audio_station.title;
                        walk_intro.querySelector('.content-location').innerHTML = audio_station.location;
                        walk_intro.querySelector('.content-location-2').innerHTML = audio_station.location_2;
                        let content_length = walk_intro.querySelector('.content-length .length');
                        content_length.innerHTML = audio_station.length;
                        content_length.setAttribute('aria-label', audio_station.length_readable);
                        first = false;
                    } else {
                        let audio_station = audio_stations[intro.audio_id];
                        details_wrapper.innerHTML += '<details><summary><span>' + intro.title + '</span><span class="arrow"></span></summary><div class="content-player" data-audio-id="' + intro.audio_id + '">' + content_player.innerHTML + '</div>' + '<div class="content-audio-time"><p class="content-length"><span class="length" aria-label="' + audio_station.length_readable + '">' + audio_station.length + '</span> <span aria-hidden="true">min.</span></p></div>' + audio_station.description + '</details>';
                    }
                }
                walk_end.querySelector('.content-station-title').innerText = mannheim_under_construction.text_walk + ' ' + audio_stations[current_walk.intros[0].audio_id].title;
                let walk_end_description = walk_end.querySelector('.content-description');
                walk_end_description.innerHTML = current_walk.description;
                let close_walk = walk_end_description.querySelector('a[href="#"]');
                if(close_walk){
                    close_walk.addEventListener('click', _ => {
                        sidebar_left.close();
                    });
                }
                let search = walk_end_description.querySelector('a[href="#search"]');
                if(search){
                    search.addEventListener('click', _ => {
                        sidebar_left.open('#search');
                    });
                }
                let image_container = walk_end.querySelector('.content-image');
                if(current_walk.thumbnail) {
                    image_container.innerHTML = '<img src="' + current_walk.thumbnail + '" loading="lazy">';
                } else {
                    image_container.innerHTML = '';
                }
                let content_players = details_wrapper.querySelectorAll('.content-player');
                if(content_players) {
                    for (let content_player of content_players) {
                        content_player.querySelector('.play_pause_button').addEventListener('click', _ => {
                            play_pause_handler_with_id(parseInt(content_player.getAttribute('data-audio-id')));
                            content_player.querySelector('.play').style.display = player.paused ? '' : 'none';
                            content_player.querySelector('.pause').style.display = player.paused ? 'none' : '';
                        });
                        content_player.querySelector('.seek_backwards').addEventListener('click', seek_backwards_handler);
                        content_player.querySelector('.seek_forwards').addEventListener('click', seek_forwards_handler);
                    }
                }
                if(!load_only) {
                    sidebar_right.close();
                    body.classList.remove('sidebar-fullscreen');
                    sidebar_left.open('#walk');
                    map.setView([current_walk.lat, current_walk.lng]);
                }
            }
        }

        function load_walk_explainer(explainer_id){
            for(let explainer_wrapper of explainers) {
                let all_explainers = explainer_wrapper.querySelectorAll('.explainer');
                let explainer_prev = explainer_wrapper.querySelector('.prev-slide');
                let explainer_next = explainer_wrapper.querySelector('.next-slide');
                let new_explainer = explainer_wrapper.querySelector('.explainer[data-id="' + explainer_id + '"]');
                if (new_explainer) {
                    for (let explainer of all_explainers) {
                        explainer.hidden = true;
                    }
                    new_explainer.hidden = false;
                    current_walk_explainer = explainer_id;
                }
                if (all_explainers.length <= explainer_id + 1) {
                    explainer_next.style.visibility = 'hidden';
                } else {
                    explainer_next.style.visibility = '';
                }
                if (explainer_id <= 0) {
                    explainer_prev.style.visibility = 'hidden';
                } else {
                    explainer_prev.style.visibility = '';
                }
            }
        }

        function load_walk_station(station_id){
            if(current_walk.stations[station_id]){
                content_walk_stations.style.display = '';
                walk_intro.style.display = 'none';
                walk_select.style.display = 'none';
                walk_end.style.display = 'none';
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
                player_new.innerHTML = '';
                if(audio_station.ogg) {
                    player_new.innerHTML += '<source src="' + audio_station.ogg + '" type="' + audio_station.ogg_mime + '">';
                }
                if(audio_station.aac) {
                    player_new.innerHTML += '<source src="' + audio_station.aac + '" type="' + audio_station.aac_mime + '">';
                }
                content_walk_stations.querySelector('.track-swipe-bar span.prev-track').innerHTML = ('' + station_id).padStart(2, '0') + ' / ' + ('' + (current_walk.stations.length - 1)).padStart(2, '0');
                for(let walk_prev of walk_prevs){
                    walk_prev.style.display = '';
                }
                if(current_walk.stations.length <= station_id) {
                    for (let walk_next of walk_nexts) {
                        walk_next.style.display = 'none';
                    }
                } else if(current_walk.stations.length <= station_id + 1) {
                    content_walk_stations.querySelector('.track-swipe-bar span.next-track').innerHTML = mannheim_under_construction.text_end;
                    for (let walk_next of walk_nexts) {
                        walk_next.style.display = '';
                    }
                } else if(current_walk.stations.length <= station_id + 2) {
                    content_walk_stations.querySelector('.track-swipe-bar span.next-track').innerHTML = mannheim_under_construction.text_bonus;
                    for(let walk_next of walk_nexts){
                        walk_next.style.display = '';
                    }
                } else {
                    content_walk_stations.querySelector('.track-swipe-bar span.next-track').innerHTML = ('' + (station_id + 2 )).padStart(2, '0') + ' / ' + ('' + (current_walk.stations.length - 1)).padStart(2, '0');
                    for(let walk_next of walk_nexts){
                        walk_next.style.display = '';
                    }
                }
                if(player.innerHTML !== player_new.innerHTML){
                    if(timeline_update_interval) {
                        clearInterval(timeline_update_interval);
                        timeline_update_interval = null;
                    }
                    document.documentElement.style.setProperty('--audio-progress', 0);
                }else if(!timeline_update_interval && !player.paused){
                    timeline_update_interval = setInterval(function(){
                        requestAnimationFrame(updateAudioPosition);
                    }, 100);
                }
                if(player_new.innerHTML !== player.innerHTML || player.paused){
                    content_walk_play.style.display = '';
                    content_walk_pause.style.display = 'none';
                } else {
                    content_walk_play.style.display = 'none';
                    content_walk_pause.style.display = '';
                }
            } else if(station_id === -1){
                current_walk_station = station_id;
                content_walk_stations.style.display = 'none';
                walk_select.style.display = 'none';
                walk_end.style.display = 'none';
                walk_intro.style.display = '';
                load_walk_explainer(0);
                player_new.innerHTML = '';
                let station = current_walk.intros[0];
                let audio_station = audio_stations[station.audio_id];
                if(audio_station.ogg) {
                    player_new.innerHTML += '<source src="' + audio_station.ogg + '" type="' + audio_station.ogg_mime + '">';
                }
                if(audio_station.aac) {
                    player_new.innerHTML += '<source src="' + audio_station.aac + '" type="' + audio_station.aac_mime + '">';
                }
                if(player.innerHTML !== player_new.innerHTML){
                    if(timeline_update_interval) {
                        clearInterval(timeline_update_interval);
                        timeline_update_interval = null;
                    }
                    document.documentElement.style.setProperty('--audio-progress', 0);
                }else if(!timeline_update_interval && !player.paused){
                    timeline_update_interval = setInterval(function(){
                        requestAnimationFrame(updateAudioPosition);
                    }, 100);
                }
            } else if(station_id === -2 && mannheim_under_construction.walk_data.length > 1){
                current_walk_station = station_id;
                content_walk_stations.style.display = 'none';
                walk_intro.style.display = 'none';
                walk_end.style.display = 'none';
                walk_select.style.display = '';
            } else if(station_id === current_walk.stations.length){
                current_walk_station = station_id;
                content_walk_stations.style.display = 'none';
                walk_intro.style.display = 'none';
                walk_select.style.display = 'none';
                walk_end.style.display = '';
            }
        }

        function updateAudioPosition() {
            const {currentTime, duration} = player;
            const percent = currentTime / duration * 100;
            document.documentElement.style.setProperty('--audio-progress', percent);
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

        play_tab.querySelector('.content-tags').addEventListener('click', e => {
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
