<!doctype html>
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
	$random_audio = $map_data[array_rand($map_data)];
	?>
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
                <p class="onboarding-explainer" id="onboarding-explainer-1"><?php esc_html_e('Change the font size or switch to black/white view', 'mannheim-under-construction'); ?></p>
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
            <div id="left_sidebar" class="leaflet-sidebar collapsed leaflet-sidebar-left">
                <div class="leaflet-sidebar-content">
                    <div class="leaflet-sidebar-pane" id="search" role="tabpanel">
                        <div class="search-filters">
                            <div class="content">
                                <button class="close-button">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                         viewBox="0 0 57.167 53.667" xml:space="preserve">
                                        <g>
                                            <path d="M46.3,12.919l-31.635,31.5l-3.963-3.991L42.531,8.586L46.3,12.919z"/>
                                            <path d="M42.085,44.302l-31.5-31.636l3.99-3.962l31.843,31.828L42.085,44.302z"/>
                                        </g>
                                    </svg>
                                </button>
                                <form class="mannheim-under-construction-search">
                                    <input name="s" type="search" placeholder="<?php esc_attr_e('Enter a search phrase', 'mannheim-under-construction'); ?>" aria-label="<?php esc_attr_e('Enter a search phrase', 'mannheim-under-construction'); ?>">
                                    <button type="submit">
                                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                             viewBox="0 0 57.167 81.5" xml:space="preserve">
                                            <g>
                                                <circle fill="none" stroke-width="3.5" stroke-miterlimit="10" cx="35.522" cy="33.893" r="16.597"/>
                                                <path fill="none" stroke-width="3.5" stroke-linecap="round" stroke-miterlimit="10" d="M24.277,34.022
                                                    c0,0-0.206-8.338,8.131-10.293"/>
                                                <path fill="none" stroke-width="3.5" stroke-linecap="round" stroke-miterlimit="10" d="M20.572,41.123"/>
                                                <path fill="none" stroke-width="3.5" stroke-linecap="round" stroke-miterlimit="10" d="M21.035,42.256
                                                    L6.573,56.563c0,0,1.544,5.662,5.867,5.559l13.639-13.638"/>
                                            </g>
                                        </svg>
                                    </button>
                                </form>
                                <p class="search-intro-text">
                                    <?php esc_html_e('This is the full-text search. Enter a word, which interests you and it can lead you to new posts :)', 'mannheim-under-construction'); ?><br>
                                    <?php esc_html_e('For example:', 'mannheim-under-construction'); ?><br>
                                    <?php esc_html_e('Vogelstang, School, Post-migration, etc.', 'mannheim-under-construction'); ?>
                                </p>
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
                                         viewBox="0 0 57.167 53.667" xml:space="preserve">
                                        <g>
                                            <path d="M46.3,12.919l-31.635,31.5l-3.963-3.991L42.531,8.586L46.3,12.919z"/>
                                            <path d="M42.085,44.302l-31.5-31.636l3.99-3.962l31.843,31.828L42.085,44.302z"/>
                                        </g>
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
                                         viewBox="0 0 57.167 53.667" xml:space="preserve">
                                        <g>
                                            <path d="M46.3,12.919l-31.635,31.5l-3.963-3.991L42.531,8.586L46.3,12.919z"/>
                                            <path d="M42.085,44.302l-31.5-31.636l3.99-3.962l31.843,31.828L42.085,44.302z"/>
                                        </g>
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
                    <div class="leaflet-sidebar-pane" id="play" role="tabpanel">
                        <button class="close-button">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                 viewBox="0 0 57.167 53.667" xml:space="preserve">
                                <g>
                                    <path d="M46.3,12.919l-31.635,31.5l-3.963-3.991L42.531,8.586L46.3,12.919z"/>
                                    <path d="M42.085,44.302l-31.5-31.636l3.99-3.962l31.843,31.828L42.085,44.302z"/>
                                </g>
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
                             viewBox="0 0 57.167 81.5" xml:space="preserve">
                            <g>
                                <circle stroke="#000000" stroke-width="2" stroke-miterlimit="10" cx="29.396" cy="41.105" r="14.771"/>
                                <path d="M29.5,26.335c8,0,14.771,6.613,14.771,14.771S37.5,55.876,29.5,55.876C29.5,55.75,30,26.5,29.5,26.335z"/>
                            </g>
                        </svg>
                    </button>
                    <button id="font_size_switcher" role="button" aria-label="<?php esc_attr_e('Change font size', 'mannheim-under-construction'); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="0 0 57.167 81.5" xml:space="preserve">
                            <g>
                                <g>
                                    <path d="M22.372,31.255L29.471,50.5h-3.674l-2.324-6.248h-5.574L15.649,50.5H11.95l7.098-19.245H22.372z M20.698,36.753
                                        l-1.8,4.799h3.599L20.698,36.753z"/>
                                    <path d="M39.048,36.636L44.161,50.5h-2.647l-1.674-4.501h-4.016l-1.62,4.501h-2.665l5.113-13.864H39.048z M37.841,40.597
                                        l-1.296,3.457h2.593L37.841,40.597z"/>
                                </g>
                                <line fill="none" stroke="#000000" stroke-width="2" stroke-miterlimit="10" x1="11.5" y1="58" x2="44.5" y2="58"/>
                            </g>
                        </svg>
                    </button>
                    <button href="#search" role="tab" aria-label="<?php esc_attr_e('Search', 'mannheim-under-construction'); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="0 0 57.167 81.5" xml:space="preserve">
                            <g>
                                <circle fill="none" stroke="#000000" stroke-width="3.5" stroke-miterlimit="10" cx="35.522" cy="33.893" r="16.597"/>
                                <path fill="none" stroke="#000000" stroke-width="3.5" stroke-linecap="round" stroke-miterlimit="10" d="M24.277,34.022
                                    c0,0-0.206-8.338,8.131-10.293"/>
                                <path fill="none" stroke="#000000" stroke-width="3.5" stroke-linecap="round" stroke-miterlimit="10" d="M20.572,41.123"/>
                                <path fill="none" stroke="#000000" stroke-width="3.5" stroke-linecap="round" stroke-miterlimit="10" d="M21.035,42.256
                                    L6.573,56.563c0,0,1.544,5.662,5.867,5.559l13.639-13.638"/>
                            </g>
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
                                             viewBox="0 0 57.167 81.5" xml:space="preserve">
                                            <g>
                                                <rect x="11.5" y="25" width="36" height="6"/>
                                                <rect x="11.5" y="38" width="36" height="6"/>
                                                <rect x="11.5" y="52" width="36" height="6"/>
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
                                         viewBox="0 0 57.167 53.667" xml:space="preserve">
                                        <g>
                                            <path d="M46.3,12.919l-31.635,31.5l-3.963-3.991L42.531,8.586L46.3,12.919z"/>
                                            <path d="M42.085,44.302l-31.5-31.636l3.99-3.962l31.843,31.828L42.085,44.302z"/>
                                        </g>
                                    </svg>
                                </button>
								<?php echo apply_filters( 'the_content', get_the_content(null, false, 218) ); ?>
                            </div>
                        </div>
                        <div class="right-part">
                            <div class="content">
                                <button class="close-button">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                         viewBox="0 0 57.167 53.667" xml:space="preserve">
                                        <g>
                                            <path d="M46.3,12.919l-31.635,31.5l-3.963-3.991L42.531,8.586L46.3,12.919z"/>
                                            <path d="M42.085,44.302l-31.5-31.636l3.99-3.962l31.843,31.828L42.085,44.302z"/>
                                        </g>
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
                                 viewBox="0 0 57.167 53.667" xml:space="preserve">
                                <g>
                                    <path d="M46.3,12.919l-31.635,31.5l-3.963-3.991L42.531,8.586L46.3,12.919z"/>
                                    <path d="M42.085,44.302l-31.5-31.636l3.99-3.962l31.843,31.828L42.085,44.302z"/>
                                </g>
                            </svg>
                        </button>
						<?php echo apply_filters( 'the_content', get_the_content(null, false, 169) ); ?>
                    </div>
                    <div class="leaflet-sidebar-pane" id="privacy" role="tabpanel">
                        <button class="close-button">
                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                 viewBox="0 0 57.167 53.667" xml:space="preserve">
                                <g>
                                    <path d="M46.3,12.919l-31.635,31.5l-3.963-3.991L42.531,8.586L46.3,12.919z"/>
                                    <path d="M42.085,44.302l-31.5-31.636l3.99-3.962l31.843,31.828L42.085,44.302z"/>
                                </g>
                            </svg>
                        </button>
						<?php echo apply_filters( 'the_content', get_the_content(null, false, 7) ); ?>
                    </div>
                </div>
                <div class="leaflet-sidebar-tabs" role="tablist">
                    <button class="open-under-construction-info" role="tab" aria-label="<?php esc_attr_e('Show info about the project', 'mannheim-under-construction'); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="0 0 57.167 81.5" xml:space="preserve">
                            <g>
                                <rect x="11.5" y="25" width="36" height="6"/>
                                <rect x="11.5" y="38" width="36" height="6"/>
                                <rect x="11.5" y="52" width="36" height="6"/>
                            </g>
                        </svg>
                    </button>
                    <button class="open-under-construction-info" role="tab" id="right_sidebar_title" aria-hidden="true">
                        <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="0 0 50 413" xml:space="preserve">
                            <g>
                                <text transform="matrix(1 0 -3.000000e-005 1 10.2793 114.1455)" font-family="'The-Neue-Black'" font-size="33" letter-spacing="1"> </text>
                            </g>
                            <g>
                                <g>
                                    <path d="M9.8,49.035l11.551-23.1h5.973l11.55,23.1h-7.228l-2.178-4.356H17.456l-2.178,4.356H9.8z M19.832,39.927h7.26
                l-3.63-7.26L19.832,39.927z"/>
                                    <path d="M11.713,85.035l0.001-23.1h9.174l11.12,16.962l0.001-16.962h5.147l-0.001,23.1h-9.174l-11.12-16.962L16.86,85.035
                H11.713z"/>
                                    <path d="M11.712,121.035v-23.1h9.175l11.12,16.962V97.936h5.148l-0.001,23.1H27.98l-11.12-16.962l-0.001,16.962H11.712z"/>
                                </g>
                                <g>
                                    <path d="M36.999,137.269l-0.001,18.348c0,1.54-0.407,2.718-1.222,3.531c-0.813,0.813-1.99,1.221-3.53,1.221H17.065
                c-1.539,0-2.717-0.407-3.53-1.221c-0.814-0.813-1.222-1.991-1.222-3.531l0.001-18.348h6.731v17.357c0,0.66,0.33,0.99,0.99,0.99
                h9.24c0.66,0,0.99-0.33,0.99-0.99v-17.357H36.999z"/>
                                    <path d="M12.379,196.368l0.001-23.1h9.174l11.12,16.962l0.001-16.962h5.147l-0.001,23.1h-9.174l-11.12-16.962l-0.001,16.962
                H12.379z"/>
                                </g>
                                <g>
                                    <path d="M13.046,175.035v-23.1h19.933c1.54,0,2.717,0.407,3.53,1.221c0.814,0.814,1.222,1.991,1.222,3.531l-0.001,13.596
                c0,1.54-0.406,2.718-1.221,3.531s-1.991,1.221-3.531,1.221H13.046z M19.778,170.283h10.229c0.66,0,0.99-0.33,0.99-0.99
                l0.001-11.616c0-0.659-0.33-0.989-0.99-0.989h-10.23V170.283z"/>
                                    <path d="M13.045,211.035l0.001-23.1h24.617v4.752H19.777v4.29h15.478l-0.001,4.752H19.776v4.554h17.887v4.752H13.045z"/>
                                    <path d="M13.044,247.035v-23.1h19.933c1.54,0,2.717,0.407,3.53,1.221c0.814,0.814,1.222,1.991,1.222,3.531l-0.001,2.145
                c0,1.54-0.406,2.706-1.221,3.498s-1.991,1.188-3.531,1.188v0.33c1.54,0,2.717,0.401,3.531,1.204
                c0.814,0.804,1.221,1.975,1.221,3.515v6.468h-6.732l0.001-8.316c0-0.659-0.33-0.989-0.99-0.989H19.776l-0.001,9.306H13.044z
                 M19.776,232.978h10.229c0.66,0,0.99-0.33,0.99-0.99l0.001-2.311c0-0.659-0.33-0.989-0.99-0.989h-10.23V232.978z"/>
                                </g>
                                <g>
                                    <path d="M12.751,310.688v-4.752h24.618v4.752h-8.942l-0.001,18.348h-6.731v-18.348H12.751z"/>
                                    <path d="M28.096,341.936l-0.001,23.1h-6.731l0.001-23.1H28.096z"/>
                                    <path d="M12.386,396.283l0.001-13.596c0-1.541,0.407-2.717,1.221-3.531s1.991-1.221,3.531-1.221h15.18
                c1.54,0,2.717,0.406,3.531,1.221c0.813,0.814,1.222,1.99,1.222,3.531l-0.001,13.596c0,1.539-0.407,2.717-1.222,3.531
                c-0.814,0.813-1.991,1.221-3.531,1.221h-15.18c-1.54,0-2.717-0.408-3.531-1.221C12.793,399,12.386,397.822,12.386,396.283z
                 M19.118,395.293c0,0.66,0.33,0.99,0.99,0.99h9.24c0.66,0,0.989-0.33,0.989-0.99l0.001-11.617c0-0.658-0.33-0.988-0.99-0.988
                h-9.24c-0.66,0-0.99,0.33-0.99,0.988V395.293z"/>
                                </g>
                                <g>
                                    <path d="M12.011,405.699v-23.1h9.175l11.12,16.963V382.6h5.148v23.1H28.28l-11.121-16.961v16.961H12.011z"/>
                                </g>
                                <g>
                                    <path d="M12.228,260.355v-4.752h24.617v4.752h-8.942l-0.001,18.348H21.17l0.001-18.348H12.228z"/>
                                    <path d="M13.381,314.703l0.001-23.1h19.932c1.539,0,2.717,0.408,3.531,1.221c0.813,0.814,1.221,1.992,1.221,3.531v2.145
                c0,1.541-0.408,2.707-1.222,3.498c-0.813,0.793-1.991,1.189-3.53,1.189v0.33c1.539,0,2.717,0.4,3.53,1.203
                c0.813,0.805,1.222,1.975,1.222,3.516v6.467h-6.732v-8.316c0-0.658-0.33-0.988-0.99-0.988h-10.23v9.305H13.381z M20.113,300.646
                h10.229c0.66,0,0.99-0.33,0.99-0.99v-2.311c0-0.66-0.33-0.99-0.99-0.99H20.113V300.646z"/>
                                    <path d="M37.999,327.604l-0.001,18.348c0,1.541-0.407,2.719-1.222,3.531c-0.814,0.814-1.991,1.221-3.531,1.221h-15.18
                c-1.54,0-2.717-0.406-3.53-1.221c-0.814-0.813-1.222-1.99-1.222-3.531l0.001-18.348h6.731v17.357c0,0.66,0.33,0.99,0.99,0.99
                h9.24c0.66,0,0.99-0.33,0.99-0.99v-17.357H37.999z"/>
                                    <path d="M13.181,381.951v-13.596c0-1.539,0.408-2.717,1.222-3.531c0.813-0.813,1.991-1.221,3.531-1.221h15.18
                c1.54,0,2.717,0.408,3.531,1.221c0.813,0.814,1.221,1.992,1.221,3.531v2.377h-6.732v-1.387c0-0.66-0.329-0.99-0.989-0.99h-9.24
                c-0.66,0-0.99,0.33-0.99,0.99v11.615c0,0.66,0.33,0.99,0.99,0.99h9.24c0.66,0,0.989-0.33,0.989-0.99v-1.385h6.732v2.375
                c-0.001,1.541-0.408,2.719-1.222,3.531c-0.814,0.814-1.991,1.221-3.531,1.221h-15.18c-1.54,0-2.717-0.406-3.531-1.221
                C13.587,384.67,13.181,383.492,13.181,381.951z"/>
                                </g>
                                <g>
                                    <path d="M13.517,218.616v-13.596c0-1.54,0.406-2.717,1.221-3.531c0.814-0.813,1.991-1.221,3.531-1.221h15.18
                c1.54,0,2.717,0.407,3.531,1.221c0.813,0.814,1.221,1.991,1.221,3.531v2.376h-6.732v-1.387c0-0.659-0.329-0.989-0.989-0.989
                h-9.24c-0.659,0-0.989,0.33-0.989,0.989l-0.001,11.616c0,0.66,0.33,0.99,0.989,0.99h9.24c0.66,0,0.99-0.33,0.99-0.99v-1.386
                H38.2v2.376c0,1.54-0.408,2.718-1.221,3.531c-0.814,0.813-1.992,1.221-3.531,1.221h-15.18c-1.541,0-2.718-0.407-3.531-1.221
                C13.923,221.334,13.517,220.156,13.517,218.616z"/>
                                    <path d="M13.515,254.616l0.001-13.596c0-1.54,0.407-2.717,1.221-3.531c0.813-0.813,1.991-1.221,3.531-1.221h15.18
                c1.54,0,2.717,0.407,3.531,1.221c0.813,0.814,1.222,1.991,1.222,3.531l-0.001,13.596c0,1.54-0.407,2.718-1.222,3.531
                s-1.99,1.221-3.531,1.221h-15.18c-1.539,0-2.717-0.407-3.531-1.221C13.922,257.334,13.515,256.156,13.515,254.616z
                 M20.247,253.626c0,0.66,0.33,0.99,0.99,0.99h9.24c0.66,0,0.989-0.33,0.989-0.99l0.001-11.616c0-0.659-0.33-0.989-0.99-0.989
                h-9.24c-0.66,0-0.99,0.33-0.99,0.989V253.626z"/>
                                    <path d="M13.712,295.368v-23.1h9.175l11.12,16.962v-16.962h5.148l-0.001,23.1H29.98l-11.12-16.962l-0.001,16.962H13.712z"/>
                                    <path d="M13.315,326.616v-2.376h6.731v1.386c0,0.66,0.33,0.99,0.99,0.99h9.239c0.66,0,0.99-0.33,0.99-0.99v-2.574
                c0-0.659-0.33-0.989-0.99-0.989H18.067c-1.541,0-2.718-0.407-3.531-1.222c-0.814-0.813-1.221-1.99-1.221-3.53v-4.29
                c0-1.54,0.406-2.717,1.221-3.531c0.814-0.813,1.991-1.221,3.531-1.221h15.18c1.54,0,2.717,0.407,3.531,1.221
                c0.814,0.814,1.221,1.991,1.221,3.531v2.376h-6.732v-1.387c0-0.659-0.33-0.989-0.99-0.989h-9.239c-0.66,0-0.99,0.33-0.99,0.989
                v2.311c0,0.66,0.33,0.99,0.99,0.99h12.21c1.539,0,2.717,0.407,3.531,1.221c0.813,0.814,1.221,1.991,1.221,3.531v4.554
                c0,1.54-0.408,2.718-1.222,3.531s-1.991,1.221-3.53,1.221H18.066c-1.54,0-2.718-0.407-3.53-1.221
                C13.722,329.334,13.314,328.156,13.315,326.616z"/>
                                </g>
                                <g>
                                    <path d="M13.528,14.994v15.708H8.38v-23.1h8.185l7.655,15.312l7.656-15.312h8.449l-0.001,23.1h-6.732l0.001-15.708
                l-7.459,15.708h-4.752L13.528,14.994z"/>
                                </g>
                                <g>
                                    <path d="M18.487,38.936v9.042h11.154v-9.042h6.732l-0.001,23.1h-6.731v-9.306H18.487v9.306h-6.732v-23.1H18.487z"/>
                                    <path d="M12.067,98.035v-23.1h24.619v4.752H18.8v4.29h15.477v4.752H18.8v4.554h17.886v4.752H12.067z"/>
                                    <path d="M27.429,110.936l-0.001,23.1h-6.731v-23.1H27.429z"/>
                                </g>
                                <g>
                                    <path d="M13.369,127.661l-0.001,15.708H8.221l0.001-23.1h8.184l7.656,15.312l7.656-15.312h8.447v23.1h-6.732v-15.708
                l-7.458,15.708h-4.752L13.369,127.661z"/>
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
