<?php
/*
Plugin Name: Glitterific Translator
Plugin URI: http://vedovini.net/plugins/?utm_source=wordpress&utm_medium=plugin&utm_campaign=wp-linkedin
Description: Automatically turns hate comments into fan comments.
Author: Claude Vedovini
Author URI: http://vedovini.net/?utm_source=wordpress&utm_medium=plugin&utm_campaign=wp-linkedin
Version: 0.1

# The code in this plugin is free software; you can redistribute the code aspects of
# the plugin and/or modify the code under the terms of the GNU Lesser General
# Public License as published by the Free Software Foundation; either
# version 3 of the License, or (at your option) any later version.

# THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
# EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
# MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
# NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
# LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
# OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
# WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
#
# See the GNU lesser General Public License for more details.
*/


class GlitterificTranslator {

	var $complete = array(
		'bitch' => 'beautylicious babe',
		'cunt' => 'glitterific smarty pants',
		'pussy' => 'genius',
		'feminatzi' => 'righteous babe',
		'rape' => 'high-five',
		'raped' => 'high-fived',
		'dead' => 'flying',
		'kill' => 'glitterbomb',
		'stab' => 'pat',
		'knife' => 'cute puppy',
		'shoot' => 'play cards',
		'bullet' => 'glitter rain',
		'punch' => 'make breakfast for',
		'pisswad' => 'sparkly fairy',
		'vulgar' => 'very vulvatious',
		'die' => 'giggle yourself into a stupor, you lovely lovely thing',
		'fucking' => 'adorable',
		'asshole' => 'assertive wonder',
		'stupid' => 'wonderfully clever',
		'stupides' =>'wonderfully cleverest of all',
		'vagina' => 'sweetest spot ever',
		'kick' => 'ferociously worship',
		'whore' => 'whilrlwind of lustorous and luck',
		'filthy' => 'sparkly like a unicorn',
		'cocksucker' => 'cartwheeling conundrum of happiness',
		'cock' => 'hat',
		'dick' => 'mittens',
		'dumbest' => 'dearest',
		'dumb' => 'sweet',
		'bullshit' => 'lovely',
		'hate' => 'love',
		'ass' => 'purple grape loving gorilla',
		'crazy' => 'level headed and thoughtful',
		'ugly' => 'sunshiney',
		'hates' => 'loves',
		'slut' => 'clever strong superfriend'
	);

	var $start = array(
		'she\'' => 'she\'',
		'he\'' => 'he\''
	);

	var $anywhere = array(
		'woman' => 'amazing person',
		'women' => 'excellent people',
		'females' => 'women'
	);

	var $end = array('heroine' => 'hero', 'heroines' => 'heroes' );

	function GlitterificTranslator() {
		$this->map = array_merge($this->start, $this->end, $this->complete, $this->anywhere);
		$this->regex = '/^(' . $this->keys($this->start) . ')|(' . $this->keys($this->end) . ')$|(' . $this->keys($this->anywhere) . ')|^(' . $this->keys($this->complete) . ')$/';

		add_action('preprocess_comment' , array(&$this, 'preprocess_comment'));
	}

	function preprocess_comment($commentdata) {
		$commentdata['comment_content'] = $this->translate($commentdata['comment_content']);
		return $commentdata;
	}

	function keys($a) {
		$keys = array_keys($a);
		return implode('|', $keys);
	}

	function matchCase($old_word, $replacement) {
		if (strtolower($replacement) == strtolower($old_word)) return $old_word;

		$first = substr($old_word, 0, 1);
		$second = substr($old_word, 1, 1);

		if (preg_match('/[a-z]/', $first)) return strtolower($replacement);
		if (preg_match('/[A-Z]/', $second)) return strtoupper($replacement);

		return ucfirst($replacement);
	}

	function findMatch($matches) {
		$word = $matches[0];
		return $this->map[$word];
	}

	function swapWord($matches) {
		$word = $matches[0];
		return $this->matchCase($word, preg_replace_callback($this->regex, array(&$this, 'findMatch'), strtolower($word)));
	}

	function translate($text) {
		return preg_replace_callback('/\b([a-z][\w\']+)\b/im', array(&$this, 'swapWord'), $text);
	}
}

global $the_glitterific_translator;
$the_glitterific_translator = new GlitterificTranslator();
