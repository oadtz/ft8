<?php

return [
	'max_file_size'			=>	env('MAX_FILE_SIZE', 209715200),
	'gif_max_width'			=>	env('GIF_MAX_WIDTH', 500),
	'gif_thumbnail_scale'	=>	env('GIF_THUMBNAIL_SCALE', 0.75),
	'gif_max_time'			=>	env('GIF_MAX_TIME', 10),
	'gif_framerate'			=>	env('GIF_FRAMERATE', 10),
	'gif_default_caption'	=>	env('GIF_DEFAULT_CAPTION', ' '),
	'gif_default_caption_color'		=>	env('GIF_DEFAULT_CAPTION_COLOR', '#FFFFFF'),
	'facebook_app_id'		=>	env('FACEBOOK_APP_ID')
];