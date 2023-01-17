<?php

/*
	XlXi 2023
	Brick color helper.
*/

namespace App\Helpers;

class BrickColorHelper
{
	private static $brickColors = [
		1 => [
			'Color' => [242, 243, 243],
			'Name' => 'White'
		],
		2 => [
			'Color' => [161, 165, 162],
			'Name' => 'Grey'
		],
		3 => [
			'Color' => [249, 233, 153],
			'Name' => 'Light yellow'
		],
		5 => [
			'Color' => [215, 197, 154],
			'Name' => 'Brick yellow'
		],
		6 => [
			'Color' => [194, 218, 184],
			'Name' => 'Light green (Mint)'
		],
		9 => [
			'Color' => [232, 186, 200],
			'Name' => 'Light reddish violet'
		],
		11 => [
			'Color' => [0x80, 0xbb, 0xdb],
			'Name' => 'Pastel Blue'
		],
		12 => [
			'Color' => [203, 132, 66],
			'Name' => 'Light orange brown'
		],
		18 => [
			'Color' => [204, 142, 105],
			'Name' => 'Nougat'
		],
		21 => [
			'Color' => [196, 40, 28],
			'Name' => 'Bright red'
		],
		22 => [
			'Color' => [196, 112, 160],
			'Name' => 'Med. reddish violet'
		],
		23 => [
			'Color' => [13, 105, 172],
			'Name' => 'Bright blue'
		],
		24 => [
			'Color' => [245, 205, 48],
			'Name' => 'Bright yellow'
		],
		25 => [
			'Color' => [98, 71, 50],
			'Name' => 'Earth orange'
		],
		26 => [
			'Color' => [27, 42, 53],
			'Name' => 'Black'
		],
		27 => [
			'Color' => [109, 110, 108],
			'Name' => 'Dark grey'
		],
		28 => [
			'Color' => [40, 127, 71],
			'Name' => 'Dark green'
		],
		29 => [
			'Color' => [161, 196, 140],
			'Name' => 'Medium green'
		],
		36 => [
			'Color' => [243, 207, 155],
			'Name' => 'Lig. Yellowich orange'
		],
		37 => [
			'Color' => [75, 151, 75],
			'Name' => 'Bright green'
		],
		38 => [
			'Color' => [160, 95, 53],
			'Name' => 'Dark orange'
		],
		39 => [
			'Color' => [193, 202, 222],
			'Name' => 'Light bluish violet'
		],
		40 => [
			'Color' => [236, 236, 236],
			'Name' => 'Transparent'
		],
		41 => [
			'Color' => [205, 84, 75],
			'Name' => 'Tr. Red'
		],
		42 => [
			'Color' => [193, 223, 240],
			'Name' => 'Tr. Lg blue'
		],
		43 => [
			'Color' => [123, 182, 232],
			'Name' => 'Tr. Blue'
		],
		44 => [
			'Color' => [247, 241, 141],
			'Name' => 'Tr. Yellow'
		],
		45 => [
			'Color' => [180, 210, 228],
			'Name' => 'Light blue'
		],
		47 => [
			'Color' => [217, 133, 108],
			'Name' => 'Tr. Flu. Reddish orange'
		],
		48 => [
			'Color' => [132, 182, 141],
			'Name' => 'Tr. Green'
		],
		49 => [
			'Color' => [248, 241, 132],
			'Name' => 'Tr. Flu. Green'
		],
		50 => [
			'Color' => [236, 232, 222],
			'Name' => 'Phosph. White'
		],
		100 => [
			'Color' => [238, 196, 182],
			'Name' => 'Light red'
		],
		101 => [
			'Color' => [218, 134, 122],
			'Name' => 'Medium red'
		],
		102 => [
			'Color' => [110, 153, 202],
			'Name' => 'Medium blue'
		],
		103 => [
			'Color' => [199, 193, 183],
			'Name' => 'Light grey'
		],
		104 => [
			'Color' => [107, 50, 124],
			'Name' => 'Bright violet'
		],
		105 => [
			'Color' => [226, 155, 64],
			'Name' => 'Br. yellowish orange'
		],
		106 => [
			'Color' => [218, 133, 65],
			'Name' => 'Bright orange'
		],
		107 => [
			'Color' => [0, 143, 156],
			'Name' => 'Bright bluish green'
		],
		108 => [
			'Color' => [104, 92, 67],
			'Name' => 'Earth yellow'
		],
		110 => [
			'Color' => [67, 84, 147],
			'Name' => 'Bright bluish violet'
		],
		111 => [
			'Color' => [191, 183, 177],
			'Name' => 'Tr. Brown'
		],
		112 => [
			'Color' => [104, 116, 172],
			'Name' => 'Medium bluish violet'
		],
		113 => [
			'Color' => [228, 173, 200],
			'Name' => 'Tr. Medi. reddish violet'
		],
		115 => [
			'Color' => [199, 210, 60],
			'Name' => 'Med. yellowish green'
		],
		116 => [
			'Color' => [85, 165, 175],
			'Name' => 'Med. bluish green'
		],
		118 => [
			'Color' => [183, 215, 213],
			'Name' => 'Light bluish green'
		],
		119 => [
			'Color' => [164, 189, 71],
			'Name' => 'Br. yellowish green'
		],
		120 => [
			'Color' => [217, 228, 167],
			'Name' => 'Lig. yellowish green'
		],
		121 => [
			'Color' => [231, 172, 88],
			'Name' => 'Med. yellowish orange'
		],
		123 => [
			'Color' => [211, 111, 76],
			'Name' => 'Br. reddish orange'
		],
		124 => [
			'Color' => [146, 57, 120],
			'Name' => 'Bright reddish violet'
		],
		125 => [
			'Color' => [234, 184, 146],
			'Name' => 'Light orange'
		],
		126 => [
			'Color' => [165, 165, 203],
			'Name' => 'Tr. Bright bluish violet'
		],
		127 => [
			'Color' => [220, 188, 129],
			'Name' => 'Gold'
		],
		128 => [
			'Color' => [174, 122, 89],
			'Name' => 'Dark nougat'
		],
		131 => [
			'Color' => [156, 163, 168],
			'Name' => 'Silver'
		],
		133 => [
			'Color' => [213, 115, 61],
			'Name' => 'Neon orange'
		],
		134 => [
			'Color' => [216, 221, 86],
			'Name' => 'Neon green'
		],
		135 => [
			'Color' => [116, 134, 157],
			'Name' => 'Sand blue'
		],
		136 => [
			'Color' => [135, 124, 144],
			'Name' => 'Sand violet'
		],
		137 => [
			'Color' => [224, 152, 100],
			'Name' => 'Medium orange'
		],
		138 => [
			'Color' => [149, 138, 115],
			'Name' => 'Sand yellow'
		],
		140 => [
			'Color' => [32, 58, 86],
			'Name' => 'Earth blue'
		],
		141 => [
			'Color' => [39, 70, 45],
			'Name' => 'Earth green'
		],
		143 => [
			'Color' => [207, 226, 247],
			'Name' => 'Tr. Flu. Blue'
		],
		145 => [
			'Color' => [121, 136, 161],
			'Name' => 'Sand blue metallic'
		],
		146 => [
			'Color' => [149, 142, 163],
			'Name' => 'Sand violet metallic'
		],
		147 => [
			'Color' => [147, 135, 103],
			'Name' => 'Sand yellow metallic'
		],
		148 => [
			'Color' => [87, 88, 87],
			'Name' => 'Dark grey metallic'
		],
		149 => [
			'Color' => [22, 29, 50],
			'Name' => 'Black metallic'
		],
		150 => [
			'Color' => [171, 173, 172],
			'Name' => 'Light grey metallic'
		],
		151 => [
			'Color' => [120, 144, 130],
			'Name' => 'Sand green'
		],
		153 => [
			'Color' => [149, 121, 119],
			'Name' => 'Sand red'
		],
		154 => [
			'Color' => [123, 46, 47],
			'Name' => 'Dark red'
		],
		157 => [
			'Color' => [255, 246, 123],
			'Name' => 'Tr. Flu. Yellow'
		],
		158 => [
			'Color' => [225, 164, 194],
			'Name' => 'Tr. Flu. Red'
		],
		168 => [
			'Color' => [117, 108, 98],
			'Name' => 'Gun metallic'
		],
		176 => [
			'Color' => [151, 105, 91],
			'Name' => 'Red flip/flop'
		],
		178 => [
			'Color' => [180, 132, 85],
			'Name' => 'Yellow flip/flop'
		],
		179 => [
			'Color' => [137, 135, 136],
			'Name' => 'Silver flip/flop'
		],
		180 => [
			'Color' => [215, 169, 75],
			'Name' => 'Curry'
		],
		190 => [
			'Color' => [249, 214, 46],
			'Name' => 'Fire Yellow'
		],
		191 => [
			'Color' => [232, 171, 45],
			'Name' => 'Flame yellowish orange'
		],
		192 => [
			'Color' => [105, 64, 40],
			'Name' => 'Reddish brown'
		],
		193 => [
			'Color' => [207, 96, 36],
			'Name' => 'Flame reddish orange'
		],
		194 => [
			'Color' => [163, 162, 165],
			'Name' => 'Medium stone grey'
		],
		195 => [
			'Color' => [70, 103, 164],
			'Name' => 'Royal blue'
		],
		196 => [
			'Color' => [35, 71, 139],
			'Name' => 'Dark Royal blue'
		],
		198 => [
			'Color' => [142, 66, 133],
			'Name' => 'Bright reddish lilac'
		],
		199 => [
			'Color' => [99, 95, 98],
			'Name' => 'Dark stone grey'
		],
		200 => [
			'Color' => [130, 138, 93],
			'Name' => 'Lemon metalic'
		],
		208 => [
			'Color' => [229, 228, 223],
			'Name' => 'Light stone grey'
		],
		209 => [
			'Color' => [176, 142, 68],
			'Name' => 'Dark Curry'
		],
		210 => [
			'Color' => [112, 149, 120],
			'Name' => 'Faded green'
		],
		211 => [
			'Color' => [121, 181, 181],
			'Name' => 'Turquoise'
		],
		212 => [
			'Color' => [159, 195, 233],
			'Name' => 'Light Royal blue'
		],
		213 => [
			'Color' => [108, 129, 183],
			'Name' => 'Medium Royal blue'
		],
		216 => [
			'Color' => [143, 76, 42],
			'Name' => 'Rust'
		],
		217 => [
			'Color' => [124, 92, 70],
			'Name' => 'Brown'
		],
		218 => [
			'Color' => [150, 112, 159],
			'Name' => 'Reddish lilac'
		],
		219 => [
			'Color' => [107, 98, 155],
			'Name' => 'Lilac'
		],
		220 => [
			'Color' => [167, 169, 206],
			'Name' => 'Light lilac'
		],
		221 => [
			'Color' => [205, 98, 152],
			'Name' => 'Bright purple'
		],
		222 => [
			'Color' => [228, 173, 200],
			'Name' => 'Light purple'
		],
		223 => [
			'Color' => [220, 144, 149],
			'Name' => 'Light pink'
		],
		224 => [
			'Color' => [240, 213, 160],
			'Name' => 'Light brick yellow'
		],
		225 => [
			'Color' => [235, 184, 127],
			'Name' => 'Warm yellowish orange'
		],
		226 => [
			'Color' => [253, 234, 141],
			'Name' => 'Cool yellow'
		],
		232 => [
			'Color' => [125, 187, 221],
			'Name' => 'Dove blue'
		],
		268 => [
			'Color' => [52, 43, 117],
			'Name' => 'Medium lilac'
		],
		301 => [
			'Color' => [80, 109, 84],
			'Name' => 'Slime green'
		],
		302 => [
			'Color' => [91, 93, 105],
			'Name' => 'Smoky grey'
		],
		303 => [
			'Color' => [0, 16, 176],
			'Name' => 'Dark blue'
		],
		304 => [
			'Color' => [44, 101, 29],
			'Name' => 'Parsley green'
		],
		305 => [
			'Color' => [82, 124, 174],
			'Name' => 'Steel blue'
		],
		306 => [
			'Color' => [51, 88, 130],
			'Name' => 'Storm blue'
		],
		307 => [
			'Color' => [16, 42, 220],
			'Name' => 'Lapis'
		],
		308 => [
			'Color' => [61, 21, 133],
			'Name' => 'Dark indigo'
		],
		309 => [
			'Color' => [52, 142, 64],
			'Name' => 'Sea green'
		],
		310 => [
			'Color' => [91, 154, 76],
			'Name' => 'Shamrock'
		],
		311 => [
			'Color' => [159, 161, 172],
			'Name' => 'Fossil'
		],
		312 => [
			'Color' => [89, 34, 89],
			'Name' => 'Mulberry'
		],
		313 => [
			'Color' => [31, 128, 29],
			'Name' => 'Forest green'
		],
		314 => [
			'Color' => [159, 173, 192],
			'Name' => 'Cadet blue'
		],
		315 => [
			'Color' => [9, 137, 207],
			'Name' => 'Electric blue'
		],
		316 => [
			'Color' => [123, 0, 123],
			'Name' => 'Eggplant'
		],
		317 => [
			'Color' => [124, 156, 107],
			'Name' => 'Moss'
		],
		318 => [
			'Color' => [138, 171, 133],
			'Name' => 'Artichoke'
		],
		319 => [
			'Color' => [185, 196, 177],
			'Name' => 'Sage green'
		],
		320 => [
			'Color' => [202, 203, 209],
			'Name' => 'Ghost grey'
		],
		321 => [
			'Color' => [167, 94, 155],
			'Name' => 'Lilac'
		],
		322 => [
			'Color' => [123, 47, 123],
			'Name' => 'Plum'
		],
		323 => [
			'Color' => [148, 190, 129],
			'Name' => 'Olivine'
		],
		324 => [
			'Color' => [168, 189, 153],
			'Name' => 'Laurel green'
		],
		325 => [
			'Color' => [223, 223, 222],
			'Name' => 'Quill grey'
		],
		327 => [
			'Color' => [151, 0, 0],
			'Name' => 'Crimson'
		],
		328 => [
			'Color' => [177, 229, 166],
			'Name' => 'Mint'
		],
		329 => [
			'Color' => [152, 194, 219],
			'Name' => 'Baby blue'
		],
		330 => [
			'Color' => [255, 152, 220],
			'Name' => 'Carnation pink'
		],
		331 => [
			'Color' => [255, 89, 89],
			'Name' => 'Persimmon'
		],
		332 => [
			'Color' => [117, 0, 0],
			'Name' => 'Maroon'
		],
		333 => [
			'Color' => [239, 184, 56],
			'Name' => 'Gold'
		],
		334 => [
			'Color' => [248, 217, 109],
			'Name' => 'Daisy orange'
		],
		335 => [
			'Color' => [231, 231, 236],
			'Name' => 'Pearl'
		],
		336 => [
			'Color' => [199, 212, 228],
			'Name' => 'Fog'
		],
		337 => [
			'Color' => [255, 148, 148],
			'Name' => 'Salmon'
		],
		338 => [
			'Color' => [190, 104, 98],
			'Name' => 'Terra Cotta'
		],
		339 => [
			'Color' => [86, 36, 36],
			'Name' => 'Cocoa'
		],
		340 => [
			'Color' => [241, 231, 199],
			'Name' => 'Wheat'
		],
		341 => [
			'Color' => [254, 243, 187],
			'Name' => 'Buttermilk'
		],
		342 => [
			'Color' => [224, 178, 208],
			'Name' => 'Mauve'
		],
		343 => [
			'Color' => [212, 144, 189],
			'Name' => 'Sunrise'
		],
		344 => [
			'Color' => [150, 85, 85],
			'Name' => 'Tawny'
		],
		345 => [
			'Color' => [143, 76, 42],
			'Name' => 'Rust'
		],
		346 => [
			'Color' => [211, 190, 150],
			'Name' => 'Cashmere'
		],
		347 => [
			'Color' => [226, 220, 188],
			'Name' => 'Khaki'
		],
		348 => [
			'Color' => [237, 234, 234],
			'Name' => 'Lily white'
		],
		349 => [
			'Color' => [233, 218, 218],
			'Name' => 'Seashell'
		],
		350 => [
			'Color' => [136, 62, 62],
			'Name' => 'Burgundy'
		],
		351 => [
			'Color' => [188, 155, 93],
			'Name' => 'Cork'
		],
		352 => [
			'Color' => [199, 172, 120],
			'Name' => 'Burlap'
		],
		353 => [
			'Color' => [202, 191, 163],
			'Name' => 'Beige'
		],
		354 => [
			'Color' => [187, 179, 178],
			'Name' => 'Oyster'
		],
		355 => [
			'Color' => [108, 88, 75],
			'Name' => 'Pine Cone'
		],
		356 => [
			'Color' => [160, 132, 79],
			'Name' => 'Fawn brown'
		],
		357 => [
			'Color' => [149, 137, 136],
			'Name' => 'Hurricane grey'
		],
		358 => [
			'Color' => [171, 168, 158],
			'Name' => 'Cloudy grey'
		],
		359 => [
			'Color' => [175, 148, 131],
			'Name' => 'Linen'
		],
		360 => [
			'Color' => [150, 103, 102],
			'Name' => 'Copper'
		],
		361 => [
			'Color' => [86, 66, 54],
			'Name' => 'Dirt brown'
		],
		362 => [
			'Color' => [126, 104, 63],
			'Name' => 'Bronze'
		],
		363 => [
			'Color' => [105, 102, 92],
			'Name' => 'Flint'
		],
		364 => [
			'Color' => [90, 76, 66],
			'Name' => 'Dark taupe'
		],
		365 => [
			'Color' => [106, 57, 9],
			'Name' => 'Burnt Sienna'
		],
		1001 => [
			'Color' => [248, 248, 248],
			'Name' => 'Institutional white'
		],
		1002 => [
			'Color' => [205, 205, 205],
			'Name' => 'Mid gray'
		],
		1003 => [
			'Color' => [17, 17, 17],
			'Name' => 'Really black'
		],
		1004 => [
			'Color' => [255, 0, 0],
			'Name' => 'Really red'
		],
		1005 => [
			'Color' => [255, 175, 0],
			'Name' => 'Deep orange'
		],
		1006 => [
			'Color' => [180, 128, 255],
			'Name' => 'Alder'
		],
		1007 => [
			'Color' => [163, 75, 75],
			'Name' => 'Dusty Rose'
		],
		1008 => [
			'Color' => [193, 190, 66],
			'Name' => 'Olive'
		],
		1009 => [
			'Color' => [255, 255, 0],
			'Name' => 'New Yeller'
		],
		1010 => [
			'Color' => [0, 0, 255],
			'Name' => 'Really blue'
		],
		1011 => [
			'Color' => [0, 32, 96],
			'Name' => 'Navy blue'
		],
		1012 => [
			'Color' => [33, 84, 185],
			'Name' => 'Deep blue'
		],
		1013 => [
			'Color' => [4, 175, 236],
			'Name' => 'Cyan'
		],
		1014 => [
			'Color' => [170, 85, 0],
			'Name' => 'CGA brown'
		],
		1015 => [
			'Color' => [170, 0, 170],
			'Name' => 'Magenta'
		],
		1016 => [
			'Color' => [255, 102, 204],
			'Name' => 'Pink'
		],
		1017 => [
			'Color' => [255, 175, 0],
			'Name' => 'Deep orange'
		],
		1018 => [
			'Color' => [18, 238, 212],
			'Name' => 'Teal'
		],
		1019 => [
			'Color' => [0, 255, 255],
			'Name' => 'Toothpaste'
		],
		1020 => [
			'Color' => [0, 255, 0],
			'Name' => 'Lime green'
		],
		1021 => [
			'Color' => [58, 125, 21],
			'Name' => 'Camo'
		],
		1022 => [
			'Color' => [127, 142, 100],
			'Name' => 'Grime'
		],
		1023 => [
			'Color' => [140, 91, 159],
			'Name' => 'Lavender'
		],
		1024 => [
			'Color' => [175, 221, 255],
			'Name' => 'Pastel light blue'
		],
		1025 => [
			'Color' => [255, 201, 201],
			'Name' => 'Pastel orange'
		],
		1026 => [
			'Color' => [177, 167, 255],
			'Name' => 'Pastel violet'
		],
		1027 => [
			'Color' => [159, 243, 233],
			'Name' => 'Pastel blue-green'
		],
		1028 => [
			'Color' => [204, 255, 204],
			'Name' => 'Pastel green'
		],
		1029 => [
			'Color' => [255, 255, 204],
			'Name' => 'Pastel yellow'
		],
		1030 => [
			'Color' => [255, 204, 153],
			'Name' => 'Pastel brown'
		],
		1031 => [
			'Color' => [98, 37, 209],
			'Name' => 'Royal purple'
		],
		1032 => [
			'Color' => [255, 0, 191],
			'Name' => 'Hot pink'
		]
	];
	
	public static function IsValidColor($colorId)
	{
		return array_key_exists($colorId, self::$brickColors);
	}
}
