<!doctype html>
<html <?php language_attributes(); ?> <?php twentytwentyone_the_html_classes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
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
		'title' => get_the_title($post_id),
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
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                     viewBox="0 0 57.167 81.5" xml:space="preserve" class="onboarding-arrow" id="onboarding-arrow-1">
                    <path d="M16.848,23.62h24.189l4.551,4.552H24.633l20.951,20.96l-3.207,3.229L21.398,31.406v20.955l-4.551-4.574V23.62z"/>
                </svg>
                <p class="onboarding-explainer" id="onboarding-explainer-1"><?php esc_html_e('Change the font size or switch to black/white view', 'mannheim-under-construction'); ?></p>
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                     viewBox="0 0 57.167 81.5" xml:space="preserve" class="onboarding-arrow" id="onboarding-arrow-2">
                    <path d="M16.848,23.62h24.189l4.551,4.552H24.633l20.951,20.96l-3.207,3.229L21.398,31.406v20.955l-4.551-4.574V23.62z"/>
                </svg>
                <p class="onboarding-explainer" id="onboarding-explainer-2"><?php esc_html_e('Search in archive', 'mannheim-under-construction'); ?></p>
                <p class="onboarding-explainer" id="onboarding-explainer-3"><?php esc_html_e('Choose a marker...', 'mannheim-under-construction'); ?></p>
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                     viewBox="0 0 57.167 81.5" xml:space="preserve" id="onboarding-marker">
                    <g>
                        <path d="M45.104,31.343c0,12.841-16.722,34.775-17.248,34.249c-6.74-6.732-17.25-21.408-17.25-34.249
                            c0-12.84,7.723-23.249,17.25-23.249C37.381,8.094,45.104,18.503,45.104,31.343z"/>
                        <g>
                            <ellipse fill="#F2FF5B" cx="27.921" cy="26.635" rx="10.579" ry="11.115"/>
                        </g>
                    </g>
                </svg>
                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                     viewBox="0 0 57.167 81.5" xml:space="preserve" class="onboarding-arrow" id="onboarding-arrow-3">
                    <path d="M16.848,23.62h24.189l4.551,4.552H24.633l20.951,20.96l-3.207,3.229L21.398,31.406v20.955l-4.551-4.574V23.62z"/>
                </svg>
                <p class="onboarding-explainer" id="onboarding-explainer-4"><?php esc_html_e('... or a random post.', 'mannheim-under-construction'); ?></p>
                <button autofocus id="onboarding-start-button"><?php esc_html_e('Start', 'mannheim-under-construction'); ?></button>
                <div class="onboarding-welcome">
			        <?php esc_html_e('Welcome to', 'mannheim-under-construction'); ?><br>
			        <?php esc_html_e('Mannheim', 'mannheim-under-construction'); ?><br>
			        <?php esc_html_e('Under Construction', 'mannheim-under-construction'); ?>
                    <p>
				        <?php esc_html_e('Current and historical.', 'mannheim-under-construction'); ?><br>
				        <?php esc_html_e('Short and longer posts.', 'mannheim-under-construction'); ?><br>
				        <?php esc_html_e('Variety of forms: Project introductions, reports, interviews, Stories and other, also experimental, audio posts invite to linger and discover.', 'mannheim-under-construction'); ?>
                    </p>
                    <p><?php esc_html_e('This digital audio map is part of the eponymous project by bermuda.funk, the free radio Rhein-Neckar.', 'mannheim-under-construction'); ?></p>
                </div>
            </div>
            <div id="left_sidebar" class="leaflet-sidebar collapsed leaflet-sidebar-left">
                <div class="leaflet-sidebar-content">
                    <div class="leaflet-sidebar-pane" id="search" role="tabpanel">
                        <p><?php esc_html_e('Next to the full-text search (by search phrase) we have assigned tags to the posts – these are, as always, subjektive, but can help finding posts with similar topics.', 'mannheim-under-construction'); ?></p>
                        <form class="mannheim-under-construction-search">
                            <button type="button"><?php esc_html_e('Search for tags', 'mannheim-under-construction'); ?></button>
                            <input name="s" type="search" placeholder="<?php esc_attr_e('Enter a search phrase', 'mannheim-under-construction'); ?>" aria-label="<?php esc_attr_e('Enter a search phrase', 'mannheim-under-construction'); ?>">
                            <button type="submit" hidden></button>
                        </form>
                    </div>
                    <div class="leaflet-sidebar-pane" id="play" role="tabpanel">
                        <div class="content-title"><?php echo esc_html($random_audio['title']); ?></div>
                        <div class="content-player">
                            <div class="play_pause_button" aria-label="<?php esc_attr_e('Play/Pause', 'mannheim-under-construction'); ?>">
                                <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                     viewBox="0 0 114.334 81.5" xml:space="preserve">
                                    <path d="M17.185,55.664V25.225l26.361,15.229L17.185,55.664z"/>
                                    <g>
                                        <rect x="73.667" y="27" width="9" height="31"/>
                                        <rect x="88.667" y="27" width="9" height="31"/>
                                    </g>
                                </svg>
                            </div>
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
                            <div class="audio-time">
                                <span class="content-length" aria-label="<?php echo esc_attr($random_audio['length_readable']); ?>"><?php echo esc_html($random_audio['length']); ?></span>
                            </div>
                        </div>
                        <div class="content-player">
                            <button id="seek_backwards"><?php esc_html_e('Seek backwards', 'mannheim-under-construction'); ?></button>
                            <button id="seek_forwards"><?php esc_html_e('Seek forwards', 'mannheim-under-construction'); ?></button>
                        </div>
                        <div class="content-description"><?php echo $random_audio['description']; ?></div>
                        <div class="content-tags">
                            <?php foreach ($random_audio['tags'] as $term_id) {
                                echo '<div class="tag" data-tagid="' . esc_attr($term_id) . '">#' . esc_html($tag_data[$term_id]) . '</div>';
                            } ?>
                        </div>
                    </div>
                </div>
                <div class="leaflet-sidebar-tabs" role="tablist">
                    <button id="black_white_switcher" aria-label="<?php esc_attr_e('Change contrast', 'mannheim-under-construction'); ?>">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="0 0 57.167 81.5" xml:space="preserve">
                            <g>
                                <circle fill="#FFFFFF" stroke="#000000" stroke-width="2" stroke-miterlimit="10" cx="29.396" cy="41.105" r="14.771"/>
                                <path d="M29.5,26.335c8,0,14.771,6.613,14.771,14.771S37.5,55.876,29.5,55.876C29.5,55.75,30,26.5,29.5,26.335z"/>
                            </g>
                        </svg>
                    </button>
                    <button id="font_size_switcher" role="button" aria-label="<?php esc_attr_e('Change font size', 'mannheim-under-construction'); ?>">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
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
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
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
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="0 0 57.167 81.5" xml:space="preserve">
                            <path d="M17.185,55.664V25.225l26.361,15.229L17.185,55.664z"/>
                        </svg>
                    </button>
                    <button id="back_button" aria-label="<?php esc_attr_e('Back', 'mannheim-under-construction'); ?>">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="0 0 57.167 81.5" xml:space="preserve">
                            <path d="M18.812,32.084v6.938L10.14,30.35l8.672-8.672v6.938h12.141c3.844,0,7.117,1.355,9.82,4.066s4.055,5.98,4.055,9.809
                                s-1.355,7.098-4.066,9.809s-5.98,4.066-9.809,4.066H18.812v-3.469h12.141c2.875,0,5.328-1.016,7.359-3.047
                                s3.047-4.484,3.047-7.359s-1.016-5.328-3.047-7.359s-4.484-3.047-7.359-3.047H18.812z"/>
                        </svg>
                    </button>
                </div>
            </div>
            <div class="search_sidebar" id="search-tags">
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
            <div class="search_sidebar" id="search-fulltext">
                <div class="message"></div>
                <ol class="audios"></ol>
            </div>
            <div id="right_sidebar" class="leaflet-sidebar collapsed leaflet-sidebar-right">
                <div class="leaflet-sidebar-content">
                    <div class="leaflet-sidebar-pane" id="info" role="tabpanel">
                        <h1><?php _e('Welcome to <span class="font-neue-black">Mannheim Under Construction</span>', 'mannheim-under-construction'); ?></h1>
                        <p><?php esc_html_e('the audio map of civil societal engagement', 'mannheim-under-construction'); ?></p>
                        <p><?php printf(__('This map introduces civil societal engagement in the city Mannheim. <span class="font-neue-black">Mannheim Under Construction</span> means, means that this engagement is an ongoing process, which always continues to be written, thought and spoken. The Free Radio Rhein-Neckar e. V., the %s, accompany this process with the eponymous Project.', 'mannheim-under-construction'), '<a href="https://bermudafunk.org" rel="noopener" target="_blank">bermuda.funk</a>'); ?></p>
                        <h2><?php esc_html_e('Discover and listen: How does engagement sound?', 'mannheim-under-construction'); ?></h2>
                        <p><?php esc_html_e('Locations and engagement. Locations and memory. Markers in the city. Current and historical. Interim report and archive. Activism. Empowerment.', 'mannheim-under-construction'); ?></p>
                        <p><?php esc_html_e('The Formats are diverse: Project introduction, interviews, Stories and other – also experimental – Posts in various lengths invite to linger, browse, discover and listen.', 'mannheim-under-construction'); ?></p>
                    </div>
                    <div class="leaflet-sidebar-pane" id="imprint" role="tabpanel">
                        <?php echo apply_filters( 'the_content', get_the_content(null, false, 169) ); ?>
                    </div>
                    <div class="leaflet-sidebar-pane" id="privacy" role="tabpanel">
                        <?php echo apply_filters( 'the_content', get_the_content(null, false, 7) ); ?>
                    </div>
                </div>
                <div class="leaflet-sidebar-tabs" role="tablist">
                    <button href="#info" role="tab" aria-label="<?php esc_attr_e('Show info about the project', 'mannheim-under-construction'); ?>">
                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                             viewBox="0 0 57.167 81.5" xml:space="preserve">
                            <g>
                                <rect x="11.5" y="25" width="36" height="6"/>
                                <rect x="11.5" y="38" width="36" height="6"/>
                                <rect x="11.5" y="52" width="36" height="6"/>
                            </g>
                        </svg>
                    </button>
                    <button href="#info" role="tab" id="right_sidebar_title">
                        <span>M</span>
                        <span>a</span>
                        <span>n</span>
                        <span>n</span>
                        <span>h</span>
                        <span>e</span>
                        <span>i</span>
                        <span>m</span>
                        <span> </span>
                        <span>U</span>
                        <span>n</span>
                        <span>d</span>
                        <span>e</span>
                        <span>r</span>
                        <span> </span>
                        <span>C</span>
                        <span>o</span>
                        <span>n</span>
                        <span>s</span>
                        <span>t</span>
                        <span>r</span>
                        <span>u</span>
                        <span>c</span>
                        <span>t</span>
                        <span>i</span>
                        <span>o</span>
                        <span>n</span>
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
</main><!-- #main -->
<?php wp_footer(); ?>
</body>
</html>
