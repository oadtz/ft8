<?php

return [
	'socketio_url'	=>	env('SOCKETIO_URL'),
	'presets'	=> [
		[
			'name'	=>	'Custom'
		],
		[
			'name'			=>	'facebook',
			'format'		=>	'h264',
			'resolution'	=>	'1080x720'
		],
		[
			'name'			=>	'Instagram',
			'format'		=>	'h264',
			'resolution'	=>	'1000x1000'
		]
	],	

	'video_formats'		=> [
		[ 'name' => 'Gif Animation', 'format' => 'gif', 'outfile' => 'gif' ],
		[ 'name' => 'H.264', 'format' => 'h264', 'outfile' => 'mkv' ],
		[ 'name' => 'Apple PhotoJPEG', 'format' => 'photojpeg', 'outfile' => 'mov' ]
	],

	'video_resolutions' => [
		[ 'name' => 'Instagram', 'resolution' => '1000x1000'  ],
		[ 'name' => 'Facebook HD', 'resolution' => '1080x720' ],
		[ 'name' => 'Full HD', 'resolution' => '1920x1080' ],
		[ 'name' => '4K', 'resolution' => '3840x2160' ],
	]
];