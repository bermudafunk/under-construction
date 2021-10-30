<?php
$map_data = [];
$posts = get_posts([
	'post_type' => 'audio-station',
	'posts_per_page' => -1,
	'fields' => 'ids',
]);
$tag_data = get_tags([
	'fields' => 'id=>name',
	'hide_empty' => true,
	'orderby' => 'name',
]);
foreach ($posts as $post_id){
	$ogg_id = get_post_meta($post_id, 'mannheim_under_construction_ogg', true);
	$aac_id = get_post_meta($post_id, 'mannheim_under_construction_aac', true);
	$ogg_meta = wp_get_attachment_metadata($ogg_id);
	$aac_meta = wp_get_attachment_metadata($aac_id);
	$length = '';
	$length_readable = '';
	$tags = get_tags([
		'object_ids' => $post_id,
		'fields' => 'ids',
	]);
	if(!is_array($tags)){
		$tags = [];
	}
	if(isset($ogg_meta['length_formatted'])) {
		$length = $ogg_meta['length_formatted'];
		$length_readable = human_readable_duration($length);
	} elseif(isset($aac_meta['length_formatted'])) {
		$length = $aac_meta['length_formatted'];
		$length_readable = human_readable_duration($length);
	}
	$map_data []= [
		'id' => $post_id,
		'lat' => get_post_meta($post_id, 'mannheim_under_construction_location_lat', true),
		'lng' => get_post_meta($post_id, 'mannheim_under_construction_location_lng', true),
		'location' => esc_html(get_post_meta($post_id, 'mannheim_under_construction_location', true)),
		'title' => esc_html(get_the_title($post_id)),
		'description' => apply_filters('the_content', get_the_content(null, false, $post_id)),
		'ogg' => wp_get_attachment_url($ogg_id),
		'ogg_mime' => get_post_mime_type($ogg_id),
		'aac' => wp_get_attachment_url($aac_id),
		'aac_mime' => get_post_mime_type($aac_id),
		'waveform' => get_post_meta($post_id, 'mannheim_under_construction_waveform', true),
		'length' => $length,
		'length_readable' => $length_readable,
		'tags' => $tags,
	];
}
$player_open = false;
$random_audio = $map_data[array_rand($map_data)];
if(!empty($_GET['audio_id'])){
	foreach ($map_data as $audio){
		if($audio['id'] === (int) $_GET['audio_id']){
			$random_audio = $audio;
			$player_open = true;
			break;
		}
	}
}
if($player_open){
    add_filter('body_class', function (array $classes){
        $classes []= 'sidebar-open';
        return $classes;
    });
}
?><!doctype html>
<html <?php language_attributes(); ?> <?php twentytwentyone_the_html_classes(); ?>>
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
		<?php
		if(!empty($random_audio['ogg'])){
			echo '<source type="audio/ogg" src="' . esc_attr($random_audio['ogg']) . '">';
		}
		if(!empty($random_audio['aac'])){
			echo '<source type="audio/aac" src="' . esc_attr($random_audio['aac']) . '">';
		}
		?>
    </audio>
    <audio id="audio_player_new" hidden preload="none">
		<?php
		if(!empty($random_audio['ogg'])){
			echo '<source type="audio/ogg" src="' . esc_attr($random_audio['ogg']) . '">';
		}
		if(!empty($random_audio['aac'])){
			echo '<source type="audio/aac" src="' . esc_attr($random_audio['aac']) . '">';
		}
		?>
    </audio>
    <div class="mannheim-under-construction-map-wrapper">
        <div class="mannheim-under-construction-map sidebar-map">
            <div class="mannheim-under-construction-onboarding active">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                     viewBox="0 0 57.167 81.5" xml:space="preserve" class="onboarding-arrow" id="onboarding-arrow-1">
                    <path d="M16.848,23.62h24.189l4.551,4.552H24.633l20.951,20.96l-3.207,3.229L21.398,31.406v20.955l-4.551-4.574V23.62z"/>
                </svg>
                <p class="onboarding-explainer" id="onboarding-explainer-1"><?php esc_html_e('Change the font size or switch to black/white mode', 'mannheim-under-construction'); ?></p>
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                     viewBox="0 0 57.167 81.5" xml:space="preserve" class="onboarding-arrow" id="onboarding-arrow-2">
                    <path d="M16.848,23.62h24.189l4.551,4.552H24.633l20.951,20.96l-3.207,3.229L21.398,31.406v20.955l-4.551-4.574V23.62z"/>
                </svg>
                <p class="onboarding-explainer" id="onboarding-explainer-2"><?php esc_html_e('Search in archive', 'mannheim-under-construction'); ?></p>
                <p class="onboarding-explainer" id="onboarding-explainer-3"><?php esc_html_e('Choose a marker...', 'mannheim-under-construction'); ?></p>
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                     viewBox="0 0 57.167 81.5" xml:space="preserve" id="onboarding-marker">
                    <g>
                        <path d="M45.104,31.343c0,12.841-16.722,34.775-17.248,34.249c-6.74-6.732-17.25-21.408-17.25-34.249
                            c0-12.84,7.723-23.249,17.25-23.249C37.381,8.094,45.104,18.503,45.104,31.343z"/>
                        <g>
                            <ellipse fill="#F2FF5B" cx="27.921" cy="26.635" rx="10.579" ry="11.115"/>
                        </g>
                    </g>
                </svg>
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                     viewBox="0 0 57.167 81.5" xml:space="preserve" class="onboarding-arrow" id="onboarding-arrow-3">
                    <path d="M16.848,23.62h24.189l4.551,4.552H24.633l20.951,20.96l-3.207,3.229L21.398,31.406v20.955l-4.551-4.574V23.62z"/>
                </svg>
                <p class="onboarding-explainer" id="onboarding-explainer-4"><?php esc_html_e('... or a random post.', 'mannheim-under-construction'); ?></p>
                <button autofocus id="onboarding-start-button"><?php esc_html_e('Start', 'mannheim-under-construction'); ?></button>
                <div class="onboarding-welcome">
					<?php echo apply_filters( 'the_content', get_the_content(null, false, 209) ); ?>
                </div>
            </div>
            <div id="left_sidebar" class="leaflet-sidebar <?php echo $player_open ? '' : 'collapsed'; ?> leaflet-sidebar-left">
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
                                         viewBox="0 0 57.167 81.5" xml:space="preserve">
                                        <path d="M44.998,48.43l-4.552,4.574V32.049L19.468,53.004l-3.206-3.229l20.95-20.96H16.257l4.551-4.552h24.19V48.43z"/>
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
                    <div class="leaflet-sidebar-pane<?php echo $player_open ? ' active' : ''; ?>" id="play" role="tabpanel">
                        <button class="close-button">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                 viewBox="0 0 60 60" xml:space="preserve">
                                <polygon points="8.871,43.823 22.729,29.963 8.871,16.103 15.918,9.055 29.777,22.916 43.639,9.055 50.687,16.103 36.826,29.963
                                    50.687,43.823 43.639,50.871 29.777,37.011 15.918,50.871"/>
                            </svg>
                        </button>
                        <div class="content-location"><?php echo esc_html($random_audio['location']); ?></div>
                        <div class="content-title"><?php echo esc_html($random_audio['title']); ?></div>
                        <div class="content-player">
                            <button class="play_pause_button" aria-label="<?php esc_attr_e('Play/Pause', 'mannheim-under-construction'); ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                     viewBox="0 0 114.334 81.5" xml:space="preserve">
                                    <path d="M17.185,55.664V25.225l26.361,15.229L17.185,55.664z"/>
                                    <g>
                                        <rect x="73.667" y="27" width="9" height="31"/>
                                        <rect x="88.667" y="27" width="9" height="31"/>
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
                                    <mask id="Mask"><path fill="url(#Gradient)" d="<?php echo esc_html($random_audio['waveform']); ?>"/></mask>
                                    <rect id="remaining" mask="url(#Mask)" x="0" y="0" width="500" height="100"/>
                                    <rect id="progress" mask="url(#Mask)" x="0" y="0" width="0" height="100"/>
                                </svg>
                            </div>
                            <button id="seek_backwards" class="seek-button">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                     viewBox="0 0 57.167 53.667" xml:space="preserve">
                                    <g>
                                        <path d="M29.316,25.454l13.182-15.229v30.439L29.316,25.454z"/>
                                        <path d="M17.191,25.454l13.182-15.229v30.439L17.191,25.454z"/>
                                    </g>
                                </svg>
                            </button>
                            <button id="seek_forwards" class="seek-button">
                                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                     viewBox="0 0 57.167 53.667" xml:space="preserve">
                                    <g>
                                        <path d="M17.191,40.665V10.226l13.182,15.229L17.191,40.665z"/>
                                        <path d="M29.316,40.665V10.226l13.182,15.229L29.316,40.665z"/>
                                    </g>
                                </svg>
                            </button>
                        </div>
                        <div class="content-description"><?php echo $random_audio['description']; ?></div>
                        <div class="content-audio-time">
                            <p class="content-length"><span class="length" aria-label="<?php echo esc_attr($random_audio['length_readable']); ?>"><?php echo esc_html($random_audio['length']); ?></span> <span aria-hidden="true"><?php esc_html_e('min.', 'mannheim-under-construction'); ?></span></p>
                        </div>
                        <div class="content-tags">
							<?php foreach ($random_audio['tags'] as $term_id) {
								echo '<div class="tag" data-tagid="' . esc_attr($term_id) . '">#' . esc_html($tag_data[$term_id]) . '</div>';
							} ?>
                        </div>
                    </div>
                </div>
                <div class="leaflet-sidebar-tabs" role="tablist">
                    <button id="black_white_switcher" aria-label="<?php esc_attr_e('Change contrast', 'mannheim-under-construction'); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="0 0 60 60" xml:space="preserve">
                            <g>
                                <polygon points="30,9 33.073,9 33.015,9 27.675,9 17.177,30 27.675,51 30,51"/>
                                <polygon points="33.073,9 30,9 30,51 33.015,51 43.513,29.941"/>
                            </g>
                        </svg>
                    </button>
                    <button id="font_size_switcher" role="button" aria-label="<?php esc_attr_e('Change font size', 'mannheim-under-construction'); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="0 0 60 60" xml:space="preserve">
                            <g>
                                <path d="M6.837,51.209l12.6-25.2h6.517l12.6,25.2h-7.884l-2.376-4.752H15.189l-2.376,4.752H6.837z M17.781,41.273h7.92l-3.96-7.92
                                    L17.781,41.273z"/>
                                <path d="M28.451,26.988l8.973-17.943h4.639l8.973,17.943h-5.613l-1.692-3.384h-9.331l-1.691,3.384H28.451z M36.244,19.914h5.639
                                    l-2.818-5.639L36.244,19.914z"/>
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
                             viewBox="0 0 57.167 81.5" xml:space="preserve">
                            <path d="M17.185,55.664V25.225l26.361,15.229L17.185,55.664z"/>
                        </svg>
                    </button>
                    <button id="back_button" class="back-button" aria-label="<?php esc_attr_e('Back', 'mannheim-under-construction'); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="0 0 57.167 81.5" xml:space="preserve">
                            <path d="M18.812,32.084v6.938L10.14,30.35l8.672-8.672v6.938h12.141c3.844,0,7.117,1.355,9.82,4.066s4.055,5.98,4.055,9.809
                                s-1.355,7.098-4.066,9.809s-5.98,4.066-9.809,4.066H18.812v-3.469h12.141c2.875,0,5.328-1.016,7.359-3.047
                                s3.047-4.484,3.047-7.359s-1.016-5.328-3.047-7.359s-4.484-3.047-7.359-3.047H18.812z"/>
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
                                                <rect x="7" y="9.089" width="44" height="5.324"/>
                                                <rect x="7" y="27.089" width="44" height="5.324"/>
                                                <rect x="7" y="46.089" width="44" height="5.324"/>
                                            </g>
                                        </svg>
                                    </button>
                                    <div>
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
                                         viewBox="0 0 57.167 81.5" xml:space="preserve">
                                        <path d="M18.147,56.521l-4.71-4.733h21.685L13.438,30.104l3.325-3.324l21.683,21.682l0.023-21.681l4.71,4.71v25.03H18.147z"/>
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
                                <rect x="7" y="9.089" width="44" height="5.324"/>
                                <rect x="7" y="27.089" width="44" height="5.324"/>
                                <rect x="7" y="46.089" width="44" height="5.324"/>
                            </g>
                        </svg>
                    </button>
                    <button class="open-under-construction-info" role="tab" id="right_sidebar_title" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="0 0 50 413" xml:space="preserve">
                            <g>
                                <g>
                                    <path d="M17.237,25.107v12.852h-4.212v-18.9h6.696l6.264,12.528l6.265-12.528h6.912v18.9h-5.509V25.107l-6.102,12.852h-3.888
                                        L17.237,25.107z"/>
                                    <path d="M20.958,39.792l9.45-18.9h4.887l9.45,18.9h-5.913l-1.782-3.564h-9.828l-1.782,3.564H20.958z M29.166,32.34h5.939
                                        l-2.97-5.94L29.166,32.34z"/>
                                    <path d="M6.525,47.625v-18.9h7.507l9.099,13.878V28.725h4.212v18.9h-7.506l-9.1-13.878v13.878H6.525z"/>
                                    <path d="M23.899,54.334v-18.9h7.507l9.099,13.877V35.434h4.212v18.9h-7.506l-9.1-13.878v13.878H23.899z"/>
                                    <path d="M21.535,45.892v7.398h9.126v-7.398h5.508v18.9h-5.508v-7.614h-9.126v7.614h-5.509v-18.9H21.535z"/>
                                    <path d="M24.192,70.126v-18.9h20.143v3.889H29.701v3.51h12.663v3.888H29.701v3.727h14.634v3.888H24.192z"/>
                                    <path d="M31.201,57.058v18.9h-5.509v-18.9H31.201z"/>
                                    <path d="M16.903,75.189v12.852h-4.212v-18.9h6.696l6.264,12.528l6.265-12.528h6.912v18.9h-5.509V75.189l-6.102,12.852H23.33
                                        L16.903,75.189z"/>
                                </g>
                                <g>
                                    <path d="M35.167,117.225v15.014c0,1.26-0.333,2.223-0.999,2.889s-1.63,0.998-2.89,0.998h-12.42c-1.26,0-2.223-0.332-2.889-0.998
                                        s-0.999-1.629-0.999-2.889v-15.014h5.508v14.203c0,0.539,0.271,0.811,0.811,0.811h7.56c0.54,0,0.811-0.271,0.811-0.811v-14.203
                                        H35.167z"/>
                                    <path d="M23.608,151.208v-18.9h7.507l9.099,13.878v-13.878h4.212v18.9H36.92l-9.1-13.878v13.878H23.608z"/>
                                    <path d="M6.358,157.625v-18.9h16.309c1.26,0,2.223,0.334,2.889,0.999c0.666,0.667,0.999,1.63,0.999,2.89v11.124
                                        c0,1.26-0.333,2.223-0.999,2.889s-1.629,0.999-2.889,0.999H6.358z M11.867,153.737h8.369c0.54,0,0.811-0.271,0.811-0.811v-9.504
                                        c0-0.54-0.271-0.81-0.811-0.81h-8.369V153.737z"/>
                                    <path d="M24.191,162.125v-18.9h20.143v3.889H29.7v3.51h12.663v3.888H29.7v3.727h14.634v3.888H24.191z"/>
                                    <path d="M16.025,177.125v-18.9h16.309c1.26,0,2.223,0.334,2.889,0.999c0.666,0.667,0.999,1.63,0.999,2.89v1.755
                                        c0,1.26-0.333,2.214-0.999,2.862c-0.666,0.647-1.629,0.972-2.889,0.972v0.27c1.26,0,2.223,0.329,2.889,0.985
                                        c0.666,0.658,0.999,1.616,0.999,2.876v5.292h-5.508v-6.804c0-0.54-0.271-0.811-0.811-0.811h-8.369v7.614H16.025z
                                         M21.534,165.623h8.369c0.54,0,0.811-0.271,0.811-0.81v-1.891c0-0.54-0.271-0.81-0.811-0.81h-8.369V165.623z"/>
                                </g>
                                <g>
                                    <path d="M15.447,221.03v-11.124c0-1.26,0.332-2.223,0.998-2.89c0.666-0.665,1.629-0.999,2.89-0.999h12.42
                                        c1.26,0,2.223,0.334,2.889,0.999c0.666,0.667,0.999,1.63,0.999,2.89v1.943h-5.508v-1.134c0-0.54-0.27-0.81-0.81-0.81h-7.561
                                        c-0.54,0-0.81,0.27-0.81,0.81v9.504c0,0.54,0.27,0.811,0.81,0.811h7.561c0.54,0,0.81-0.271,0.81-0.811v-1.134h5.508v1.944
                                        c0,1.26-0.333,2.223-0.999,2.889s-1.629,0.999-2.889,0.999h-12.42c-1.261,0-2.224-0.333-2.89-0.999S15.447,222.29,15.447,221.03
                                        z"/>
                                    <path d="M24.197,229.238v-11.124c0-1.26,0.332-2.223,0.998-2.89c0.666-0.665,1.629-0.999,2.89-0.999h12.42
                                        c1.26,0,2.223,0.334,2.889,0.999c0.666,0.667,0.999,1.63,0.999,2.89v11.124c0,1.26-0.333,2.223-0.999,2.889
                                        s-1.629,0.999-2.889,0.999h-12.42c-1.261,0-2.224-0.333-2.89-0.999S24.197,230.498,24.197,229.238z M29.705,228.428
                                        c0,0.54,0.27,0.811,0.81,0.811h7.561c0.54,0,0.81-0.271,0.81-0.811v-9.504c0-0.54-0.27-0.81-0.81-0.81h-7.561
                                        c-0.54,0-0.81,0.27-0.81,0.81V228.428z"/>
                                    <path d="M6.358,358.793v-18.9h7.507l9.099,13.878v-13.878h4.212v18.9H19.67l-9.1-13.878v13.878H6.358z"/>
                                    <path d="M6.451,251.905v-1.944h5.508v1.134c0,0.54,0.27,0.811,0.81,0.811h7.561c0.54,0,0.81-0.271,0.81-0.811v-2.105
                                        c0-0.54-0.27-0.811-0.81-0.811h-9.99c-1.26,0-2.224-0.333-2.889-0.999c-0.667-0.665-0.999-1.629-0.999-2.889v-3.51
                                        c0-1.26,0.332-2.223,0.999-2.89c0.665-0.665,1.629-0.999,2.889-0.999h12.42c1.26,0,2.223,0.334,2.889,0.999
                                        c0.666,0.667,0.999,1.63,0.999,2.89v1.943h-5.508v-1.134c0-0.54-0.27-0.81-0.81-0.81h-7.561c-0.54,0-0.81,0.27-0.81,0.81v1.891
                                        c0,0.539,0.27,0.81,0.81,0.81h9.99c1.26,0,2.223,0.333,2.889,0.999s0.999,1.629,0.999,2.889v3.727
                                        c0,1.26-0.333,2.223-0.999,2.889s-1.629,0.999-2.889,0.999h-12.42c-1.26,0-2.224-0.333-2.889-0.999
                                        C6.783,254.128,6.451,253.165,6.451,251.905z"/>
                                    <path d="M14.997,251.447v-3.889h20.142v3.889h-7.316v15.012h-5.509v-15.012H14.997z"/>
                                    <path d="M24.066,271.543v-18.9h16.309c1.26,0,2.223,0.334,2.889,0.999c0.666,0.667,0.999,1.63,0.999,2.89v1.755
                                        c0,1.26-0.333,2.214-0.999,2.862c-0.666,0.647-1.629,0.972-2.889,0.972v0.27c1.26,0,2.223,0.329,2.889,0.985
                                        c0.666,0.658,0.999,1.616,0.999,2.876v5.292h-5.508v-6.804c0-0.54-0.271-0.811-0.811-0.811h-8.369v7.614H24.066z
                                         M29.575,260.041h8.369c0.54,0,0.811-0.271,0.811-0.81v-1.891c0-0.54-0.271-0.81-0.811-0.81h-8.369V260.041z"/>
                                    <path d="M26.584,262.559v15.013c0,1.26-0.333,2.223-0.999,2.889s-1.63,0.999-2.89,0.999h-12.42c-1.26,0-2.223-0.333-2.889-0.999
                                        s-0.999-1.629-0.999-2.889v-15.013h5.508v14.202c0,0.54,0.271,0.811,0.811,0.811h7.56c0.54,0,0.811-0.271,0.811-0.811v-14.202
                                        H26.584z"/>
                                    <path d="M14.947,285.071v-11.124c0-1.26,0.332-2.223,0.998-2.89c0.666-0.665,1.629-0.999,2.89-0.999h12.42
                                        c1.26,0,2.223,0.334,2.889,0.999c0.666,0.667,0.999,1.63,0.999,2.89v1.943h-5.508v-1.134c0-0.54-0.27-0.81-0.81-0.81h-7.561
                                        c-0.54,0-0.81,0.27-0.81,0.81v9.504c0,0.54,0.27,0.811,0.81,0.811h7.561c0.54,0,0.81-0.271,0.81-0.811v-1.134h5.508v1.944
                                        c0,1.26-0.333,2.223-0.999,2.889s-1.629,0.999-2.889,0.999h-12.42c-1.261,0-2.224-0.333-2.89-0.999
                                        S14.947,286.331,14.947,285.071z"/>
                                    <path d="M24.247,286.114v-3.889h20.142v3.889h-7.316v15.012h-5.509v-15.012H24.247z"/>
                                    <path d="M27.783,295.226v18.9h-5.509v-18.9H27.783z"/>
                                    <path d="M24.03,332.488v-11.124c0-1.26,0.332-2.223,0.998-2.89c0.666-0.665,1.629-0.999,2.89-0.999h12.42
                                        c1.26,0,2.223,0.334,2.889,0.999c0.666,0.667,0.999,1.63,0.999,2.89v11.124c0,1.26-0.333,2.223-0.999,2.889
                                        s-1.629,0.999-2.889,0.999h-12.42c-1.261,0-2.224-0.333-2.89-0.999S24.03,333.748,24.03,332.488z M29.538,331.678
                                        c0,0.54,0.27,0.811,0.81,0.811h7.561c0.54,0,0.81-0.271,0.81-0.811v-9.504c0-0.54-0.27-0.81-0.81-0.81h-7.561
                                        c-0.54,0-0.81,0.27-0.81,0.81V331.678z"/>
                                    <path d="M14.899,245.001v-18.9h7.507l9.099,13.878v-13.878h4.212v18.9h-7.506l-9.1-13.878v13.878H14.899z"/>
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
        </div>
        <span class="location-info" hidden><?php echo esc_html(json_encode($map_data)); ?></span>
        <span class="tags-info" hidden><?php echo esc_html(json_encode($tag_data)); ?></span>
        <noscript id="mannheim-under-construction-no-js-error"><p><?php esc_html_e('Sorry, you need to enable JavaScript to use this map.', 'mannheim-under-construction'); ?></p></noscript>
    </div>
</main>
<?php wp_footer(); ?>
</body>
</html>
