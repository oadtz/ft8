<?php

return [
	'broadcast_url'	=>	env('BROADCAST_URL'),
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
		[ 'name' => 'Gif Animation', 'format' => 'gif', 'ext' => 'gif' ],
		[ 'name' => 'MP4', 'format' => 'mp4', 'ext' => 'mp4' ],
		[ 'name' => 'Mov', 'format' => 'mov', 'ext' => 'mov' ],
		[ 'name' => 'Wmv', 'format' => 'wmv', 'ext' => 'wmv' ]
	],

	'video_resolutions' => [
		[ 'name' => 'Instagram', 'resolution' => '1000x1000'  ],
		[ 'name' => 'VideoHive', 'resolution' => '960x540' ],
		[ 'name' => '720p', 'resolution' => '1280x720' ],
		[ 'name' => '1080p', 'resolution' => '1920x1080' ],
		[ 'name' => '4K', 'resolution' => '3840x2160' ],
	]
];