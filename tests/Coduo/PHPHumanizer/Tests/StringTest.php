<?php

namespace Coduo\PHPHumanizer\Tests;

use Coduo\PHPHumanizer\String;
use PHPUnit_Framework_TestCase;

class StringTest extends PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider humanizeStringProvider
     *
     * @param $input
     * @param $expected
     * @param $capitalize
     * @param $separator
     * @param array $forbiddenWords
     */
    public function test_humanize_strings($input, $expected, $capitalize, $separator, array $forbiddenWords)
    {
        $this->assertEquals($expected, String::humanize($input, $capitalize, $separator, $forbiddenWords));
    }

    /**
     * @dataProvider truncateStringProvider
     *
     * @param $text
     * @param $expected
     * @param $charactersCount
     * @param string $append
     */
    function test_truncate_string_to_word_closest_to_a_certain_number_of_characters($text, $expected, $charactersCount, $append = '')
    {
        $this->assertEquals($expected, String::truncate($text, $charactersCount, $append));
    }

    function it_truncate_string_to_word_closest_to_a_certain_number_of_characters_with_html_tags($text, $charactersCount, $allowedTags, $expected, $append = '')
    {
        $this->assertEquals($expected, String::truncateHtml($text, $charactersCount, $allowedTags, $append));
    }

    /**
     *
     * @return array
     */
    public function humanizeStringProvider()
    {
        return array(
            array('news_count', 'News count', true, '_', array('id')),
            array('user', 'user', false, '_', array('id')),
            array('news_id', 'News', true, '_', array('id')),
            array('customer_id', 'Customer id', true, '_', array()),
            array('news_count', 'News count', true, '_', array('id')),
            array('news-count', 'News count', true, '-', array('id')),
            array('news-count', 'news count', false, '-', array('id'))
        );
    }

    /**
     * @return array
     */
    public function truncateStringProvider()
    {
        $longText = 'Lorem ipsum dolorem si amet, lorem ipsum. Dolorem sic et nunc.';
        $shortText = 'Short text';
        
        return array(
            array($longText, 'Lorem', 2),
            array($longText, 'Lorem ipsum...', 10, '...'),
            array($longText, 'Lorem ipsum dolorem si amet, lorem', 30),
            array($longText, 'Lorem', 0),
            array($longText, 'Lorem...', 0, '...'),
            array($longText, 'Lorem ipsum dolorem si amet, lorem ipsum. Dolorem sic et nunc.', -2),
            array($shortText, "Short...", 1,  '...'),
            array($shortText, "Short...", 2,  '...'),
            array($shortText, "Short...", 3,  '...'),
            array($shortText, "Short...", 4,  '...'),
            array($shortText, "Short...", 5,  '...'),
            array($shortText, "Short text", 6,  '...'),
            array($shortText, "Short text", 7,  '...'),
            array($shortText, "Short text", 8,  '...'),
            array($shortText, "Short text", 9,  '...'),
            array($shortText, "Short text", 10, '...')
        );
    }
    
    public function truncateHtmlStringProvider()
    {
        $text = '<p><b>HyperText Markup Language</b>, commonly referred to as <b>HTML</b>, is the standard <a href="/wiki/Markup_language" title="Markup language">markup language</a> used to create <a href="/wiki/Web_page" title="Web page">web pages</a>.<sup id="cite_ref-1" class="reference"><a href="#cite_note-1"><span>[</span>1<span>]</span></a></sup> <a href="/wiki/Web_browser" title="Web browser">Web browsers</a> can read HTML files and render them into visible or audible web pages. HTML describes the structure of a <a href="/wiki/Website" title="Website">website</a> <a href="/wiki/Semantic" title="Semantic" class="mw-redirect">semantically</a> along with cues for presentation, making it a markup language, rather than a <a href="/wiki/Programming_language" title="Programming language">programming language</a>.</p>';
        
        return array(
            array($text, 3,  '<b><i><u><em><strong><a><span>',  "<b>HyperText</b>"), 
            array($text, 12, '<b><i><u><em><strong><a><span>', "<b>HyperText Markup</b>"),
            array($text, 30, '<b><i><u><em><strong><a><span>', "<b>HyperText Markup Language</b>, commonly"), 
            array($text, 50, '<b><i><u><em><strong><a><span>', "<b>HyperText Markup Language</b>, commonly referred to as"),
            array($text, 75, '<b><i><u><em><strong><a><span>', '<b>HyperText Markup Language</b>, commonly referred to as <b>HTML</b>, is the standard <a href="/wiki/Markup_language" title="Markup language">markup</a>'),
            array($text, 100,'<b><i><u><em><strong><a><span>', '<b>HyperText Markup Language</b>, commonly referred to as <b>HTML</b>, is the standard <a href="/wiki/Markup_language" title="Markup language">markup language</a> used to create'),
            array($text, 3  , '', "HyperText"),
            array($text, 12 , '', "HyperText Markup"),
            array($text, 50 , '', "HyperText Markup Language, commonly referred to as"),
            array($text, 75 , '', "HyperText Markup Language, commonly referred to as HTML, is the standard markup"),
            array($text, 100, '', "HyperText Markup Language, commonly referred to as HTML, is the standard markup language used to create"),
            array($text, 50, '', "HyperText Markup Language, commonly referred to as...", '...'),
            array($text, 75, '<b><i><u><em><strong><a><span>', '<b>HyperText Markup Language</b>, commonly referred to as <b>HTML</b>, is the standard <a href="/wiki/Markup_language" title="Markup language">markup...</a>', '...')
        );
    }
}