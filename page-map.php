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
                                                <rect x="7" y="18.089" width="44" height="5.324"/>
                                                <rect x="7" y="29.089" width="44" height="5.324"/>
                                                <rect x="7" y="40.089" width="44" height="5.324"/>
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
                                <rect x="7" y="18.089" width="44" height="5.324"/>
                                <rect x="7" y="29.089" width="44" height="5.324"/>
                                <rect x="7" y="40.089" width="44" height="5.324"/>
                            </g>
                        </svg>
                    </button>
                    <button class="open-under-construction-info" role="tab" id="right_sidebar_title" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="0 0 51.5 522" xml:space="preserve">
                            <g>
                                <g>
                                    <path d="M14.861,7.401v15.725H9.709V0.002H17.9l7.666,15.327l7.663-15.327h8.459v23.124l-6.741-6.77V7.401l-7.466,15.725h-4.757
                                        L14.861,7.401z"/>
                                    <path d="M21.537,34.2L33.1,11.077h6.715l11.563,23.124h-7.234l-2.18-4.361H29.937l-2.18,4.361L21.537,34.2z M32.314,25.083h7.27
                                        l-3.635-7.268L32.314,25.083z"/>
                                    <path d="M-0.164,56.639V33.226h9.301l11.27,17.192V33.226h5.219v23.413h-9.299L5.181,39.447v17.191H-0.164z"/>
                                    <path d="M25.415,67.757V44.301h9.317l11.293,17.224V44.301h5.229v23.457h-9.316L30.816,50.533v17.224H25.415z"/>
                                    <path d="M20.28,55.375v9.052h10.536v-9.052h7.074V78.5h-7.074v-9.316H20.28V78.5h-7.032V55.375H20.28z"/>
                                    <path d="M25.586,100.65V77.525h25.842v4.758h-18.24v4.294h15.828v4.756H33.188v4.56h18.24v4.757H25.586z"/>
                                    <path d="M28.768,88.56v23.125h-6.738V88.56H28.768z"/>
                                    <path d="M4.917,117.205v15.724h-5.153v-23.125h8.193l7.664,15.329l7.666-15.329h8.455v23.125h-6.74v-15.724l-7.463,15.724
                                        h-4.758L4.917,117.205z"/>
                                </g>
                                <g>
                                    <path d="M38.405,166.154v18.834c0,1.578-0.419,2.787-1.253,3.622c-0.837,0.835-2.043,1.253-3.627,1.253H17.133
                                        c-1.58,0-2.785-0.418-3.623-1.253c-0.836-0.835-1.252-2.044-1.252-3.622v-18.834h7.286v17.815c0,0.677,0.341,1.019,1.017,1.019
                                        h9.485c0.677,0,1.017-0.342,1.017-1.019v-17.815H38.405z"/>
                                    <path d="M-0.164,211.669v-23.123h19.953c1.543,0,2.721,0.408,3.536,1.222c0.813,0.815,1.222,1.995,1.222,3.535v13.61
                                        c0,1.543-0.408,2.72-1.222,3.536c-0.815,0.814-1.993,1.22-3.536,1.22H-0.164z M6.576,206.913h10.24
                                        c0.661,0,0.991-0.331,0.991-0.991v-11.627c0-0.662-0.33-0.992-0.991-0.992H6.576V206.913z"/>
                                    <path d="M12.856,233.636v-23.124h25.841v4.757H20.459v4.296h15.826v4.756H20.459v4.559h18.238v4.757H12.856z"/>
                                    <path d="M26.812,254.586v-23.124h19.956c1.54,0,2.72,0.409,3.535,1.225c0.813,0.815,1.221,1.991,1.221,3.534v2.148
                                        c0,1.54-0.408,2.707-1.221,3.501c-0.815,0.792-1.995,1.188-3.535,1.188v0.33c1.54,0,2.72,0.402,3.535,1.205
                                        c0.813,0.804,1.221,1.979,1.221,3.519v6.474h-6.74v-8.321c0-0.663-0.33-0.993-0.99-0.993h-10.24v9.314H26.812z M33.553,240.514
                                        h10.24c0.66,0,0.99-0.329,0.99-0.988v-2.314c0-0.661-0.33-0.99-0.99-0.99h-10.24V240.514z"/>
                                    <path d="M25.497,200.79v-23.413h9.299l11.272,17.191v-17.191h5.219v23.413h-9.299l-11.146-17.192v17.192H25.497z"/>
                                    <rect x="31" y="183" width="1" height="3"/>
                                </g>
                                <g>
                                    <path d="M13.116,306.405v-13.613c0-1.541,0.405-2.72,1.22-3.532c0.813-0.815,1.993-1.225,3.535-1.225h15.196
                                        c1.542,0,2.72,0.409,3.534,1.225c0.815,0.813,1.223,1.992,1.223,3.532v2.379h-6.738v-1.387c0-0.661-0.33-0.992-0.99-0.992
                                        h-9.252c-0.66,0-0.988,0.331-0.988,0.992v11.629c0,0.659,0.328,0.992,0.988,0.992h9.252c0.66,0,0.99-0.333,0.99-0.992v-1.388
                                        h6.738v2.38c0,1.54-0.407,2.719-1.223,3.533c-0.814,0.816-1.992,1.222-3.534,1.222H17.871c-1.542,0-2.722-0.406-3.535-1.222
                                        C13.521,309.124,13.116,307.945,13.116,306.405z"/>
                                    <path d="M26.494,317.379v-13.7c0-1.554,0.408-2.739,1.23-3.562c0.82-0.818,2.006-1.23,3.559-1.23h15.298
                                        c1.552,0,2.735,0.412,3.557,1.23c0.821,0.822,1.231,2.008,1.231,3.562v13.7c0,1.55-0.41,2.737-1.231,3.557
                                        c-0.821,0.82-2.005,1.231-3.557,1.231H31.283c-1.553,0-2.738-0.411-3.559-1.231C26.902,320.116,26.494,318.929,26.494,317.379z
                                         M33.277,316.378c0,0.668,0.334,1.001,0.998,1.001h9.314c0.663,0,0.995-0.333,0.995-0.999v-11.707
                                        c0-0.664-0.332-0.655-0.995-0.655h-9.314c-0.664,0-0.998-0.009-0.998,0.655V316.378z"/>
                                    <path d="M-0.182,361.729v-2.38h6.738v1.387c0,0.66,0.332,0.993,0.99,0.993h9.252c0.66,0,0.992-0.333,0.992-0.993v-2.576
                                        c0-0.661-0.332-0.99-0.992-0.99H4.576c-1.542,0-2.723-0.409-3.535-1.225c-0.816-0.812-1.223-1.992-1.223-3.534v-4.294
                                        c0-1.541,0.406-2.719,1.223-3.534c0.813-0.815,1.993-1.224,3.535-1.224h15.195c1.541,0,2.721,0.408,3.535,1.224
                                        c0.813,0.815,1.224,1.993,1.224,3.534v2.378h-6.739v-1.388c0-0.659-0.332-0.99-0.992-0.99H7.547c-0.658,0-0.99,0.331-0.99,0.99
                                        v2.314c0,0.659,0.332,0.989,0.99,0.989h12.225c1.541,0,2.721,0.409,3.535,1.225c0.813,0.815,1.224,1.991,1.224,3.534v4.56
                                        c0,1.54-0.41,2.719-1.224,3.534c-0.814,0.813-1.994,1.22-3.535,1.22H4.576c-1.542,0-2.723-0.406-3.535-1.22
                                        C0.225,364.448-0.182,363.27-0.182,361.729z"/>
                                    <path d="M26.785,359.118v-4.758H51.43v4.758h-8.953v18.365h-6.738v-18.365H26.785z"/>
                                    <path d="M13.168,399.836v-23.124h19.955c1.541,0,2.723,0.407,3.534,1.223c0.815,0.815,1.225,1.993,1.225,3.534v2.148
                                        c0,1.541-0.409,2.708-1.225,3.5c-0.812,0.794-1.993,1.189-3.534,1.189v0.331c1.541,0,2.723,0.401,3.534,1.204
                                        c0.815,0.806,1.225,1.979,1.225,3.521v6.474h-6.739v-8.324c0-0.661-0.333-0.99-0.992-0.99H19.909v9.314H13.168z M19.909,385.762
                                        H30.15c0.659,0,0.992-0.328,0.992-0.988v-2.313c0-0.662-0.333-0.991-0.992-0.991H19.909V385.762z"/>
                                    <path d="M25.896,398.725v18.834c0,1.578-0.42,2.787-1.254,3.622c-0.836,0.836-2.042,1.253-3.625,1.253H4.623
                                        c-1.579,0-2.787-0.417-3.622-1.253c-0.835-0.835-1.253-2.044-1.253-3.622v-18.834h7.287v17.814c0,0.677,0.342,1.02,1.016,1.02
                                        h9.484c0.679,0,1.018-0.343,1.018-1.02v-17.814H25.896z"/>
                                    <path d="M12.91,344.735v-23.414h9.299l11.273,17.191v-17.191h5.215v23.414H29.4l-11.145-17.191v17.191H12.91z"/>
                                    <path d="M-0.371,521.777v-23.414H8.93l11.271,17.191v-17.191h5.219v23.414h-9.299L4.975,504.586v17.191H-0.371z"/>
                                    <path d="M13.241,439.346v-13.612c0-1.539,0.405-2.719,1.22-3.534c0.816-0.813,1.994-1.223,3.539-1.223h15.193
                                        c1.541,0,2.719,0.409,3.534,1.223c0.814,0.815,1.224,1.995,1.224,3.534v2.379h-6.74v-1.385c0-0.663-0.33-0.994-0.99-0.994
                                        h-9.252c-0.658,0-0.99,0.331-0.99,0.994v11.627c0,0.659,0.332,0.991,0.99,0.991h9.252c0.66,0,0.99-0.332,0.99-0.991v-1.389h6.74
                                        v2.38c0,1.542-0.409,2.72-1.224,3.535c-0.815,0.815-1.993,1.222-3.534,1.222H18c-1.545,0-2.723-0.406-3.539-1.222
                                        C13.646,442.064,13.241,440.888,13.241,439.346z"/>
                                    <path d="M26.413,447.015v-4.758h24.645v4.758h-8.954v18.368h-6.739v-18.368H26.413z"/>
                                    <path d="M28.895,453.897v23.126h-6.74v-23.126H28.895z"/>
                                    <path d="M26.148,494.772v-13.61c0-1.541,0.406-2.719,1.221-3.536c0.815-0.813,1.992-1.223,3.535-1.223H46.1
                                        c1.543,0,2.719,0.409,3.535,1.224c0.814,0.816,1.223,1.994,1.223,3.535v13.61c0,1.54-0.408,2.719-1.223,3.533
                                        c-0.816,0.815-1.992,1.224-3.535,1.224H30.904c-1.543,0-2.72-0.408-3.535-1.224C26.555,497.491,26.148,496.313,26.148,494.772z
                                         M32.888,493.779c0,0.663,0.329,0.993,0.989,0.993h9.251c0.66,0,0.99-0.33,0.99-0.993v-11.627c0-0.661-0.33-0.99-0.99-0.99
                                        h-9.251c-0.66,0-0.989,0.329-0.989,0.99V493.779z"/>
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
