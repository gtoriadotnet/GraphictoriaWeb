<?php

/*
	XlXi 2022
	A bit of a hacky way to implement markdown with laravel.
*/

namespace App\Helpers;

use Illuminate\Support\HtmlString;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Extension\CommonMark\Node\Inline\Image;
use League\CommonMark\Extension\DefaultAttributes\DefaultAttributesExtension;
use League\CommonMark\Extension\Table\TableExtension;
use League\CommonMark\MarkdownConverter;

class MarkdownHelper
{
	// TODO: XlXi: Add a non-nav alert mode for links.
	// XlXi: This bit was partially taken from https://github.com/laravel/framework/blob/b9203fca96960ef9cd8860cb4ec99d1279353a8d/src/Illuminate/Mail/Markdown.php line 106
	public static function parse($text) {
		$environment = new Environment([
			'default_attributes' => [
				Link::class => [
					'class' => ['text-decoration-none']
				],
				Image::class => [
					'classes' => ['img-fluid']
				]
			]
		]);

		$environment->addExtension(new CommonMarkCoreExtension);
		$environment->addExtension(new TableExtension);
		$environment->addExtension(new DefaultAttributesExtension);

		$converter = new MarkdownConverter($environment);

		return new HtmlString($converter->convert($text)->getContent());
	}
}
