// https://github.com/highlightjs/highlight.js
// https://github.com/highlightjs/highlight.js/blob/main/SUPPORTED_LANGUAGES.md

hljs.highlightAll();
hljs.addPlugin({
	'after:highlightElement': ({el, result}) => {
		el.innerHTML = result.value.replace(/^/gm,'<div class="row-number"></div>');
	}
});