<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="<?php esc_attr_e('Mannheim Under Construction, the audio map of civil societal engagement. This map introduces civil societal engagement in the city Mannheim.', 'mannheim-under-construction'); ?>">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<main>
    <audio id="audio_player" hidden preload="metadata">
    </audio>
    <audio id="audio_player_new" hidden preload="none">
    </audio>
    <div class="mannheim-under-construction-map-wrapper">
        <div class="mannheim-under-construction-map sidebar-map">
            <div class="mannheim-under-construction-onboarding active">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                     viewBox="0 0 60 60" xml:space="preserve" class="onboarding-arrow" id="onboarding-arrow-1">
                    <polygon points="24.538,18.547 45.318,15.286 38.7,8.668 17.959,12.008 12.435,17.533 9.002,38.366 15.619,44.984 18.881,24.204
                        42.02,47.344 47.678,41.688 	"/>
                </svg>
                <p class="onboarding-explainer" id="onboarding-explainer-1"><?php esc_html_e('Change the font size or switch to black/white mode', 'mannheim-under-construction'); ?></p>
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                     viewBox="0 0 60 60" xml:space="preserve" class="onboarding-arrow" id="onboarding-arrow-2">
                    <polygon points="24.538,18.547 45.318,15.286 38.7,8.668 17.959,12.008 12.435,17.533 9.002,38.366 15.619,44.984 18.881,24.204
                        42.02,47.344 47.678,41.688 	"/>
                </svg>
                <p class="onboarding-explainer" id="onboarding-explainer-2"><?php esc_html_e('Search in archive', 'mannheim-under-construction'); ?></p>
                <p class="onboarding-explainer" id="onboarding-explainer-3"><?php esc_html_e('Choose a marker...', 'mannheim-under-construction'); ?></p>
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                     viewBox="0 0 60 60" xml:space="preserve" id="onboarding-marker">
                    <g>
                        <path d="M16.168,30.059V18.68c0-3.137,0.83-5.535,2.49-7.193C20.32,9.828,22.721,9,25.861,9h8.755c3.141,0,5.54,0.829,7.206,2.488
                            c1.656,1.658,2.488,4.056,2.488,7.193L44.303,30L16.168,30.059z"/>
                        <polygon points="33.323,9 27.25,9 16.168,30.059 30.25,51 44.303,30"/>
                        <path fill="#F2FF5B" d="M23.172,30v-5.625c0-1.55,0.41-2.736,1.23-3.555c0.82-0.82,2.004-1.229,3.555-1.229h4.32
                            c1.549,0,2.734,0.41,3.557,1.23c0.816,0.819,1.227,2.005,1.227,3.556V30H23.172z"/>
                    </g>
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                     viewBox="0 0 60 60" xml:space="preserve" class="onboarding-arrow" id="onboarding-arrow-3">
                    <polygon points="18.88,35.461 15.619,14.681 9,21.299 12.341,42.041 17.865,47.565 38.699,50.997 45.317,44.38 24.537,41.119
                        47.678,17.979 42.021,12.321"/>
                </svg>
                <p class="onboarding-explainer" id="onboarding-explainer-4"><?php esc_html_e('... or a random post.', 'mannheim-under-construction'); ?></p>
                <button autofocus id="onboarding-start-button"><?php esc_html_e('Start', 'mannheim-under-construction'); ?></button>
                <div class="onboarding-welcome">
					<?php echo apply_filters( 'the_content', get_the_content(null, false, 209) ); ?>
                </div>
            </div>
            <div id="left_sidebar" class="leaflet-sidebar collapsed leaflet-sidebar-left">
                <div class="leaflet-sidebar-content">
                    <div class="leaflet-sidebar-pane" id="search" role="tabpanel">
                        <div class="search-filters">
                            <div class="content">
                                <button class="close-button">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                         viewBox="0 0 60 60" xml:space="preserve">
                                        <polygon points="8.871,43.823 22.729,29.963 8.871,16.103 15.918,9.055 29.777,22.916 43.639,9.055 50.687,16.103 36.826,29.963
                                            50.687,43.823 43.639,50.871 29.777,37.011 15.918,50.871"/>
                                    </svg>
                                </button>
                                <form>
                                    <div class="mannheim-under-construction-search">
                                        <input name="s" type="search" aria-label="<?php esc_attr_e('Enter a search phrase', 'mannheim-under-construction'); ?>">
                                        <button type="submit">
                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                                 viewBox="0 0 60 60" xml:space="preserve">
                                                <path d="M12.433,30.425l7.931,7.93c2.282,2.282,4.675,3.382,7.088,3.378c2.071,0,4.129-0.886,6.113-2.585l10.461,10.438
                                                    c0.78,0.779,1.803,1.168,2.825,1.168c1.025,0,2.05-0.392,2.831-1.175c1.561-1.563,1.558-4.096-0.006-5.657L39.202,33.471
                                                    c1.634-1.956,2.462-3.958,2.458-6.007c-0.003-2.415-1.151-4.764-3.433-7.047l-7.939-7.921c-3.816-3.816-7.742-4.458-11.751-1.937
                                                    l-1.145,1.15l-5.645,5.669l-1.244,1.249C7.998,22.647,8.617,26.608,12.433,30.425z M14.771,21.071l0.338-0.339l5.65-5.663
                                                    l0.313-0.314c1.385-1.388,2.473-1.501,3.452-0.522l11.639,11.638c0.979,0.98,0.839,2.102-0.546,3.489l-0.268,0.268l-5.649,5.664
                                                    l-0.266,0.266c-1.381,1.387-2.716,1.643-3.696,0.663L14.272,24.707C13.292,23.727,13.388,22.46,14.771,21.071z"/>
                                            </svg>
                                        </button>
                                    </div>
                                    <p class="search-intro-text">
		                                <?php esc_html_e('This is the full-text search.', 'mannheim-under-construction'); ?><br>
                                        <?php esc_html_e('Enter a word, which interests you and it can lead you to new posts.', 'mannheim-under-construction'); ?><br>
                                        <br>
		                                <?php esc_html_e('For example:', 'mannheim-under-construction'); ?><br>
		                                <?php esc_html_e('Vogelstang, School, Post-migration etc.', 'mannheim-under-construction'); ?>
                                    </p>
                                    <div class="extended-filters">
                                        <div class="filter-box">
                                            <input type="checkbox" id="type-filter" hidden>
                                            <label id="type-filter-box-label" for="type-filter"><?php esc_html_e('Post type', 'mannheim-under-construction'); ?><span class="arrow"></span></label>
                                            <select multiple name="type[]" aria-labelledby="type-filter-box-label">
                                                <?php
                                                $terms = get_terms([
                                                    'taxonomy' => 'post-type',
                                                    'hide_empty' => true,
                                                    'orderby' => 'name',
                                                ]);
                                                if(is_array($terms)){
                                                    foreach($terms as $term){
                                                        echo '<option value="' . $term->term_id . '">' . esc_html($term->name) . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="filter-box">
                                            <input type="checkbox" id="length-filter" hidden>
                                            <label id="length-filter-box-label" for="length-filter"><?php esc_html_e('Post length', 'mannheim-under-construction'); ?><span class="arrow"></span></label>
                                            <select multiple name="length[]" aria-labelledby="length-filter-box-label">
                                                <?php
                                                $terms = get_terms([
                                                    'taxonomy' => 'length',
                                                    'hide_empty' => true,
                                                    'orderby' => 'term_order',
                                                ]);
                                                if(is_array($terms)){
                                                    foreach($terms as $term){
                                                        echo '<option value="' . $term->term_id . '">' . esc_html($term->name) . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="filter-box">
                                            <input type="checkbox" id="location-filter" hidden>
                                            <label id="location-filter-box-label" for="location-filter"><?php esc_html_e('Location', 'mannheim-under-construction'); ?><span class="arrow"></span></label>
                                            <select multiple name="location[]" aria-labelledby="location-filter-box-label">
                                                <?php
                                                $terms = get_terms([
                                                    'taxonomy' => 'location',
                                                    'hide_empty' => true,
                                                    'orderby' => 'name',
                                                ]);
                                                if(is_array($terms)){
                                                    foreach($terms as $term){
                                                        echo '<option value="' . $term->term_id . '">' . esc_html($term->name) . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <div class="filter-box">
                                            <input type="checkbox" id="date-filter" hidden>
                                            <label id="date-filter-box-label" for="date-filter"><?php esc_html_e('Production date', 'mannheim-under-construction'); ?><span class="arrow"></span></label>
                                            <select multiple name="production-date[]" aria-labelledby="date-filter-box-label">
                                                <?php
                                                $terms = get_terms([
                                                    'taxonomy' => 'production-date',
                                                    'hide_empty' => true,
                                                    'orderby' => 'term_order',
                                                ]);
                                                if(is_array($terms)){
                                                    foreach($terms as $term){
                                                        echo '<option value="' . $term->term_id . '">' . esc_html($term->name) . '</option>';
                                                    }
                                                }
                                                ?>
                                            </select>
                                        </div>
                                        <button type="reset"><?php esc_html_e('Reset', 'mannheim-under-construction'); ?></button>
                                    </div>
                                </form>
                                <button id="search-extend">
                                    <span><?php esc_html_e('Click here for extended search', 'mannheim-under-construction'); ?></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                         viewBox="0 0 60 60" xml:space="preserve" class="extended-search-arrow">
                                        <polygon points="35.46,41.119 14.68,44.379 21.298,50.999 42.039,47.658 47.563,42.133 50.996,21.299 44.379,14.681 41.117,35.461
                                            17.979,12.321 12.32,17.978 "/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                        <div class="search_sidebar" id="search-fulltext">
                            <div class="content">
                                <button class="close-button">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                         viewBox="0 0 60 60" xml:space="preserve">
                                        <polygon points="8.871,43.823 22.729,29.963 8.871,16.103 15.918,9.055 29.777,22.916 43.639,9.055 50.687,16.103 36.826,29.963
                                            50.687,43.823 43.639,50.871 29.777,37.011 15.918,50.871"/>
                                    </svg>
                                </button>
                                <div class="message"><?php esc_html_e('You haven\'t started a search query yet. Your results will appear here.', 'mannheim-under-construction'); ?></div>
                                <ol class="audios"></ol>
                            </div>
                        </div>
                        <div class="search_sidebar" id="search-tags">
                            <div class="content">
                                <button class="close-button">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                         viewBox="0 0 60 60" xml:space="preserve">
                                        <polygon points="8.871,43.823 22.729,29.963 8.871,16.103 15.918,9.055 29.777,22.916 43.639,9.055 50.687,16.103 36.826,29.963
                                            50.687,43.823 43.639,50.871 29.777,37.011 15.918,50.871"/>
                                    </svg>
                                </button>
                                <?php
                                $tag_data = get_tags([
	                                'fields' => 'id=>name',
	                                'hide_empty' => true,
	                                'orderby' => 'name',
                                ]);
                                if(is_array($tag_data)){
                                    foreach ($tag_data as $term_id => $term_name) {
                                        echo '<div class="tag" data-tagid="' . esc_attr($term_id) . '">#' . esc_html($term_name) . '</div>';
                                        echo '<ol class="tag-result">';
                                        $audios = get_posts([
                                            'post_type' => 'audio-station',
                                            'posts_per_page' => -1,
                                            'tax_query' => [
                                                ['taxonomy' => 'post_tag', 'field' => 'term_id', 'terms' => $term_id],
                                            ],
                                        ]);
                                        foreach($audios as $audio){
                                            echo '<li data-id="' . $audio->ID . '">' . esc_html($audio->post_title) . '</li>';
                                        }
                                        echo '</ol>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                    <div class="leaflet-sidebar-pane" id="play" role="tabpanel">
                        <button class="close-button">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                 viewBox="0 0 60 60" xml:space="preserve">
                                <polygon points="8.871,43.823 22.729,29.963 8.871,16.103 15.918,9.055 29.777,22.916 43.639,9.055 50.687,16.103 36.826,29.963
                                    50.687,43.823 43.639,50.871 29.777,37.011 15.918,50.871"/>
                            </svg>
                        </button>
                        <div class="content-location"></div>
                        <div class="content-title"></div>
                        <div class="content-player">
                            <button class="play_pause_button" aria-label="<?php esc_attr_e('Play/Pause', 'mannheim-under-construction'); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                     viewBox="0 0 60 60" xml:space="preserve">
                                    <path d="M51,30.055L9,51.035V9.074L51,30.055z"/>
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                     viewBox="0 0 60 60" xml:space="preserve">
                                    <g>
                                        <polyline points="26,51 15,51 15,9 26,9"/>
                                        <polyline points="45,51 34,51 34,9 45,9"/>
                                    </g>
                                </svg>
                            </button>
                            <div class="waveform">
                                <svg preserveAspectRatio="none" width="500" height="50" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 500 100">
                                    <linearGradient id="Gradient" x1="0" x2="0" y1="0" y2="1">
                                        <stop offset="0%" stop-color="white"/>
                                        <stop offset="90%" stop-color="white" stop-opacity="0.75"/>
                                        <stop offset="100%" stop-color="white" stop-opacity="0"/>
                                    </linearGradient>
                                    <mask id="Mask"><path fill="url(#Gradient)" d=""/></mask>
                                    <rect id="remaining" mask="url(#Mask)" x="0" y="0" width="500" height="100"/>
                                    <rect id="progress" mask="url(#Mask)" x="0" y="0" width="0" height="100"/>
                                </svg>
                            </div>
                            <button class="seek-button seek_backwards">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                     viewBox="0 0 60 60" xml:space="preserve">
                                    <path d="M8.979,30l9.762-21h11.106l-9.763,21l9.763,21H18.741L8.979,30z M30.133,30l9.764-21H51l-9.762,21L51,51H39.896L30.133,30z"/>
                                </svg>
                            </button>
                            <button class="seek-button seek_forwards">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                     viewBox="0 0 60 60" xml:space="preserve">
                                    <path d="M41.239,51H30.133l9.763-21L30.133,9h11.106l9.762,21L41.239,51z M20.084,51H8.98l9.762-21L8.98,9h11.104l9.764,21
                                        L20.084,51z"/>
                                </svg>
                            </button>
                        </div>
                        <div class="content-description"></div>
                        <div class="content-audio-time">
                            <p class="content-length"><span class="length" aria-label=""></span> <span aria-hidden="true"><?php esc_html_e('min.', 'mannheim-under-construction'); ?></span></p>
                        </div>
                        <div class="content-credits"></div>
                        <div class="content-tags">
                        </div>
                    </div>
                    <div class="leaflet-sidebar-pane" id="walk" role="tabpanel">
                        <button class="close-button">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                 viewBox="0 0 60 60" xml:space="preserve">
                                <polygon points="8.871,43.823 22.729,29.963 8.871,16.103 15.918,9.055 29.777,22.916 43.639,9.055 50.687,16.103 36.826,29.963
                                    50.687,43.823 43.639,50.871 29.777,37.011 15.918,50.871"/>
                            </svg>
                        </button>
                        <div class="walk-onboarding">

                        </div>
                        <div class="walk-intro" style="display:none">
                            <div class="intro-station-title">
                                <span>Los</span><br><span>&emsp;&emsp;&emsp;geht's!</span>
                            </div>
                            <div class="intro-station-walk-icon"><div class="full-width-spacer"></div><i></i></div>
                            <div class="content-player">
                                <button class="play_pause_button" aria-label="<?php esc_attr_e('Play/Pause', 'mannheim-under-construction'); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                         viewBox="0 0 60 60" xml:space="preserve">
                                        <path d="M51,30.055L9,51.035V9.074L51,30.055z"/>
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                         viewBox="0 0 60 60" xml:space="preserve">
                                        <g>
                                            <polyline points="26,51 15,51 15,9 26,9"/>
                                            <polyline points="45,51 34,51 34,9 45,9"/>
                                        </g>
                                    </svg>
                                </button>
                                <div class="full-width-spacer"></div>
                                <button class="seek-button seek_backwards">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                         viewBox="0 0 60 60" xml:space="preserve">
                                        <path d="M8.979,30l9.762-21h11.106l-9.763,21l9.763,21H18.741L8.979,30z M30.133,30l9.764-21H51l-9.762,21L51,51H39.896L30.133,30z"/>
                                    </svg>
                                </button>
                                <button class="seek-button seek_forwards">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                         viewBox="0 0 60 60" xml:space="preserve">
                                        <path d="M41.239,51H30.133l9.763-21L30.133,9h11.106l9.762,21L41.239,51z M20.084,51H8.98l9.762-21L8.98,9h11.104l9.764,21
                                            L20.084,51z"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="intro-station-description"></div>
                            <div class="intro-station-to-walk"><div class="full-width-spacer"></div>
                                <span class="next-track"></span>
                                <svg class="next-track" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 60 60" xml:space="preserve">
                                    <path d="M31.83,51H20.728l9.761-21L20.728,9H31.83l9.764,21L31.83,51z"/>
                                </svg>
                            </div>
                            <div class="intro-station-details-wrapper"></div>
                        </div>
                        <div class="content-walk-stations">
                            <div>&emsp;&emsp;&emsp;<span class="content-station-title"></span></div>
                            <div class="content-title"></div>
                            <div>&emsp;&emsp;&emsp;<span class="content-location"></span></div>
                            <div class="content-location-2"></div>
                            <div class="content-audio-time">
                                <p class="content-length">&emsp;&emsp;&emsp;<span class="length" aria-label=""></span> <span aria-hidden="true"><?php esc_html_e('min.', 'mannheim-under-construction'); ?></span></p>
                            </div>
                            <div class="content-image"></div>
                            <div class="content-player">
                                <button class="play_pause_button" aria-label="<?php esc_attr_e('Play/Pause', 'mannheim-under-construction'); ?>">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                         viewBox="0 0 60 60" xml:space="preserve">
                                        <path d="M51,30.055L9,51.035V9.074L51,30.055z"/>
                                    </svg>
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                         viewBox="0 0 60 60" xml:space="preserve">
                                        <g>
                                            <polyline points="26,51 15,51 15,9 26,9"/>
                                            <polyline points="45,51 34,51 34,9 45,9"/>
                                        </g>
                                    </svg>
                                </button>
                                <div class="full-width-spacer"></div>
                                <button class="seek-button seek_backwards">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                         viewBox="0 0 60 60" xml:space="preserve">
                                        <path d="M8.979,30l9.762-21h11.106l-9.763,21l9.763,21H18.741L8.979,30z M30.133,30l9.764-21H51l-9.762,21L51,51H39.896L30.133,30z"/>
                                    </svg>
                                </button>
                                <button class="seek-button seek_forwards">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                         viewBox="0 0 60 60" xml:space="preserve">
                                        <path d="M41.239,51H30.133l9.763-21L30.133,9h11.106l9.762,21L41.239,51z M20.084,51H8.98l9.762-21L8.98,9h11.104l9.764,21
                                            L20.084,51z"/>
                                    </svg>
                                </button>
                            </div>
                            <div class="content-description"></div>
                            <div class="full-height-spacer"></div>
                            <div class="track-swipe-bar">
                                <svg class="prev-track" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 60 60" xml:space="preserve">
                                    <path d="M20.566,30L30.33,9h11.104l-9.762,21l9.762,21H30.33L20.566,30z"/>
                                </svg>
                                <span class="prev-track">01 / 03</span>
                                <div class="full-width-spacer"></div>
                                <span class="next-track">02 / 03</span>
                                <svg class="next-track" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 60 60" xml:space="preserve">
                                    <path d="M31.83,51H20.728l9.761-21L20.728,9H31.83l9.764,21L31.83,51z"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="leaflet-sidebar-tabs" role="tablist">
                    <button id="black_white_switcher" aria-label="<?php esc_attr_e('Change contrast', 'mannheim-under-construction'); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="0 0 60 60" xml:space="preserve">
                            <g>
                                <polygon points="27,51 30,51 30,9 27,9 16,30.059"/>
                                <polygon points="33.073,9 30,9 30,51 33.015,51 44,30"/>
                            </g>
                        </svg>
                    </button>
                    <button id="font_size_switcher" role="button" aria-label="<?php esc_attr_e('Change font size', 'mannheim-under-construction'); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="0 0 60 60" xml:space="preserve">
                            <g>
                                <path d="M8.837,51.209l12.6-25.2h6.517l12.6,25.2h-7.884l-2.376-4.752H17.189l-2.376,4.752H8.837z M19.781,41.273h7.92l-3.96-7.92
                                    L19.781,41.273z"/>
                                <path d="M29.878,25.855l8.405-16.81h4.346l8.406,16.81h-5.258l-1.586-3.17h-8.742l-1.584,3.17H29.878z M37.179,19.227h5.282
                                    l-2.641-5.283L37.179,19.227z"/>
                            </g>
                        </svg>
                    </button>
                    <button href="#search" role="tab" aria-label="<?php esc_attr_e('Search', 'mannheim-under-construction'); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="0 0 60 60" xml:space="preserve">
                            <path d="M12.433,30.425l7.931,7.93c2.282,2.282,4.675,3.382,7.088,3.378c2.071,0,4.129-0.886,6.113-2.585l10.461,10.438
                                c0.78,0.779,1.803,1.168,2.825,1.168c1.025,0,2.05-0.392,2.831-1.175c1.561-1.563,1.558-4.096-0.006-5.657L39.202,33.471
                                c1.634-1.956,2.462-3.958,2.458-6.007c-0.003-2.415-1.151-4.764-3.433-7.047l-7.939-7.921c-3.816-3.816-7.742-4.458-11.751-1.937
                                l-1.145,1.15l-5.645,5.669l-1.244,1.249C7.998,22.647,8.617,26.608,12.433,30.425z M14.771,21.071l0.338-0.339l5.65-5.663
                                l0.313-0.314c1.385-1.388,2.473-1.501,3.452-0.522l11.639,11.638c0.979,0.98,0.839,2.102-0.546,3.489l-0.268,0.268l-5.649,5.664
                                l-0.266,0.266c-1.381,1.387-2.716,1.643-3.696,0.663L14.272,24.707C13.292,23.727,13.388,22.46,14.771,21.071z"/>
                        </svg>
                    </button>
                    <button id="play_tab_button" href="#play" role="tab" aria-label="<?php esc_attr_e('Open player', 'mannheim-under-construction'); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="0 0 60 60" xml:space="preserve">
                            <path d="M51,30.055L9,51.035V9.074L51,30.055z"/>
                        </svg>
                    </button>
                    <button id="back_button" class="back-button" aria-label="<?php esc_attr_e('Back', 'mannheim-under-construction'); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="0 0 60 60" xml:space="preserve">
                            <g>
                                <polygon points="18.275,17.667 24.866,8.667 15.408,8.666 9,17.36 9,25.173 15.662,34.667 24.866,34.667 18.273,25.667
                                    50.998,25.667 51,17.667"/>
                                <rect x="43" y="24.167" width="8" height="26.493"/>
                                <rect x="31" y="42.667" width="20" height="8"/>
                            </g>
                        </svg>
                    </button>
                </div>
            </div>
            <div id="right_sidebar" class="leaflet-sidebar collapsed leaflet-sidebar-right">
                <div class="leaflet-sidebar-content">
                    <div class="leaflet-sidebar-pane" id="info" role="tabpanel">
                        <div class="left-part">
                            <div class="content">
                                <div class="info-menu">
                                    <button id="info-menu-button" class="under-construction-more-info" role="tab" aria-label="<?php esc_attr_e('Show more info about the project', 'mannheim-under-construction'); ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                             viewBox="0 0 60 60" xml:space="preserve">
                                            <g>
                                                <rect x="9" y="18.089" width="42" height="5.324"/>
                                                <rect x="9" y="29.089" width="42" height="5.324"/>
                                                <rect x="9" y="40.089" width="42" height="5.324"/>
                                            </g>
                                        </svg>
                                    </button>
                                    <div id="info-menu-links">
                                        <button href="#imprint" id="imprint_menu" role="tab">
											<?php esc_html_e('Imprint', 'mannheim-under-construction'); ?>
                                        </button>
                                        <button href="#privacy" id="privacy_menu" role="tab">
											<?php esc_html_e('Privacy', 'mannheim-under-construction'); ?>
                                        </button>
                                        <button id="help_menu" role="tab">
											<?php esc_html_e('Help', 'mannheim-under-construction'); ?>
                                        </button>
                                    </div>
                                </div>
                                <div class="under-construction-title">
                                    <div class="title-line-1">
                                        <span>Ma</span><span>nn</span><span>he</span><span>im</span>
                                    </div>
                                    <div class="title-line-2">
                                        <span>U</span><span>n</span><span>de</span><span>r</span>
                                    </div>
                                    <div class="title-line-3">
                                        <span>C</span><span>onst</span><span>ruc</span><span>tion</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="open-middle-part">
                            <div class="content">
								<?php echo apply_filters( 'the_content', get_the_content(null, false, 216) ); ?>
                            </div>
                        </div>
                        <div class="open-right-part">
                            <div class="content">
                                <button class="close-button">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                         viewBox="0 0 60 60" xml:space="preserve">
                                        <polygon points="8.871,43.823 22.729,29.963 8.871,16.103 15.918,9.055 29.777,22.916 43.639,9.055 50.687,16.103 36.826,29.963
                                            50.687,43.823 43.639,50.871 29.777,37.011 15.918,50.871"/>
                                    </svg>
                                </button>
								<?php echo apply_filters( 'the_content', get_the_content(null, false, 218) ); ?>
                            </div>
                        </div>
                        <div class="right-part">
                            <div class="content">
                                <button class="close-button">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                         viewBox="0 0 60 60" xml:space="preserve">
                                        <polygon points="8.871,43.823 22.729,29.963 8.871,16.103 15.918,9.055 29.777,22.916 43.639,9.055 50.687,16.103 36.826,29.963
                                            50.687,43.823 43.639,50.871 29.777,37.011 15.918,50.871"/>
                                    </svg>
                                </button>
								<?php echo apply_filters( 'the_content', get_the_content(null, false, 212) ); ?>
                                <button id="deepdive-more-info" class="under-construction-more-info">
                                    <span><?php esc_html_e('more information', 'mannheim-under-construction'); ?></span>
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                         viewBox="0 0 60 60" xml:space="preserve">
                                        <polygon points="35.46,41.119 14.68,44.379 21.298,50.999 42.039,47.658 47.563,42.133 50.996,21.299 44.379,14.681 41.117,35.461
                                            17.979,12.321 12.32,17.978 "/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="leaflet-sidebar-pane" id="imprint" role="tabpanel">
                        <button class="close-button">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                 viewBox="0 0 60 60" xml:space="preserve">
                                <polygon points="8.871,43.823 22.729,29.963 8.871,16.103 15.918,9.055 29.777,22.916 43.639,9.055 50.687,16.103 36.826,29.963
                                    50.687,43.823 43.639,50.871 29.777,37.011 15.918,50.871"/>
                            </svg>
                        </button>
						<?php echo apply_filters( 'the_content', get_the_content(null, false, 169) ); ?>
                    </div>
                    <div class="leaflet-sidebar-pane" id="privacy" role="tabpanel">
                        <button class="close-button">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                 viewBox="0 0 60 60" xml:space="preserve">
                                <polygon points="8.871,43.823 22.729,29.963 8.871,16.103 15.918,9.055 29.777,22.916 43.639,9.055 50.687,16.103 36.826,29.963
                                    50.687,43.823 43.639,50.871 29.777,37.011 15.918,50.871"/>
                            </svg>
                        </button>
						<?php echo apply_filters( 'the_content', get_the_content(null, false, 7) ); ?>
                    </div>
                </div>
                <div class="leaflet-sidebar-tabs" role="tablist">
                    <button class="open-under-construction-info" role="tab" aria-label="<?php esc_attr_e('Show info about the project', 'mannheim-under-construction'); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="0 0 60 60" xml:space="preserve">
                            <g>
                                <rect x="9" y="18.089" width="42" height="5.324"/>
                                <rect x="9" y="29.089" width="42" height="5.324"/>
                                <rect x="9" y="40.089" width="42" height="5.324"/>
                            </g>
                        </svg>
                    </button>
                    <button class="open-under-construction-info" role="tab" id="right_sidebar_title" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="0 0 51.5 450.33" xml:space="preserve">
                            <g>
                                <g>
                                    <path d="M17.33,15.042v12.679h-4.154V9.077h6.604l6.18,12.358l6.178-12.358l6.822,0v18.645l-5.437-5.458v-7.221l-6.02,12.679
                                        h-3.835L17.33,15.042z"/>
                                    <path d="M22.711,36.65l9.324-18.644h5.414l9.322,18.645h-5.833l-1.758-3.516h-9.697l-1.757,3.516L22.711,36.65z M31.4,29.299
                                        h5.863l-2.933-5.86L31.4,29.299z"/>
                                    <path d="M5.216,54.743V35.865h7.497l9.088,13.862V35.865h4.207v18.878H18.51L9.523,40.882v13.861H5.216z"/>
                                    <path d="M25.84,63.707V44.795h7.51l9.105,13.887V44.795h4.216v18.912H39.16L30.193,49.82v13.887H25.84z"/>
                                    <path d="M21.699,53.724v7.298h8.494v-7.298h5.703v18.645h-5.703v-7.512h-8.494v7.512h-5.671V53.724H21.699z"/>
                                    <path d="M25.975,90.228V71.583h20.837v3.836H32.105v3.462h12.762v3.835l-12.762,0v3.676h14.706v3.835H25.975z"/>
                                    <path d="M28.542,80.48v18.646h-5.434V80.48H28.542z"/>
                                    <path d="M9.312,103.577v12.677H5.156V97.609h6.605l6.182,12.358l6.18-12.358h6.816v18.645h-5.434v-12.677l-6.017,12.677h-3.837
                                        L9.312,103.577z"/>
                                </g>
                                <g>
                                    <path d="M36.313,143.045v15.184c0,1.273-0.34,2.248-1.012,2.92c-0.674,0.674-1.645,1.012-2.922,1.012H19.162
                                        c-1.275,0-2.248-0.338-2.922-1.012c-0.674-0.672-1.012-1.646-1.012-2.92v-15.184h5.876v14.363c0,0.545,0.276,0.82,0.82,0.82
                                        h7.648c0.544,0,0.819-0.275,0.819-0.82v-14.363H36.313z"/>
                                    <path d="M5.216,179.74v-18.643h16.088c1.243,0,2.192,0.33,2.851,0.986c0.655,0.656,0.984,1.607,0.984,2.85v10.973
                                        c0,1.244-0.329,2.191-0.984,2.852c-0.658,0.656-1.607,0.982-2.851,0.982H5.216z M10.65,175.906h8.256
                                        c0.531,0,0.797-0.268,0.797-0.799v-9.375c0-0.533-0.266-0.799-0.797-0.799H10.65V175.906z"/>
                                    <path d="M15.713,197.453v-18.645h20.836v3.834H21.842v3.465h12.762v3.836H21.842v3.674h14.707v3.836H15.713z"/>
                                    <path d="M26.965,214.344v-18.645h16.09c1.242,0,2.191,0.33,2.851,0.988c0.655,0.658,0.984,1.605,0.984,2.85v1.732
                                        c0,1.242-0.329,2.182-0.984,2.822c-0.659,0.639-1.608,0.959-2.851,0.959v0.266c1.242,0,2.191,0.324,2.851,0.971
                                        c0.655,0.648,0.984,1.598,0.984,2.838v5.219h-5.437v-6.707c0-0.537-0.266-0.803-0.797-0.803h-8.258v7.51H26.965z
                                         M32.398,202.998h8.258c0.531,0,0.797-0.266,0.797-0.797v-1.865c0-0.535-0.266-0.799-0.797-0.799h-8.258V202.998z"/>
                                    <path d="M25.904,170.969v-18.877H33.4l9.09,13.861v-13.861h4.208v18.877h-7.496l-8.987-13.861v13.861H25.904z"/>
                                    <rect x="30" y="156" width="1" height="3"/>
                                </g>
                                <g>
                                    <path d="M15.924,256.125v-10.977c0-1.24,0.325-2.189,0.982-2.848c0.656-0.658,1.607-0.986,2.852-0.986h12.251
                                        c1.243,0,2.192,0.328,2.849,0.986c0.659,0.656,0.986,1.607,0.986,2.848v1.918H30.41v-1.117c0-0.531-0.265-0.801-0.797-0.801
                                        h-7.461c-0.531,0-0.797,0.27-0.797,0.801v9.377c0,0.531,0.266,0.799,0.797,0.799h7.461c0.532,0,0.797-0.268,0.797-0.801v-1.119
                                        h5.434v1.92c0,1.242-0.327,2.193-0.986,2.85c-0.656,0.658-1.605,0.984-2.849,0.984H19.758c-1.244,0-2.195-0.328-2.852-0.984
                                        C16.249,258.318,15.924,257.367,15.924,256.125z"/>
                                    <path d="M26.709,264.973v-11.045c0-1.254,0.328-2.209,0.992-2.873c0.662-0.66,1.615-0.99,2.867-0.99h12.335
                                        c1.253,0,2.204,0.33,2.868,0.99c0.662,0.664,0.994,1.619,0.994,2.873v11.045c0,1.25-0.332,2.209-0.994,2.869
                                        c-0.664,0.662-1.615,0.992-2.868,0.992H30.568c-1.252,0-2.205-0.33-2.867-0.992C27.037,267.182,26.709,266.223,26.709,264.973z
                                         M32.178,264.168c0,0.537,0.268,0.805,0.805,0.805h7.51c0.533,0,0.803-0.268,0.803-0.805v-9.438c0-0.537-0.27-0.531-0.803-0.531
                                        h-7.51c-0.537,0-0.805-0.006-0.805,0.531V264.168z"/>
                                    <path d="M5.201,300.732v-1.918h5.433v1.117c0,0.533,0.269,0.801,0.8,0.801h7.459c0.531,0,0.8-0.268,0.8-0.801v-2.078
                                        c0-0.531-0.269-0.797-0.8-0.797H9.037c-1.243,0-2.196-0.33-2.852-0.988c-0.658-0.654-0.984-1.605-0.984-2.85v-3.461
                                        c0-1.242,0.326-2.191,0.984-2.848c0.655-0.66,1.608-0.988,2.852-0.988h12.252c1.241,0,2.192,0.328,2.85,0.988
                                        c0.656,0.656,0.986,1.605,0.986,2.848v1.916h-5.433v-1.117c0-0.531-0.269-0.799-0.8-0.799h-7.459c-0.531,0-0.8,0.268-0.8,0.799
                                        v1.867c0,0.529,0.269,0.795,0.8,0.795h9.855c1.241,0,2.192,0.332,2.85,0.988c0.656,0.658,0.986,1.605,0.986,2.85v3.676
                                        c0,1.242-0.33,2.191-0.986,2.85c-0.657,0.656-1.608,0.984-2.85,0.984H9.037c-1.243,0-2.196-0.328-2.852-0.984
                                        C5.527,302.924,5.201,301.975,5.201,300.732z"/>
                                    <path d="M26.941,298.627v-3.834h19.871v3.834h-7.217v14.809H34.16v-14.809H26.941z"/>
                                    <path d="M15.964,331.457v-18.645h16.089c1.243,0,2.196,0.33,2.849,0.986c0.659,0.658,0.987,1.607,0.987,2.85v1.732
                                        c0,1.242-0.328,2.184-0.987,2.822c-0.652,0.639-1.605,0.959-2.849,0.959v0.266c1.243,0,2.196,0.324,2.849,0.973
                                        c0.659,0.648,0.987,1.594,0.987,2.838v5.219h-5.432v-6.711c0-0.533-0.27-0.799-0.801-0.799h-8.257v7.51H15.964z M21.399,320.111
                                        h8.257c0.531,0,0.801-0.266,0.801-0.799v-1.865c0-0.533-0.27-0.799-0.801-0.799h-8.257V320.111z"/>
                                    <path d="M26.227,330.563v15.186c0,1.271-0.338,2.246-1.012,2.918c-0.673,0.676-1.645,1.012-2.922,1.012H9.074
                                        c-1.273,0-2.247-0.336-2.92-1.012c-0.672-0.672-1.01-1.646-1.01-2.918v-15.186h5.875v14.363c0,0.545,0.275,0.822,0.82,0.822
                                        h7.646c0.546,0,0.819-0.277,0.819-0.822v-14.363H26.227z"/>
                                    <path d="M15.758,287.031v-18.879h7.496l9.089,13.861v-13.861h4.206v18.879h-7.498l-8.983-13.861v13.861H15.758z"/>
                                    <path d="M5.047,429.777v-18.879h7.5l9.088,13.861v-13.861h4.207v18.879h-7.497l-8.987-13.861v13.861H5.047z"/>
                                    <path d="M16.023,363.314v-10.977c0-1.24,0.327-2.191,0.983-2.848c0.657-0.656,1.608-0.988,2.854-0.988h12.251
                                        c1.24,0,2.189,0.332,2.849,0.988c0.656,0.656,0.985,1.607,0.985,2.848v1.92h-5.434v-1.119c0-0.533-0.267-0.801-0.799-0.801
                                        h-7.458c-0.531,0-0.8,0.268-0.8,0.801v9.377c0,0.531,0.269,0.799,0.8,0.797h7.458c0.532,0,0.799-0.266,0.799-0.797v-1.119h5.434
                                        v1.916c0,1.244-0.329,2.193-0.985,2.852c-0.659,0.656-1.608,0.986-2.849,0.986H19.86c-1.245,0-2.196-0.328-2.854-0.986
                                        C16.351,365.506,16.023,364.557,16.023,363.314z"/>
                                    <path d="M26.643,369.496v-3.834h19.87v3.834h-7.219v14.811H33.86v-14.811H26.643z"/>
                                    <path d="M28.645,375.047v18.646H23.21v-18.646H28.645z"/>
                                    <path d="M26.429,408.004v-10.975c0-1.242,0.327-2.191,0.985-2.85c0.658-0.656,1.607-0.986,2.85-0.986h12.253
                                        c1.244,0,2.191,0.33,2.85,0.986c0.657,0.658,0.985,1.607,0.985,2.85v10.975c0,1.242-0.328,2.191-0.985,2.85
                                        c-0.658,0.656-1.605,0.984-2.85,0.984H30.264c-1.242,0-2.191-0.328-2.85-0.984C26.756,410.195,26.429,409.246,26.429,408.004z
                                         M31.863,407.203c0,0.533,0.266,0.801,0.797,0.801h7.459c0.533,0,0.799-0.268,0.799-0.801v-9.373
                                        c0-0.535-0.266-0.801-0.799-0.801H32.66c-0.531,0-0.797,0.266-0.797,0.801V407.203z"/>
                                </g>
                            </g>
                        </svg>
                    </button>
                    <button href="#imprint" id="imprint_button" role="tab" class="legal-link">
						<?php esc_html_e('Imprint', 'mannheim-under-construction'); ?>
                    </button>
                    <button href="#privacy" id="privacy_button" role="tab" class="legal-link">
						<?php esc_html_e('Privacy', 'mannheim-under-construction'); ?>
                    </button>
                </div>
            </div>
	        <?php
	        if(get_option('mannheim_under_construction_popup_show', false)){
		        ?><div class="mannheim-under-construction-popup">
                <div class="mannheim-under-construction-popup-box">
                    <button class="close-button">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="0 0 60 60" xml:space="preserve">
                            <polygon points="8.871,43.823 22.729,29.963 8.871,16.103 15.918,9.055 29.777,22.916 43.639,9.055 50.687,16.103 36.826,29.963
                                50.687,43.823 43.639,50.871 29.777,37.011 15.918,50.871"/>
                        </svg>
                    </button>
                    <h2 class="font-neue-black"><?php echo esc_html(get_option('mannheim_under_construction_popup_headline', '')); ?></h2>
                    <?php echo apply_filters('the_content', get_option('mannheim_under_construction_popup_text', '')); ?>
                </div>
            </div>
	        <?php } ?>
        </div>
        <noscript id="mannheim-under-construction-no-js-error"><p><?php esc_html_e('Sorry, you need to enable JavaScript to use this map.', 'mannheim-under-construction'); ?></p></noscript>
    </div>
</main>
<?php wp_footer(); ?>
</body>
</html>
