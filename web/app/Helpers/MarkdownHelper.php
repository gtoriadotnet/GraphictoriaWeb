<?php

/*
	Graphictoria 2022
	A bit of a hacky way to implement markdown with laravel.
*/

namespace App\Helpers;

use Illuminate\Support\HtmlString;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\MarkdownConverter;

class MarkdownHelper
{
	// XlXi: This bit was taken from https://github.com/laravel/framework/blob/b9203fca96960ef9cd8860cb4ec99d1279353a8d/src/Illuminate/Mail/Markdown.php line 106
	public static function parse($text) {
		$environment = new Environment([
			//
		]);

		$environment->addExtension(new CommonMarkCoreExtension);
		$environment->addExtension(new TableExtension);

		$converter = new MarkdownConverter($environment);

		return new HtmlString($converter->convert($text)->getContent());
	}
}
